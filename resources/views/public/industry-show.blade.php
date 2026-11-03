@extends('layouts.public')

@section('title', $industry['seo_title'] ?: ('IT Support for '.$industry['name'].' — AI Security Services'))
@section('description', $industry['meta_description'] ?: $industry['description'])
@section('keywords', strtolower($industry['name']).', IT support, managed IT, cybersecurity, '.(strtolower($industry['category'] ?? 'industry')))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => 'IT Support for '.$industry['name'],
    'serviceType' => 'Managed IT support',
    'description' => $industry['meta_description'] ?: $industry['description'],
    'url' => route('industries.show', $industry['slug']),
    'provider' => [
        '@type' => 'Organization',
        'name' => 'AI Security Services',
        'url' => url('/'),
    ],
    'areaServed' => 'Worldwide',
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Industries', 'item' => route('industries')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $industry['name'], 'item' => route('industries.show', $industry['slug'])],
    ],
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
@endpush

@section('content')

{{-- HERO --}}
<section class="relative w-full overflow-hidden" aria-labelledby="industry-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-10 lg:pb-14">
        <nav aria-label="Breadcrumb" class="mb-7">
            <ol class="flex flex-wrap items-center gap-2 font-mono text-[10px] tracking-[0.2em] uppercase text-term-700">
                <li><a href="{{ route('home') }}" class="hover:text-accent-soft">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('industries') }}" class="hover:text-accent-soft">Industries</a></li>
                <li aria-hidden="true">/</li>
                <li class="text-accent-soft" aria-current="page">{{ $industry['name'] }}</li>
            </ol>
        </nav>

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8">
                <div class="flex flex-wrap items-center gap-2.5 mb-5">
                    @if($industry['category'])
                    <span class="term-tag term-tag-accent">{{ strtoupper($industry['category']) }}</span>
                    @endif
                    <span class="term-tag">INDUSTRIES://SECTOR</span>
                </div>
                <h1 id="industry-heading" class="font-display font-extrabold tracking-tight leading-[1.05] text-3xl sm:text-5xl lg:text-6xl text-navy-900 dark:text-white text-balance">
                    {{ $industry['name'] }}
                </h1>
                <p class="mt-5 text-base sm:text-lg leading-relaxed text-slate-600 dark:text-term-800 max-w-3xl">
                    {{ $industry['description'] }}
                </p>
                <div class="mt-7 flex flex-col sm:flex-row flex-wrap gap-3">
                    <a href="{{ route('get-quote') }}" class="term-btn">
                        Request a Consultation
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="term-btn term-btn-ghost">Talk to Our Team</a>
                </div>
            </div>

            <div class="lg:col-span-4 w-full">
                <div class="term-panel p-5 sm:p-6">
                    @if($industry['image_url'])
                    <img src="{{ $industry['image_url'] }}" alt="{{ $industry['image_alt'] }}" loading="lazy" decoding="async"
                         class="w-full h-56 object-cover rounded-xl border border-term-300 dark:border-white/10">
                    @else
                    <div class="relative flex items-center justify-center h-44 rounded-xl border border-term-300 dark:border-white/10 bg-gradient-to-br from-royal-500/10 via-transparent to-accent/10">
                        <svg class="w-16 h-16 text-accent-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="{{ $industry['icon'] }}"/>
                        </svg>
                    </div>
                    @endif

                    @if(!empty($industry['tags']))
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        @foreach($industry['tags'] as $tag)
                        <span class="term-tag">{{ strtoupper($tag) }}</span>
                        @endforeach
                    </div>
                    @endif

                    <a href="{{ route('contact') }}" class="term-link mt-5 text-sm">Discuss your requirements
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BODY --}}
<section class="relative w-full pb-16 sm:pb-20 lg:pb-24" aria-label="{{ $industry['name'] }} IT support details">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="grid lg:grid-cols-12 gap-4 sm:gap-5">
            <div class="lg:col-span-7 space-y-4 sm:space-y-5">
                @if($industry['overview'])
                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-navy-900 dark:text-white">Industry overview</h2>
                    <div class="mt-4 text-sm sm:text-[15px] leading-relaxed text-slate-600 dark:text-term-800 space-y-4">
                        {!! nl2br(e($industry['overview'])) !!}
                    </div>
                </div>
                @endif

                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-navy-900 dark:text-white">Common IT challenges</h2>
                    <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                        @foreach($industry['challenges'] as $item)
                        <li class="flex gap-2.5"><span class="text-term-700 mt-0.5" aria-hidden="true">–</span><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-navy-900 dark:text-white">Recommended IT support services</h2>
                    <div class="mt-4 grid sm:grid-cols-2 gap-2.5">
                        @foreach($industry['services'] as $item)
                        <div class="flex gap-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                            <span class="text-accent-soft mt-0.5" aria-hidden="true">✓</span><span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-4 sm:space-y-5">
                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-navy-900 dark:text-white">Cybersecurity requirements</h2>
                    <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                        @foreach($industry['cybersecurity'] as $item)
                        <li class="flex gap-2.5"><span class="text-term-700 mt-0.5" aria-hidden="true">–</span><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-navy-900 dark:text-white">Technology requirements</h2>
                    <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                        @foreach($industry['technology'] as $item)
                        <li class="flex gap-2.5"><span class="text-term-700 mt-0.5" aria-hidden="true">–</span><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>

                <div class="term-panel p-6 sm:p-8">
                    <h2 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-navy-900 dark:text-white">Benefits of outsourced IT support</h2>
                    <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                        @foreach($industry['benefits'] as $item)
                        <li class="flex gap-2.5"><span class="text-accent-soft mt-0.5" aria-hidden="true">✓</span><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
        <div class="mt-10 sm:mt-14">
            <div class="flex items-end justify-between gap-4 mb-5">
                <h2 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-navy-900 dark:text-white">Related industries</h2>
                <a href="{{ route('industries') }}" class="term-link text-sm">All industries
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="grid gap-4 sm:gap-5 md:grid-cols-3">
                @foreach($related as $item)
                <a href="{{ route('industries.show', $item['slug']) }}" class="term-panel p-6 group block">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <svg class="w-6 h-6 text-term-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                        <span class="term-tag">{{ strtoupper($item['category'] ?? 'SECTOR') }}</span>
                    </div>
                    <h3 class="font-display text-lg font-bold tracking-tight text-navy-900 dark:text-white group-hover:text-accent-soft transition-colors">{{ $item['name'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-term-800 line-clamp-2">{{ $item['description'] }}</p>
                    <span class="term-link mt-3 text-xs">Learn More
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="relative w-full py-16 sm:py-20 border-t border-term-300 dark:border-white/5" aria-labelledby="industry-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="term-tag term-tag-accent">INDUSTRIES://CONSULT</span>
        <h2 id="industry-cta-heading" class="mt-4 font-display text-3xl sm:text-5xl font-bold tracking-tight text-navy-900 dark:text-white">
            IT support built around {{ $industry['name'] }}.
        </h2>
        <p class="mt-4 text-base sm:text-lg text-slate-600 dark:text-term-800">
            Tell us about your systems, locations, and compliance requirements. We will map a support plan that fits your operation.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('contact') }}" class="term-btn term-btn-lg">Schedule a Consultation
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('industries') }}" class="term-btn term-btn-lg term-btn-ghost">Browse All Industries</a>
        </div>
    </div>
</section>

@endsection
