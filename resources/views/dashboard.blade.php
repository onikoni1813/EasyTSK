<x-user-layout>
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">স্বাগতম,
                {{ auth()->user()->full_name ?? auth()->user()->name }}! 👋
            </h1>
            <p class="text-sm text-slate-400 font-medium mt-1">আজ আপনার সফলতার যাত্রা শুরু হোক। নিচে আপনার কাজের আপডেট
                দেখুন।</p>
        </div>
        <div class="flex items-center gap-2 bg-dark-card px-4 py-2 rounded-2xl border border-white/5 shadow-sm">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <span
                class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ now()->format('d M, Y') }}</span>
        </div>
    </div>

    <!-- Social Actions / Tutorials -->
    @php
        $youtubeLink = setting('youtube_tutorial_link', '');
        $telegramLink = setting('telegram_channel_link', '');
    @endphp

    @if($youtubeLink || $telegramLink)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            @if($youtubeLink)
                <a href="{{ $youtubeLink }}" target="_blank"
                    class="flex items-center gap-4 p-5 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white rounded-[24px] shadow-lg shadow-rose-900/40 transition-all hover:-translate-y-1 group">
                    <div
                        class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-1">Work Tutorial</h4>
                        <p class="text-[10px] font-bold text-rose-200 leading-snug">Having trouble understanding the work? Watch
                            our video tutorial!</p>
                    </div>
                </a>
            @endif

            @if($telegramLink)
                <a href="{{ $telegramLink }}" target="_blank"
                    class="flex items-center gap-4 p-5 bg-[#0088cc] hover:bg-[#0077b5] active:bg-[#006699] text-white rounded-[24px] shadow-lg shadow-[#0088cc]/30 transition-all hover:-translate-y-1 group">
                    <div
                        class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-1">Live Updates</h4>
                        <p class="text-[10px] font-bold text-blue-100 leading-snug">Join our Telegram to get new job updates and
                            payment proof!</p>
                    </div>
                </a>
            @endif
        </div>
    @endif

    <!-- Global Notifications handled in layout.blade.php -->

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Main Balance -->
        <div
            class="relative overflow-hidden p-6 bg-dark-card border border-white/5 rounded-3xl shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
            </div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">বর্তমান
                        ব্যালেন্স</span>
                    <div class="p-2 bg-primary-600 rounded-xl text-white shadow-lg shadow-primary-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-black text-white">{{ number_format($user->points) }}</h3>
                    <span class="text-xs font-bold text-slate-500">PTS</span>
                </div>
                <div class="mt-4 flex items-center justify-between pt-4 border-t border-white/5">
                    <span class="text-sm font-bold text-primary-500">৳ {{ $user->balance_in_bdt }} BDT</span>
                    @if(empty($user->facebook_link) || empty($user->telegram_username))
                        <a href="javascript:void(0)"
                            onclick="alert('উইথড্র করার আগে দয়া করে প্রোফাইল সেটিংসে গিয়ে আপনার আসল Facebook ID লিংক এবং Telegram ইউজারনেম সেভ করুন।')"
                            class="text-[10px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-tighter">উইথড্র
                            →</a>
                    @else
                        <a href="{{ route('withdrawals.index') }}"
                            class="text-[10px] font-black text-slate-400 hover:text-primary-500 uppercase tracking-tighter">উইথড্র
                            →</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Balance -->
        <div
            class="relative overflow-hidden p-6 bg-dark-card border border-white/5 rounded-3xl shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
            </div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">পেন্ডিং
                        ব্যালেন্স</span>
                    <div class="p-2 bg-orange-500 rounded-xl text-white shadow-lg shadow-orange-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-black text-white">{{ number_format($user->pending_points) }}</h3>
                    <span class="text-xs font-bold text-slate-500">PTS</span>
                </div>
                <p
                    class="mt-4 text-[10px] font-black text-slate-500 uppercase tracking-tighter pt-4 border-t border-white/5">
                    উইথড্র প্রসেসিং এ আছে</p>
            </div>
        </div>

        <!-- Lifetime Earnings -->
        <div
            class="relative overflow-hidden p-6 bg-dark-card border border-white/5 rounded-3xl shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
            </div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">মোট আয় (BDT)</span>
                    <div class="p-2 bg-primary-600 rounded-xl text-white shadow-lg shadow-primary-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 112-2h2a2 2 0 112 2v14a2 2 0 11-2 2h-2a2 2 0 11-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-3xl font-black text-white">৳ {{ number_format($user->total_earned_bdt, 2) }}</h3>
                </div>
                <p
                    class="mt-4 text-[10px] font-black text-slate-500 uppercase tracking-tighter pt-4 border-t border-white/5">
                    সফলভাবে প্রাপ্ত আয়</p>
            </div>
        </div>

        <!-- Trust Score -->
        @php
            $score = $user->trust_score;
            $scoreColor = 'rose-500';
            $scoreBg = 'bg-rose-500';
            $scoreGradient = 'from-rose-500 to-rose-400';
            $scoreShadow = 'shadow-rose-900/40';
            $scoreLabel = 'CRITICAL';
            $scoreBgAlpha = 'bg-rose-500/10';
            $scoreText = 'text-rose-500';

            if ($score >= 80) {
                $scoreColor = 'emerald-500';
                $scoreBg = 'bg-emerald-500';
                $scoreGradient = 'from-emerald-600 to-emerald-400';
                $scoreShadow = 'shadow-emerald-900/40';
                $scoreLabel = 'EXCELLENT';
                $scoreBgAlpha = 'bg-emerald-500/10';
                $scoreText = 'text-emerald-500';
            } elseif ($score >= 50) {
                $scoreColor = 'orange-500';
                $scoreBg = 'bg-orange-500';
                $scoreGradient = 'from-orange-500 to-orange-400';
                $scoreShadow = 'shadow-orange-900/40';
                $scoreLabel = 'AVERAGE';
                $scoreBgAlpha = 'bg-orange-500/10';
                $scoreText = 'text-orange-500';
            }
        @endphp
        <div
            class="relative overflow-hidden p-6 bg-dark-card border border-white/5 rounded-3xl shadow-sm transition-all hover:shadow-xl hover:-translate-y-1 group">
            <div
                class="absolute -right-4 -top-4 w-24 h-24 {{ $scoreBgAlpha }} rounded-full group-hover:scale-150 transition-transform duration-700">
            </div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">ট্রাস্ট স্কোর</span>
                    <div class="p-2 {{ $scoreBg }} rounded-xl text-white shadow-lg {{ $scoreShadow }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-3xl font-black text-white">{{ $user->trust_score }}%</h3>
                    <span class="text-[10px] font-black {{ $scoreText }}">{{ $scoreLabel }}</span>
                </div>
                <div class="w-full bg-white/5 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $scoreGradient }} h-full transition-all duration-1000 progress-bar"
                        data-width="{{ $user->trust_score }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral & Progress Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Referral Bonus Progress -->
            @if($referralsBonusPending)
                <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-700 text-primary-500">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-.83-.17-1.58-.51-2.22-1l1.41-1.41c.42.33.88.58 1.41.69v-3.41c-1.35-.33-2.35-1.08-2.35-2.45 0-1.41 1.09-2.32 2.35-2.61V6h2.82v1.9ic.71.13 1.33.42 1.83.83l-1.41 1.41c-.26-.19-.57-.34-.92-.41v3.31c1.35.32 2.35 1.08 2.35 2.45 0 1.41-1.09 2.31-2.35 2.61zm-2.82-7.85c-.53.13-.93.44-.93.85 0 .41.4.72.93.85v-1.7zm2.82 4.49c.53-.13.94-.45.94-.85s-.41-.72-.94-.85v1.7z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-black text-white tracking-tight">রেফারেল বোনাস আনলক প্রগ্রেস</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">ক্যাশ আউট করতে
                                    বোনাস আনলক করুন</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="block text-2xl font-black text-primary-500">{{ number_format($user->referralsMade()->where('status', 'Locked')->sum('bonus_points')) }}
                                    Pts</span>
                                <span class="text-[10px] font-black text-slate-500 uppercase">লকড বোনাস</span>
                            </div>
                        </div>

                        @php
                            $latestReferral = $user->referralsMade()->where('status', 'Locked')->latest('id')->first();
                            $percent = $latestReferral ? $latestReferral->progressPercent() : 0;
                            $bonusAmt = $latestReferral ? $latestReferral->bonus_points : 0;
                            $remaining = $latestReferral && $latestReferral->referredUser ? max(0, $latestReferral->unlock_threshold - $latestReferral->referredUser->total_earned_lifetime) : 0;
                        @endphp

                        <div class="space-y-3">
                            <div
                                class="flex justify-between text-[11px] font-black text-slate-500 uppercase tracking-tighter">
                                <span>Progress</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-white/5 rounded-2xl h-6 p-1 relative overflow-hidden">
                                <div class="bg-gradient-to-r from-primary-600 to-primary-400 h-full rounded-xl transition-all duration-1000 relative group progress-bar"
                                    data-width="{{ $percent }}%">
                                    <div class="absolute inset-0 bg-white/10 animate-pulse"></div>
                                </div>
                            </div>
                            <!--<p class="text-[11px] text-slate-400 font-medium leading-relaxed italic">
                                                                                                                    💡 প্রো টিপ: Your {{ $bonusAmt }} Points bonus will be unlocked when your friend earns
                                                                                                                    another {{ $remaining }} Taka!
                                                                                                                </p>-->
                        </div>
                    </div>
                </div>
            @endif

            @php
                $socialActive = setting('is_social_tasks_active', '1') == '1';
                $timewallActive = setting('is_timewall_active', '0') == '1';
                $adsterraActive = setting('is_adsterra_active', '0') == '1';
                $monlixActive = setting('is_monlix_active', '0') == '1';
            @endphp

            <!-- Mini Menus -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Social Tasks -->
                <a href="{{ $socialActive ? route('tasks.index') : route('module.maintenance') }}"
                    class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center group transition-all duration-300 shadow-sm relative overflow-hidden {{ $socialActive ? 'hover:bg-primary-600 hover:border-primary-600' : 'opacity-50 grayscale' }}">
                    @if(!$socialActive)
                        <div
                            class="absolute top-3 right-0 px-3 py-1 bg-rose-600 text-[8px] font-black text-white rounded-l-lg rotate-0 z-10">
                            OFF</div>
                    @endif
                    <div
                        class="w-12 h-12 {{ $socialActive ? 'bg-primary-500/10 text-primary-500' : 'bg-white/5 text-slate-500' }} rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">টাস্ক</span>
                </a>

                <!-- TimeWall -->
                <a href="{{ $timewallActive ? route('timewall.index') : route('module.maintenance') }}"
                    class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center transition-all duration-300 shadow-sm relative overflow-hidden {{ $timewallActive ? 'hover:bg-orange-600 hover:border-orange-600' : 'opacity-50 grayscale' }}">
                    @if(!$timewallActive)
                        <div
                            class="absolute top-3 right-0 px-3 py-1 bg-rose-600 text-[8px] font-black text-white rounded-l-lg z-10">
                            OFF</div>
                    @endif
                    <div
                        class="w-12 h-12 {{ $timewallActive ? 'bg-orange-500/10 text-orange-500' : 'bg-white/5 text-slate-500' }} rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">টাইমওয়াল</span>
                </a>

                <!-- Adsterra -->
                <a href="{{ $adsterraActive ? route('adsterra.index') : route('module.maintenance') }}"
                    class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center transition-all duration-300 shadow-sm relative overflow-hidden {{ $adsterraActive ? 'hover:bg-emerald-600 hover:border-emerald-600' : 'opacity-50 grayscale' }}">
                    @if(!$adsterraActive)
                        <div
                            class="absolute top-3 right-0 px-3 py-1 bg-rose-600 text-[8px] font-black text-white rounded-l-lg z-10">
                            OFF</div>
                    @endif
                    <div
                        class="w-12 h-12 {{ $adsterraActive ? 'bg-emerald-500/10 text-emerald-500' : 'bg-white/5 text-slate-500' }} rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">অ্যাড
                        দেখা</span>
                </a>

                <!-- Monlix -->
                <a href="{{ $monlixActive ? route('monlix.index') : route('module.maintenance') }}"
                    class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center transition-all duration-300 shadow-sm relative overflow-hidden {{ $monlixActive ? 'hover:bg-rose-600 hover:border-rose-600' : 'opacity-50 grayscale' }}">
                    @if(!$monlixActive)
                        <div
                            class="absolute top-3 right-0 px-3 py-1 bg-rose-600 text-[8px] font-black text-white rounded-l-lg z-10">
                            OFF</div>
                    @endif
                    <div
                        class="w-12 h-12 {{ $monlixActive ? 'bg-rose-500/10 text-rose-500' : 'bg-white/5 text-slate-500' }} rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span
                        class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">বোনাস
                        ওয়াল</span>
                </a>
                @if(empty($user->facebook_link) || empty($user->telegram_username))
                    <a href="javascript:void(0)"
                        onclick="alert('উইথড্র করার আগে দয়া করে প্রোফাইল সেটিংসে গিয়ে আপনার আসল Facebook ID লিংক এবং Telegram ইউজারনেম সেভ করুন।')"
                        class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center hover:bg-rose-500 hover:border-rose-500 transition-all duration-300 shadow-sm">
                @else
                        <a href="{{ route('withdrawals.index') }}"
                            class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center hover:bg-primary-600 hover:border-primary-600 transition-all duration-300 shadow-sm">
                    @endif
                        <div
                            class="w-12 h-12 bg-primary-500/10 rounded-2xl flex items-center justify-center text-primary-500 mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">ওয়ালেট</span>
                    </a>
                    <a href="{{ route('activity.index') }}"
                        class="group p-6 bg-dark-card border border-white/5 rounded-3xl text-center hover:bg-slate-700 hover:border-slate-600 transition-all duration-300 shadow-sm">
                        <div
                            class="w-12 h-12 bg-slate-500/10 rounded-2xl flex items-center justify-center text-slate-400 mx-auto mb-3 group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span
                            class="block text-xs font-black text-slate-300 uppercase tracking-tighter group-hover:text-white">হিস্টরি</span>
                    </a>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Referral Link Card -->
            <div class="p-8 bg-slate-900 rounded-[40px] text-white shadow-2xl relative overflow-hidden group">
                <div
                    class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary-600/20 rounded-full blur-3xl group-hover:bg-primary-600/40 transition-all duration-700">
                </div>
                <h3 class="text-lg font-black tracking-tight mb-2">আপনার রেফারেল লিংক</h3>
                <p class="text-[11px] text-slate-400 font-medium mb-6 leading-relaxed">লিংক শেয়ার করুন এবং বন্ধুদের আয়ের
                    একটি অংশ আজীবনের জন্য জিতে নিন!</p>

                <div class="relative group/input mb-6">
                    <input type="text" readonly value="{{ route('register') . '?ref=' . $user->referral_code }}"
                        id="refLink"
                        class="w-full bg-white/10 border-0 rounded-2xl py-4 pl-4 pr-12 text-xs font-bold text-primary-200 focus:ring-0">
                    <button onclick="copyToClipboard()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-primary-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <a href="https://wa.me/?text={{ urlencode('বন্ধুরা, এই সাইটে মাইক্রো জব করে প্রতিদিন ২০০-৫০০ টাকা আয় করা যায়। আমার লিংকে জয়েন করো: ' . route('register') . '?ref=' . $user->referral_code) }}"
                        target="_blank"
                        class="flex items-center justify-center gap-2 p-3 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-500 rounded-2xl border border-emerald-500/10 transition-all text-[11px] font-black uppercase tracking-widest">
                        WhatsApp
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(route('register') . '?ref=' . $user->referral_code) }}&text={{ urlencode('পার্ট টাইম কাজ করে আয় করার সেরা সাইট।') }}"
                        target="_blank"
                        class="flex items-center justify-center gap-2 p-3 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 rounded-2xl border border-blue-500/10 transition-all text-[11px] font-black uppercase tracking-widest">
                        Telegram
                    </a>
                </div>

                <button onclick="shareLink()"
                    class="w-full py-4 bg-primary-600 hover:bg-primary-500 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-primary-900/40 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                        </path>
                    </svg>
                    Invite Friends (Native)
                </button>

                <div class="mt-8 flex items-center justify-between">
                    <div>
                        <span
                            class="block text-2xl font-black text-primary-400">{{ number_format($user->referralsMade()->count()) }}</span>
                        <span class="text-[10px] font-black text-slate-500 uppercase">মোট রেফারেল</span>
                    </div>
                    <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pro Tips / Why EasyTSK? -->
            <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm">
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-4">প্রো টিপস 💡</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div
                            class="w-5 h-5 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 mt-0.5 shrink-0 italic text-[10px] font-black underline decoration-emerald-500/30">
                            1</div>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">সবসময় টাস্কের ইন্সট্রাকশন মনোযোগ
                            দিয়ে পড়ুন। ভুল কাজে সাবমিশন দিলে ট্রাস্ট স্কোর কমবে।</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div
                            class="w-5 h-5 bg-primary-500/10 rounded-full flex items-center justify-center text-primary-500 mt-0.5 shrink-0 italic text-[10px] font-black underline decoration-primary-500/30">
                            2</div>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">বেশি আয়ের জন্য অন্যদের রেফার করুন।
                            আপনার রেফারেল সারাজীবন যা আয় করবে তার লাভ আপনি পাবেন।</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            // Initialize progress bars
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.progress-bar').forEach(el => {
                    el.style.width = el.getAttribute('data-width');
                });
            });

            function copyToClipboard() {
                const copyText = document.getElementById("refLink");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                alert("Referral link copied! ✅");
            }

            function shareLink() {
                if (navigator.share) {
                    navigator.share({
                        title: "{{ \App\Models\Setting::get('site_name', 'EasyTSK') }}",
                        text: 'বন্ধুরা, এই সাইটে মাইক্রো জব করে প্রতিদিন ২০০-৫০০ টাকা আয় করা যায়। আমার লিংকে জয়েন করো!',
                        url: document.getElementById('refLink').value,
                    }).then(() => {
                        console.log('Successfully shared');
                    }).catch((error) => {
                        console.log('Error sharing:', error);
                    });
                } else {
                    // Fallback to clipboard
                    copyToClipboard();
                }
            }
        </script>
    @endpush
    <style>
        .adsbox {
            height: 1px;
            width: 1px;
            position: absolute;
            left: -100px;
            top: -100px;
        }
    </style>
</x-user-layout>