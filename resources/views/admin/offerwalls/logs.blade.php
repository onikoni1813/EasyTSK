<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">
                {{ $offerwall->icon_emoji }} {{ $offerwall->name }} <span class="text-primary-500">Logs</span>
            </h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Postback request history & debugging
            </p>
        </div>
        <a href="{{ route('admin.offerwalls.index') }}"
            class="bg-white/10 border border-white/5 px-5 py-3 rounded-2xl text-xs text-white font-bold hover:bg-white/20 transition-all inline-flex items-center gap-2 w-fit">
            ← Back to Offerwalls
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/5">
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Time</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">User</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Reward</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Points</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">TX ID</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">IP</th>
                    <th class="px-4 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Error</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($logs as $log)
                    @php
                        $statusColors = [
                            'success'           => 'emerald',
                            'duplicate'         => 'amber',
                            'invalid_signature' => 'rose',
                            'user_not_found'    => 'rose',
                            'not_found'         => 'slate',
                            'inactive'          => 'slate',
                            'reward_too_low'    => 'amber',
                            'failed'            => 'rose',
                        ];
                        $sc = $statusColors[$log->status] ?? 'slate';
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors" x-data="{ showPayload: false }">
                        <td class="px-4 py-3 text-[10px] font-bold text-slate-400 whitespace-nowrap">
                            {{ $log->created_at->format('M d H:i:s') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 bg-{{ $sc }}-500/10 border border-{{ $sc }}-500/20 text-[8px] font-black text-{{ $sc }}-400 rounded uppercase tracking-wider">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-[10px] font-bold text-white">
                            @if($log->user)
                                #{{ $log->user_id }} — {{ $log->user->name }}
                            @else
                                <span class="text-slate-500">{{ $log->user_id ? '#'.$log->user_id : '—' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[10px] font-bold text-amber-400">{{ $log->reward_raw }}</td>
                        <td class="px-4 py-3 text-[10px] font-black text-emerald-400">{{ $log->points_credited > 0 ? '+'.$log->points_credited : '—' }}</td>
                        <td class="px-4 py-3 text-[9px] font-mono text-slate-500 truncate max-w-[100px]">{{ $log->external_transaction_id ?? '—' }}</td>
                        <td class="px-4 py-3 text-[9px] font-mono text-slate-500">{{ $log->ip_address }}</td>
                        <td class="px-4 py-3">
                            @if($log->error_message)
                                <span class="text-[9px] font-bold text-rose-400 truncate block max-w-[150px]" title="{{ $log->error_message }}">{{ $log->error_message }}</span>
                            @endif
                            @if($log->raw_payload)
                                <button @click="showPayload = !showPayload" class="text-[8px] font-black text-primary-400 hover:underline uppercase tracking-widest mt-1">
                                    <span x-show="!showPayload">View Payload</span>
                                    <span x-show="showPayload" x-cloak>Hide</span>
                                </button>
                                <div x-show="showPayload" x-cloak class="mt-2 p-2 bg-white/5 rounded-lg max-w-[300px] overflow-x-auto">
                                    <pre class="text-[8px] font-mono text-slate-400 whitespace-pre-wrap">{{ json_encode($log->raw_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center text-slate-500 italic text-xs uppercase tracking-widest">
                            এখনো কোনো postback রিকোয়েস্ট আসেনি।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="mt-6">{{ $logs->links() }}</div>
    @endif
</x-admin-layout>
