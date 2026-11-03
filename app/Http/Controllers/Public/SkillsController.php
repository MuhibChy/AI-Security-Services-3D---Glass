<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;

class SkillsController extends Controller
{
    public function index()
    {
        $groups = config('capabilities.groups', []);
        $serviceMap = config('capabilities.service_map', []);

        // Resolve each capability to a catalogue service using STABLE SLUGS only
        // (never display names):
        //   1. explicit config('capabilities.service_map') override, else
        //   2. a service whose slug equals the capability slug.
        // Anything else is left unmatched ("Available on request").
        $targets = [];
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                $targets[] = $serviceMap[$item['slug']] ?? $item['slug'];
            }
        }

        $services = Service::query()
            ->where('is_active', true)
            ->whereIn('slug', array_values(array_unique($targets)))
            ->get(['id', 'name', 'slug'])
            ->keyBy('slug');

        $resolved = [];
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                $target = $serviceMap[$item['slug']] ?? $item['slug'];
                $resolved[$item['slug']] = $services->get($target);
            }
        }

        $total = collect($groups)->sum(fn ($g) => count($g['items'] ?? []));

        return view('public.skills', compact('groups', 'resolved', 'total'));
    }
}
