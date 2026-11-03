@props([
    'type' => 'it-support', // 'it-support', 'cybersecurity', 'network', 'cloud', 'ai', 'web-development', 'backup'
    'class' => 'w-full h-44 sm:h-52',
    'badge' => null
])

@php
    // Map various category slugs/service slugs to standard 3D visual concepts
    $typeKey = strtolower((string)$type);
    if (str_contains($typeKey, 'cyber') || str_contains($typeKey, 'security') || str_contains($typeKey, 'penetration') || str_contains($typeKey, 'vulnerability')) {
        $concept = 'cybersecurity';
        $badgeText = $badge ?? 'ENTERPRISE PROTECTION';
        $headline = 'Cybersecurity & Defense';
    } elseif (str_contains($typeKey, 'cloud') || str_contains($typeKey, 'server')) {
        $concept = 'cloud';
        $badgeText = $badge ?? '99.99% INFRASTRUCTURE';
        $headline = 'Cloud & Server Architecture';
    } elseif (str_contains($typeKey, 'network') || str_contains($typeKey, 'infra')) {
        $concept = 'network';
        $badgeText = $badge ?? 'ZERO-LATENCY TOPOLOGY';
        $headline = 'Network & Infrastructure';
    } elseif (str_contains($typeKey, 'ai') || str_contains($typeKey, 'automation') || str_contains($typeKey, 'business-tech') || str_contains($typeKey, 'machine')) {
        $concept = 'ai';
        $badgeText = $badge ?? 'AI-DRIVEN AUTOMATION';
        $headline = 'AI & Intelligent Systems';
    } elseif (str_contains($typeKey, 'web') || str_contains($typeKey, 'dev') || str_contains($typeKey, 'software') || str_contains($typeKey, 'app')) {
        $concept = 'web-development';
        $badgeText = $badge ?? 'SCALABLE ARCHITECTURE';
        $headline = 'Web & Digital Engineering';
    } elseif (str_contains($typeKey, 'backup') || str_contains($typeKey, 'disaster') || str_contains($typeKey, 'recovery')) {
        $concept = 'backup';
        $badgeText = $badge ?? 'RAPID RESTORE SLA';
        $headline = 'Backup & Disaster Recovery';
    } else {
        $concept = 'it-support';
        $badgeText = $badge ?? '24/7 HELPDESK READY';
        $headline = 'IT Support & Management';
    }
@endphp

<div class="service-3d-card group/visual relative overflow-hidden rounded-xl bg-gradient-to-b from-[#0B2A55] to-[#071A38] border border-[#36D9FF]/20 hover:border-[#36D9FF]/50 transition-all duration-300 shadow-lg hover:shadow-[#287BFF]/20 {{ $class }}"
     data-visual-concept="{{ $concept }}">

    {{-- Subtle studio lighting glow --}}
    <div class="absolute inset-0 bg-gradient-to-tr from-[#287BFF]/10 via-transparent to-[#36D9FF]/10 pointer-events-none"></div>
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#36D9FF]/15 rounded-full blur-2xl pointer-events-none group-hover/visual:bg-[#36D9FF]/25 transition-all duration-500"></div>
    <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-[#287BFF]/20 rounded-full blur-2xl pointer-events-none"></div>

    {{-- Advertising Copy Badge --}}
    <div class="absolute top-3 left-3 z-10">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-mono tracking-wider font-semibold uppercase bg-[#071A38]/80 text-[#36D9FF] border border-[#36D9FF]/30 backdrop-blur-md">
            <span class="w-1.5 h-1.5 rounded-full bg-[#36D9FF] animate-pulse"></span>
            {{ $badgeText }}
        </span>
    </div>

    {{-- 3D Staged Visual Object --}}
    <div class="w-full h-full flex items-center justify-center p-4 pt-8 select-none transition-transform duration-500 group-hover/visual:scale-105">
        @switch($concept)
            @case('cybersecurity')
                {{-- 3D Sculpted Blue Shield with Dimensional Lock & Security Mesh --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="Cybersecurity & Protection 3D Visual">
                    <defs>
                        <linearGradient id="shieldGrad-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3B82F6" />
                            <stop offset="45%" stop-color="#287BFF" />
                            <stop offset="100%" stop-color="#0F225C" />
                        </linearGradient>
                        <linearGradient id="bevelGrad-{{ $concept }}" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#73ECFA" />
                            <stop offset="100%" stop-color="#1D4ED8" />
                        </linearGradient>
                        <linearGradient id="lockSilver-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#F7FAFF" />
                            <stop offset="50%" stop-color="#C9D4E5" />
                            <stop offset="100%" stop-color="#758CA5" />
                        </linearGradient>
                        <radialGradient id="cyanCore-{{ $concept }}" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#36D9FF" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#287BFF" stop-opacity="0" />
                        </radialGradient>
                    </defs>

                    {{-- Ground Shadow --}}
                    <ellipse cx="120" cy="155" rx="65" ry="12" fill="#030812" opacity="0.65" />

                    {{-- Background Connected Topology Lines --}}
                    <path d="M50 70 L90 85 M190 70 L150 85 M120 40 L120 20" stroke="#36D9FF" stroke-width="1.5" stroke-dasharray="3 3" opacity="0.4" />
                    <circle cx="50" cy="70" r="4" fill="#36D9FF" />
                    <circle cx="190" cy="70" r="4" fill="#36D9FF" />
                    <circle cx="120" cy="20" r="3.5" fill="#805CFF" />

                    {{-- Outer Shield Bevel --}}
                    <path d="M120 24 L175 48 C175 105 148 135 120 152 C92 135 65 105 65 48 Z"
                          fill="url(#bevelGrad-{{ $concept }})" opacity="0.95" />

                    {{-- Inner Sculpted Royal Blue Shield Face --}}
                    <path d="M120 32 L166 52 C166 100 143 126 120 142 C97 126 74 100 74 52 Z"
                          fill="url(#shieldGrad-{{ $concept }})" />

                    {{-- 3D Specular Highlight Ridge --}}
                    <path d="M120 34 L120 140 C100 125 78 100 78 54 L120 34 Z"
                          fill="#FFFFFF" opacity="0.12" />

                    {{-- Glowing Core Pulse --}}
                    <circle cx="120" cy="85" r="32" fill="url(#cyanCore-{{ $concept }})" />

                    {{-- Dimensional Silver Lock Base --}}
                    <rect x="104" y="80" width="32" height="26" rx="4" fill="url(#lockSilver-{{ $concept }})" stroke="#071A38" stroke-width="1.5" />
                    {{-- Lock Shackle --}}
                    <path d="M110 80 V72 C110 66 130 66 130 72 V80" fill="none" stroke="url(#lockSilver-{{ $concept }})" stroke-width="3.5" stroke-linecap="round" />
                    {{-- Lock Keyhole --}}
                    <circle cx="120" cy="91" r="2.5" fill="#071A38" />
                    <path d="M119 92 L118 99 L122 99 L121 92 Z" fill="#071A38" />

                    {{-- Dynamic Orbit Ring --}}
                    <ellipse cx="120" cy="88" rx="55" ry="16" fill="none" stroke="#36D9FF" stroke-width="1.5" stroke-dasharray="8 6" opacity="0.75" />
                </svg>
                @break

            @case('cloud')
                {{-- 3D Solid White-and-Blue Cloud over Compact Server Stack --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="Cloud & Server Architecture 3D Visual">
                    <defs>
                        <linearGradient id="cloudGrad-{{ $concept }}" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#FFFFFF" />
                            <stop offset="60%" stop-color="#F7FAFF" />
                            <stop offset="100%" stop-color="#C9D4E5" />
                        </linearGradient>
                        <linearGradient id="serverChassis-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#0B2A55" />
                            <stop offset="50%" stop-color="#253344" />
                            <stop offset="100%" stop-color="#0D1522" />
                        </linearGradient>
                        <linearGradient id="beamGrad-{{ $concept }}" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#36D9FF" stop-opacity="0.8" />
                            <stop offset="100%" stop-color="#287BFF" stop-opacity="0.2" />
                        </linearGradient>
                    </defs>

                    {{-- Floor shadow --}}
                    <ellipse cx="120" cy="158" rx="60" ry="10" fill="#030812" opacity="0.7" />

                    {{-- Server Stack Blade 3 (Bottom) --}}
                    <g transform="translate(68, 128)">
                        <polygon points="0,10 52,-8 104,10 52,28" fill="#0B2A55" stroke="#36D9FF" stroke-width="0.8" />
                        <polygon points="0,10 52,28 52,36 0,18" fill="#0D1522" />
                        <polygon points="104,10 52,28 52,36 104,18" fill="#1F2E40" />
                        <circle cx="48" cy="24" r="1.8" fill="#36D9FF" />
                        <circle cx="58" cy="21" r="1.8" fill="#22C55E" />
                    </g>

                    {{-- Server Stack Blade 2 (Middle) --}}
                    <g transform="translate(68, 110)">
                        <polygon points="0,10 52,-8 104,10 52,28" fill="#1D2A3E" stroke="#36D9FF" stroke-width="0.8" />
                        <polygon points="0,10 52,28 52,36 0,18" fill="#0D1522" />
                        <polygon points="104,10 52,28 52,36 104,18" fill="#243550" />
                        <circle cx="48" cy="24" r="1.8" fill="#36D9FF" />
                        <circle cx="58" cy="21" r="1.8" fill="#287BFF" />
                    </g>

                    {{-- Server Stack Blade 1 (Top) --}}
                    <g transform="translate(68, 92)">
                        <polygon points="0,10 52,-8 104,10 52,28" fill="#22334B" stroke="#36D9FF" stroke-width="1" />
                        <polygon points="0,10 52,28 52,36 0,18" fill="#0D1522" />
                        <polygon points="104,10 52,28 52,36 104,18" fill="#2A3E5C" />
                        <circle cx="48" cy="24" r="1.8" fill="#36D9FF" />
                        <circle cx="58" cy="21" r="1.8" fill="#73ECFA" />
                    </g>

                    {{-- Uplink Beams Connecting Server to Cloud --}}
                    <path d="M100 95 L100 65 M120 90 L120 60 M140 95 L140 65" stroke="url(#beamGrad-{{ $concept }})" stroke-width="2" stroke-dasharray="4 2" />

                    {{-- 3D Volumetric Cloud --}}
                    <g transform="translate(60, 20)">
                        {{-- Cloud back shadow layer --}}
                        <ellipse cx="60" cy="46" rx="48" ry="18" fill="#287BFF" opacity="0.4" />
                        {{-- Cloud body shapes --}}
                        <circle cx="42" cy="40" r="22" fill="url(#cloudGrad-{{ $concept }})" />
                        <circle cx="70" cy="30" r="26" fill="url(#cloudGrad-{{ $concept }})" />
                        <circle cx="96" cy="42" r="19" fill="url(#cloudGrad-{{ $concept }})" />
                        <rect x="36" y="36" width="65" height="20" rx="10" fill="url(#cloudGrad-{{ $concept }})" />
                        {{-- Cloud Rim Highlight --}}
                        <path d="M30 46 C30 30 52 24 64 26 C72 14 96 18 102 34 C112 36 116 48 106 54" fill="none" stroke="#FFFFFF" stroke-width="2" opacity="0.9" />
                    </g>
                </svg>
                @break

            @case('network')
                {{-- 3D Miniature Network Installation: Router, Nodes, Data Paths --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="Network & Infrastructure 3D Visual">
                    <defs>
                        <linearGradient id="routerChassis-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#243550" />
                            <stop offset="100%" stop-color="#0D1522" />
                        </linearGradient>
                        <radialGradient id="nodeCyan-{{ $concept }}" cx="35%" cy="35%" r="65%">
                            <stop offset="0%" stop-color="#F7FAFF" />
                            <stop offset="40%" stop-color="#36D9FF" />
                            <stop offset="100%" stop-color="#0891B2" />
                        </radialGradient>
                        <radialGradient id="nodeRoyal-{{ $concept }}" cx="35%" cy="35%" r="65%">
                            <stop offset="0%" stop-color="#93C5FD" />
                            <stop offset="40%" stop-color="#287BFF" />
                            <stop offset="100%" stop-color="#0F225C" />
                        </radialGradient>
                    </defs>

                    <ellipse cx="120" cy="155" rx="65" ry="12" fill="#030812" opacity="0.6" />

                    {{-- 3D Network Router Base (Isometric) --}}
                    <g transform="translate(70, 95)">
                        <polygon points="0,16 50,-8 100,16 50,40" fill="url(#routerChassis-{{ $concept }})" stroke="#36D9FF" stroke-width="1.2" />
                        <polygon points="0,16 50,40 50,56 0,32" fill="#0D1522" />
                        <polygon points="100,16 50,40 50,56 100,32" fill="#0B2A55" />
                        {{-- Status LED Bar --}}
                        <circle cx="28" cy="33" r="1.5" fill="#36D9FF" />
                        <circle cx="36" cy="37" r="1.5" fill="#36D9FF" />
                        <circle cx="44" cy="41" r="1.5" fill="#22C55E" />
                        <circle cx="52" cy="44" r="1.5" fill="#36D9FF" />
                        {{-- 4 Dual-Band 3D Antennas --}}
                        <line x1="20" y1="6" x2="16" y2="-24" stroke="#C9D4E5" stroke-width="3" stroke-linecap="round" />
                        <circle cx="16" cy="-24" r="2.5" fill="#36D9FF" />
                        <line x1="42" y1="-3" x2="40" y2="-30" stroke="#C9D4E5" stroke-width="3" stroke-linecap="round" />
                        <circle cx="40" cy="-30" r="2.5" fill="#36D9FF" />
                        <line x1="62" y1="-3" x2="64" y2="-30" stroke="#C9D4E5" stroke-width="3" stroke-linecap="round" />
                        <circle cx="64" cy="-30" r="2.5" fill="#36D9FF" />
                        <line x1="84" y1="6" x2="88" y2="-24" stroke="#C9D4E5" stroke-width="3" stroke-linecap="round" />
                        <circle cx="88" cy="-24" r="2.5" fill="#36D9FF" />
                    </g>

                    {{-- Connected Network Nodes with Beams --}}
                    <path d="M40 70 Q80 85 120 115" stroke="#36D9FF" stroke-width="2" fill="none" opacity="0.8" />
                    <circle cx="40" cy="70" r="12" fill="url(#nodeCyan-{{ $concept }})" />

                    <path d="M200 65 Q160 85 120 115" stroke="#287BFF" stroke-width="2" fill="none" opacity="0.8" />
                    <circle cx="200" cy="65" r="12" fill="url(#nodeRoyal-{{ $concept }})" />

                    <path d="M120 35 L120 100" stroke="#805CFF" stroke-width="2" stroke-dasharray="4 3" fill="none" />
                    <circle cx="120" cy="35" r="10" fill="url(#nodeCyan-{{ $concept }})" />

                    {{-- Data Pulse Rings --}}
                    <circle cx="40" cy="70" r="18" fill="none" stroke="#36D9FF" stroke-width="1" opacity="0.4" />
                    <circle cx="200" cy="65" r="18" fill="none" stroke="#287BFF" stroke-width="1" opacity="0.4" />
                </svg>
                @break

            @case('ai')
                {{-- 3D Geometric AI Core & Structured Neural Interface --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="AI-Powered IT Services 3D Visual">
                    <defs>
                        <linearGradient id="aiFacetA-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#A78BFA" />
                            <stop offset="100%" stop-color="#805CFF" />
                        </linearGradient>
                        <linearGradient id="aiFacetB-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#67E8F9" />
                            <stop offset="100%" stop-color="#36D9FF" />
                        </linearGradient>
                        <linearGradient id="aiFacetC-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3B82F6" />
                            <stop offset="100%" stop-color="#1E40AF" />
                        </linearGradient>
                    </defs>

                    <ellipse cx="120" cy="155" rx="55" ry="10" fill="#030812" opacity="0.6" />

                    {{-- Neural Link Web --}}
                    <g opacity="0.6">
                        <line x1="120" y1="80" x2="60" y2="45" stroke="#36D9FF" stroke-width="1.5" />
                        <line x1="120" y1="80" x2="180" y2="45" stroke="#805CFF" stroke-width="1.5" />
                        <line x1="120" y1="80" x2="50" y2="115" stroke="#36D9FF" stroke-width="1.5" />
                        <line x1="120" y1="80" x2="190" y2="115" stroke="#805CFF" stroke-width="1.5" />
                    </g>

                    {{-- Neural Satellite Nodes --}}
                    <circle cx="60" cy="45" r="6" fill="#36D9FF" />
                    <circle cx="180" cy="45" r="6" fill="#805CFF" />
                    <circle cx="50" cy="115" r="5" fill="#36D9FF" />
                    <circle cx="190" cy="115" r="5" fill="#805CFF" />

                    {{-- Surrounding Orbital Data Rings --}}
                    <ellipse cx="120" cy="80" rx="58" ry="24" fill="none" stroke="#36D9FF" stroke-width="1.5" stroke-dasharray="6 4" transform="rotate(-15 120 80)" />
                    <ellipse cx="120" cy="80" rx="46" ry="38" fill="none" stroke="#805CFF" stroke-width="1.5" stroke-dasharray="4 4" transform="rotate(25 120 80)" />

                    {{-- 3D Geometric Faceted AI Core --}}
                    <g transform="translate(120, 80)">
                        {{-- Top Facet --}}
                        <polygon points="0,-36 28,-10 0,6 -28,-10" fill="url(#aiFacetB-{{ $concept }})" opacity="0.95" />
                        {{-- Right Facet --}}
                        <polygon points="0,6 28,-10 24,24 0,34" fill="url(#aiFacetA-{{ $concept }})" />
                        {{-- Left Facet --}}
                        <polygon points="0,6 -28,-10 -24,24 0,34" fill="url(#aiFacetC-{{ $concept }})" />
                        {{-- Center Core Glow --}}
                        <circle cx="0" cy="4" r="7" fill="#FFFFFF" opacity="0.9" filter="drop-shadow(0 0 8px #36D9FF)" />
                    </g>
                </svg>
                @break

            @case('web-development')
                {{-- 3D Dimensional Browser Window & Layered UI Panels --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="Web Development & Digital Solutions 3D Visual">
                    <defs>
                        <linearGradient id="browserChrome-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#1E293B" />
                            <stop offset="100%" stop-color="#0F172A" />
                        </linearGradient>
                        <linearGradient id="uiGlass-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#287BFF" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#0F225C" stop-opacity="0.9" />
                        </linearGradient>
                    </defs>

                    <ellipse cx="120" cy="155" rx="65" ry="10" fill="#030812" opacity="0.6" />

                    {{-- Background Main 3D Browser Window (Tilted) --}}
                    <g transform="translate(50, 30)">
                        <rect x="0" y="0" width="125" height="85" rx="6" fill="url(#browserChrome-{{ $concept }})" stroke="#36D9FF" stroke-width="1.2" />
                        {{-- Browser Header Dots --}}
                        <circle cx="10" cy="10" r="3" fill="#EF4444" />
                        <circle cx="18" cy="10" r="3" fill="#F59E0B" />
                        <circle cx="26" cy="10" r="3" fill="#22C55E" />
                        <rect x="36" y="6" width="60" height="8" rx="3" fill="#0D1522" />
                        {{-- Code Lines Simulation --}}
                        <rect x="12" y="24" width="35" height="4" rx="2" fill="#36D9FF" opacity="0.7" />
                        <rect x="12" y="32" width="55" height="4" rx="2" fill="#93C5FD" opacity="0.5" />
                        <rect x="12" y="40" width="45" height="4" rx="2" fill="#805CFF" opacity="0.6" />
                        <rect x="12" y="48" width="25" height="4" rx="2" fill="#36D9FF" opacity="0.5" />
                    </g>

                    {{-- Floating Layered Frontend Component Card (Pop-out 3D effect) --}}
                    <g transform="translate(100, 65)">
                        <rect x="0" y="0" width="95" height="65" rx="6" fill="url(#uiGlass-{{ $concept }})" stroke="#36D9FF" stroke-width="1.5" />
                        {{-- Mini Graph / Metric UI --}}
                        <rect x="10" y="10" width="45" height="6" rx="2" fill="#F7FAFF" />
                        <polyline points="10,48 26,38 42,42 58,26 74,32 85,20" fill="none" stroke="#36D9FF" stroke-width="2.5" stroke-linecap="round" />
                        <circle cx="85" cy="20" r="3" fill="#F7FAFF" />
                        <rect x="10" y="52" width="20" height="4" rx="2" fill="#73ECFA" opacity="0.6" />
                        <rect x="34" y="52" width="20" height="4" rx="2" fill="#73ECFA" opacity="0.6" />
                    </g>

                    {{-- 3D Code Symbol Accent --}}
                    <g transform="translate(42, 95)">
                        <circle cx="16" cy="16" r="16" fill="#287BFF" stroke="#36D9FF" stroke-width="1.5" />
                        <text x="16" y="22" font-family="monospace" font-size="14" font-weight="bold" fill="#F7FAFF" text-anchor="middle">&lt;/&gt;</text>
                    </g>
                </svg>
                @break

            @case('backup')
                {{-- 3D Backup Vault, Storage Server & Recovery Indicator --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="Backup & Disaster Recovery 3D Visual">
                    <defs>
                        <linearGradient id="vaultBody-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#243550" />
                            <stop offset="100%" stop-color="#0D1522" />
                        </linearGradient>
                        <linearGradient id="shieldMini-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#3B82F6" />
                            <stop offset="100%" stop-color="#287BFF" />
                        </linearGradient>
                    </defs>

                    <ellipse cx="120" cy="155" rx="60" ry="10" fill="#030812" opacity="0.6" />

                    {{-- Storage Array / Server Tower (3D Isometric) --}}
                    <g transform="translate(65, 55)">
                        <polygon points="0,20 55,-6 110,20 55,46" fill="#1E293B" stroke="#36D9FF" stroke-width="1" />
                        <polygon points="0,20 55,46 55,95 0,69" fill="#0D1522" />
                        <polygon points="110,20 55,46 55,95 110,69" fill="#0B2A55" />

                        {{-- Hot-swappable Drive Trays with Activity LEDs --}}
                        <line x1="15" y1="42" x2="48" y2="57" stroke="#36D9FF" stroke-width="2.5" />
                        <circle cx="51" cy="58" r="1.5" fill="#22C55E" />

                        <line x1="15" y1="52" x2="48" y2="67" stroke="#36D9FF" stroke-width="2.5" />
                        <circle cx="51" cy="68" r="1.5" fill="#22C55E" />

                        <line x1="15" y1="62" x2="48" y2="77" stroke="#36D9FF" stroke-width="2.5" />
                        <circle cx="51" cy="78" r="1.5" fill="#36D9FF" />
                    </g>

                    {{-- 3D Protective Recovery Shield in Foreground --}}
                    <g transform="translate(135, 80)">
                        <path d="M30 4 L55 16 C55 42 43 56 30 65 C17 56 5 42 5 16 Z" fill="url(#shieldMini-{{ $concept }})" stroke="#36D9FF" stroke-width="2" />
                        {{-- Circular Restore Arrow inside Shield --}}
                        <path d="M24 30 A10 10 0 1 1 36 38" fill="none" stroke="#F7FAFF" stroke-width="2.5" stroke-linecap="round" />
                        <polyline points="33,26 24,30 28,38" fill="none" stroke="#F7FAFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                </svg>
                @break

            @default
                {{-- 3D IT Support Workstation & Helpdesk Diagnostic Screen --}}
                <svg viewBox="0 0 240 180" class="w-full h-full max-h-40 filter drop-shadow-[0_12px_24px_rgba(7,20,38,0.7)]" aria-label="IT Support & Helpdesk 3D Visual">
                    <defs>
                        <linearGradient id="monitorFrame-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#243550" />
                            <stop offset="100%" stop-color="#0D1522" />
                        </linearGradient>
                        <linearGradient id="monitorScreen-{{ $concept }}" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#0E233E" />
                            <stop offset="100%" stop-color="#071A38" />
                        </linearGradient>
                        <linearGradient id="standSilver-{{ $concept }}" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#F7FAFF" />
                            <stop offset="50%" stop-color="#C9D4E5" />
                            <stop offset="100%" stop-color="#758CA5" />
                        </linearGradient>
                    </defs>

                    <ellipse cx="120" cy="158" rx="65" ry="10" fill="#030812" opacity="0.6" />

                    {{-- Stand Base & Stem --}}
                    <polygon points="98,148 142,148 138,154 102,154" fill="url(#standSilver-{{ $concept }})" />
                    <rect x="117" y="115" width="6" height="34" fill="url(#standSilver-{{ $concept }})" />

                    {{-- 3D Curved Ultrawide Display --}}
                    <rect x="50" y="42" width="140" height="78" rx="7" fill="url(#monitorFrame-{{ $concept }})" stroke="#36D9FF" stroke-width="1.4" />
                    <rect x="55" y="47" width="130" height="68" rx="4" fill="url(#monitorScreen-{{ $concept }})" />

                    {{-- Live Diagnostic Interface on Screen --}}
                    <rect x="62" y="54" width="40" height="5" rx="2" fill="#36D9FF" />
                    <rect x="62" y="62" width="28" height="3" rx="1.5" fill="#73ECFA" opacity="0.7" />

                    {{-- Telemetry Bar Graph --}}
                    <rect x="62" y="92" width="5" height="15" rx="1" fill="#287BFF" />
                    <rect x="70" y="86" width="5" height="21" rx="1" fill="#287BFF" />
                    <rect x="78" y="78" width="5" height="29" rx="1" fill="#36D9FF" />
                    <rect x="86" y="82" width="5" height="25" rx="1" fill="#36D9FF" />
                    <rect x="94" y="72" width="5" height="35" rx="1" fill="#73ECFA" />

                    {{-- System Waveform --}}
                    <polyline points="112,88 122,88 127,76 132,96 137,84 142,88 174,88" fill="none" stroke="#36D9FF" stroke-width="2" stroke-linecap="round" />

                    {{-- Floating 3D Support Headset Icon --}}
                    <g transform="translate(162, 28)">
                        <circle cx="16" cy="16" r="16" fill="#0B2A55" stroke="#36D9FF" stroke-width="1.5" />
                        <path d="M8 18 A8 8 0 0 1 24 18 V22 M8 18 V22 M24 22 A4 4 0 0 1 20 26 H17" fill="none" stroke="#36D9FF" stroke-width="2" stroke-linecap="round" />
                    </g>
                </svg>
        @endswitch
    </div>
</div>

