<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Python AI Service — server-to-server client for the FastAPI wrapper
 * around the local Ollama assistant.
 *
 * Security: the Python service binds to loopback (127.0.0.1) so even if
 * the web server is exposed, the Python API is not reachable from outside.
 */
class PythonAiService implements AiProviderInterface
{
    private string $baseUrl;
    private ?string $authToken;
    private int $timeout;
    private int $connectTimeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('AI_PYTHON_API_URL', 'http://127.0.0.1:8888'), '/');
        $this->authToken = env('AI_SERVICE_AUTH_TOKEN');
        $this->timeout = (int) env('AI_TIMEOUT_SECONDS', 120);
        $this->connectTimeout = (int) env('AI_CONNECT_TIMEOUT', 5);
    }

    public function chat(array $messages, array $options = []): array
    {
        $conversationId = $options['conversation_id'] ?? $this->newConversation($options['model'] ?? null);
        $maxTokens = $options['max_tokens'] ?? 1024;
        $temperature = $options['temperature'] ?? 0.7;
        $model = $options['model'] ?? config('ollama.models.fast', config('ollama.default_model', 'llama3.2:latest'));

        $lastMessage = last($messages)['content'] ?? '';
        if (!$lastMessage) {
            throw new AiProviderException(AiProviderException::UNKNOWN, 'Empty message');
        }

        try {
            $response = Http::connectTimeout($this->connectTimeout)
                ->timeout($this->timeout)
                ->withHeaders($this->authHeaders())
                ->post("{$this->baseUrl}/conversation/{$conversationId}/message", [
                    'message' => mb_substr($lastMessage, 0, 12000),
                    'conversation_id' => $conversationId,
                    'model' => $model,
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                    'thinking' => false,
                ]);

            if ($response->failed()) {
                $status = $response->status();
                Log::warning('Python AI service error', ['status' => $status]);
                throw AiProviderException::fromStatus($status);
            }

            $data = $response->json();
            return [
                'content' => $data['content'] ?? '',
                'tokens_used' => ($data['tokens_input'] ?? 0) + ($data['tokens_output'] ?? 0),
                'input_tokens' => $data['tokens_input'] ?? 0,
                'output_tokens' => $data['tokens_output'] ?? 0,
                'model' => $data['model'] ?? $model,
                'cost' => 0.0,
                'conversation_id' => $conversationId,
            ];
        } catch (AiProviderException $e) {
            throw $e;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('Python AI service connection failed');
            throw AiProviderException::connectionError();
        } catch (\Throwable $e) {
            Log::warning('Python AI service error', ['error' => $e->getMessage()]);
            throw AiProviderException::unknown();
        }
    }

    public function getName(): string
    {
        return 'python_ai';
    }

    public function isAvailable(): bool
    {
        try {
            $res = Http::connectTimeout(2)->timeout(5)->get("{$this->baseUrl}/health");
            return $res->successful() && ($res->json('status') ?? '') === 'healthy';
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function healthCheck(): array
    {
        $started = microtime(true);
        $checkedAt = now()->toDateTimeString();
        try {
            $res = Http::connectTimeout($this->connectTimeout)->timeout(10)->get("{$this->baseUrl}/health");
            if (!$res->successful()) {
                return $this->unhealthy($checkedAt, 'server_error');
            }
            $data = $res->json();
            return [
                'provider' => 'python_ai',
                'reachable' => true,
                'model' => $data['model'] ?? '',
                'model_available' => $data['ollama_reachable'] ?? false,
                'models' => $data['installed_models'] ?? [],
                'latency_ms' => (int)((microtime(true) - $started) * 1000),
                'checked_at' => $checkedAt,
                'error' => ($data['ollama_reachable'] ?? false) ? null : 'ollama_unreachable',
            ];
        } catch (\Throwable $e) {
            return $this->unhealthy($checkedAt, 'unreachable');
        }
    }

    private function newConversation(?string $model = null): string
    {
        try {
            $res = Http::connectTimeout($this->connectTimeout)
                ->timeout(10)
                ->withHeaders($this->authHeaders())
                ->post("{$this->baseUrl}/conversation/new", [
                    'model' => $model,
                ]);
            if ($res->successful()) {
                return $res->json('conversation_id') ?? bin2hex(random_bytes(16));
            }
        } catch (\Throwable $e) {
            Log::warning('Python AI new conversation failed', ['error' => $e->getMessage()]);
        }
        return bin2hex(random_bytes(16));
    }

    private function authHeaders(): array
    {
        if ($this->authToken) {
            return ['Authorization' => 'Bearer ' . $this->authToken];
        }
        return [];
    }

    private function unhealthy(string $checkedAt, string $error): array
    {
        return [
            'provider' => 'python_ai',
            'reachable' => false,
            'model' => '',
            'model_available' => false,
            'models' => [],
            'latency_ms' => null,
            'checked_at' => $checkedAt,
            'error' => $error,
        ];
    }
}
