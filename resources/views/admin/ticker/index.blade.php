<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Live Ticker <span class="text-primary-500">HUD</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                System-wide signal management & broadcast calibration
            </p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="glass-card px-5 py-2.5">
                <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest">Active Signals: {{ count($recentWithdrawals) }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Calibration Hub -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-8">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    Signal Parameters
                    <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                </h3>
                <form action="{{ route('admin.ticker.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl group active:bg-white/10 transition-all">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Member Signal Offset</label>
                            <input type="number" name="fake_member_offset" value="{{ $settings['fake_member_offset'] }}" class="w-full bg-transparent border-none p-0 text-white font-black text-xl outline-none focus:ring-0">
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl group active:bg-white/10 transition-all">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Payment Value Offset (BDT)</label>
                            <input type="number" name="fake_paid_offset" value="{{ $settings['fake_paid_offset'] }}" class="w-full bg-transparent border-none p-0 text-white font-black text-xl outline-none focus:ring-0">
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl group active:bg-white/10 transition-all">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Task Activity Offset</label>
                            <input type="number" name="fake_today_tasks_offset" value="{{ $settings['fake_today_tasks_offset'] }}" class="w-full bg-transparent border-none p-0 text-white font-black text-xl outline-none focus:ring-0">
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl group active:bg-white/10 transition-all">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pulse Delta (Speed)</label>
                            <select name="ticker_speed" class="w-full bg-transparent border-none p-0 text-white font-black text-xl outline-none focus:ring-0 appearance-none">
                                <option value="10s" {{ $settings['ticker_speed'] == '10s' ? 'selected' : '' }} class="bg-dark text-white">Aggressive (10s)</option>
                                <option value="15s" {{ $settings['ticker_speed'] == '15s' ? 'selected' : '' }} class="bg-dark text-white">Standard (15s)</option>
                                <option value="20s" {{ $settings['ticker_speed'] == '20s' ? 'selected' : '' }} class="bg-dark text-white">Passive (20s)</option>
                                <option value="30s" {{ $settings['ticker_speed'] == '30s' ? 'selected' : '' }} class="bg-dark text-white">Static (30s)</option>
                            </select>
                        </div>
                        <div class="p-4 bg-white/5 border border-white/5 rounded-2xl group active:bg-white/10 transition-all">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Custom Message Sequences (One per line)</label>
                            <textarea name="ticker_custom_messages" rows="4" class="w-full bg-transparent border-none p-0 text-white font-bold text-sm outline-none focus:ring-0 resize-none" placeholder="User X just joined...&#10;New payout sent to...&#10;Refer and earn 10% bonus!">{{ $settings['ticker_custom_messages'] }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="w-full premium-btn py-5 rounded-2xl text-[10px]">
                        Sync Broadcast Parameters
                    </button>
                </form>
            </div>

            <div class="glass-card p-8 bg-primary-600/5 border-primary-500/20 relative overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-primary-500/10 to-transparent"></div>
                <div class="relative">
                    <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-4">HUD Preview</h4>
                    <div class="bg-dark rounded-xl p-4 border border-white/5 overflow-hidden">
                        <div class="flex items-center gap-4 animate-pulse">
                            <span class="px-2 py-0.5 bg-primary-600 text-[8px] font-black rounded text-white tracking-widest">LIVE</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter truncate">Initializing signal stream...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Signal Matrix -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card overflow-hidden min-h-[500px]">
                <div class="px-8 py-5 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Validated Payment Transmissions</h3>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Global Feed (Last 20)</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Agent Node</th>
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">Settlement</th>
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest text-center">Channel</th>
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest text-right">Yield</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($recentWithdrawals as $w)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center font-black text-[9px] text-primary-500 group-hover:scale-110 transition-transform">
                                            {{ strtoupper(substr(optional($w->user)->name ?? 'D', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-black text-white uppercase tracking-tight">{{ optional($w->user)->masked_name ?? 'Deleted User' }}</div>
                                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">{{ $w->updated_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-[10px] font-black text-slate-400 font-mono italic">{{ optional($w->user)->bkash_number ?? optional($w->user)->nagad_number ?? $w->account ?? 'EXTERNAL_NODE' }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-primary-500 transition-colors">
                                        {{ $w->method }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-[13px] font-black text-emerald-500">৳{{ number_format($w->amount_bdt) }}</div>
                                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Settled</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-32 text-center">
                                    <div class="w-16 h-16 bg-white/5 border border-white/5 rounded-3xl flex items-center justify-center text-slate-400 mx-auto mb-6">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">No validated transmissions detected</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
