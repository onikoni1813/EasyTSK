<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight uppercase">
            {{ $offerwall->display_name }}
        </h2>
        <p class="text-sm text-slate-400 font-medium mt-1">এই সেকশন থেকে সার্ভে, কাজ এবং অফার সম্পন্ন করুন। ক্রেডিট অটোমেটিক আপনার একাউন্টে যোগ হবে।</p>
    </div>

    @php
        $hasAvailableRegularTasks = \App\Models\Task::where('is_active', true)
            ->whereNotIn('type', ['timewall', 'adsterra'])
            ->where('quota_remaining', '>', 0)
            ->whereDoesntHave('submissions', function ($query) {
                $query->where('user_id', auth()->id())
                    ->whereIn('status', ['pending', 'approved']);
            })
            ->exists();
    @endphp

    <!-- Enhanced Tabs Nav -->
    <div
        class="flex flex-wrap gap-4 mb-10 bg-dark-card/50 p-2.5 rounded-[32px] border border-white/5 shadow-2xl w-fit backdrop-blur-xl">
        @if(setting('is_social_tasks_active', '1') == '1')
            <a href="{{ route('tasks.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group text-slate-500 hover:bg-white/5 hover:text-slate-300">
                <span class="text-sm">📱</span> সোশ্যাল টাস্ক
            </a>
        @endif


        {{-- Dynamic Offerwalls --}}
        @php
            $dynamicOfferwalls = \App\Models\Offerwall::active()->ordered()->get();
        @endphp
        @foreach($dynamicOfferwalls as $ow)
            @php
                $colorMap = [
                    'indigo'  => 'from-indigo-600 to-indigo-500 shadow-indigo-900/40',
                    'emerald' => 'from-emerald-600 to-emerald-500 shadow-emerald-900/40',
                    'amber'   => 'from-amber-600 to-amber-500 shadow-amber-900/40',
                    'rose'    => 'from-rose-600 to-rose-500 shadow-rose-900/40',
                    'sky'     => 'from-sky-600 to-sky-500 shadow-sky-900/40',
                    'violet'  => 'from-violet-600 to-violet-500 shadow-violet-900/40',
                    'teal'    => 'from-teal-600 to-teal-500 shadow-teal-900/40',
                    'orange'  => 'from-orange-600 to-orange-500 shadow-orange-900/40',
                    'pink'    => 'from-pink-600 to-pink-500 shadow-pink-900/40',
                    'cyan'    => 'from-cyan-600 to-cyan-500 shadow-cyan-900/40',
                ];
                $isCurrentOw = isset($offerwall) && $offerwall->id === $ow->id;
                $activeClass = $isCurrentOw
                    ? 'bg-gradient-to-r '.($colorMap[$ow->color] ?? $colorMap['indigo']).' text-white shadow-lg scale-105 active:scale-95'
                    : 'text-slate-500 hover:bg-white/5 hover:text-slate-300';
            @endphp
            <a href="{{ route('offerwalls.show', $ow) }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ $activeClass }}">
                <span class="text-sm">{{ $ow->icon_emoji }}</span> {{ $ow->display_name }}
                @if($ow->requires_task_lock && $hasAvailableRegularTasks)
                    <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>

    @php $displayMode = $offerwall->display_mode ?? 'iframe'; @endphp

    {{-- ── iFrame section (shown for 'iframe' or 'both') ─────────────────── --}}
    @if(in_array($displayMode, ['iframe', 'both']))
        <div class="p-0 bg-dark-card border border-white/5 rounded-[40px] shadow-sm overflow-hidden @if($displayMode === 'both') mb-6 @endif">
            <div class="p-8 pb-0">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-white uppercase flex items-center gap-3">
                        <span
                            class="w-10 h-10 bg-{{ $offerwall->color }}-500/10 text-{{ $offerwall->color }}-500 rounded-xl flex items-center justify-center text-lg border border-{{ $offerwall->color }}-500/20">{{ $offerwall->icon_emoji }}</span>
                        {{ $offerwall->display_name }}
                        @if($displayMode === 'both')
                            <span class="text-[9px] font-black bg-white/10 text-slate-400 px-2 py-1 rounded-lg border border-white/10 tracking-widest ml-2">iFrame</span>
                        @endif
                    </h3>
                    <span
                        class="text-[10px] font-black uppercase text-slate-500 tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">Secured
                        Connection Active</span>
                </div>
            </div>

            @if($iframeUrl)
                <div class="relative w-full overflow-hidden" style="min-height: 85vh;">
                    <iframe src="{{ $iframeUrl }}" style="width: 100%; height: 85vh; border: none; display: block;"
                        allow="camera; microphone; geolocation"></iframe>
                </div>
            @else
                <div class="py-24 text-center">
                    <div
                        class="w-20 h-20 bg-rose-500/10 text-rose-500 rounded-[24px] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-500/20">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-xl font-black text-white uppercase tracking-tight">iFrame কানেকশন সেটআপ হয়নি!</p>
                    <p class="text-sm text-slate-500 mt-2 font-medium">অ্যাডমিন এখনো এই অফারওয়ালের iFrame URL কনফিগার করেননি।</p>
                </div>
            @endif
        </div>
    @endif

    {{-- ── Widget section (shown for 'widget' or 'both') ──────────────────── --}}
    @if(in_array($displayMode, ['widget', 'both']))
        <div class="p-0 bg-dark-card border border-white/5 rounded-[40px] shadow-sm overflow-hidden">
            <div class="p-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-white uppercase flex items-center gap-3">
                        <span class="w-10 h-10 bg-violet-500/10 text-violet-500 rounded-xl flex items-center justify-center text-lg border border-violet-500/20">🧩</span>
                        {{ $offerwall->display_name }}
                        <span class="text-[9px] font-black bg-violet-500/20 text-violet-400 px-2 py-1 rounded-lg border border-violet-500/20 tracking-widest">Widget</span>
                    </h3>
                    <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">Secured Connection Active</span>
                </div>
            </div>

            @if($widgetScript)
                {{-- Render the widget embed code. The {user_id} has already been replaced server-side. --}}
                <div class="px-8 pb-8 widget-container overflow-x-auto w-full">
                    {!! $widgetScript !!}
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="w-20 h-20 bg-violet-500/10 text-violet-500 rounded-[24px] flex items-center justify-center mx-auto mb-6 shadow-sm border border-violet-500/20">
                        <span class="text-4xl">🧩</span>
                    </div>
                    <p class="text-xl font-black text-white uppercase tracking-tight">Widget সেটআপ হয়নি!</p>
                    <p class="text-sm text-slate-500 mt-2 font-medium">অ্যাডমিন এখনো এই অফারওয়ালের Widget Script কনফিগার করেননি।</p>
                </div>
            @endif
        </div>
    @endif


    @if($offerwall->description || $offerwall->platform_share_pct > 0)
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($offerwall->description)
                <div class="p-8 bg-primary-500/10 rounded-[32px] border border-primary-500/20 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary-500/10 rounded-full blur-3xl"></div>
                    <h4 class="text-sm font-black text-primary-500 uppercase mb-3 flex items-center gap-2 relative">
                        কিভাবে কাজ করবেন?
                        <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                    </h4>
                    <p class="text-xs text-primary-400 font-bold leading-relaxed relative">{{ $offerwall->description }}</p>
                </div>
            @endif


        </div>
    @endif
</x-user-layout>
