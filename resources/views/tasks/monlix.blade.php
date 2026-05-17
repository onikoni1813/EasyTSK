<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight uppercase">
            {{ setting('monlix_display_name', 'Bonus Wall') }}
        </h2>
        <p class="text-sm text-slate-400 font-medium mt-1">এই সেকশন থেকে সার্ভে, অফার এবং অ্যাপ ইনষ্টল সম্পন্ন করুন।
            ব্যালেন্স অটোমেটিক যোগ হবে।</p>
    </div>

    <!-- Enhanced Tabs Nav -->
    <div
        class="flex flex-wrap gap-4 mb-10 bg-dark-card/50 p-2.5 rounded-[32px] border border-white/5 shadow-2xl w-fit backdrop-blur-xl">
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

        @if(setting('is_social_tasks_active', '1') == '1')
            <a href="{{ route('tasks.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('tasks.index') ? 'bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">📱</span> সোশ্যাল টাস্ক
            </a>
        @endif
        @if(setting('is_timewall_active', '0') == '1')
            <a href="{{ route('timewall.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('timewall.index') ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white shadow-lg shadow-orange-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">🔥</span> প্রিমিয়াম টাস্ক
                @if($hasAvailableRegularTasks)
                    <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                @endif
            </a>
        @endif
        @if(setting('is_monlix_active', '0') == '1')
            <a href="{{ route('monlix.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('monlix.index') ? 'bg-gradient-to-r from-rose-600 to-rose-500 text-white shadow-lg shadow-rose-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">💎</span> বোনাস ওয়াল
                @if($hasAvailableRegularTasks)
                    <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                @endif
            </a>
        @endif
        @if(setting('is_adsterra_active', '0') == '1')
            <a href="{{ route('adsterra.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('adsterra.index') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">📺</span> অ্যাড ভিউ
                @if($hasAvailableRegularTasks)
                    <svg class="w-3 h-3 text-rose-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                @endif
            </a>
        @endif
    </div>

    <div class="p-0 bg-dark-card border border-white/5 rounded-[40px] shadow-sm overflow-hidden">
        <div class="p-8 pb-0">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-white uppercase flex items-center gap-3">
                    <span
                        class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center text-lg border border-rose-500/20">💎</span>
                    {{ setting('monlix_display_name', 'Bonus Wall') }}
                </h3>
                <span
                    class="text-[10px] font-black uppercase text-slate-500 tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">Secured
                    Connection Active</span>
            </div>
        </div>

        @if($monlixId)
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
                <p class="text-xl font-black text-white uppercase tracking-tight">API কানেকশন এরর!</p>
                <p class="text-sm text-slate-500 mt-2 font-medium">অ্যাডমিন এখনো Monlix App ID কনফিগার করেননি।</p>
            </div>
        @endif
    </div>
</x-user-layout>