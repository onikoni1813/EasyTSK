<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel — {{ \App\Models\Setting::get('site_name', 'EasyTSK') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@400;600;700;800&display=swap"
        rel="stylesheet">

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
            --radius: 20px;
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
            border-radius: var(--radius);
        }

        /* Mobile specific adjustments */
        @media (max-width: 640px) {
            :root {
                --radius: 16px;
            }

            .p-8,
            .p-10,
            .p-12 {
                padding: 1.25rem !important;
            }

            .px-8,
            .px-10,
            .px-12 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .py-5,
            .py-6,
            .py-8 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .gap-8,
            .gap-10 {
                gap: 1rem !important;
            }

            .text-3xl {
                font-size: 1.5rem !important;
            }

            /* Table specific */
            table th,
            table td {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                font-size: 9px !important;
            }
        }

        @media (max-width: 1024px) {
            .sm\:ml-64 {
                margin-left: 0 !important;
            }
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

        .premium-btn {
            background: linear-gradient(135deg, var(--green), #00e676);
            color: #000;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -10px rgba(0, 200, 83, 0.4);
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(0, 200, 83, 0.6);
        }
    </style>
</head>

<body class="antialiased selection:bg-primary-500/30 selection:text-primary-200" x-data="{ sidebarOpen: false }">
    <!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full glass-nav">
        <div class="px-4 py-3 lg:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click.stop="sidebarOpen = !sidebarOpen"
                        class="inline-flex items-center p-2 text-slate-400 rounded-lg sm:hidden hover:bg-white/5 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex ml-2 md:mr-24 items-center gap-2">
                        <span class="text-2xl">⚡</span>
                        <span
                            class="self-center text-xl font-black text-primary-500 tracking-tighter uppercase whitespace-nowrap">Admin
                            <span class="text-white">Nexus</span></span>
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex flex-col items-end">
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">System
                            Status</span>
                        <div
                            class="flex items-center gap-2 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-black text-emerald-500 uppercase">Operational</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-3 group">
                            <div class="hidden sm:block text-right">
                                <p
                                    class="text-xs font-black text-white leading-none mb-1 group-hover:text-primary-500 transition-colors">
                                    {{ auth()->user()->name }}
                                </p>
                                <span
                                    class="text-[9px] font-bold text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded uppercase tracking-widest">Master
                                    Admin</span>
                            </div>
                            <div
                                class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white font-black border border-primary-500/50 shadow-lg shadow-primary-900/40 group-hover:scale-105 transition-transform">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
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
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Admin Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Manage Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.withdrawals.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.withdrawals.index') || request()->routeIs('admin.withdrawals.approve') || request()->routeIs('admin.withdrawals.reject') || request()->routeIs('admin.withdrawals.confiscate') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Withdraw Requests</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.withdrawal-methods.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.withdrawal-methods.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Payment Methods</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kyc.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Pending KYC</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tasks.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Manage Tasks</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.domains.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Domain Manager</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.posts.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v3m2 4l-4 4m0 0l-4-4m4 4V4">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Blog Post Manager</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.submissions.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Task Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.support.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Support Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ticker.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.ticker.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Live Ticker HUD</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.referrals.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Referral Logs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.fraud.index') }}"
                        class="sidebar-link flex items-center p-3 text-rose-500 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.fraud.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Security Radar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Global Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.email') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Email Protocol</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.system.index') }}"
                        class="sidebar-link flex items-center p-3 text-slate-400 rounded-2xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.system.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">System Manager</span>
                    </a>
                </li>
            </ul>

            <div class="mt-8 pt-6 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center p-3 text-rose-500 rounded-2xl hover:bg-rose-500/5 transition-all font-bold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="text-[12px] uppercase tracking-wide">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="sm:ml-64 min-h-screen">
        <div class="p-4 md:p-10 lg:p-12 mt-16 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
</body>

</html>