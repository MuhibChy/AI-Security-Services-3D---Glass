{{--
    Team member card — "Meet Our IT Experts".
    Props: member (array from config/team.php), demo (bool).

    Renders a monogram avatar when no photo is supplied, so demo profiles never
    imply a real person's likeness. "View Profile" expands certifications and
    responsibilities in place; "Contact Expert" routes to the contact form.
--}}
@props(['member', 'demo' => true])

@php
    $panelId = 'team-profile-' . $member['slug'];
    $contactUrl = route('contact') . '?subject=' . urlencode('Enquiry for ' . $member['name'] . ' (' . $member['title'] . ')');
    $visibleSkills = array_slice($member['skills'], 0, 6);
    $hiddenSkillCount = count($member['skills']) - count($visibleSkills);

    // Certifications are only presented as credentials once someone has actually
    // sighted them. Until then the card says so, in demo mode or not.
    $certsVerified = (bool) ($member['certifications_verified'] ?? false);
@endphp

<article class="term-panel group flex h-full flex-col overflow-hidden" x-data="{ open: false }">

    <div class="flex flex-1 flex-col p-6 sm:p-7">

        {{-- Identity --}}
        <div class="flex items-start gap-4">
            @if(!empty($member['photo']))
                <img src="{{ $member['photo'] }}"
                     alt="Portrait of {{ $member['name'] }}"
                     width="72" height="72" loading="lazy" decoding="async"
                     class="h-[72px] w-[72px] flex-shrink-0 rounded-xl object-cover border border-term-300 dark:border-white/10">
            @else
                <div class="flex h-[72px] w-[72px] flex-shrink-0 items-center justify-center rounded-xl border border-accent/30 bg-gradient-to-br from-[#287BFF]/25 to-[#36D9FF]/10"
                     role="img"
                     aria-label="Monogram avatar for {{ $member['name'] }}">
                    <span class="font-display text-xl font-bold tracking-tight text-navy-900 dark:text-accent-soft" aria-hidden="true">{{ $member['initials'] }}</span>
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <h3 class="font-display text-lg font-bold leading-snug tracking-tight text-navy-900 dark:text-white">
                    {{ $member['name'] }}
                </h3>
                <p class="mt-1 text-sm font-medium leading-snug text-slate-600 dark:text-term-800">
                    {{ $member['title'] }}
                </p>
                <span class="term-tag term-tag-accent mt-2.5">
                    <span aria-hidden="true">&#9679;</span>
                    {{ $member['years'] }}+ Years of Experience
                </span>
            </div>
        </div>

        {{-- Tagline --}}
        <p class="mt-5 border-l-2 border-accent/50 pl-3.5 text-sm italic leading-relaxed text-slate-700 dark:text-term-800">
            &ldquo;{{ $member['tagline'] }}&rdquo;
        </p>

        {{-- Biography --}}
        <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-term-800">
            {{ $member['bio'] }}
        </p>

        {{-- Skill badges --}}
        <div class="mt-5">
            <h4 class="font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700">Core Skills</h4>
            <ul class="mt-2.5 flex flex-wrap gap-1.5" aria-label="Core technical skills">
                @foreach($visibleSkills as $skill)
                    <li class="rounded-md border border-term-300 bg-white/60 px-2 py-1 font-mono text-[10px] uppercase tracking-wider text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-term-800">
                        {{ $skill }}
                    </li>
                @endforeach
                @if($hiddenSkillCount > 0)
                    <li class="rounded-md border border-accent/30 bg-accent/5 px-2 py-1 font-mono text-[10px] uppercase tracking-wider text-navy-800 dark:text-accent-soft">
                        +{{ $hiddenSkillCount }} more
                    </li>
                @endif
            </ul>
        </div>

        {{-- Expandable detail --}}
        <div id="{{ $panelId }}"
             x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mt-5 space-y-5 border-t border-term-300 pt-5 dark:border-white/10"
             role="region"
             aria-label="Full profile for {{ $member['name'] }}">

            {{-- Certifications --}}
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h4 class="font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700">Certifications</h4>
                    @unless($certsVerified)
                        <span class="rounded border border-amber-500/40 bg-amber-500/10 px-1.5 py-0.5 font-mono text-[9px] uppercase tracking-wider text-amber-700 dark:text-amber-300">
                            Pending verification
                        </span>
                    @endunless
                </div>
                <ul class="mt-2.5 space-y-1.5">
                    @foreach($member['certifications'] as $cert)
                        <li class="flex items-start gap-2 text-xs leading-relaxed text-slate-600 dark:text-term-800">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                            <span>{{ $cert }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Responsibilities --}}
            <div>
                <h4 class="font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700">Key Responsibilities</h4>
                <ul class="mt-2.5 space-y-1.5">
                    @foreach($member['responsibilities'] as $duty)
                        <li class="flex items-start gap-2 text-xs leading-relaxed text-slate-600 dark:text-term-800">
                            <span class="mt-1.5 h-1 w-1 flex-shrink-0 bg-accent" aria-hidden="true"></span>
                            <span>{{ $duty }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Full skill list --}}
            <div>
                <h4 class="font-mono text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-term-700">Full Technical Stack</h4>
                <ul class="mt-2.5 flex flex-wrap gap-1.5">
                    @foreach($member['skills'] as $skill)
                        <li class="rounded-md border border-term-300 bg-white/60 px-2 py-1 font-mono text-[10px] uppercase tracking-wider text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-term-800">
                            {{ $skill }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-auto pt-6">
            <button type="button"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="{{ $panelId }}"
                    class="term-btn term-btn-ghost term-btn-sm w-full">
                <span x-text="open ? 'Hide Profile' : 'View Profile'">View Profile</span>
                <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between gap-3 border-t border-term-300 px-6 py-4 dark:border-white/10">
        <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-slate-500 dark:text-term-700">
            {{ $member['location'] }}
        </span>
        <a href="{{ $contactUrl }}" class="term-link text-[11px]">
            Contact Expert
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
    </div>
</article>
