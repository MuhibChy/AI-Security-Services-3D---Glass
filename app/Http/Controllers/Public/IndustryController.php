<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\IndustryCatalogue;

class IndustryController extends Controller
{
    public function index()
    {
        $newIndustries = IndustryCatalogue::all();

        return view('public.industries', compact('newIndustries'));
    }

    public function show(string $slug)
    {
        $industry = IndustryCatalogue::find($slug);

        abort_if($industry === null, 404);

        $related = IndustryCatalogue::all()
            ->where('slug', '!=', $slug)
            ->where('category', $industry['category'])
            ->take(3)
            ->values();

        if ($related->count() < 3) {
            $related = $related
                ->merge(IndustryCatalogue::all()->where('slug', '!=', $slug)->take(3))
                ->unique('slug')
                ->take(3)
                ->values();
        }

        return view('public.industry-show', compact('industry', 'related'));
    }
}
