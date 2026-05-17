<x-admin-layout>
    <x-slot name="title">
        Leaderboards
    </x-slot>

    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Top Performance Leaderboard</h1>
            <p class="text-xs font-bold text-slate-400 mt-2 uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Fast Indexed From cache (leaderboard_stats)
            </p>
        </div>

        <div class="bg-[#0f172a] p-1.5 rounded-2xl flex items-center border border-white/5 shadow-sm">
            <a href="{{ route('admin.leaderboard.index', ['period' => 'weekly']) }}"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $period == 'weekly' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                This Week
            </a>
            <a href="{{ route('admin.leaderboard.index', ['period' => 'monthly']) }}"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $period == 'monthly' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                This Month
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Earners Column -->
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 shadow-2xl relative overflow-hidden">
            <!-- Decorative blur -->
            <div
                class="absolute -left-20 -top-20 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="p-8 border-b border-white/5 flex items-center justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-500/20 text-primary-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white">Top Earners</h2>
                        <p class="text-[10px] font-black uppercase tracking-widest text-primary-500">Highest Paid Users
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-4 relative z-10">
                @if($topEarners->count() > 0)
                    <div class="space-y-2">
                        @foreach($topEarners as $index => $stat)
                                    <div
                                        class="group flex items-center justify-between p-4 bg-slate-800/50 hover:bg-slate-800 rounded-3xl border border-white/5 transition-all">
                                        <div class="flex items-center gap-4">
                                            <!-- Rank Badge -->
                                            <div class="w-10 h-10 rounded-[14px] flex items-center justify-center font-black text-sm
                                                    {{ $index == 0 ? 'bg-amber-400 text-amber-900 shadow-md shadow-amber-400/20' :
                            ($index == 1 ? 'bg-slate-300 text-slate-800 shadow-md shadow-slate-300/20' :
                                ($index == 2 ? 'bg-amber-700 text-yellow-100 shadow-md shadow-amber-700/20' :
                                    'bg-slate-900/50 text-slate-500 border border-white/5')) }}">
                                                #{{ $index + 1 }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-white">
                                                    {{ $stat->user->masked_name ?? 'Unknown User' }}</div>
                                                <div
                                                    class="text-[9px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-1">
                                                    UID: {{ $stat->user_id }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-black text-primary-500">৳
                                                {{ number_format($stat->total_amount_or_count, 2) }}</div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Earnings
                                            </div>
                                        </div>
                                    </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center text-slate-500 font-bold text-sm uppercase tracking-widest">No earning data
                        found for this period.</div>
                @endif
            </div>
        </div>

        <!-- Top Referrers Column -->
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 shadow-2xl relative overflow-hidden">
            <!-- Decorative blur -->
            <div
                class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="p-8 border-b border-white/5 flex items-center justify-between relative z-10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white">Top Referrers</h2>
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Most Invites</p>
                    </div>
                </div>
            </div>

            <div class="p-4 relative z-10">
                @if($topReferrers->count() > 0)
                    <div class="space-y-2">
                        @foreach($topReferrers as $index => $stat)
                                    <div
                                        class="group flex items-center justify-between p-4 bg-slate-800/50 hover:bg-slate-800 rounded-3xl border border-white/5 transition-all">
                                        <div class="flex items-center gap-4">
                                            <!-- Rank Badge -->
                                            <div class="w-10 h-10 rounded-[14px] flex items-center justify-center font-black text-sm
                                                    {{ $index == 0 ? 'bg-amber-400 text-amber-900 shadow-md shadow-amber-400/20' :
                            ($index == 1 ? 'bg-slate-300 text-slate-800 shadow-md shadow-slate-300/20' :
                                ($index == 2 ? 'bg-amber-700 text-yellow-100 shadow-md shadow-amber-700/20' :
                                    'bg-slate-900/50 text-slate-500 border border-white/5')) }}">
                                                #{{ $index + 1 }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-white">
                                                    {{ $stat->user->masked_name ?? 'Unknown User' }}</div>
                                                <div
                                                    class="text-[9px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-1">
                                                    UID: {{ $stat->user_id }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-black text-indigo-400">
                                                {{ number_format($stat->total_amount_or_count, 0) }}</div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Invited Users
                                            </div>
                                        </div>
                                    </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center text-slate-500 font-bold text-sm uppercase tracking-widest">No referral
                        data found for this period.</div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>