<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight uppercase">
            {{ setting('adsterra_display_name', 'Ads View') }}
        </h2>
        <p class="text-sm text-slate-400 font-medium mt-1">এই সেকশন থেকে বিজ্ঞাপন দেখে পয়েন্ট আয় করুন। ক্রেডিট অটোমেটিক
            আপনার একাউন্টে যোগ হবে।</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($tasks as $task)
            <div
                class="relative p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 overflow-hidden group">
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
                </div>

                <div class="flex justify-between items-start mb-6 relative">
                    <span
                        class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        {{ strtoupper(setting('adsterra_display_name', 'Ads View')) }}
                    </span>
                    <span class="text-xl font-black text-primary-500 flex items-center gap-1">
                        {{ number_format($task->points) }}
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest">Pts</span>
                    </span>
                </div>

                <h5
                    class="mb-3 text-lg font-black tracking-tight text-white line-clamp-1 group-hover:text-primary-500 transition-colors relative">
                    {{ $task->title }}
                </h5>
                <p class="mb-8 text-xs font-bold text-slate-400 line-clamp-2 leading-relaxed relative">
                    {{ $task->description ?: 'অ্যাডটি ক্লিক করুন এবং ৩০ সেকেন্ড অপেক্ষা করুন কোড পাওয়ার জন্য।' }}
                </p>

                <a href="{{ route('adsterra.gateway', $task) }}"
                    class="inline-flex items-center justify-center w-full px-6 py-4 text-xs font-black text-center text-white bg-primary-600 rounded-[24px] hover:bg-white hover:text-dark-card transition-all shadow-xl shadow-primary-900/40 relative group">
                    <span class="uppercase tracking-widest">📺 অ্যাডটি দেখুন</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
            </div>
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center py-32 bg-white/5 border-2 border-dashed border-white/5 rounded-[48px]">
                <div
                    class="w-20 h-20 bg-dark-card rounded-[24px] flex items-center justify-center text-slate-600 mb-6 shadow-sm border border-white/5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <p class="text-lg font-black text-white uppercase tracking-tight">আপাতত কোনো অ্যাড নেই</p>
                <p class="text-xs text-slate-500 mt-2 font-medium">নতুন অ্যাডের জন্য পরে আবার চেষ্টা করুন।</p>
            </div>
        @endforelse
    </div>
</x-user-layout>