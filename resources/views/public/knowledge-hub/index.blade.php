@extends('layouts.public')

@section('title', 'Knowledge Hub & IT Learning Resources | AI Security Services')
@section('description', 'Curated collection of 100+ reputable cybersecurity, web development, mobile, AI agent engineering, cloud, and computer science resources.')

@section('content')

{{-- HERO — KNOWLEDGE://HUB --}}
<section class="relative w-full overflow-hidden" aria-labelledby="kh-hero-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-12 lg:pb-16">
        <div class="max-w-3xl">
            <div class="flex flex-wrap items-center gap-2.5 mb-7">
                <span class="term-tag term-tag-accent">KNOWLEDGE://HUB</span>
                <span class="term-tag">100+ Reputable Resources</span>
                <span class="font-mono text-xs text-slate-500 dark:text-term-700">// Total Catalog: {{ $totalCount }}</span>
            </div>
            <h1 id="kh-hero-heading" class="font-display font-extrabold tracking-tight leading-[1.02] text-4xl sm:text-6xl lg:text-7xl text-navy-900 dark:text-white text-balance">
                IT & CYBERSECURITY <span class="text-accent-soft">KNOWLEDGE HUB.</span>
            </h1>
            <p class="mt-6 text-base sm:text-lg lg:text-xl leading-relaxed text-slate-600 dark:text-term-800 max-w-2xl">
                Explore our curated library of 100+ top-tier training labs, official documentation, interactive courses, and security platforms across cybersecurity, web development, AI agent engineering, and computer science.
            </p>

            {{-- Search & Filters Form --}}
            <form action="{{ route('knowledge-hub.index') }}" method="GET" role="search" class="mt-8 flex flex-col sm:flex-row gap-2.5 max-w-2xl">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                @if(request('difficulty'))
                <input type="hidden" name="difficulty" value="{{ request('difficulty') }}">
                @endif
                @if(request('pricing'))
                <input type="hidden" name="pricing" value="{{ request('pricing') }}">
                @endif

                <label for="kh-search" class="sr-only">Search resources, tools, or guides</label>
                <input type="text" id="kh-search" name="search" value="{{ request('search') }}" placeholder="search tools, labs, documentation, courses…"
                       class="term-input flex-1 font-mono text-sm">
                <button type="submit" class="term-btn flex-shrink-0">Search ->ṣ</button>
            </form>
        </div>
    </div>
</section>

{{-- Main Catalog Section --}}
<section class="relative w-full py-12 sm:py-16 lg:py-20 border-t border-term-300 dark:border-white/5" aria-label="Knowledge hub resources catalog">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">

        {{-- Filter Navigation Bar --}}
        <div class="space-y-6 mb-12">

            {{-- Category Filter Pills --}}
            <div>
                <div class="font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700 mb-3">// Filter by Domain</div>
                <div class="flex items-center gap-2 overflow-x-auto pb-2" role="navigation" aria-label="Filter by domain category">
                    @foreach($categories as $key => $label)
                    <a href="{{ route('knowledge-hub.index', array_merge(request()->except('page'), ['category' => $key])) }}"
                       class="term-tag flex-shrink-0 {{ (request('category', 'all') === $key) || (!request('category') && $key === 'all') ? 'term-tag-accent' : '' }}">
                        {{ strtoupper($label) }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Secondary Filters: Type, Difficulty, Pricing --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-term-300 dark:border-white/5">

                {{-- Resource Type --}}
                <div>
                    <label for="filter-type" class="block font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700 mb-2">// Resource Type</label>
                    <select id="filter-type" onchange="location = this.value;" class="term-input text-xs font-mono w-full bg-white dark:bg-navy-950">
                        @foreach($types as $key => $label)
                        <option value="{{ route('knowledge-hub.index', array_merge(request()->except('page'), ['type' => $key])) }}"
                                {{ request('type', 'all') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Difficulty Level --}}
                <div>
                    <label for="filter-difficulty" class="block font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700 mb-2">// Difficulty Level</label>
                    <select id="filter-difficulty" onchange="location = this.value;" class="term-input text-xs font-mono w-full bg-white dark:bg-navy-950">
                        @foreach($difficulties as $key => $label)
                        <option value="{{ route('knowledge-hub.index', array_merge(request()->except('page'), ['difficulty' => $key])) }}"
                                {{ request('difficulty', 'all') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pricing Model --}}
                <div>
                    <label for="filter-pricing" class="block font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700 mb-2">// Pricing Model</label>
                    <select id="filter-pricing" onchange="location = this.value;" class="term-input text-xs font-mono w-full bg-white dark:bg-navy-950">
                        @foreach($pricingOptions as $key => $label)
                        <option value="{{ route('knowledge-hub.index', array_merge(request()->except('page'), ['pricing' => $key])) }}"
                                {{ request('pricing', 'all') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Active Filter Reset & Count --}}
            @if(request()->filled('search') || (request('category') && request('category') !== 'all') || (request('type') && request('type') !== 'all') || (request('difficulty') && request('difficulty') !== 'all') || (request('pricing') && request('pricing') !== 'all'))
            <div class="flex items-center justify-between pt-2">
                <span class="font-mono text-xs text-slate-600 dark:text-term-800">
                    Showing filtered results ({{ $resources->total() }} matching)
                </span>
                <a href="{{ route('knowledge-hub.index') }}" class="term-link font-mono text-xs text-accent-soft hover:underline">
                    Reset all filters ->
                </a>
            </div>
            @endif

        </div>

        {{-- Resources Grid --}}
        @if($resources->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($resources as $resource)
            <div class="term-panel p-6 group flex flex-col justify-between relative hover:border-accent/50 transition-all duration-300">

                {{-- Top Badge row --}}
                <div>
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <span class="term-tag text-[10px]">{{ strtoupper(str_replace('-', ' ', $resource->category)) }}</span>
                        <div class="flex items-center gap-1.5">
                            @if($resource->is_featured)
                            <span class="term-tag term-tag-accent text-[9px]">FEATURED</span>
                            @endif
                            <span class="font-mono text-[10px] uppercase px-2 py-0.5 rounded bg-term-200 dark:bg-white/5 text-slate-600 dark:text-term-800 border border-term-300 dark:border-white/10">
                                {{ $resource->resource_type }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-display text-lg font-bold tracking-tight text-navy-900 dark:text-white group-hover:text-accent-soft transition-colors leading-snug mb-2">
                        {{ $resource->title }}
                    </h3>

                    <p class="text-sm leading-relaxed text-slate-600 dark:text-term-800 line-clamp-3 mb-4">
                        {{ $resource->description }}
                    </p>
                </div>

                {{-- Bottom Metadata & External Link --}}
                <div class="pt-4 border-t border-term-300 dark:border-white/5 flex items-center justify-between gap-3 mt-auto">
                    <div class="flex items-center gap-2 font-mono text-[11px] text-slate-500 dark:text-term-700">
                        <span class="capitalize">{{ $resource->difficulty }}</span>
                        <span>•</span>
                        <span class="capitalize">{{ $resource->pricing }}</span>
                    </div>

                    <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                       class="term-btn term-btn-sm term-btn-ghost inline-flex items-center gap-1.5 text-xs">
                        <span>Access Resource</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $resources->withQueryString()->links() }}
        </div>

        @else
        <div class="term-panel p-12 sm:p-16 text-center max-w-lg mx-auto">
            <div class="font-mono text-[11px] tracking-[0.24em] text-slate-500 dark:text-term-700 mb-3">QUERY://EMPTY</div>
            <h3 class="font-display text-lg font-bold text-navy-900 dark:text-white mb-2">No matching knowledge hub resources found</h3>
            <p class="text-sm text-slate-600 dark:text-term-800 mb-6">Try broadening your search query or selecting a different category filter.</p>
            <a href="{{ route('knowledge-hub.index') }}" class="term-btn term-btn-ghost term-btn-sm">Clear all filters</a>
        </div>
        @endif

    </div>
</section>

@endsection
