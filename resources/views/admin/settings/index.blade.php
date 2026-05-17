<x-admin-layout>
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Global <span
                    class="text-rose-500">Parameters</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                System core configuration & operational thresholds
            </p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="glass-card px-5 py-2.5">
                <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Master Control Panel</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        @if ($errors->any())
            <div class="glass-card p-6 border-rose-500/20 bg-rose-500/5 mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-8 h-8 bg-rose-500/10 text-rose-500 rounded-lg flex items-center justify-center border border-rose-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Configuration Error Protocol
                        Triggered</h4>
                </div>
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-[11px] font-bold text-slate-400 flex items-center gap-2">
                            <span class="w-1 h-1 bg-rose-500 rounded-full"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Infrastructure -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Site Identity -->
                <div class="glass-card p-8 group">
                    <div class="flex items-center gap-3 mb-10">
                        <div
                            class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center border border-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Site Identity &
                            Branding</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Website
                                Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] }}"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                        </div>
                        <div>
                            <label
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Logo
                                (PNG/JPG)</label>
                            @if($settings['site_logo'])
                                <div class="mb-4 p-4 bg-white/5 rounded-2xl border border-white/5 inline-block">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-8 w-auto">
                                </div>
                            @endif
                            <input type="file" name="site_logo"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-3 text-white text-xs outline-none">
                        </div>
                        <div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Site
                                    Favicon</label>
                                <div class="glass-card p-4 flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 bg-white/5 rounded-xl border border-white/5 flex items-center justify-center p-2">
                                        @if($settings['site_favicon'])
                                            <img src="{{ asset('storage/' . $settings['site_favicon']) }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <span class="text-lg">💎</span>
                                        @endif
                                    </div>
                                    <input type="file" name="site_favicon"
                                        class="text-[10px] font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-primary-500/10 file:text-primary-500 hover:file:bg-primary-500/20 transition-all">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">PWA
                                    App Icon (512x512)</label>
                                <div class="glass-card p-4 flex items-center gap-4 border-indigo-500/20">
                                    <div
                                        class="w-10 h-10 bg-indigo-500/5 rounded-xl border border-indigo-500/10 flex items-center justify-center p-2">
                                        @if(($settings['pwa_icon'] ?? false))
                                            <img src="{{ asset('storage/' . $settings['pwa_icon']) }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <span class="text-lg">📱</span>
                                        @endif
                                    </div>
                                    <input type="file" name="pwa_icon"
                                        class="text-[10px] font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Social References -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-white/5">
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-rose-400 uppercase tracking-widest ml-1 leading-none">YouTube
                                    Tutorial Link</label>
                                <input type="url" name="youtube_tutorial_link"
                                    value="{{ $settings['youtube_tutorial_link'] ?? '' }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none"
                                    placeholder="https://youtube.com/watch?v=...">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-sky-400 uppercase tracking-widest ml-1 leading-none">Telegram
                                    Channel Link</label>
                                <input type="url" name="telegram_channel_link"
                                    value="{{ $settings['telegram_channel_link'] ?? '' }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-sky-500 focus:border-sky-500 transition-all outline-none"
                                    placeholder="https://t.me/your_channel">
                            </div>
                        </div>
                    </div>

                    <!-- Financial Engine -->
                    <div class="glass-card p-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-24 h-24 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-3 mb-10">
                            <div
                                class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center border border-primary-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Financial
                                Intelligence
                                Engine</h3>
                        </div>

                        {{-- ℹ️ Min amount & charges are now managed per-method in Payment Methods --}}
                        <div
                            class="mb-6 p-4 bg-primary-500/5 border border-primary-500/20 rounded-2xl flex items-center gap-3">
                            <span class="text-primary-500 text-lg">💳</span>
                            <p class="text-[10px] font-bold text-primary-400 leading-relaxed">
                                Withdrawal minimum amounts ও charges এখন <a
                                    href="{{ route('admin.withdrawal-methods.index') }}"
                                    class="underline font-black hover:text-primary-300">Payment Methods</a> থেকে প্রতিটি
                                method-এ আলাদাভাবে সেট করা যাবে।
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Yield
                                    Exchange Rate (PTS/1 BDT)</label>
                                <input type="number" name="point_conversion_rate"
                                    value="{{ $settings['point_conversion_rate'] }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Sign
                                    Up
                                    Bonus (PTS)</label>
                                <input type="number" name="signup_bonus_points"
                                    value="{{ $settings['signup_bonus_points'] }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Global
                                    Reserve (%)</label>
                                <input type="number" name="custom_platform_share_percent"
                                    value="{{ $settings['custom_platform_share_percent'] }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Moderator
                                    Cycle Bounty (BDT)</label>
                                <input type="number" step="0.01" name="moderator_salary_per_task"
                                    value="{{ $settings['moderator_salary_per_task'] }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Security Protocols -->
                    <div class="glass-card p-8">
                        <div class="flex items-center gap-3 mb-10">
                            <div
                                class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center border border-amber-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Security & Identity
                                Protocols</h3>
                        </div>

                        <div class="space-y-8">
                            <div
                                class="p-6 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4
                                            class="text-[10px] font-black text-white uppercase tracking-tight group-hover:text-amber-500 transition-colors">
                                            Hard-Mandatory Identity Audit</h4>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                            Force
                                            NID verification for all withdrawal requests</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="require_kyc_for_withdrawal" value="1" {{ $settings['require_kyc_for_withdrawal'] ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div
                                        class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                    </div>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div>
                                    <label
                                        class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Automated
                                        Audit Threshold (BDT)</label>
                                    <input type="number" name="kyc_threshold" value="{{ $settings['kyc_threshold'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all outline-none">
                                </div>
                                <p
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed mt-4 italic">
                                    Threshold at which system triggers automated identity verification sequence for
                                    high-value nodes.</p>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center pt-6 border-t border-white/5">
                                <div>
                                    <label
                                        class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Withdrawal
                                        Cooldown (Hours)</label>
                                    <input type="number" name="withdrawal_cooldown_hours"
                                        value="{{ $settings['withdrawal_cooldown_hours'] ?? 24 }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all outline-none">
                                </div>
                                <p
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed mt-4 italic">
                                    Minimum hours a user must wait after a completed withdrawal before requesting
                                    another. Set 0 to disable.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Network Grid Integrations -->
                    <div class="glass-card p-8 group">
                        <div class="flex items-center gap-3 mb-10">
                            <div
                                class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center border border-indigo-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Strategic Network
                                Matrix</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Social Tasks -->
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 bg-primary-500/10 text-primary-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-primary-500/20 italic">Social
                                        Media Tasks</span>
                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                        <input type="checkbox" name="is_social_tasks_active" value="1" {{ $settings['is_social_tasks_active'] ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-primary-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                        </div>
                                    </label>
                                </div>
                                <p
                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed italic">
                                    Toggle visibility of internal Social Media Tasks for users.</p>
                            </div>

                            <!-- TimeWall -->
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 bg-indigo-500/10 text-indigo-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-indigo-500/20 italic">TimeWall
                                        Protocol</span>
                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                        <input type="checkbox" name="is_timewall_active" value="1" {{ $settings['is_timewall_active'] ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-indigo-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                        </div>
                                    </label>
                                </div>
                                <div class="space-y-4">
                                    <input type="text" name="timewall_api_key"
                                        value="{{ $settings['timewall_api_key'] }}" placeholder="API Key"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-indigo-500 transition-all">
                                    <input type="text" name="timewall_display_name"
                                        value="{{ $settings['timewall_display_name'] }}"
                                        placeholder="Display Name (White-label)"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-indigo-500 transition-all">
                                    <input type="text" name="timewall_secret_key"
                                        value="{{ $settings['timewall_secret_key'] }}" placeholder="Secret Key"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-indigo-500 transition-all">
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="number" name="timewall_usd_to_points_rate"
                                            value="{{ $settings['timewall_usd_to_points_rate'] }}" placeholder="Ratio"
                                            class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                        <input type="number" name="timewall_platform_share_percent"
                                            value="{{ $settings['timewall_platform_share_percent'] }}"
                                            placeholder="Share %"
                                            class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Adsterra -->
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-amber-500/20 italic">Adsterra
                                        Gateway</span>
                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                        <input type="checkbox" name="is_adsterra_active" value="1" {{ $settings['is_adsterra_active'] ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                        </div>
                                    </label>
                                </div>
                                <div class="space-y-4">
                                    <input type="text" name="adsterra_display_name"
                                        value="{{ $settings['adsterra_display_name'] }}"
                                        placeholder="Display Name (White-label)"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-amber-500 transition-all">
                                    <input type="text" name="adsterra_direct_link"
                                        value="{{ $settings['adsterra_direct_link'] }}" placeholder="Direct Link Source"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-amber-500 transition-all">
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="number" name="adsterra_timer_seconds"
                                            value="{{ $settings['adsterra_timer_seconds'] }}" placeholder="Timer (SEC)"
                                            class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                        <input type="number" name="adsterra_platform_share_percent"
                                            value="{{ $settings['adsterra_platform_share_percent'] }}"
                                            placeholder="Share %"
                                            class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                    </div>
                                    <div class="pt-4 border-t border-white/5 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pop-under
                                                Script</label>
                                            <label class="relative inline-flex items-center cursor-pointer scale-75">
                                                <input type="checkbox" name="adsterra_popunder_enabled" value="1" {{ $settings['adsterra_popunder_enabled'] ? 'checked' : '' }}
                                                    class="sr-only peer">
                                                <div
                                                    class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-amber-500 after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                                </div>
                                            </label>
                                        </div>
                                        <textarea name="adsterra_popunder_script" rows="3"
                                            class="w-full p-3 bg-white/5 border border-white/5 rounded-xl text-[10px] font-mono text-amber-300 placeholder:text-slate-500 outline-none resize-none"
                                            placeholder="Paste Adsterra Pop-under script here...">{{ $settings['adsterra_popunder_script'] }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Monlix -->
                            <div class="space-y-6">

                                <div class="flex items-center justify-between">
                                    <span
                                        class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-rose-500/20 italic">Monlix
                                        Offerwall</span>
                                    <label class="relative inline-flex items-center cursor-pointer scale-75">
                                        <input type="checkbox" name="is_monlix_active" value="1" {{ $settings['is_monlix_active'] ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                        </div>
                                    </label>
                                </div>
                                <div class="space-y-4">
                                    <input type="text" name="monlix_display_name"
                                        value="{{ $settings['monlix_display_name'] }}"
                                        placeholder="Display Name (White-label)"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-rose-500 transition-all">
                                    <input type="text" name="monlix_secret_key"
                                        value="{{ $settings['monlix_secret_key'] }}" placeholder="Secret Key"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-rose-500 transition-all">
                                    <input type="number" step="0.00001" name="monlix_conversion_rate"
                                        value="{{ $settings['monlix_conversion_rate'] }}"
                                        placeholder="Monlix Point Rate (e.g. 0.01)"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                </div>
                            </div>
                            <!-- VPN Intelligence Matrix -->
                            <div class="glass-card p-8 group border-rose-500/20">
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center border border-rose-500/20 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">VPN
                                            Intelligence
                                            Matrix</h3>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer scale-90">
                                        <input type="checkbox" name="vpn_check_enabled" value="1" {{ $settings['vpn_check_enabled'] ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                        </div>
                                    </label>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label
                                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Proxycheck.io
                                            API Key</label>
                                        <input type="text" name="proxycheck_api_key"
                                            value="{{ $settings['proxycheck_api_key'] }}"
                                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm transition-all outline-none focus:bg-white/10"
                                            placeholder="Enter your API Key">
                                    </div>
                                    <p
                                        class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed italic">
                                        Critical security layer to detect VPN/Proxy usage on sensitive task routes.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Growth & Social -->
                    <div class="lg:col-span-4 space-y-8">


                        <!-- Referral Ecosystem -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center gap-3 mb-8">
                                <div
                                    class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Referral
                                    Ecosystem</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Recruitment
                                        Bounty (Points)</label>
                                    <input type="number" name="referral_bonus_amount"
                                        value="{{ $settings['referral_bonus_amount'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm transition-all outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Unlock
                                        Target (Lifetime BDT)</label>
                                    <input type="number" step="0.01" name="referral_unlock_target"
                                        value="{{ $settings['referral_unlock_target'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Signals & Resilience -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center border border-rose-500/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>

                                    </div>
                                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Resilience
                                        Lock
                                        (Maintenance)</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer scale-90">
                                    <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                    </div>
                                </label>
                            </div>
                            <textarea name="maintenance_message" rows="3"
                                class="w-full p-4 bg-white/5 border border-white/5 rounded-2xl text-[10px] font-bold text-slate-300 placeholder:text-slate-400 focus:bg-white/10 transition-all outline-none resize-none"
                                placeholder="Maintenance message for users...">{{ $settings['maintenance_message'] }}</textarea>
                        </div>

                        <!-- Signal Offsets -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center gap-3 mb-8">
                                <div
                                    class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center border border-rose-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Growth
                                    Calibration</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Artificial
                                        Agent Offset</label>
                                    <input type="number" name="fake_member_offset"
                                        value="{{ $settings['fake_member_offset'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Paid
                                        Signal Offset (BDT)</label>
                                    <input type="number" name="fake_paid_offset"
                                        value="{{ $settings['fake_paid_offset'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mission
                                        Activity Offset</label>
                                    <input type="number" name="fake_today_tasks_offset"
                                        value="{{ $settings['fake_today_tasks_offset'] }}"
                                        class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Blog Timer Config -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center gap-3 mb-8">
                                <div
                                    class="w-10 h-10 bg-violet-500/10 text-violet-500 rounded-xl flex items-center justify-center border border-violet-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Blog Secret
                                    Code Timer</h3>
                            </div>
                            <div>
                                <label
                                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Timer
                                    Duration (Seconds)</label>
                                <input type="number" name="blog_timer_seconds"
                                    value="{{ $settings['blog_timer_seconds'] ?? 30 }}" min="10" max="300"
                                    class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-black text-white outline-none focus:border-violet-500 transition-all">
                                <p class="text-[9px] font-bold text-slate-500 mt-2">ব্লগ সাবডোমেইনে সিক্রেট কোড জেনারেট
                                    করার জন্য কত সেকেন্ড অপেক্ষা করতে হবে। (ডিফল্ট: ৩০)</p>
                            </div>
                        </div>

                        <!-- System Broadcast -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-white/5 text-primary-500 rounded-xl flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.167M12.073 5.882l-3.938 2.63a1.71 1.71 0 01-1.12.308l-3.974-.338M12.073 5.882A5.988 5.988 0 006 4.5a5.988 5.988 0 00-6 1.382m12.073 0c1.71 0 3.3.413 4.693 1.144m-4.693-1.144V19.24a1.76 1.76 0 01-3.417.592m3.417-13.95l3.938 2.63a1.71 1.71 0 011.12.308l3.974-.338m-3.974.338a5.988 5.988 0 016 1.382m-6-1.382V19.24a1.76 1.76 0 01-3.417.592">
                                            </path>
                                        </svg>

                                    </div>
                                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Global
                                        Broadcast
                                        HUD</h3>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Broadcast
                                            Active</span>
                                        <label class="relative inline-flex items-center cursor-pointer scale-90">
                                            <input type="checkbox" name="notice_board_enabled" value="1" {{ $settings['notice_board_enabled'] ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div
                                                class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-primary-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-[9px] font-black text-rose-500 uppercase tracking-widest">Urgent
                                            Protocol
                                            (Persistent)</span>
                                        <label class="relative inline-flex items-center cursor-pointer scale-90">
                                            <input type="checkbox" name="notice_board_urgent" value="1" {{ ($settings['notice_board_urgent'] ?? false) ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div
                                                class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-rose-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <textarea name="notice_board_text" rows="4"
                                class="w-full p-5 bg-white/5 border border-white/5 rounded-2xl text-[11px] font-bold text-slate-300 placeholder:text-slate-400 focus:bg-white/10 transition-all outline-none resize-none"
                                placeholder="Enter global broadcast signal value...">{{ $settings['notice_board_text'] }}</textarea>
                        </div>

                        <!-- Custom Scripts / Pixel -->
                        <div class="glass-card p-8 group">
                            <div class="flex items-center gap-3 mb-8">
                                <div
                                    class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center border border-indigo-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Custom Scripts
                                    / Pixel
                                </h3>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Header
                                        Script (Rendered in &lt;head&gt;)</label>
                                    <textarea name="header_script" rows="4"
                                        class="w-full p-4 bg-white/5 border border-white/5 rounded-2xl text-[10px] font-mono text-indigo-300 placeholder:text-slate-500 focus:bg-white/10 transition-all outline-none resize-none"
                                        placeholder="Paste FB Pixel, GTM, or Google Analytics base code here...">{{ $settings['header_script'] }}</textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Body
                                        Script (Tawk.to Live Chat / Body Scripts)</label>
                                    <textarea name="body_script" rows="4"
                                        class="w-full p-4 bg-white/5 border border-white/5 rounded-2xl text-[10px] font-mono text-indigo-300 placeholder:text-slate-500 focus:bg-white/10 transition-all outline-none resize-none"
                                        placeholder="Paste GTM noscript or other general body scripts here...">{{ $settings['body_script'] }}</textarea>
                                </div>

                                {{-- ══ Tawk.to Live Chat ══════════════════════════════ --}}
                                <div class="pt-6 border-t border-white/5">
                                    <div class="flex items-center justify-between mb-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20 text-lg">
                                                💬
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-white uppercase tracking-widest">
                                                    Tawk.to Live Chat</p>
                                                <p
                                                    class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                                                    Smart widget — Support page only</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer scale-90">
                                            <input type="checkbox" name="tawkto_enabled" value="1" {{ $settings['tawkto_enabled'] ? 'checked' : '' }} class="sr-only peer">
                                            <div
                                                class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Property
                                                ID</label>
                                            <input type="text" name="tawkto_property_id"
                                                value="{{ $settings['tawkto_property_id'] }}"
                                                placeholder="e.g. 5f1a2b3c4d5e6f7890abcdef"
                                                class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-mono text-emerald-300 placeholder:text-slate-600 outline-none focus:border-emerald-500 transition-all">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Widget
                                                ID</label>
                                            <input type="text" name="tawkto_widget_id"
                                                value="{{ $settings['tawkto_widget_id'] }}" placeholder="e.g. 1ijk2lmno"
                                                class="w-full bg-white/5 border border-white/5 rounded-xl p-3 text-[11px] font-mono text-emerald-300 placeholder:text-slate-600 outline-none focus:border-emerald-500 transition-all">
                                        </div>
                                        <p class="text-[9px] font-bold text-slate-500 leading-relaxed">
                                            🔗 Tawk.to Dashboard → Administration → Chat Widget → <span
                                                class="text-emerald-500 font-black">Direct Chat Link</span> থেকে ID দুটো
                                            পাবেন।
                                            Widget শুধু <span class="text-emerald-400 font-black">Support পেজে</span>
                                            logged-in user-দের দেখাবে।
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full premium-btn py-6 rounded-[32px] text-sm">
                            Commit Protocol Overrides
                        </button>
                    </div>
                </div>
    </form>
</x-admin-layout>