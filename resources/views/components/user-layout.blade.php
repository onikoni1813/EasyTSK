<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::get('site_name', config('app.name')) }} — ড্যাশবোর্ড</title>

    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eafff1',
                            100: '#cdfedd',
                            200: '#9dfab8',
                            300: '#5bf388',
                            400: '#23e459',
                            500: '#00c853',
                            600: '#00a33f',
                            700: '#038035',
                            800: '#09652d',
                            900: '#0a5327',
                            950: '#002f13',
                        },
                        dark: {
                            DEFAULT: '#0a0f1e',
                            alt: '#111827',
                            card: '#1a2235',
                            card2: '#1e2d45',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Hind Siliguri', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --green: #00c853;
            --green2: #00e676;
            --dark: #0a0f1e;
            --dark2: #111827;
            --card: #1a2235;
            --card2: #1e2d45;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.08);
            --radius: 16px;
        }

        body {
            background-color: var(--dark);
            color: var(--text);
        }

        .sidebar-link.active {
            background: rgba(0, 200, 83, 0.1);
            color: var(--green);
            border-right: 3px solid var(--green);
        }

        .glass-nav {
            background: rgba(10, 15, 30, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .glass-card {
            background: var(--card);
            border: 1px solid var(--border);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--card2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--green);
        }
    </style>
    <!-- Custom Header Scripts -->
    {!! \App\Models\Setting::get('header_script') !!}
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('manifest') }}">
    <meta name="theme-color" content="#00c853">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <!-- Toastr (non-render-blocking CSS + deferred JS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" media="print"
        onload="this.media='all'">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" defer></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        toastr-container {
            font-family: 'Inter', sans-serif !important;
        }

        .toast {
            border-radius: 12px !important;
            font-weight: 600 !important;
        }
    </style>
</head>

<body class="antialiased selection:bg-primary-500/30 selection:text-primary-200" x-data="{ sidebarOpen: false }">
    {{-- ═══ Smart Tawk.to Live Chat (Dynamic) ════════════════════════════════ --}}
    @php
        $tawkEnabled = \App\Models\Setting::get('tawkto_enabled', false);
        $tawkPropertyId = \App\Models\Setting::get('tawkto_property_id', '');
        $tawkWidgetId = \App\Models\Setting::get('tawkto_widget_id', '1xxxxxxxx');
    @endphp

    @if($tawkEnabled && $tawkPropertyId)
        @auth
            @php $tawkUser = auth()->user(); @endphp
            <script>
                // Pre-configure Tawk.to BEFORE the widget loads
                var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();

                // Auto-fill visitor identity (name + email)
                Tawk_API.visitor = {
                    name: '{{ addslashes($tawkUser->full_name ?? $tawkUser->name) }}',
                    email: '{{ $tawkUser->email ?? "" }}'
                };

                Tawk_API.onLoad = function () {
                    // Set extra custom attributes (mobile number, user ID)
                    Tawk_API.setAttributes({
                        'mobile': '{{ $tawkUser->mobile_number ?? "" }}',
                        'user_id': '{{ $tawkUser->id }}'
                    }, function (error) { });

                    // Route check — show ONLY on Support pages, hide everywhere else
                    @if(!request()->routeIs('support.*'))
                        Tawk_API.hideWidget();
                    @endif
                                                                            };
            </script>
            {{-- Load the actual Tawk.to widget script --}}
            <script async src="https://embed.tawk.to/{{ $tawkPropertyId }}/{{ $tawkWidgetId }}"></script>
        @else
            <script>
                // Guest visitors — hide Tawk.to completely
                var Tawk_API = Tawk_API || {};
                Tawk_API.onLoad = function () { Tawk_API.hideWidget(); };
            </script>
        @endauth
    @endif
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}

    <!-- Custom Body Scripts -->
    {!! \App\Models\Setting::get('body_script') !!}

    @if(\App\Models\Setting::get('adsterra_popunder_enabled', '0') == '1' && \App\Models\Setting::get('adsterra_popunder_script'))
        {!! \App\Models\Setting::get('adsterra_popunder_script') !!}
    @endif
    <!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full glass-nav">
        <div class="px-4 py-3 lg:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click.stop="sidebarOpen = !sidebarOpen"
                        class="inline-flex items-center p-2 text-slate-300 rounded-lg sm:hidden hover:bg-white/5 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex ml-2 md:mr-24 items-center gap-2">
                        @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                        @if($logo)
                            <img src="{{ asset('storage/' . $logo) }}" class="h-8 w-auto">
                        @else
                            <span class="text-2xl">💼</span>
                        @endif
                        <span
                            class="self-center text-xl font-black text-primary-500 tracking-tighter uppercase whitespace-nowrap">{{ \App\Models\Setting::get('site_name', 'EasyTSK') }}</span>
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    @auth
                        <a href="{{ route('withdrawals.index') }}" class="hidden md:flex flex-col items-end group">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 group-hover:text-primary-500 transition-colors">আপনার
                                ব্যালেন্স</span>
                            <div
                                class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full border border-white/5 group-hover:bg-white/10 transition-all">
                                <span class="text-emerald-400 font-bold">৳</span>
                                <span class="text-sm font-black text-white">
                                    {{ number_format(auth()->user()->points) }} <span
                                        class="text-[10px] text-slate-300 uppercase tracking-tighter">Pts</span>
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('referrals.index') }}" class="hidden md:flex flex-col items-end group">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1 group-hover:text-rose-400 transition-colors">লকড রেফারেল</span>
                            <div
                                class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full border border-white/5 group-hover:bg-white/10 transition-all text-slate-400">
                                <span class="text-rose-400 font-bold">🔒</span>
                                <span class="text-sm font-black text-white">
                                    {{ number_format(auth()->user()->locked_referral_points) }} <span
                                        class="text-[10px] text-slate-300 uppercase tracking-tighter">Pts</span>
                                </span>
                            </div>
                        </a>
                    @endauth
                    <div class="flex items-center gap-4">
                        @auth
                            <!-- Notifications Bell -->
                            <div class="relative" x-data="{ open: false }" @click="if(!open) markNotificationsAsRead()">
                                <button @click="open = !open" id="notification-bell"
                                    class="w-10 h-10 bg-white/5 border border-white/5 rounded-xl flex items-center justify-center text-slate-300 hover:text-white transition-all relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                    <span id="notif-badge"
                                        class="{{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }} absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-[#0B0F17]"></span>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute right-[-60px] md:right-0 mt-3 w-[90vw] md:w-80 glass-card p-4 z-50 animate-in fade-in slide-in-from-top-5 duration-200">
                                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/5">
                                        <h4 class="text-[10px] font-black text-white uppercase tracking-widest">সিগন্যাল
                                            এন্ড এলার্টস</h4>
                                        <span id="notif-count-text"
                                            class="text-[9px] font-black text-primary-500 uppercase">{{ auth()->user()->unreadNotifications->count() }}
                                            New</span>
                                    </div>
                                    <div id="notif-container"
                                        class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar">
                                        @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                                            <div
                                                class="p-3 rounded-xl {{ $notification->read_at ? 'bg-white/5 opacity-60' : 'bg-primary-500/5 border border-primary-500/10' }}">
                                                <p class="text-[10px] font-bold text-slate-100 leading-relaxed">
                                                    {{ $notification->data['message'] ?? 'No message' }}
                                                </p>
                                                <span
                                                    class="text-[8px] font-black text-slate-300 uppercase mt-2 block tracking-tighter">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @empty
                                            <div class="py-10 text-center" id="no-notif-msg">
                                                <p
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                                    কোন নোটিফিকেশন নেই</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 group">
                                <div class="hidden sm:block text-right">
                                    <p
                                        class="text-xs font-black text-white leading-none mb-1 group-hover:text-primary-500 transition-colors">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <span
                                        class="text-[9px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded uppercase tracking-widest">Premium
                                        User</span>
                                </div>
                                <div
                                    class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white font-black border border-primary-500/50 shadow-lg shadow-primary-900/40 group-hover:scale-105 transition-transform">
                                    {{ substr(auth()->user()->full_name ?? auth()->user()->name, 0, 1) }}
                                </div>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 text-xs font-black text-white bg-primary-600 rounded-xl hover:bg-primary-500 transition-all uppercase tracking-widest">
                                লগইন করুন
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm sm:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside id="logo-sidebar" x-cloak @click.away="sidebarOpen = false"
        class="fixed top-0 left-0 z-50 w-64 h-screen pt-20 transition-transform bg-[#0d1222] border-r border-white/5 sm:translate-x-0 -translate-x-full shadow-2xl sm:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-full px-4 pb-4 overflow-y-auto pt-4">
            <ul class="space-y-1 font-bold">
                {{-- <li>
                    <a href="{{ route('home') }}"
                        class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="text-[13px] uppercase tracking-wide">মেইন হোম</span>
                    </a>
                </li> --}}
                @auth
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">ড্যাশবোর্ড</span>
                        </a>
                    </li>
                    @php
                        $isSocialActive = setting('is_social_tasks_active', '1') == '1';
                        $isTimewallActive = setting('is_timewall_active', '0') == '1';
                        $isMonlixActive = setting('is_monlix_active', '0') == '1';
                        $isAdsterraActive = setting('is_adsterra_active', '0') == '1';
                        $anyTaskActive = $isSocialActive || $isTimewallActive || $isMonlixActive || $isAdsterraActive;
                    @endphp
                    <li>
                        <a href="{{ $isSocialActive ? route('tasks.index') : ($anyTaskActive ? route('tasks.index') : route('module.maintenance')) }}"
                            class="sidebar-link flex items-center justify-between p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('tasks.*') || request()->routeIs('timewall.*') || request()->routeIs('monlix.*') || request()->routeIs('adsterra.*') || request()->routeIs('offerwalls.*') ? 'active' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-[13px] uppercase tracking-wide">কাজসমূহ</span>
                            </div>
                            @if(!$anyTaskActive || !$isSocialActive)
                                <span
                                    class="text-[8px] font-black bg-rose-500/10 text-rose-500 px-1.5 py-0.5 rounded opacity-70">OFF</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('withdrawals.index') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('withdrawals.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">ওয়ালেট ও উইথড্র</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activity.index') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">অ্যাক্টিভিটি হিস্টরি</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('referrals.index') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">রেফারেল লিস্ট</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('support.index') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('support.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">সাপোর্ট টিকেট</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">প্রোফাইল সেটিং</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}"
                            class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span class="text-[13px] uppercase tracking-wide">লগইন করুন</span>
                        </a>
                    </li>
                @endauth
                <li>
                    <a href="{{ route('faq') }}"
                        class="sidebar-link flex items-center p-3 text-slate-300 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('faq') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span class="text-[13px] uppercase tracking-wide">সাধারণ প্রশ্ন</span>
                    </a>
                </li>
            </ul>

            @auth
                <div class="mt-8 pt-6 border-t border-white/5">
                    <ul class="space-y-1 font-bold">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center p-3 text-rose-400 rounded-2xl hover:bg-rose-500/10 transition-all font-bold">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="text-[13px] uppercase tracking-wide">লগআউট</span>
                                </button>
                            </form>
                        </li>
                        <!-- PWA Install Button -->
                        <li id="pwa-install-container" style="display: none;">
                            <button type="button" id="pwa-install-btn"
                                class="w-full flex items-center p-3 mt-4 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-2xl hover:bg-indigo-600/30 transition-all font-black">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-[12px] uppercase tracking-widest">📱 Download App</span>
                            </button>
                        </li>
                    </ul>
                </div>
            @endauth

            <!-- <div class="mt-10 p-4 bg-emerald-500/5 rounded-3xl border border-emerald-500/10">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1 text-center">সাপোর্ট হেল্পলাইন</p>
                <p class="text-[9px] text-slate-500 font-medium text-center">যেকোনো প্রয়োজনে আমাদের সাথে যোগাযোগ করুন।</p>
            </div>-->
    </aside>

    <!-- Global Notification System -->
    <div
        class="fixed top-6 left-4 right-4 md:left-auto md:right-6 md:top-24 z-[999] space-y-4 md:max-w-sm pointer-events-none">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-[-20px] opacity-0 md:translate-x-full md:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="pointer-events-auto bg-emerald-500 rounded-[28px] p-5 shadow-2xl flex items-center gap-4 border border-emerald-400/20 relative overflow-hidden group">
                <!-- Decorative background pulse -->
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <p class="text-xs font-black text-white uppercase tracking-widest">সাফল্য / SUCCESS</p>
                    <p class="text-[11px] font-bold text-emerald-100 leading-tight">{{ session('success') }}</p>
                </div>
                <button @click="show = false"
                    class="p-2 -mr-2 text-emerald-100 hover:text-white transition-colors relative z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-[-20px] opacity-0 md:translate-x-full md:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="pointer-events-auto bg-rose-500 rounded-[28px] p-5 shadow-2xl flex items-center gap-4 border border-rose-400/20 relative overflow-hidden group">
                <!-- Decorative background pulse -->
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <p class="text-xs font-black text-white uppercase tracking-widest">ত্রুটি / ERROR</p>
                    <p class="text-[11px] font-bold text-rose-100 leading-tight">{{ session('error') }}</p>
                </div>
                <button @click="show = false"
                    class="p-2 -mr-2 text-rose-100 hover:text-white transition-colors relative z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-[-20px] opacity-0 md:translate-x-full md:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="pointer-events-auto bg-amber-500 rounded-[28px] p-5 shadow-2xl flex items-center gap-4 border border-amber-400/20 relative overflow-hidden group">
                <!-- Decorative background pulse -->
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <p class="text-xs font-black text-white uppercase tracking-widest">সতর্কতা / WARNING</p>
                    <p class="text-[11px] font-bold text-amber-100 leading-tight">{{ session('warning') }}</p>
                </div>
                <button @click="show = false"
                    class="p-2 -mr-2 text-amber-100 hover:text-white transition-colors relative z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-[-20px] opacity-0 md:translate-x-full md:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 md:translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="pointer-events-auto bg-indigo-500 rounded-[28px] p-5 shadow-2xl flex items-center gap-4 border border-indigo-400/20 relative overflow-hidden group">
                <!-- Decorative background pulse -->
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 relative z-10">
                    <p class="text-xs font-black text-white uppercase tracking-widest">তথ্য / INFO</p>
                    <p class="text-[11px] font-bold text-indigo-100 leading-tight">{{ session('info') }}</p>
                </div>
                <button @click="show = false"
                    class="p-2 -mr-2 text-indigo-100 hover:text-white transition-colors relative z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <!-- Welcome Bonus Celebration Modal -->
    @if(session('welcome_bonus'))
        <div x-data="{ showBonus: true }" x-show="showBonus"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
            <!-- Overlay -->
            <div x-show="showBonus" x-transition.opacity.duration.500ms class="fixed inset-0 bg-black/80 backdrop-blur-md">
            </div>

            <!-- Confetti (Simple CSS implementation) -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none z-0 flex justify-center items-center">
                <div
                    class="w-full h-full absolute animate-[ping_2s_ease-out_infinite] opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iNCIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==')]">
                </div>
            </div>

            <!-- Modal Body -->
            <div x-show="showBonus" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-50 translate-y-12"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 w-full max-w-md bg-gradient-to-br from-[#1a2235] to-[#0f1525] border border-primary-500/30 rounded-[40px] p-8 text-center shadow-[0_0_80px_rgba(0,200,83,0.3)] overflow-hidden">

                <!-- Glowing Orb Background -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-48 bg-primary-500/30 rounded-full blur-[60px]">
                </div>

                <div class="relative z-20">
                    <!-- Icon -->
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_40px_rgba(0,200,83,0.5)] border-4 border-[#1a2235] animate-bounce">
                        <span class="text-4xl">🎁</span>
                    </div>

                    <h2
                        class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-primary-200 uppercase tracking-tight mb-2">
                        স্বাগতম!</h2>
                    <p class="text-primary-400 font-bold text-sm tracking-widest uppercase mb-6">আপনার অ্যাকাউন্টে বোনাস যোগ
                        হয়েছে</p>

                    <div class="bg-black/40 rounded-[30px] p-6 border border-white/5 mb-8 backdrop-blur-sm shadow-inner">
                        <div class="text-5xl font-black text-white flex items-center justify-center gap-2 mb-2">
                            <span class="text-primary-500 text-3xl">✨</span>
                            {{ session('welcome_bonus') }}
                            <span class="text-primary-500 text-3xl">✨</span>
                        </div>
                        <p class="text-slate-300 text-xs font-bold uppercase tracking-widest">Points Received</p>
                    </div>

                    <button @click="showBonus = false"
                        class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white font-black rounded-2xl uppercase tracking-widest transition-all shadow-[0_10px_25px_rgba(0,200,83,0.4)] transform hover:-translate-y-1 hover:shadow-[0_15px_35px_rgba(0,200,83,0.5)]">
                        কাজ শুরু করুন 🚀
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="sm:ml-64 min-h-screen">
        <div class="mt-20 px-6 md:px-10 lg:px-12">
            <div class="max-w-7xl mx-auto">
                <x-live-ticker />
            </div>
        </div>
        <div class="p-6 md:p-10 lg:p-12 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- AdBlock Detector Modal -->
    <div id="adblock-modal" style="display: none;"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-dark/95 backdrop-blur-md p-4">
        <div class="max-w-md w-full bg-dark-card border border-white/10 rounded-[40px] p-8 text-center shadow-2xl">
            <div
                class="w-20 h-20 bg-rose-500/10 rounded-3xl flex items-center justify-center text-rose-500 mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-white mb-4 uppercase tracking-tight">অ্যাড-ব্লকার শনাক্ত হয়েছে!</h2>
            <p class="text-slate-300 font-medium mb-8 leading-relaxed">
                আমাদের প্ল্যাটফর্মটি সম্পূর্ণ ফ্রি এবং আমরা শুধুমাত্র বিজ্ঞাপনের মাধ্যমে টিকে আছি। অনুগ্রহ করে আপনার
                অ্যাড-ব্লকারটি বন্ধ করুন এবং পেজটি রিফ্রেশ দিন।
            </p>
            <button onclick="window.location.reload()"
                class="w-full py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-primary-900/40 transform hover:-translate-y-1">
                বিজ্ঞাপন বন্ধ করে রিলোড দিন 🔄
            </button>
        </div>
    </div>

    <div id="ab-honeypot" class="adsbox ad-zone ad-unit"
        style="position:absolute;left:-9999px;top:-9999px;height:1px;width:1px;pointer-events:none;z-index:-1;">&nbsp;
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Wait slightly longer to allow external scripts/extensions to load
            setTimeout(function () {
                const honeypot = document.getElementById('ab-honeypot');
                const modal = document.getElementById('adblock-modal');

                // Check if element was removed or hidden via CSS
                let isBlocked = false;

                if (!honeypot) {
                    isBlocked = true;
                } else {
                    const style = window.getComputedStyle(honeypot);
                    if (style.display === 'none' || style.visibility === 'hidden' || honeypot.offsetHeight === 0) {
                        isBlocked = true;
                    }
                }

                if (isBlocked && modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            }, 2500);
        });
    </script>

    @stack('scripts')

    <!-- Conversion Tracking Events -->
    @if(session('fire_event'))
        <script>
            window.addEventListener('load', function () {
                const eventName = "{{ session('fire_event') }}";
                console.log('Firing Conversion Event:', eventName);

                // 1. GTM DataLayer Push
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    'event': eventName,
                    'user_id': "{{ auth()->id() }}",
                    'timestamp': new Date().getTime()
                });

                // 2. Facebook Pixel/Google Ads (Custom JS Event)
                // This triggers a generic 'TrackingEvent' that the admin can listen to 
                // in their custom header scripts
                document.dispatchEvent(new CustomEvent('TrackingEvent', { detail: { name: eventName } }));

                // 3. Direct FB Pixel call if fbq exists
                if (typeof fbq === 'function') {
                    if (eventName === 'CompleteRegistration') fbq('track', 'CompleteRegistration');
                    else fbq('trackCustom', eventName);
                }
            });
        </script>
    @endif
    <!-- PWA Install Logic -->
    <script>
        let deferredPrompt;
        const installBtn = document.getElementById('pwa-install-btn');
        const installContainer = document.getElementById('pwa-install-container');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            if (installContainer) {
                installContainer.style.display = 'block';
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                // We've used the prompt, and can't use it again, throw it away
                deferredPrompt = null;
                // Hide the install button
                installContainer.style.display = 'none';
            });
        }

        window.addEventListener('appinstalled', (evt) => {
            console.log('PWA was installed');
            if (installContainer) {
                installContainer.style.display = 'none';
            }
        });
    </script>
    <!-- Notifications & Real-time logic -->
    <script>
        let lastNotifCount = {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }};

        function pollNotifications() {
            fetch("{{ route('notifications.poll') }}")
                .then(response => response.json())
                .then(data => {
                    updateNotificationUI(data);
                })
                .catch(err => console.error('Polling error:', err));
        }

        function updateNotificationUI(data) {
            const badge = document.getElementById('notif-badge');
            const countText = document.getElementById('notif-count-text');
            const container = document.getElementById('notif-container');
            const noNotifMsg = document.getElementById('no-notif-msg');

            // Update Badge & Count
            if (data.unread_count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
            countText.innerText = data.unread_count + ' New';

            // Check if we should fire Toastr
            if (data.unread_count > lastNotifCount) {
                const newNotifs = data.notifications.slice(0, data.unread_count - lastNotifCount);
                newNotifs.reverse().forEach(n => {
                    toastr.info(n.message, 'নতুন নোটিফিকেশন!', {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        onclick: function () { window.location.href = n.link; }
                    });
                });
            }
            lastNotifCount = data.unread_count;

            // Handle Urgent Global Notice
            if (data.urgent_notice) {
                if (!window.urgentSeen || window.urgentSeen !== data.urgent_notice) {
                    toastr.error(data.urgent_notice, 'জরুরী ঘোষণা 🚨', {
                        timeOut: 0,
                        extendedTimeOut: 0,
                        closeButton: true,
                        positionClass: "toast-top-right"
                    });
                    window.urgentSeen = data.urgent_notice;
                }
            }

            // Update Dropdown Content
            if (data.notifications.length > 0) {
                if (noNotifMsg) noNotifMsg.remove();

                // Clear and repopulate for simplicity in this flow
                container.innerHTML = '';
                data.notifications.forEach(n => {
                    const div = document.createElement('div');
                    div.className = 'p-3 rounded-xl bg-primary-500/5 border border-primary-500/10 transition-all hover:bg-primary-500/10 cursor-pointer';
                    div.onclick = () => window.location.href = n.link;
                    div.innerHTML = `
                        <p class="text-[10px] font-bold text-slate-100 leading-relaxed">${n.message}</p>
                        <span class="text-[8px] font-black text-slate-300 uppercase mt-2 block tracking-tighter">${n.created_at}</span>
                    `;
                    container.appendChild(div);
                });
            }
        }

        function markNotificationsAsRead() {
            const badge = document.getElementById('notif-badge');
            if (badge.classList.contains('hidden')) return; // Already read

            fetch("{{ route('notifications.mark_read') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        badge.classList.add('hidden');
                        document.getElementById('notif-count-text').innerText = '0 New';
                        lastNotifCount = 0;
                        // Optionally fade out the "unread" styling from items
                        document.querySelectorAll('#notif-container > div').forEach(el => {
                            el.classList.add('opacity-60');
                            el.classList.remove('bg-primary-500/5', 'border-primary-500/10');
                            el.classList.add('bg-white/5');
                        });
                    }
                });
        }

        @auth
            // Start polling every 20 seconds for global notifications
            setInterval(pollNotifications, 20000);

            // Initial poll to sync after 3 seconds
            setTimeout(pollNotifications, 3000);
        @endauth
    </script>
</body>

</html>