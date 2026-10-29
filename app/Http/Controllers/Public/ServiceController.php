<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    // Cache tag/key: the page is 1 MB+ HTML (grows with the catalogue), and
    // the render cost dominated the response time. Guests — the overwhelming
    // majority of public traffic — get a cached render; authenticated users
    // keep the live personalized render. Admin mutations invalidate the key,
    // and the TTL bounds staleness for any write path we could not hook.
    public const INDEX_CACHE_KEY = 'public:services:index:v1';

    public const INDEX_CACHE_TTL = 600; // seconds

    public static function forgetIndexCache(): void
    {
        try {
            Cache::forget(self::INDEX_CACHE_KEY);
        } catch (\Throwable $e) {
            // Cache is an optimization; a broken store must never break the page.
        }
    }

    public function index()
    {
        if (auth()->check()) {
            return $this->buildIndexResponse();
        }

        $html = Cache::remember(self::INDEX_CACHE_KEY, now()->addSeconds(self::INDEX_CACHE_TTL), function () {
            return $this->buildIndexResponse()->getContent();
        });

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function buildIndexResponse()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['services' => function ($q) {
                $q->where('is_active', true)
                  ->with('countryPrices.country')
                  ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $featured = Service::where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'countryPrices.country')
            ->orderBy('sort_order')
            ->get();

        $countries = Country::where('is_active', true)->orderBy('sort_order')->get();

        return response()->view('public.services.index', compact('categories', 'featured', 'countries'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with('category', 'countryPrices.country')
            ->firstOrFail();

        $related = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->with('countryPrices.country')
            ->limit(3)
            ->get();

        $countries = Country::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.services.show', compact('service', 'related', 'countries'));
    }
}
