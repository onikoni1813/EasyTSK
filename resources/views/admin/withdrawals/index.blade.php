<x-admin-layout>
    <div x-data="{ approveShow: false, rejectShow: false, currentId: null }">
        <!-- Header Section -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Ledger <span
                        class="text-rose-500">Audit</span></h1>
                <p
                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                    Pending financial disbursement protocols
                </p>
            </div>
            <div class="hidden md:flex gap-4">
                <div class="glass-card px-5 py-2.5">
                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Pending Liability:
                        ৳{{ number_format($withdrawals->where('status', 'pending')->sum('amount_bdt'), 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Disbursement Matrix -->
        <div class="glass-card overflow-hidden min-h-[400px]">
            <!-- Desktop Table (visible on md+) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                                Network Agent</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Settlement Hub</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Total Amount</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Fee</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Receivable</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Log Time</th>
                            <th
                                class="px-4 md:px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                                Authorizations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($withdrawals as $withdrawal)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-primary-500/10 text-primary-500 border border-primary-500/20 rounded-2xl flex items-center justify-center font-black text-xs group-hover:scale-110 transition-transform uppercase">
                                            {{ substr(optional($withdrawal->user)->name ?? 'NA', 0, 2) }}
                                        </div>
                                        <div>
                                            <div
                                                class="font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors">
                                                {{ optional($withdrawal->user)->name ?? 'N/A' }}
                                            </div>
                                            <div
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">
                                                ID: #{{ $withdrawal->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="font-black text-emerald-400 tracking-widest text-sm font-mono">{{ $withdrawal->account }}</span>
                                        <span
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $withdrawal->method }}</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="font-black text-white text-lg tracking-tighter">৳{{ number_format($withdrawal->amount_bdt, 2) }}</span>
                                        <span
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gross
                                            Debit</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="font-black text-rose-500 text-sm tracking-tighter">৳{{ number_format($withdrawal->fee_amount, 2) }}</span>
                                        <span
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Protocol
                                            Fee</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span
                                            class="font-black text-emerald-400 text-lg tracking-tighter">৳{{ number_format($withdrawal->receivable_amount, 2) }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Net
                                            Settlement</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-center">
                                    <div
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                        {{ $withdrawal->created_at->format('M d, Y') }}<br>
                                        <span
                                            class="text-[9px] opacity-50 font-bold uppercase">{{ $withdrawal->created_at->format('H:i') }}
                                            Zulu</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.users.show', $withdrawal->user) }}"
                                            class="px-4 py-2.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/20">
                                            Profile
                                        </a>
                                        <button type="button"
                                            class="px-6 py-2.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/20"
                                            @click="currentId = '{{ $withdrawal->id }}'; approveShow = true">Authorize</button>
                                        <button type="button"
                                            class="px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40"
                                            @click="currentId = '{{ $withdrawal->id }}'; rejectShow = true">Void</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-8 py-32 text-center text-slate-500 italic text-xs uppercase tracking-widest">
                                    No pending disbursement cycles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (visible below md) -->
            <div class="md:hidden divide-y divide-white/5">
                @forelse($withdrawals as $withdrawal)
                    <div class="p-6 space-y-6">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center font-black text-xs uppercase">
                                    {{ substr(optional($withdrawal->user)->name ?? 'NA', 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-black text-white uppercase tracking-tight">
                                        {{ optional($withdrawal->user)->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">
                                        #{{ $withdrawal->id }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-emerald-400 text-lg">
                                    ৳{{ number_format($withdrawal->receivable_amount, 2) }}</div>
                                <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Receivable</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                <span
                                    class="block text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Hub/Account</span>
                                <span class="font-black text-white text-xs">{{ $withdrawal->account }}</span>
                                <span
                                    class="block text-[8px] font-bold text-slate-400 uppercase mt-0.5">{{ $withdrawal->method }}</span>
                            </div>
                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                <span
                                    class="block text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Gross/Fee</span>
                                <span
                                    class="font-black text-white text-xs">৳{{ number_format($withdrawal->amount_bdt, 2) }}</span>
                                <span
                                    class="block text-[8px] font-bold text-rose-500 uppercase mt-0.5">৳{{ number_format($withdrawal->fee_amount, 2) }}
                                    Fee</span>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.users.show', $withdrawal->user) }}"
                                class="flex-1 py-3.5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-500 transition-all text-center">
                                Profile
                            </a>
                            <button type="button"
                                class="flex-1 py-3.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all"
                                @click="currentId = '{{ $withdrawal->id }}'; approveShow = true">Authorize</button>
                            <button type="button"
                                class="flex-1 py-3.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-all"
                                @click="currentId = '{{ $withdrawal->id }}'; rejectShow = true">Void</button>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-20 text-center">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">No pending disbursement
                            cycles</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($withdrawals->hasPages())
            <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                {{ $withdrawals->links() }}
            </div>
        @endif

        <!-- Approve Modal -->
        <div id="approveModal" x-show="approveShow" x-cloak
            class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-card w-full max-w-md p-10 transform scale-110 animate-in fade-in zoom-in duration-200">
                <div
                    class="w-16 h-16 bg-primary-500/10 text-primary-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white text-center mb-2 uppercase tracking-tight">Authorize
                    Payment
                </h3>
                <p
                    class="text-[10px] font-black text-slate-400 text-center mb-8 uppercase tracking-widest leading-relaxed">
                    Provide Transaction ID or Payment Confirmation Message.</p>

                <form
                    :action="`{{ route('admin.withdrawals.approve', 'REPLACE_ID') }}`.replace('REPLACE_ID', currentId)"
                    method="POST">
                    @csrf
                    <input type="text" name="admin_feedback" required placeholder="TrxID: 5X89KLP0..."
                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-sm focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400 mb-8">

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="approveShow = false"
                            class="py-4 bg-white/5 text-slate-400 font-black rounded-2xl uppercase tracking-widest hover:bg-white/10 transition-all text-[11px]">Abort</button>
                        <button type="submit"
                            class="py-4 bg-primary-600 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/40 text-[11px]">Authorize
                            Now</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="rejectModal" x-show="rejectShow" x-cloak
            class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-card w-full max-w-md p-10 transform scale-110 animate-in fade-in zoom-in duration-200">
                <div
                    class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white text-center mb-2 uppercase tracking-tight">Void
                    Disbursement
                </h3>
                <p
                    class="text-[10px] font-black text-slate-400 text-center mb-8 uppercase tracking-widest leading-relaxed">
                    System will auto-notify the agent of the operational rejection reason.</p>

                <form :action="`{{ route('admin.withdrawals.reject', 'REPLACE_ID') }}`.replace('REPLACE_ID', currentId)"
                    method="POST">
                    @csrf
                    <textarea name="admin_feedback" rows="4" required
                        placeholder="Specify the security violation or error..."
                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-sm focus:bg-white/10 focus:ring-rose-500 focus:border-rose-500 transition-all placeholder:text-slate-400 mb-8"></textarea>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="rejectShow = false"
                            class="py-4 bg-white/5 text-slate-400 font-black rounded-2xl uppercase tracking-widest hover:bg-white/10 transition-all text-[11px]">Abort</button>
                        <button type="submit"
                            class="py-4 bg-rose-600 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40 text-[11px]">Execute
                            Void</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>