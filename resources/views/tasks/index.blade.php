<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight uppercase"> <span
                class="text-primary-500">কাজসমূহ</span></h2>
        <p class="text-sm text-slate-400 font-medium mt-1">সঠিকভাবে কাজ সম্পন্ন করুন এবং নিশ্চিত পেমেন্ট বুঝে নিন।</p>
    </div>

    @php
        $socialActive = setting('is_social_tasks_active', '1') == '1';
        $timewallActive = setting('is_timewall_active', '0') == '1';
        $monlixActive = setting('is_monlix_active', '0') == '1';
        $adsterraActive = setting('is_adsterra_active', '0') == '1';
    @endphp

    <!-- Enhanced Tabs Nav -->
    <div
        class="flex flex-wrap gap-4 mb-10 bg-dark-card/50 p-2.5 rounded-[32px] border border-white/5 shadow-2xl w-fit backdrop-blur-xl">
        <a href="{{ $socialActive ? route('tasks.index') : route('module.maintenance') }}"
            class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('tasks.index') ? 'bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }} {{ !$socialActive ? 'opacity-60' : '' }}">
            <span class="text-sm">📱</span> সোশ্যাল টাস্ক
            @if(!$socialActive) <span
            class="text-[8px] bg-rose-500/20 text-rose-400 px-1.5 py-0.5 rounded-full">OFF</span> @endif
        </a>
        @php
            $hasAvailableMandatoryTasks = \App\Models\Task::where('is_active', true)
                ->where('is_optional', false)
                ->whereNotIn('type', ['timewall', 'adsterra'])
                ->where('quota_remaining', '>', 0)
                ->whereDoesntHave('submissions', function ($query) {
                    $query->where('user_id', auth()->id())
                        ->whereIn('status', ['pending', 'approved']);
                })
                ->exists();
        @endphp

        <a href="{{ $timewallActive ? route('timewall.index') : route('module.maintenance') }}"
            class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('timewall.index') ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white shadow-lg shadow-orange-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }} {{ !$timewallActive ? 'opacity-60' : '' }}">
            <span class="text-sm">🔥</span> প্রিমিয়াম টাস্ক
            @if($hasAvailableMandatoryTasks)
                <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            @endif
            @if(!$timewallActive) <span
            class="text-[8px] bg-rose-500/20 text-rose-400 px-1.5 py-0.5 rounded-full">OFF</span> @endif
        </a>
        <a href="{{ $monlixActive ? route('monlix.index') : route('module.maintenance') }}"
            class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('monlix.index') ? 'bg-gradient-to-r from-rose-600 to-rose-500 text-white shadow-lg shadow-rose-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }} {{ !$monlixActive ? 'opacity-60' : '' }}">
            <span class="text-sm">💎</span> বোনাস ওয়াল
            @if($hasAvailableMandatoryTasks)
                <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            @endif
            @if(!$monlixActive) <span
            class="text-[8px] bg-rose-500/20 text-rose-400 px-1.5 py-0.5 rounded-full">OFF</span> @endif
        </a>
        <a href="{{ $adsterraActive ? route('adsterra.index') : route('module.maintenance') }}"
            class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('adsterra.index') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }} {{ !$adsterraActive ? 'opacity-60' : '' }}">
            <span class="text-sm">📺</span> অ্যাড ভিউ
            @if($hasAvailableMandatoryTasks)
                <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            @endif
            @if(!$adsterraActive) <span
            class="text-[8px] bg-rose-500/20 text-rose-400 px-1.5 py-0.5 rounded-full">OFF</span> @endif
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($tasks as $task)
            <div
                class="relative p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 overflow-hidden group flex flex-col h-full">
                <!-- Kinetic Decoration -->
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
                </div>

                <div class="flex justify-between items-start mb-6 relative">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span
                            class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-primary-500/10 text-primary-500 border border-primary-500/20">
                            {{ strtoupper($task->type) }} SIGNAL
                        </span>
                        @if($task->requires_text_proof && !$task->secret_code)
                            <span
                                class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded-xl bg-violet-500/10 text-violet-400 border border-violet-500/20 flex items-center gap-1">
                                🔐 ব্লগ কোড
                            </span>
                        @elseif($task->requires_text_proof && $task->secret_code)
                            <span
                                class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 flex items-center gap-1">
                                🔑 স্ট্যাটিক কোড
                            </span>
                        @endif
                        @if($task->requires_image_proof)
                            <span
                                class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center gap-1">
                                📸 স্ক্রিনশট
                            </span>
                        @endif
                    </div>
                    <span class="text-xl font-black text-primary-500 flex items-center gap-1 shrink-0">
                        {{ number_format($task->points) }}
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest">Pts</span>
                    </span>
                </div>

                <div class="flex-1 relative">
                    <h5
                        class="mb-3 text-lg font-black tracking-tight text-white line-clamp-2 group-hover:text-primary-500 transition-colors">
                        {{ $task->title }}
                    </h5>
                    <p class="mb-8 text-xs font-bold text-slate-400 line-clamp-3 leading-relaxed">
                        {{ Str::limit($task->description, 100) }}
                    </p>

                    {{-- Slot Progress Bar --}}
                    @php
                        $slotUsed = $task->quota_max - $task->quota_remaining;
                        $slotPct = $task->quota_max > 0 ? min(100, ($slotUsed / $task->quota_max) * 100) : 0;
                        $slotBar = $slotPct >= 100 ? 'bg-rose-500' : ($slotPct >= 75 ? 'bg-amber-400' : 'bg-emerald-500');
                        $slotText = $slotPct >= 100 ? 'text-rose-400' : ($slotPct >= 75 ? 'text-amber-400' : 'text-emerald-400');
                    @endphp
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">স্লট পূর্ণতা</span>
                            <span class="text-[9px] font-black {{ $slotText }} uppercase tracking-widest">
                                {{ $task->quota_remaining }} বাকি / {{ $task->quota_max }} মোট
                            </span>
                        </div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $slotBar }} rounded-full transition-all duration-500"
                                style="width: {{ $slotPct }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="relative mt-auto">
                    <a href="{{ route('tasks.show', $task) }}"
                        class="inline-flex items-center justify-center w-full px-6 py-4 text-xs font-black text-center text-white bg-primary-600 rounded-[24px] hover:bg-white hover:text-dark-card transition-all shadow-xl shadow-primary-900/40 group/btn">
                        <span class="uppercase tracking-widest">🚀 কাজটি শুরু করুন</span>
                        <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center py-24 bg-dark-card border border-white/5 rounded-[40px] shadow-sm">
                <div
                    class="w-24 h-24 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-center text-slate-700 mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xl font-black text-white">আপাতত কোনো টাস্ক নেই</p>
                <p class="text-sm text-slate-500 font-medium mt-2 italic">কিছুক্ষণ পর আবার চেক করুন। নতুন কাজ যোগ করা হচ্ছে।
                </p>
            </div>
        @endforelse
    </div>

    @if($tasks->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $tasks->links() }}
        </div>
    @endif
</x-user-layout>