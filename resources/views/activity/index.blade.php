<x-user-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight uppercase">অ্যাক্টিভিটি <span class="text-primary-500">হিস্টরি</span></h2>
            <p class="text-sm text-slate-400 font-medium mt-1">আপনার করা সকল কাজ এবং উইথড্র এর বিস্তারিত রিপোর্ট এখানে দেখুন।</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-primary-600/10 border border-primary-500/20 px-6 py-3 rounded-2xl text-primary-500">
                <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">মোট ব্যালেন্স</span>
                <span class="text-xl font-black tracking-tighter">৳ {{ number_format($user->points / $rate, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Activity Dual-Stream Hub -->
    <div class="glass-card p-10 rounded-[40px] shadow-sm flex flex-col overflow-hidden min-h-[600px]"
        x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'tasks' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10 pb-6 border-b border-white/5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-500/10 rounded-2xl flex items-center justify-center text-primary-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">অপারেশনাল লগ</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">টাস্ক এবং পেমেন্ট ট্র্যাকিং সিস্টেম</p>
                </div>
            </div>

            <div class="flex bg-white/5 p-1 rounded-2xl border border-white/5">
                <button @click="tab = 'tasks'"
                    :class="tab === 'tasks' ? 'bg-white text-dark-card shadow-lg' : 'text-slate-500 hover:text-slate-300'"
                    class="px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-[0.1em] transition-all duration-300">
                    কমপ্লিটেড টাস্ক
                </button>
                <button @click="tab = 'withdraws'"
                    :class="tab === 'withdraws' ? 'bg-white text-dark-card shadow-lg' : 'text-slate-500 hover:text-slate-300'"
                    class="px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-[0.1em] transition-all duration-300">
                    উইথড্র হিস্টরি
                </button>
            </div>
        </div>

        <!-- Task Stream -->
        <div x-show="tab === 'tasks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex-1">
            <div class="relative overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                            <th class="pb-5 px-4">মিশন</th>
                            <th class="pb-5 px-4 text-center">পুরস্কার</th>
                            <th class="pb-5 px-4 text-center">সময়</th>
                            <th class="pb-5 text-right">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($submissions as $sub)
                        <tr class="group hover:bg-white/[0.01] transition-colors">
                            <td class="py-6 px-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-primary-500 font-black text-[10px] border border-white/10 italic">
                                        ID:{{ $sub->task_id }}
                                    </div>
                                    <div class="max-w-[200px] md:max-w-md">
                                        <span class="text-sm font-black text-white block uppercase tracking-tight group-hover:text-primary-500 transition-colors truncate">{{ $sub->task->title }}</span>
                                        <span class="text-[9px] font-bold text-slate-500 mt-1 block uppercase tracking-tighter">Mission Category: {{ $sub->task->type }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <span class="text-sm font-black text-emerald-500 italic">{{ number_format($sub->task->reward_points ?? $sub->task->points) }} <span class="text-[9px] uppercase not-italic">Pts</span></span>
                            </td>
                            <td class="py-6 px-4 text-center font-bold text-xs text-slate-400">
                                {{ $sub->created_at->format('M d, Y') }}<br>
                                <span class="text-[9px] opacity-50 uppercase">{{ $sub->created_at->format('H:i') }} Zulu</span>
                            </td>
                            <td class="py-6 text-right">
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest
                                    {{ $sub->status == 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20 shadow-lg shadow-amber-900/10' : 
                                    ($sub->status == 'approved' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shadow-lg shadow-emerald-900/10' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20 shadow-lg shadow-rose-900/10') }}">
                                    {{ $sub->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center">
                                <p class="text-[11px] font-black text-slate-600 uppercase tracking-[0.3em]">No operational missions logged yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
            <div class="mt-10 pt-8 border-t border-white/5">
                {{ $submissions->appends(['tab' => 'tasks'])->links() }}
            </div>
            @endif
        </div>

        <!-- Withdraw Stream -->
        <div x-show="tab === 'withdraws'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="flex-1">
            <div class="relative overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                            <th class="pb-5 px-4">লগ টাইম</th>
                            <th class="pb-5 px-4 text-center">পেমেন্ট মেথড</th>
                            <th class="pb-5 px-4 text-center">টাকার পরিমাণ</th>
                            <th class="pb-5 text-right">ট্রানজেকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($withdrawals as $withdraw)
                        <tr class="group hover:bg-white/[0.01] transition-colors">
                            <td class="py-6 px-4">
                                <span class="text-xs font-black text-white block">{{ $withdraw->created_at->format('d F, Y') }}</span>
                                <span class="text-[9px] font-bold text-slate-500 block uppercase tracking-widest mt-1">{{ $withdraw->created_at->format('l') }}</span>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <span class="px-4 py-1.5 bg-white/5 border border-white/5 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:bg-primary-500/10 group-hover:text-primary-400 group-hover:border-primary-500/20 transition-all">
                                    {{ $withdraw->method }}
                                </span>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <span class="text-sm font-black text-white">৳ {{ number_format($withdraw->amount_bdt, 2) }}</span>
                            </td>
                            <td class="py-6 text-right">
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest
                                    {{ $withdraw->status == 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 
                                    ($withdraw->status == 'approved' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20') }}">
                                    {{ $withdraw->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center text-slate-600 uppercase tracking-[0.3em] text-[11px] font-black">উইথড্র হিস্টরি নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdrawals->hasPages())
            <div class="mt-10 pt-8 border-t border-white/5">
                {{ $withdrawals->appends(['tab' => 'withdraws'])->links() }}
            </div>
            @endif
        </div>
    </div>
</x-user-layout>