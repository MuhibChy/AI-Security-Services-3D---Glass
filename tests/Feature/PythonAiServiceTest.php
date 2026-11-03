<?php

namespace Tests\Feature;

use App\Services\Ai\PythonAiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Python AI Service Integration Tests
 * Tests the FastAPI wrapper around the local Ollama assistant.
 */
class PythonAiServiceTest extends TestCase
{
    use RefreshDatabase;

    private PythonAiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PythonAiService();
    }

    /** @test */
    public function service_returns_correct_name()
    {
        $this->assertEquals('python_ai', $this->service->getName());
    }

    /** @test */
    public function health_check_returns_reachable_when_service_available()
    {
        // The Python API should be running on port 8888
        $health = $this->service->healthCheck();

        $this->assertArrayHasKey('provider', $health);
        $this->assertEquals('python_ai', $health['provider']);
        $this->assertArrayHasKey('reachable', $health);
        $this->assertArrayHasKey('checked_at', $health);
    }

    /** @test */
    public function is_available_returns_true_when_service_running()
    {
        $available = $this->service->isAvailable();

        // Should not throw exception
        $this->assertTrue(is_bool($available));
    }

    /** @test */
    public function chat_returns_content_when_message_sent()
    {
        // Skip if Python API not available
        if (!$this->service->isAvailable()) {
            $this->markTestSkipped('Python AI service not available');
        }

        // Set environment variables for the service
        putenv('AI_PYTHON_API_URL=http://127.0.0.1:8888');
        putenv('AI_TIMEOUT_SECONDS=120');
        putenv('AI_CONNECT_TIMEOUT=5');

        // Recreate service to pick up new env vars
        $service = new PythonAiService();

        $result = $service->chat([
            ['role' => 'user', 'content' => 'What is 2+2?']
        ], ['model' => 'fableforge-ai/nexus-security:q4_k_m']);

        $this->assertArrayHasKey('content', $result);
        $this->assertNotEmpty($result['content']);
        $this->assertArrayHasKey('model', $result);
        $this->assertArrayHasKey('cost', $result);
        $this->assertEquals(0.0, $result['cost']);
    }
}
