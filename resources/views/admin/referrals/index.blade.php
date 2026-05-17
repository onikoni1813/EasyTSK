<x-admin-layout>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-indigo-500 tracking-tighter uppercase flex items-center gap-3">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                Referral Logs
            </h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">
                Automated Referral System Track & Trace
            </p>
        </div>
    </div>

    <div class="bg-white/5 border border-white/5 rounded-[32px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        <th class="px-6 py-5 border-b border-white/5 rounded-tl-[32px]">Log Time</th>
                        <th class="px-6 py-5 border-b border-white/5">Referrer</th>
                        <th class="px-6 py-5 border-b border-white/5">Referred User</th>
                        <th class="px-6 py-5 border-b border-white/5 text-center">Auto-Unlock Status</th>
                        <th class="px-6 py-5 border-b border-white/5 text-right rounded-tr-[32px]">Bonus Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($referrals as $log)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-6 text-xs text-slate-300 font-bold whitespace-nowrap">
                                {{ $log->created_at->format('d M, Y') }} <br>
                                <span
                                    class="text-[10px] font-black text-slate-500 uppercase">{{ $log->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-6">
                                @if($log->referrer)
                                    <a href="{{ route('admin.users.show', $log->referrer->id) }}"
                                        class="text-xs font-black text-indigo-500 hover:text-indigo-400 transition-colors">
                                        {{ $log->referrer->name }}
                                    </a>
                                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Host</div>
                                @else
                                    <span class="text-xs text-slate-500 font-bold inline-flex items-center gap-1">Deleted
                                        User</span>
                                @endif
                            </td>
                            <td class="px-6 py-6">
                                @if($log->referredUser)
                                    <a href="{{ route('admin.users.show', $log->referredUser->id) }}"
                                        class="text-xs font-black text-emerald-500 hover:text-emerald-400 transition-colors">
                                        {{ $log->referredUser->name }}
                                    </a>
                                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">
                                        Earned ৳ {{ number_format($log->referredUser->total_earned_lifetime, 2) }}
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 font-bold inline-flex items-center gap-1">Deleted
                                        User</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($log->status === 'Unlocked')
                                    <span
                                        class="inline-flex py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                                        Auto-Unlocked
                                    </span>
                                @else
                                    <span
                                        class="inline-flex py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                        Pending Target (৳ {{ $log->unlock_threshold }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <div
                                    class="text-lg font-black {{ $log->status === 'Unlocked' ? 'text-primary-500' : 'text-slate-400' }}">
                                    {{ number_format($log->bonus_points) }} Pts
                                </div>
                                @if($log->status === 'Unlocked' && $log->unlocked_at)
                                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">
                                        Unlocked: {{ $log->unlocked_at->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 bg-white/5 rounded-3xl text-slate-500 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-300 uppercase tracking-widest mb-1">No Referral
                                    Data</h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">There are no
                                    referral records currently synced in the system.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($referrals->hasPages())
            <div class="p-6 border-t border-white/5 bg-white/[0.02]">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>