<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">System <span
                class="text-primary-500">Overview</span></h1>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Real-time platform performance
            & operational metrics</p>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Card: Admin Profit -->
        <a href="{{ route('admin.settings.index') }}"
            class="glass-card p-6 relative overflow-hidden group hover:bg-primary-500/5 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                    </path>
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Total Admin Profit</p>
            <h3 class="text-2xl font-black text-white">৳{{ number_format($financialRadar['totalAdminProfit'], 2) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span
                    class="text-[9px] font-bold text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded uppercase tracking-tighter">Live
                    Audit</span>
            </div>
        </a>

        <!-- Card: Pending Withdrawals -->
        <a href="{{ route('admin.withdrawals.index') }}"
            class="glass-card p-6 relative overflow-hidden group hover:bg-rose-500/5 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                    </path>
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Pending Withdrawals</p>
            <h3 class="text-2xl font-black text-rose-500">
                ৳{{ number_format($financialRadar['pendingWithdrawalsTotal'], 2) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span
                    class="text-[9px] font-bold text-rose-400 bg-rose-400/10 px-2 py-0.5 rounded uppercase tracking-tighter">Action
                    Required</span>
            </div>
        </a>

        <!-- Card: Pending Review -->
        <a href="{{ route('admin.submissions.index') }}"
            class="glass-card p-6 relative overflow-hidden group hover:bg-amber-500/5 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Tasks Pending Review
            </p>
            <h3 class="text-2xl font-black text-white">{{ $activityRadar['tasksPendingReview'] }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span
                    class="text-[9px] font-bold text-amber-400 bg-amber-400/10 px-2 py-0.5 rounded uppercase tracking-tighter">Queue
                    Active</span>
            </div>
        </a>

        <!-- Card: Support Tickets -->
        <a href="{{ route('admin.support.index') }}"
            class="glass-card p-6 relative overflow-hidden group hover:bg-emerald-500/5 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Support Tickets</p>
            <h3
                class="text-2xl font-black {{ $activityRadar['unreadSupportCount'] > 0 ? 'text-emerald-500 animate-pulse' : 'text-white' }}">
                {{ $activityRadar['unreadSupportCount'] }}
            </h3>
            <div class="mt-4 flex items-center gap-2">
                <span
                    class="text-[9px] font-bold {{ $activityRadar['unreadSupportCount'] > 0 ? 'text-emerald-400 bg-emerald-400/10' : 'text-slate-500 bg-white/5' }} px-2 py-0.5 rounded uppercase tracking-tighter">
                    {{ $activityRadar['unreadSupportCount'] > 0 ? 'Urgent Attention' : 'All Clear' }}
                </span>
            </div>
        </a>

        <!-- Card: Total Users -->
        <a href="{{ route('admin.users.index') }}"
            class="glass-card p-6 relative overflow-hidden group hover:bg-primary-500/5 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Total Active Agents</p>
            <h3 class="text-2xl font-black text-white">{{ number_format($activityRadar['totalUsers']) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span
                    class="text-[9px] font-bold text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded uppercase tracking-tighter">Growth
                    Tracking</span>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profit Chart -->
        <div class="lg:col-span-2 glass-card p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Weekly Profit Analysis</h3>
                <span
                    class="px-3 py-1 bg-white/5 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-tighter">7
                    Day Projection</span>
            </div>
            <div class="h-[300px] w-full" id="profitChartContainer">
                <canvas id="profitChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="glass-card p-8">
            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8">System Activity Log</h3>
            <div class="space-y-6">
                @foreach($recentTransactions->take(6) as $tx)
                    <div
                        class="flex items-start gap-4 p-4 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 transition-colors">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center text-primary-500 shrink-0">
                            <span class="text-xs font-black">{{ substr($tx->description, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-black text-white uppercase tracking-tight truncate">
                                {{ $tx->description }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                {{ $tx->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p
                                class="text-[11px] font-black {{ $tx->amount_points > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $tx->amount_points > 0 ? '+' : '' }}{{ number_format($tx->amount_points) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Marketing Analysis Section -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass-card p-8">
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                Traffic Source Distribution
            </h3>
            <div class="space-y-4">
                @foreach($marketingRadar['topSources'] as $source)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span
                                class="text-[11px] font-black text-white uppercase tracking-widest">{{ $source->utm_source }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-black text-indigo-400">{{ $source->count }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter ml-1">Leads</span>
                        </div>
                    </div>
                @endforeach
                @if($marketingRadar['topSources']->isEmpty())
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center py-8">No source
                        data available</p>
                @endif
            </div>
        </div>

        <div class="glass-card p-8">
            <h3 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Profit ROI by Source
            </h3>
            <div class="space-y-4">
                @foreach($marketingRadar['profitBySource'] as $profit)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span
                                class="text-[11px] font-black text-white uppercase tracking-widest">{{ $profit->utm_source }}</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-[11px] font-black text-emerald-500">৳{{ number_format($profit->total_profit, 2) }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter ml-1">Profit</span>
                        </div>
                    </div>
                @endforeach
                @if($marketingRadar['profitBySource']->isEmpty())
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center py-8">No profit
                        attribution yet</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script id="chart-labels" type="application/json">
            @json($chartData['labels'])
        </script>
        <script id="chart-values" type="application/json">
            @json($chartData['values'])
        </script>

        <script>
            const ctx = document.getElementById('profitChart');
            if (ctx) {
                const labels = JSON.parse(document.getElementById('chart-labels').textContent);
                const values = JSON.parse(document.getElementById('chart-values').textContent);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Admin Profit (BDT)',
                            data: values,
                            borderWidth: 4,
                            borderColor: '#00c853',
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const {
                                    ctx,
                                    chartArea
                                } = chart;
                                if (!chartArea) return null;
                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                gradient.addColorStop(0, 'rgba(0, 200, 83, 0)');
                                gradient.addColorStop(1, 'rgba(0, 200, 83, 0.2)');
                                return gradient;
                            },
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#00c853',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 10,
                                        weight: '800'
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 10,
                                        weight: '800'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-admin-layout>