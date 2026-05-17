<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">System <span
                class="text-primary-500">Manager</span></h1>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Core engine maintenance &
            automation hub</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Automation/Cron -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-10 opacity-5 group-hover:scale-110 transition-transform">
                    <svg class="w-32 h-32 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 3l-2 3H4v15h16V6h-7l-2-3z" />
                    </svg>
                </div>

                <h3
                    class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3 relative">
                    Master Cron Configuration
                    <span
                        class="px-2 py-0.5 bg-primary-500/10 text-primary-500 text-[8px] rounded uppercase tracking-widest">Crucial</span>
                </h3>

                <div class="relative mb-10">
                    <p
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 leading-relaxed italic">
                        Install this command in your cPanel / Server Cron Jobs to automate payments, tasks, and system
                        audits. Set frequency to <span class="text-primary-500">Every Minute (* * * * *)</span>.
                    </p>
                    <div
                        class="bg-black/40 border border-white/5 p-6 rounded-2xl font-mono text-sm text-primary-400 break-all select-all flex items-center justify-between gap-4">
                        <code>{{ $cronCommand }}</code>
                        <button onclick="copyToClipboard('{{ $cronCommand }}')"
                            class="shrink-0 p-2 hover:bg-white/10 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m3.385 10.915l-3.385 3.385-1.615-1.615m1.615 1.615l1.615-1.615" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="p-6 bg-white/5 border border-white/5 rounded-[28px] group hover:border-primary-500/30 transition-all">
                        <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-2">Automated Payouts
                        </h4>
                        <p class="text-[9px] font-bold text-slate-500 uppercase leading-relaxed">System audits &
                            processes withdrawal queues every 60 seconds.</p>
                    </div>
                    <div
                        class="p-6 bg-white/5 border border-white/5 rounded-[28px] group hover:border-indigo-500/30 transition-all">
                        <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-2">Integrity Sweeps
                        </h4>
                        <p class="text-[9px] font-bold text-slate-500 uppercase leading-relaxed">Fraud detection
                            algorithms scan active nodes hourly.</p>
                    </div>
                </div>
            </div>

            <!-- Manual Overrides -->
            <div class="glass-card p-10">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-10">Manual Control Overrides
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <form action="{{ route('admin.system.clear_cache') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex flex-col items-center gap-4 p-8 bg-white/5 border border-white/5 rounded-[32px] hover:bg-amber-500/10 hover:border-amber-500/30 transition-all group">
                            <div
                                class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-white uppercase tracking-widest">Purge Cache</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.system.optimize') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex flex-col items-center gap-4 p-8 bg-white/5 border border-white/5 rounded-[32px] hover:bg-primary-500/10 hover:border-primary-500/30 transition-all group">
                            <div
                                class="w-12 h-12 bg-primary-500/10 text-primary-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-white uppercase tracking-widest">Optimize
                                Engine</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.system.clear_notifications') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex flex-col items-center gap-4 p-8 bg-white/5 border border-white/5 rounded-[32px] hover:bg-rose-500/10 hover:border-rose-500/30 transition-all group">
                            <div
                                class="w-12 h-12 bg-rose-500/10 text-rose-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-white uppercase tracking-widest text-center">Clear
                                Signals</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-10 bg-indigo-500/5 border-indigo-500/10">
                <h4
                    class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-6 flex items-center gap-2">
                    Toolkit Shortcuts
                    <span class="w-1.5 h-1.5 bg-indigo-500/30 rounded-full"></span>
                </h4>
                <div class="space-y-4">
                    <a href="{{ route('admin.toolkit.migrate') }}"
                        class="flex items-center justify-between p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all group">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">DB
                            Migration</span>
                        <svg class="w-4 h-4 text-indigo-500 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('admin.toolkit.storage-link') }}"
                        class="flex items-center justify-between p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all group">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Storage
                            Link</span>
                        <svg class="w-4 h-4 text-emerald-500 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="glass-card p-10">
                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-6 px-1">Engine Metrics
                </h4>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[9px] font-black text-slate-500 uppercase">PHP Version</span>
                            <span class="text-[10px] font-black text-white lowercase italic">{{ phpversion() }}</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[9px] font-black text-slate-500 uppercase">Environment</span>
                            <span
                                class="px-2 py-0.5 bg-primary-500/10 text-primary-500 text-[8px] rounded font-black uppercase tracking-widest">{{ app()->environment() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Cron command copied to clipboard! ✅');
            });
        }
    </script>
</x-admin-layout>