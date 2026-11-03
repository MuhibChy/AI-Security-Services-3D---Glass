<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeHubResource;
use Illuminate\Http\Request;

class KnowledgeHubController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeHubResource::active();

        if ($request->filled('search')) {
            $s = addcslashes(mb_substr(trim((string) $request->search), 0, 100), '%_\\');
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('resource_type', $request->type);
        }

        if ($request->filled('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('pricing') && $request->pricing !== 'all') {
            $query->where('pricing', $request->pricing);
        }

        $resources = $query->ordered()->paginate(18)->withQueryString();

        $categories = [
            'all' => 'All Categories',
            'cybersecurity' => 'Cybersecurity & Ethical Hacking',
            'web-dev' => 'Web & App Development',
            'mobile-dev' => 'Mobile Development',
            'ai-agents' => 'AI Agents & LLM Engineering',
            'cloud-devops' => 'Cloud, DevOps & Systems',
            'general-cs' => 'Computer Science & Data Science',
        ];

        $types = [
            'all' => 'All Types',
            'lab' => 'Interactive Labs',
            'course' => 'Courses & Guides',
            'documentation' => 'Official Docs',
            'tool' => 'Tools & Platforms',
            'community' => 'Community',
        ];

        $difficulties = [
            'all' => 'All Levels',
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'all-levels' => 'All Levels',
        ];

        $pricingOptions = [
            'all' => 'All Pricing',
            'free' => 'Free',
            'freemium' => 'Freemium',
            'paid' => 'Paid',
        ];

        $totalCount = KnowledgeHubResource::active()->count();

        return view('public.knowledge-hub.index', compact(
            'resources',
            'categories',
            'types',
            'difficulties',
            'pricingOptions',
            'totalCount'
        ));
    }
}
