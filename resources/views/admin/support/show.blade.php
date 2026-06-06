<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Comm <span
                    class="text-primary-500">Interface</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Authorized link with Agent: {{ optional($ticket->user)->name ?? 'N/A' }}
            </p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.support.index') }}"
                class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">
                Term Link
            </a>
            @if($ticket->status !== 'closed')
                <form action="{{ route('admin.support.close', $ticket) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black rounded-xl hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40 uppercase tracking-widest">
                        Void Protocol
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Intelligence -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-8">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    Signal Intelligence
                    <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                </h3>
                <div class="space-y-6">
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Subject Header
                        </p>
                        <p class="text-xs font-black text-white uppercase italic">"{{ $ticket->subject }}"</p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Protocol ID</p>
                        <p class="text-xs font-black text-primary-500 tracking-tighter font-mono">#INF-{{ $ticket->id }}
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Agent Integrity
                        </p>
                        <div class="flex items-center gap-3">
                            @php
                                $score = optional($ticket->user)->trust_score ?? 0;
                                $scoreBg = 'bg-rose-500';
                                $scoreText = 'text-rose-500';
                                if ($score >= 80) {
                                    $scoreBg = 'bg-emerald-500';
                                    $scoreText = 'text-emerald-500';
                                } elseif ($score >= 50) {
                                    $scoreBg = 'bg-orange-500';
                                    $scoreText = 'text-orange-500';
                                }
                            @endphp
                            <div class="flex-grow h-1.5 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full {{ $scoreBg }}" style="width: {{ $score }}%"></div>
                            </div>
                            <span class="text-[11px] font-black {{ $scoreText }}">{{ $score }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 text-center pt-10 relative overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-primary-500/10 to-transparent"></div>
                <div class="relative">
                    <div
                        class="w-20 h-20 bg-primary-600 rounded-3xl flex items-center justify-center text-white font-black text-3xl mx-auto mb-4 silver-border group-hover:scale-105 transition-transform">
                        {{ substr(optional($ticket->user)->name ?? 'U', 0, 1) }}
                    </div>
                    <h4 class="text-sm font-black text-white uppercase tracking-tight">
                        {{ optional($ticket->user)->name ?? 'N/A' }}</h4>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                        {{ optional($ticket->user)->email ?? 'N/A' }}
                    </p>
                    @if($ticket->user)
                    <a href="{{ route('admin.users.show', $ticket->user) }}"
                        class="inline-block mt-6 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">View
                        Dossier</a>
                    @else
                    <span class="inline-block mt-6 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-rose-400 uppercase tracking-widest">Deleted User</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Comm Feed -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card flex flex-col min-h-[600px]">
                <div class="flex-grow p-8 space-y-8 overflow-y-auto max-h-[500px]">
                    @foreach($ticket->messages as $msg)
                        <div class="flex {{ $msg->is_admin_reply ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[80%] {{ $msg->is_admin_reply ? 'bg-primary-600 text-white' : 'bg-white/5 border border-white/5 text-slate-200' }} p-6 rounded-[28px] {{ $msg->is_admin_reply ? 'rounded-tr-none shadow-lg shadow-primary-900/20' : 'rounded-tl-none' }} relative group">
                                <p class="text-sm font-bold leading-relaxed">{{ $msg->message }}</p>
                                <div
                                    class="mt-3 flex items-center gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest">{{ $msg->created_at->diffForHumans() }}</span>
                                    <span class="w-1 h-1 bg-current rounded-full"></span>
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest">{{ $msg->is_admin_reply ? 'COMMAND_HUB' : 'EXTERNAL_NODE' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($ticket->status !== 'closed')
                    <div class="p-6 md:p-8 border-t border-white/5 bg-white/5">
                        <form action="{{ route('admin.support.reply', $ticket) }}" method="POST">
                            @csrf
                            <div class="flex flex-col gap-4 md:relative">
                                <textarea name="message" rows="3" required
                                    placeholder="Enter transmission value to transmit to agent..."
                                    class="w-full bg-dark border border-white/10 rounded-3xl p-6 text-white font-bold text-sm focus:ring-1 focus:ring-primary-500 transition-all outline-none placeholder:text-slate-400 resize-none md:pr-32"></textarea>
                                <div class="md:absolute md:right-3 md:bottom-3">
                                    <button type="submit"
                                        class="w-full md:w-auto px-8 py-3.5 bg-primary-600 hover:bg-primary-500 text-white font-black text-[10px] rounded-2xl uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                        Transmit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-10 border-t border-white/5 bg-rose-500/5 text-center">
                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em]">Protocol Terminated — No
                            Further Comm Authorized</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>