<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন প্যানেল — {{ \App\Models\Setting::get('site_name', 'EasyTSK') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Inter:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .sidebar-link.active {
            background-color: rgba(0, 200, 83, 0.1);
            color: #00c853;
            border-right: 4px solid #00c853;
        }

        /* Smooth transitions */
        .transition-all {
            transition-duration: 300ms;
        }
    </style>
</head>

<body class="antialiased text-slate-300 bg-[#0a0f1e]">

    <div class="flex min-h-screen" x-data="{ 
        sidebarOpen: true, 
        unreadSupportCount: 0,
        init() {
            this.fetchUnreadCount();
            setInterval(() => this.fetchUnreadCount(), 10000);
        },
        async fetchUnreadCount() {
            try {
                const res = await fetch('{{ route('admin.support.unread_count') }}');
                const data = await res.json();
                this.unreadSupportCount = data.unread_count || 0;
            } catch (e) {
                console.error('Failed to fetch unread support count', e);
            }
        }
    }">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-[#0d1222] border-r border-white/5 transition-all duration-300 lg:static"
            x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">

            <div class="flex items-center px-8 h-24 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" class="h-8 w-auto">
                    @else
                        <span class="text-3xl">💼</span>
                    @endif
                    <span
                        class="text-xl font-black text-primary-500 tracking-tighter uppercase whitespace-nowrap">{{ \App\Models\Setting::get('site_name', 'EasyTSK') }}</span>
                </a>
            </div>

            <nav class="flex-1 px-6 py-8 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    DASHBOARD
                </a>

                <div class="pt-6 pb-2 px-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">Management
                </div>

                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    USERS
                </a>

                <a href="{{ route('admin.tasks.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    TASKS
                </a>

                <a href="{{ route('admin.leaderboard.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.leaderboard.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    LEADERBOARDS
                </a>

                <a href="{{ route('admin.submissions.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    MODERATION
                </a>

                <a href="{{ route('admin.withdrawals.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    WITHDRAWALS
                </a>

                <a href="{{ route('admin.referrals.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    REFERRAL LOGS
                </a>

                <a href="{{ route('admin.fraud.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-rose-500 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.fraud.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    SECURITY RADAR
                </a>

                <div class="pt-6 pb-2 px-4 text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">System</div>

                <a href="{{ route('admin.kyc.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    KYC REQUESTS
                </a>

                <a href="{{ route('admin.support.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            TICKETS
                        </div>
                        <template x-if="unreadSupportCount > 0">
                            <span
                                class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full font-black animate-pulse"
                                x-text="unreadSupportCount"></span>
                        </template>
                    </div>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="sidebar-link flex items-center px-4 py-4 text-xs font-black text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    SETTINGS
                </a>
            </nav>

            <div class="px-6 py-8 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-4 text-xs font-black text-rose-500 rounded-2xl hover:bg-rose-500/5 transition-all uppercase">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#0a0f1e]">
            <!-- Topbar -->
            <header
                class="flex items-center justify-between h-24 px-8 bg-[#0a0f1e]/80 backdrop-blur-md border-b border-white/5 sticky top-0 z-40">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="ml-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        {{ $title ?? 'System Console' }}
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Live Support Notification -->
                    <a href="{{ route('admin.support.index') }}"
                        class="relative group p-2 rounded-xl hover:bg-white/5 transition-all">
                        <svg class="w-6 h-6 text-slate-400 group-hover:text-primary-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                        <template x-if="unreadSupportCount > 0">
                            <span class="absolute -top-1 -right-1 flex h-5 w-5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-5 w-5 bg-rose-500 items-center justify-center">
                                    <span class="text-[8px] font-black text-white" x-text="unreadSupportCount"></span>
                                </span>
                            </span>
                        </template>
                    </a>

                    <div class="flex items-center gap-4">
                        <div class="hidden md:flex flex-col text-right">
                            <span
                                class="text-xs font-black text-white tracking-tight leading-none mb-1">{{ auth()->user()->full_name ?? auth()->user()->name }}</span>
                            <span
                                class="text-[9px] font-black text-primary-500 bg-primary-500/10 px-2 py-0.5 rounded uppercase tracking-widest">Root
                                Admin</span>
                        </div>
                        <div
                            class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-primary-900/40 border border-primary-500/50">
                            {{ substr(auth()->user()->full_name ?? auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>


            <!-- Page Content -->
            <div class="flex-1 px-10 py-12 overflow-y-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>