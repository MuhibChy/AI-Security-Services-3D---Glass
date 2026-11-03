@extends('layouts.public')

@section('title', 'Skills & Capabilities — Full IT, Cloud, Security & AI Coverage | AI Security Services')
@section('description', 'Explore every capability we deliver: IT service desk, servers and networking, Microsoft 365, cloud and DevOps, cybersecurity, software development, IT automation, AI agents, and managed IT service management.')

@section('content')
@php
    $slugify = fn ($s) => \Illuminate\Support\Str::slug($s);
@endphp

{{-- HERO — CAPABILITIES://MATRIX --}}
<section class="relative w-full overflow-hidden" aria-labelledby="skills-hero-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-12 lg:pb-16">
        <div class="max-w-4xl">
            <div class="flex flex-wrap items-center gap-2.5 mb-7">
                <span class="term-tag term-tag-accent">CAPABILITIES://MATRIX</span>
                <span class="term-status text-accent-soft"><span class="term-status-dot" aria-hidden="true"></span>Status: Online</span>
            </div>
            <h1 id="skills-hero-heading" class="font-display font-extrabold tracking-tight leading-[1.02] text-4xl sm:text-6xl lg:text-7xl text-navy-900 dark:text-white text-balance">
                EVERY CAPABILITY WE DELIVER, <span class="text-accent-soft">IN ONE PLACE.</span>
            </h1>
            <p class="mt-6 text-base sm:text-lg lg:text-xl leading-relaxed text-slate-600 dark:text-term-800 max-w-3xl">
                {{ $total }} engineered capabilities spanning IT service desk, servers and networking, Microsoft 365, cloud and DevOps, cybersecurity, software development, automation, applied AI, and managed service operations — delivered under clear scope, documentation, and SLAs.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3">
                <a href="{{ route('services.index') }}" class="term-btn">
                    Browse the Service Catalogue
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="term-btn term-btn-ghost">Discuss Your Requirements</a>
            </div>
        </div>
    </div>
</section>

{{-- Quick nav --}}
<section class="dark-island sticky top-14 z-30 bg-term-0/95 backdrop-blur-md border-y border-term-300 py-3.5" aria-label="Capability groups">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 flex flex-wrap gap-1.5">
        @foreach($groups as $group)
        <a href="#cap-{{ $slugify($group['name']) }}"
           class="term-tag hover:border-accent/50 hover:text-accent-soft transition-colors">{{ $group['name'] }}</a>
        @endforeach
    </div>
</section>

{{-- Groups --}}
@foreach($groups as $group)
<section id="cap-{{ $slugify($group['name']) }}" class="relative w-full py-14 sm:py-16 lg:py-20 border-b border-term-300 dark:border-white/5 scroll-mt-28" aria-labelledby="cap-h-{{ $slugify($group['name']) }}">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-start gap-4 max-w-3xl">
            <span class="flex-shrink-0 w-10 h-10 border border-accent/40 bg-accent/10 flex items-center justify-center text-accent-soft" aria-hidden="true">
                <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $group['icon'] }}"/></svg>
            </span>
            <div>
                <div class="term-sec-head" aria-hidden="true">
                    <span class="term-sec-num">{{ str_pad((string)($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="term-sec-label">CAPABILITIES://{{ strtoupper($slugify($group['name'])) }}</span>
                </div>
                <h2 id="cap-h-{{ $slugify($group['name']) }}" class="term-sec-title text-2xl sm:text-3xl">{{ $group['name'] }}</h2>
                <p class="term-sec-desc mt-2 text-[13px]">{{ count($group['items']) }} capabilities delivered by our engineering teams.</p>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
            @foreach($group['items'] as $item)
            @php $service = $resolved[$item['slug']] ?? null; @endphp
            <div class="term-panel p-4 flex items-start gap-2.5">
                <span class="mt-0.5 flex-shrink-0 text-accent-soft" aria-hidden="true">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
                <div class="min-w-0">
                    @if($service)
                        <a href="{{ route('services.show', $service->slug) }}" class="block text-[13.5px] font-semibold text-navy-900 dark:text-white leading-snug hover:text-accent-soft transition-colors">{{ $item['name'] }}</a>
                        <span class="block font-mono text-[10.5px] mt-0.5 uppercase tracking-wider text-term-700">Service available</span>
                    @else
                        <span class="block text-[13.5px] font-semibold text-navy-900 dark:text-white leading-snug">{{ $item['name'] }}</span>
                        <span class="block font-mono text-[10.5px] mt-0.5 uppercase tracking-wider text-term-700">Available on request</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

{{-- CTA --}}
<section class="relative w-full" aria-labelledby="skills-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
        <span class="term-tag term-tag-accent">CUSTOM://ENGAGEMENT</span>
        <h2 id="skills-cta-heading" class="font-display font-extrabold tracking-tight text-2xl sm:text-3xl lg:text-4xl text-navy-900 dark:text-white mt-4">
            Need a capability that spans several areas?
        </h2>
        <p class="mt-4 text-sm sm:text-base text-slate-600 dark:text-term-800">
            Most engagements combine several of the capabilities above. Tell us the outcome you need and we will scope, sequence, and deliver it — with a clear plan, documentation, and SLA-backed communication.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3 justify-center">
            <a href="{{ route('get-quote') }}" class="term-btn">
                Request a Quote
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('services.index') }}" class="term-btn term-btn-ghost">View All Services</a>
        </div>
    </div>
</section>
@endsection
