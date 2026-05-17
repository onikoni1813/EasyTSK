<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Support <span
                    class="text-primary-500">Center</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Inbound agent communications & incident reports
            </p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="glass-card px-5 py-2.5">
                <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest">Active Tickets:
                    {{ $tickets->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Ticket Matrix -->
    <div class="glass-card overflow-hidden min-h-[400px]">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Inbound
                            Agent</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Subject
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Status</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Arrival Log</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                            Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center font-black text-xs group-hover:scale-110 transition-transform uppercase text-primary-500">
                                        {{ substr(optional($ticket->user)->name ?? 'NA', 0, 2) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors">
                                            {{ optional($ticket->user)->name ?? 'N/A' }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">
                                            {{ optional($ticket->user)->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-[300px]">
                                    <div
                                        class="font-black text-white uppercase tracking-tight leading-tight truncate italic">
                                        "{{ $ticket->subject }}"</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID:
                                        #INF-{{ $ticket->id }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($ticket->status === 'open')
                                    <span
                                        class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full text-[9px] font-black text-amber-500 uppercase tracking-widest">Awaiting
                                        Response</span>
                                @elseif($ticket->status === 'responded')
                                    <span
                                        class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-[9px] font-black text-indigo-500 uppercase tracking-widest">System
                                        Responded</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-black text-slate-400 uppercase tracking-widest">Protocol
                                        Closed</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                    {{ $ticket->created_at->format('M d, Y') }}<br>
                                    <span
                                        class="text-[9px] opacity-50 font-bold uppercase">{{ $ticket->created_at->format('H:i') }}
                                        Zulu</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.support.show', $ticket) }}"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/20 transform hover:-translate-y-1">
                                    Open Comm
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                        </path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-8 py-32 text-center text-slate-500 italic text-xs uppercase tracking-widest">No
                                inbound comm signals detected</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (visible below md) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($tickets as $ticket)
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center font-black text-xs uppercase text-primary-500">
                                {{ substr(optional($ticket->user)->name ?? 'NA', 0, 2) }}
                            </div>
                            <div>
                                <div class="font-black text-white uppercase tracking-tight text-xs">
                                    {{ optional($ticket->user)->name ?? 'N/A' }}</div>
                                <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic">ID:
                                    #INF-{{ $ticket->id }}</div>
                            </div>
                        </div>
                        @if($ticket->status === 'open')
                            <span
                                class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 rounded-full text-[7px] font-black text-amber-500 uppercase tracking-widest">Awaiting</span>
                        @elseif($ticket->status === 'responded')
                            <span
                                class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-[7px] font-black text-indigo-500 uppercase tracking-widest">Responded</span>
                        @else
                            <span
                                class="px-2 py-0.5 bg-white/5 border border-white/10 rounded-full text-[7px] font-black text-slate-400 uppercase tracking-widest">Closed</span>
                        @endif
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <div class="text-[9px] font-black text-white uppercase italic truncate">"{{ $ticket->subject }}"
                        </div>
                        <div class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-1">Logged:
                            {{ $ticket->created_at->format('M d, H:i') }} Zulu</div>
                    </div>
                    <a href="{{ route('admin.support.show', $ticket) }}"
                        class="block w-full text-center py-3 bg-primary-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl">Open
                        Comm Channel</a>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 text-[10px] font-black uppercase tracking-widest italic">No
                    inbound comm signals detected</div>
            @endforelse
        </div>
    </div>

    @if($tickets->hasPages())
        <div class="p-8 border-t border-white/5 bg-white/[0.01]">
            {{ $tickets->links() }}
        </div>
    @endif
    </div>
</x-admin-layout>