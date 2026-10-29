{{-- 3D IT Support Command Center Telemetry Overlay
     Restrained glassmorphic status panels staged with the 3D Command Center.
     Pointer-transparent, viewport-anchored, solid-color design palette. --}}
<div id="global-hud" class="global-hud pointer-events-none" aria-hidden="true">
    <div class="global-hud-box">
        {{-- Floating Translucent Telemetry Panels --}}
        <div class="global-hud-chip chip-a backdrop-blur-md bg-[#0B2A55]/75 border border-[#36D9FF]/30 text-[#F7FAFF] shadow-lg shadow-[#071A38]/50">
            <span class="w-2 h-2 rounded-full bg-[#36D9FF] animate-pulse"></span>
            <span class="font-mono text-[10px] tracking-wider font-semibold">24/7 SOC MONITORING</span>
        </div>

        <div class="global-hud-chip chip-b backdrop-blur-md bg-[#0B2A55]/75 border border-[#287BFF]/40 text-[#F7FAFF] shadow-lg shadow-[#071A38]/50">
            <svg class="w-3.5 h-3.5 text-[#36D9FF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="M5 12.55a11 11 0 0114.08 0M8.53 16.11a6 6 0 016.95 0M12 20h.01"/>
            </svg>
            <span class="font-mono text-[10px] tracking-wider font-semibold text-[#C9D4E5]">NETWORK HEALTH: <strong class="text-[#36D9FF]">OPTIMAL</strong></span>
        </div>

        <div class="global-hud-chip chip-c backdrop-blur-md bg-[#0B2A55]/75 border border-[#36D9FF]/30 text-[#F7FAFF] shadow-lg shadow-[#071A38]/50">
            <svg class="w-3.5 h-3.5 text-[#73ECFA]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-mono text-[10px] tracking-wider font-semibold text-[#F7FAFF]">ZERO-TRUST SHIELD: <span class="text-[#36D9FF]">ACTIVE</span></span>
        </div>

        <div class="global-hud-chip chip-d backdrop-blur-md bg-[#0B2A55]/75 border border-[#805CFF]/40 text-[#F7FAFF] shadow-lg shadow-[#071A38]/50">
            <svg class="w-3.5 h-3.5 text-[#805CFF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="font-mono text-[10px] tracking-wider font-semibold text-[#C9D4E5]">RAPID SLA: <strong class="text-[#805CFF]">&lt;15 MIN</strong></span>
        </div>
    </div>
</div>

