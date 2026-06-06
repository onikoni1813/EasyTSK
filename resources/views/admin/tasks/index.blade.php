<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Mission <span
                    class="text-primary-500">Registry</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Protocol deployment and engagement management
            </p>
        </div>
        <a href="{{ route('admin.tasks.create') }}"
            class="inline-flex items-center gap-2 px-8 py-3.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/40 transform hover:-translate-y-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
            </svg>
            Deploy New Mission
        </a>
    </div>

    <!-- Data Matrix -->
    <div class="glass-card overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Protocol
                            Intelligence</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Bounty (PTS)</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Saturation</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Engine</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                            Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-primary-500 group-hover:bg-primary-500/10 group-hover:scale-110 transition-all">
                                        @if($task->type === 'youtube')
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                            </svg>
                                        @elseif($task->type === 'facebook')
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                            </svg>
                                        @elseif($task->type === 'telegram')
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.891 7.007l-2.012 9.487c-.15 1.15-.83 1.442-1.787.902l-3.072-2.264-1.482 1.428c-.164.164-.301.301-.617.301l.221-3.132 5.7-5.151c.248-.221-.054-.344-.384-.124l-7.045 4.437-3.033-.951c-.659-.208-.671-.659.138-.971l11.851-4.566c.549-.208 1.028.12 1.021.734z" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="max-w-[240px]">
                                        <div
                                            class="font-black text-white uppercase tracking-tight truncate group-hover:text-primary-500 transition-colors">
                                            {{ $task->title }}</div>
                                        <div
                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 truncate">
                                            {{ $task->created_at->format('M d, Y') }} — #{{ $task->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="font-black text-primary-500 tracking-widest text-sm italic">{{ number_format($task->points) }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    @php
                                        $isUnlim = $task->quota_max == 0 || $task->quota_remaining == -1;
                                        $used    = $isUnlim ? 0 : max(0, $task->quota_max - $task->quota_remaining);
                                        $pct     = (!$isUnlim && $task->quota_max > 0) ? min(100, ($used / $task->quota_max) * 100) : 0;
                                        $barColor = $pct >= 100 ? 'bg-rose-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-emerald-500');
                                    @endphp
                                    <div class="w-24 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        @if($isUnlim)
                                            <div class="h-full bg-emerald-500 rounded-full" style="width:100%;opacity:0.3"></div>
                                        @else
                                            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                        @endif
                                    </div>
                                    @if($isUnlim)
                                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">∞ আনলিমিটেড</span>
                                        <span class="text-[8px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full uppercase tracking-widest">∞ OPEN</span>
                                    @else
                                        <span class="text-[9px] font-black {{ $pct >= 100 ? 'text-rose-400' : 'text-slate-400' }} uppercase tracking-widest">
                                            {{ $used }} / {{ $task->quota_max }} স্লট
                                        </span>
                                        @if($task->quota_remaining <= 0)
                                            <span class="text-[8px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full uppercase tracking-widest">FULL</span>
                                        @else
                                            <span class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">{{ $task->quota_remaining }} বাকি</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="px-3 py-1 bg-white/5 border border-white/5 rounded-full text-[9px] font-black {{ $task->type === 'timewall' ? 'text-indigo-400' : 'text-amber-400' }} uppercase tracking-widest italic">
                                    {{ strtoupper($task->type) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tasks.edit', $task) }}"
                                        class="p-2.5 bg-white/5 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 transition-all group/icon">
                                        <svg class="w-4 h-4 group-hover/icon:scale-110 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2.5 bg-rose-500/10 rounded-xl border border-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all group/icon"
                                            onclick="return confirm('Terminate this mission protocol?')">
                                            <svg class="w-4 h-4 group-hover/icon:scale-110 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-8 py-32 text-center text-slate-500 italic text-xs uppercase tracking-widest">No
                                mission protocols currently deployed</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (visible below md) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($tasks as $task)
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-primary-500 font-black text-xs uppercase">
                                @if($task->type === 'youtube') 📺 @elseif($task->type === 'facebook') 👥
                                @elseif($task->type === 'telegram') ✈️ @else 🎯 @endif
                            </div>
                            <div class="max-w-[180px]">
                                <div class="font-black text-white uppercase tracking-tight truncate text-xs">
                                    {{ $task->title }}</div>
                                <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                                    #{{ $task->id }} — {{ strtoupper($task->type) }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="font-black text-primary-500 tracking-widest text-sm italic">{{ number_format($task->points) }}
                                PTS</span>
                        </div>
                    </div>

                    <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                        @php
                            $isUnlim  = $task->quota_max == 0 || $task->quota_remaining == -1;
                            $used     = $isUnlim ? 0 : max(0, $task->quota_max - $task->quota_remaining);
                            $pct      = (!$isUnlim && $task->quota_max > 0) ? min(100, ($used / $task->quota_max) * 100) : 0;
                            $barColor = $pct >= 100 ? 'bg-rose-500' : ($pct >= 75 ? 'bg-amber-500' : 'bg-emerald-500');
                        @endphp
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Slot Usage</span>
                            @if($isUnlim)
                                <span class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">∞ আনলিমিটেড</span>
                            @else
                                <span class="text-[8px] font-black {{ $pct >= 100 ? 'text-rose-400' : 'text-slate-400' }} uppercase tracking-widest">
                                    {{ $used }} / {{ $task->quota_max }}
                                    @if($task->quota_remaining <= 0) (FULL) @endif
                                </span>
                            @endif
                        </div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                            @if($isUnlim)
                                <div class="h-full bg-emerald-500 rounded-full" style="width:100%;opacity:0.3"></div>
                            @else
                                <div class="h-full {{ $barColor }} transition-all" style="width: {{ $pct }}%"></div>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.tasks.edit', $task) }}"
                            class="flex-1 py-3 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 text-center uppercase tracking-widest">Edit</a>
                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-3 bg-rose-500/10 border border-rose-500/10 text-rose-500 rounded-xl text-[9px] font-black uppercase tracking-widest"
                                onclick="return confirm('Terminate?')">Void</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 text-[10px] font-black uppercase tracking-widest italic">No
                    missions deployed</div>
            @endforelse
        </div>
    </div>

    @if($tasks->hasPages())
        <div class="p-8 border-t border-white/5 bg-white/[0.01]">
            {{ $tasks->links() }}
        </div>
    @endif
    </div>
</x-admin-layout>