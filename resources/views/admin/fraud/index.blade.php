<x-admin-layout>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-rose-500 tracking-tighter uppercase flex items-center gap-3">
                <svg class="w-8 h-8 animate-pulse text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                Security Radar 🛡️
            </h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">
                Multi-accounting & IP Fraud Interception Center
            </p>
        </div>
    </div>

    @if(session('success'))
        <div
            class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white/5 border border-white/5 rounded-[32px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 text-[10px] uppercase font-black tracking-[0.2em] text-slate-500">
                        <th class="px-6 py-5 border-b border-white/5 rounded-tl-[32px]">Log Time</th>
                        <th class="px-6 py-5 border-b border-white/5">Trigger Factor</th>
                        <th class="px-6 py-5 border-b border-white/5">Device / IP Data</th>
                        <th class="px-6 py-5 border-b border-white/5 text-center">Ghost Contact</th>
                        <th class="px-6 py-5 border-b border-white/5 text-center">Original Suspect</th>
                        <th class="px-6 py-5 border-b border-white/5 text-right rounded-tr-[32px]">Direct Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-6 text-xs text-slate-300 font-bold whitespace-nowrap">
                                {{ $log->created_at->format('d M, Y') }} <br>
                                <span
                                    class="text-[10px] font-black text-slate-500 uppercase">{{ $log->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-6 font-bold text-sm text-white">
                                <span
                                    class="inline-flex py-1.5 px-3 rounded-lg text-[10px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-500 border border-rose-500/20 shadow-[0_0_15px_rgba(244,63,94,0.1)]">
                                    {{ $log->reason }}
                                </span>
                            </td>
                            <td class="px-6 py-6 min-w-[200px]">
                                <div class="space-y-1">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">IP: <span
                                            class="text-white">{{ $log->ip_address ?? 'N/A' }}</span></div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Fingerprint: <span
                                            class="text-white truncate block max-w-[150px]">{{ $log->device_fingerprint ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center text-xs font-bold text-slate-300">
                                {{ $log->attempted_email ?: 'No Email / Guest' }}
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($log->originalUser)
                                    <div class="inline-flex flex-col items-center">
                                        <a href="{{ route('admin.users.show', $log->originalUser->id) }}"
                                            class="text-xs font-black hover:text-primary-400 transition-colors {{ $log->originalUser->is_banned ? 'text-rose-500 line-through' : 'text-primary-500 underline underline-offset-4 decoration-primary-500/30' }}">
                                            {{ $log->originalUser->name }}
                                        </a>
                                        @if($log->originalUser->is_banned)
                                            <span
                                                class="mt-1 text-[9px] bg-rose-500/20 text-rose-500 px-2 rounded font-black uppercase tracking-widest">Already
                                                Banned</span>
                                        @else
                                            <span class="mt-1 text-[9px] text-slate-500 uppercase tracking-widest font-black">Link
                                                Match Found</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">NO DIRECT
                                        MATCH / HIDDEN</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.fraud.ban', $log->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Permanently ban the original user (if found) and blacklist this Device/IP completely?')"
                                            class="w-8 h-8 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-lg hover:shadow-rose-900/40"
                                            title="One-Click Ban & Blacklist">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.fraud.destroy', $log->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Dismiss this log? Avoid if it is actual fraud!')"
                                            class="w-8 h-8 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl flex items-center justify-center transition-all"
                                            title="Dismiss Log">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 bg-white/5 rounded-3xl text-slate-500 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black text-slate-300 uppercase tracking-widest mb-1">Radar is clear
                                </h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">No
                                    multi-accounting attempts detected currently.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-6 border-t border-white/5 bg-white/[0.02]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>