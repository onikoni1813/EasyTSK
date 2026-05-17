<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Agent <span
                    class="text-primary-500">Database</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Personnel management & integrity monitoring
            </p>
        </div>

        <!-- Control HUD -->
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Search -->
            <div
                class="glass-card flex items-center px-4 py-2 w-full md:w-80 group focus-within:border-primary-500/50 transition-all">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-primary-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <form action="{{ route('admin.users.index') }}" method="GET" class="w-full">
                    <input type="text" name="id_search" value="{{ request('id_search') }}"
                        placeholder="Search Agents..."
                        class="bg-transparent border-none focus:ring-0 text-white placeholder:text-slate-400 font-bold text-sm w-full pl-3">
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                </form>
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-2 bg-white/5 p-1 rounded-2xl border border-white/5">
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'latest'])) }}"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('sort', 'latest') == 'latest' ? 'bg-primary-500 text-black shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:text-white' }}">All</a>
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'banned'])) }}"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('sort') == 'banned' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-white' }}">Banned</a>
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'highest_profit'])) }}"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('sort') == 'highest_profit' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-white' }}">High
                    Yield</a>
            </div>
        </div>
    </div>

    <!-- Data Matrix -->
    <div class="glass-card overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Agent
                            Identity</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Net Yield</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Integrity</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Network Loc</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Source</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                            Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-primary-500/10 text-primary-500 border border-primary-500/20 rounded-2xl flex items-center justify-center font-black text-xs group-hover:scale-110 transition-transform uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors flex items-center gap-2">
                                            {{ $user->name }}
                                            @if($user->is_banned)
                                                <span
                                                    class="px-2 py-0.5 bg-rose-500 text-white text-[8px] font-black rounded uppercase tracking-widest">Banned</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span
                                        class="font-black text-white tracking-tight">৳{{ number_format($user->points / $rate, 2) }}</span>
                                    <span
                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ number_format($user->points) }}
                                        PTS</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $score = $user->trust_score;
                                    $scoreColor = 'bg-rose-500';
                                    if ($score >= 80)
                                        $scoreColor = 'bg-emerald-500';
                                    elseif ($score >= 50)
                                        $scoreColor = 'bg-orange-500';
                                @endphp
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-24 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full {{ $scoreColor }} rounded-full"
                                            style="width: {{ $user->trust_score }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-black text-white">{{ $user->trust_score }}%</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="px-3 py-1 bg-white/5 border border-white/5 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ $user->registration_ip ?? 'INTERNAL_NODE' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="px-3 py-1 {{ $user->utm_source ? 'bg-primary-500/10 text-primary-500' : 'bg-white/5 text-slate-400' }} border border-white/5 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $user->utm_source ?? 'Organic' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($user->is_banned)
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20 transform hover:-translate-y-1">
                                                Restore
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/20 transform hover:-translate-y-1">
                                        Inspect
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-8 py-32 text-center text-slate-500 italic text-xs uppercase tracking-widest">No
                                agents detected in database</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (visible below md) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($users as $user)
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center font-black text-xs uppercase">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-black text-white uppercase tracking-tight text-xs flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->is_banned)
                                        <span
                                            class="px-1.5 py-0.5 bg-rose-500 text-white text-[7px] font-black rounded uppercase tracking-widest">Banned</span>
                                    @endif
                                </div>
                                <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-white text-xs">৳{{ number_format($user->points / $rate, 2) }}</div>
                            <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest">
                                {{ number_format($user->points) }} PTS
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 p-3 bg-white/5 rounded-xl border border-white/5">
                        <div class="flex items-center gap-2 flex-grow">
                            <div class="flex-grow h-1.5 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-500" style="width: {{ $user->trust_score }}%"></div>
                            </div>
                            <span class="text-[10px] font-black text-white">{{ $user->trust_score }}%</span>
                        </div>
                        <div class="flex gap-2">
                            @if($user->is_banned)
                                <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Restore</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.users.show', $user) }}"
                                class="px-4 py-2 bg-primary-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Inspect</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 text-[10px] font-black uppercase tracking-widest italic">No
                    agents detected</div>
            @endforelse
        </div>
    </div>

    @if($users->hasPages())
        <div class="p-8 border-t border-white/5 bg-white/[0.01]">
            {{ $users->links() }}
        </div>
    @endif
    </div>

    <style>
        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s;
        }

        .page-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .page-item.active .page-link {
            background: #00c853;
            color: white;
            border-color: #00e676;
        }
    </style>
</x-admin-layout>