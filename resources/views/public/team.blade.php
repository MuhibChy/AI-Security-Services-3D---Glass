@extends('layouts.public')

@php
    $team = config('team');
    $members = $team['members'];
    $practices = $team['practices'];
    $isDemo = (bool) ($team['is_demo'] ?? true);
    $section = $team['section'];

    // Only offer filter chips for practices that actually have members.
    $activePractices = array_intersect_key($practices, array_flip(array_column($members, 'practice')));
@endphp

@section('title', 'Meet Our IT Experts | AI Security Services')
@section('description', 'Our technical team spans endpoint support, network infrastructure, systems administration, cybersecurity, cloud and Microsoft 365 engineering, and service desk leadership.')
@section('keywords', 'IT support team, IT experts, network specialists, systems administrators, cybersecurity specialists, Microsoft 365 engineers, help desk managers')

@section('content')

{{-- HERO — SYSTEM://TEAM --}}
<section class="relative w-full overflow-hidden" aria-labelledby="team-hero-heading">
    <div class="absolute inset-0 bg-cyber-grid opacity-60 pointer-events-none" aria-hidden="true"></div>
    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 pt-24 sm:pt-28 lg:pt-32 pb-12 lg:pb-16">
        <div class="max-w-4xl">
            <div class="flex flex-wrap items-center gap-2.5 mb-7">
                <span class="term-tag term-tag-accent">{{ $section['eyebrow'] }}</span>
                <span class="term-tag">AI Security Services</span>
            </div>
            <h1 id="team-hero-heading" class="font-display font-extrabold tracking-tight leading-[1.02] text-4xl sm:text-6xl lg:text-7xl text-navy-900 dark:text-white text-balance">
                THE PEOPLE WHO <span class="text-accent-soft">ANSWER THE TICKET</span>.
            </h1>
            <p class="mt-6 text-base sm:text-lg lg:text-xl leading-relaxed text-slate-600 dark:text-term-800 max-w-3xl">
                {{ $section['description'] }}
            </p>
        </div>
    </div>
</section>

{{-- DEMO DISCLOSURE --}}
@if($isDemo)
<section class="relative w-full pb-4" aria-label="Sample content notice">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="term-panel flex items-start gap-4 p-5 sm:p-6" role="note">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.34 3.94l-7.6 13.16A1.5 1.5 0 004.03 19.5h15.94a1.5 1.5 0 001.29-2.4l-7.6-13.16a1.5 1.5 0 00-2.6 0z"/>
            </svg>
            <div>
                <p class="font-display text-sm font-bold tracking-tight text-navy-900 dark:text-white">
                    Demonstration content &mdash; not real personnel
                </p>
                <p class="mt-1.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">
                    {{ $section['demo_notice'] }}
                </p>
            </div>
        </div>
    </div>
</section>
@endif

{{-- TEAM GRID --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24" aria-labelledby="team-grid-heading"
         x-data="{ practice: 'all' }">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">

        <div id="team-grid-heading">
            <x-section-head num="01" label="SYSTEM://PERSONNEL"
                            :title="count($members) . ' specialists, one support bench.'"
                            desc="Filter by practice area to see who owns which part of your environment." />
        </div>

        {{-- Practice filter --}}
        <div class="mt-8 flex flex-wrap gap-2" role="group" aria-label="Filter team by practice area">
            <button type="button"
                    @click="practice = 'all'"
                    :aria-pressed="(practice === 'all').toString()"
                    :class="practice === 'all' ? 'term-tag term-tag-accent' : 'term-tag'"
                    class="cursor-pointer transition-colors hover:border-accent/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-[#071A38]">
                All Practices ({{ count($members) }})
            </button>
            @foreach($activePractices as $key => $label)
                <button type="button"
                        @click="practice = '{{ $key }}'"
                        :aria-pressed="(practice === '{{ $key }}').toString()"
                        :class="practice === '{{ $key }}' ? 'term-tag term-tag-accent' : 'term-tag'"
                        class="cursor-pointer transition-colors hover:border-accent/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-[#071A38]">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Cards --}}
        <div class="mt-8 grid gap-5 sm:gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($members as $member)
                <div x-show="practice === 'all' || practice === '{{ $member['practice'] }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="reveal h-full">
                    <x-team-card :member="$member" :demo="$isDemo" />
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW WE WORK --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="team-ops-heading">
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <div id="team-ops-heading">
            <x-section-head num="02" label="SYSTEM://OPERATIONS" title="How the bench is organised."
                            desc="Every engagement maps to a named owner, a defined escalation path, and a response target." />
        </div>

        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach([
                ['label' => 'TIER-1', 'title' => 'Service Desk', 'desc' => 'First contact triage, password and access requests, and remote fixes resolved on the first call.'],
                ['label' => 'TIER-2', 'title' => 'Specialist Escalation', 'desc' => 'Endpoint, network, and systems engineers pick up what Tier-1 cannot close remotely.'],
                ['label' => 'TIER-3', 'title' => 'Engineering', 'desc' => 'Architecture, migration, and root-cause work owned by the relevant practice lead.'],
                ['label' => 'SECURITY', 'title' => 'Security Operations', 'desc' => 'Monitoring, vulnerability management, and incident response running alongside support.'],
            ] as $tier)
                <div class="term-panel p-7 sm:p-8">
                    <div class="font-mono text-[11px] tracking-[0.24em] text-accent-soft mb-5">{{ $tier['label'] }}</div>
                    <h3 class="font-display text-xl font-bold tracking-tight text-navy-900 dark:text-white">{{ $tier['title'] }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-slate-600 dark:text-term-800">{{ $tier['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative w-full py-16 sm:py-20 lg:py-24 border-t border-term-300 dark:border-white/5" aria-labelledby="team-cta-heading">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <span class="term-tag term-tag-accent">SYSTEM://ENGAGE</span>
        <h2 id="team-cta-heading" class="mt-4 font-display text-3xl sm:text-5xl font-bold tracking-tight text-navy-900 dark:text-white text-balance">
            Talk to the specialist who owns your problem.
        </h2>
        <p class="mt-4 text-base sm:text-lg leading-relaxed text-slate-600 dark:text-term-800 max-w-2xl mx-auto">
            Tell us what is failing and we will route it to the right engineer, with a named owner and a response target.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('contact') }}" class="term-btn term-btn-lg w-full sm:w-auto">
                Contact Our Team
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('services.index') }}" class="term-btn term-btn-ghost term-btn-lg w-full sm:w-auto">
                View Our Services
            </a>
        </div>
    </div>
</section>

@endsection
