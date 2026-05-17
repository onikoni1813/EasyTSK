<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('site_name', 'EasyTSK') }} — ঘরে বসে আয় করুন | Bkash Nagad Rocket</title>
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <meta name="description"
        content="EasyTSK — বাংলাদেশের সেরা টাস্ক আর্নিং প্ল্যাটফর্ম। ছোট ছোট কাজ করুন, বিকাশ/নগদ/রকেটে পেমেন্ট পান। কোনো বিনিয়োগ নেই।">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--dark);
            color: var(--text);
            font-family: 'Hind Siliguri', sans-serif;
            line-height: 1.6;
        }

        /* ── Header ─────────────────────────────────────── */
        header {
            position: sticky;
            top: 0;
            z-index: 999;
            background: rgba(10, 15, 30, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: .8rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--green2);
            letter-spacing: -0.5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .logo-icon {
            font-size: 1.6rem;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-outline {
            padding: .5rem 1.1rem;
            border: 1.5px solid var(--green);
            border-radius: 50px;
            color: var(--green);
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .3s;
        }

        .btn-outline:hover {
            background: var(--green);
            color: #000;
        }

        .btn-green {
            padding: .5rem 1.3rem;
            background: linear-gradient(135deg, var(--green), #00a843);
            border: none;
            border-radius: 50px;
            color: #000;
            font-size: .9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .3s;
            box-shadow: 0 4px 20px rgba(0, 200, 83, .3);
        }

        .btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 200, 83, .5);
        }

        /* ── Hero ─────────────────────────────────────── */
        .hero {
            min-height: 88vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 1.5rem 3rem;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(0, 200, 83, .15) 0%, transparent 60%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2300c853' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(0, 200, 83, .1);
            border: 1px solid rgba(0, 200, 83, .3);
            color: var(--green2);
            padding: .35rem .9rem;
            border-radius: 50px;
            font-size: .8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            animation: fadeDown .6s ease;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            animation: fadeDown .7s ease;
        }

        .hero h1 span {
            color: var(--green2);
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--muted);
            max-width: 600px;
            margin: 0 auto 2rem;
            animation: fadeDown .8s ease;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeDown .9s ease;
        }

        .btn-hero {
            padding: .9rem 2rem;
            background: linear-gradient(135deg, var(--green), #00a843);
            border: none;
            border-radius: 50px;
            color: #000;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 0 40px rgba(0, 200, 83, .4);
            transition: all .3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 60px rgba(0, 200, 83, .6);
        }

        .btn-hero-outline {
            padding: .9rem 2rem;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 50px;
            color: var(--text);
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .3s;
        }

        .btn-hero-outline:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .trust-badges {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2.5rem;
            animation: fadeDown 1s ease;
        }

        .trust-label {
            font-size: .8rem;
            color: var(--muted);
        }

        .badge-pill {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: .4rem 1rem;
            font-size: .85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .bkash {
            color: #e01f5a;
        }

        .nagad {
            color: #ef6c00;
        }

        .rocket {
            color: #8b1a8b;
        }

        /* ── Live Stats ─────────────────────────────────── */
        .stats-bar {
            background: var(--card);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .stats-inner {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            text-align: center;
        }

        .stat-item {
            padding: 1.5rem 1rem;
            border-right: 1px solid var(--border);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-num {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 700;
            color: var(--green2);
            font-family: 'Inter', sans-serif;
        }

        .stat-lbl {
            font-size: .85rem;
            color: var(--muted);
            margin-top: .2rem;
        }

        /* ── Payment Ticker ─────────────────────────────── */
        /* ── How It Works ───────────────────────────────── */
        section {
            padding: 5rem 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
        }

        .section-title p {
            color: var(--muted);
            margin-top: .5rem;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .step-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem 1.5rem;
            text-align: center;
            transition: transform .3s, border-color .3s;
            position: relative;
        }

        .step-card:hover {
            transform: translateY(-6px);
            border-color: var(--green);
        }

        .step-num {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--green), #00a843);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: #000;
            margin: 0 auto 1.2rem;
        }

        .step-icon {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .step-card h3 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: .6rem;
        }

        .step-card p {
            font-size: .9rem;
            color: var(--muted);
        }

        /* ── Features ──────────────────────────────────── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--card2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.75rem;
            display: flex;
            gap: 1.2rem;
            align-items: flex-start;
            transition: transform .3s, border-color .3s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 200, 83, .4);
        }

        .feature-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .feature-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: .3rem;
        }

        .feature-card p {
            font-size: .85rem;
            color: var(--muted);
        }

        /* ── CTA Section ─────────────────────────────────── */
        .cta-section {
            text-align: center;
            padding: 5rem 1.5rem;
            background: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(0, 200, 83, .12) 0%, transparent 70%);
        }

        .cta-section h2 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: var(--muted);
            margin-bottom: 2rem;
        }

        /* ── Footer ─────────────────────────────────────── */
        footer {
            background: var(--card);
            border-top: 1px solid var(--border);
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: var(--muted);
            font-size: .85rem;
            text-decoration: none;
            transition: color .3s;
        }

        .footer-links a:hover {
            color: var(--green2);
        }

        .footer-copy {
            font-size: .8rem;
            color: #4b5563;
        }

        /* ── Animations ─────────────────────────────────── */
        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Mobile ─────────────────────────────────────── */
        @media (max-width: 600px) {
            .hero {
                min-height: auto;
                padding-top: 5rem;
                padding-bottom: 3rem;
            }

            .stats-inner {
                grid-template-columns: 1fr;
            }

            .stat-item {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .header-nav .btn-outline {
                display: none;
            }
        }
    </style>
    <!-- Custom Header Scripts -->
    {!! \App\Models\Setting::get('header_script') !!}
</head>

<body>
    <!-- Tawk.to: Hide on landing/welcome page -->
    <script>var Tawk_API = Tawk_API || {}; Tawk_API.onLoad = function () { Tawk_API.hideWidget(); };</script>
    <!-- Custom Body Scripts -->
    {!! \App\Models\Setting::get('body_script') !!}

    <!-- ═══════════════ HEADER ════════════════════════════════════════════════ -->
    <header>
        <a href="/" class="logo">
            @php $logo = \App\Models\Setting::get('site_logo'); @endphp
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" class="h-8 w-auto">
            @else
                <span class="logo-icon">⚡</span>
            @endif
            <span>{{ \App\Models\Setting::get('site_name', 'EasyTSK') }}</span>
        </a>
        <nav class="header-nav">
            <a href="{{ route('login') }}" class="btn-outline">লগইন</a>
            <a href="{{ route('register') }}" class="btn-green">একাউন্ট খুলুন</a>
        </nav>
    </header>

    <!-- ═══════════════ HERO ══════════════════════════════════════════════════ -->
    <section class="hero">
        <div class="badge"> বাংলাদেশের বিশ্বস্ত আয়ের প্ল্যাটফর্ম</div>
        <h1>ঘরে বসে <span>ছোট কাজ</span> করুন,<br>সরাসরি <span>বিকাশে</span> পান</h1>
        <p>কোনো বিনিয়োগ নেই। কোনো স্কিল দরকার নেই। শুধু মোবাইল থেকে কাজ করুন এবং প্রতিদিন আয় করুন।</p>
        <div class="hero-cta">
            <a href="{{ route('register') }}" class="btn-hero">🚀 এখনই শুরু করুন — বিনামূল্যে</a>
            <a href="{{ route('login') }}" class="btn-hero-outline">লগইন করুন</a>
        </div>
        <div class="trust-badges">
            <span class="trust-label">পেমেন্ট মেথড:</span>
            <span class="badge-pill bkash">🔴 বিকাশ</span>
            <span class="badge-pill nagad">🟠 নগদ</span>
            <span class="badge-pill" style="color:#64748b">📱 মোবাইল রিচার্জ</span>
        </div>
    </section>

    <!-- ═══════════════ LIVE STATS ════════════════════════════════════════════ -->
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-num" id="stat-members">লোড হচ্ছে…</div>
                <div class="stat-lbl">মোট সদস্য</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" id="stat-paid">লোড হচ্ছে…</div>
                <div class="stat-lbl">মোট পেমেন্ট</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" id="stat-tasks">লোড হচ্ছে…</div>
                <div class="stat-lbl">আজকের টাস্ক</div>
            </div>
        </div>
    </div>

    <!-- ═══════════════ PAYMENT TICKER ════════════════════════════════════════ -->
    <x-live-ticker />

    <!-- ═══════════════ HOW IT WORKS ══════════════════════════════════════════ -->
    <section>
        <div class="section-title">
            <h2>কিভাবে কাজ করে?</h2>
            <p>মাত্র ৩টি সহজ ধাপে আয় শুরু করুন</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">১</div>
                <div class="step-icon">📝</div>
                <h3>বিনামূল্যে একাউন্ট খুলুন</h3>
                <p>মোবাইল নম্বর ও ইমেইল দিয়ে ৩০ সেকেন্ডে রেজিস্ট্রেশন করুন। কোনো ফি নেই।</p>
            </div>
            <div class="step-card">
                <div class="step-num">২</div>
                <div class="step-icon">✅</div>
                <h3>ছোট কাজ সম্পন্ন করুন</h3>
                <p>সোশ্যাল মিডিয়া টাস্ক, অ্যাড দেখা, সার্ভে ইত্যাদি কাজ করুন এবং পয়েন্ট আয় করুন।</p>
            </div>
            <div class="step-card">
                <div class="step-num">৩</div>
                <div class="step-icon">💸</div>
                <h3>বিকাশ/নগদে উইথড্র করুন</h3>
                <p>পর্যাপ্ত পয়েন্ট জমলে সরাসরি বিকাশ, নগদ বা মোবাইল রিচার্জ করুন।</p>
            </div>
        </div>
    </section>

    <!-- ═══════════════ FEATURES ══════════════════════════════════════════════ -->
    <section style="padding-top: 1rem;">
        <div class="section-title">
            <h2>কেন আমাদের বেছে নেবেন?</h2>
            <p>আমাদের কোনো এক্টিভেশন চার্জ নেই ।</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-icon">⚡</span>
                <div>
                    <h3>দ্রুত ম্যানুয়াল পেমেন্ট</h3>
                    <p>আমাদের টিম ২৪-৪৮ ঘণ্টার মধ্যে আপনার পেমেন্ট প্রসেস করে।</p>
                </div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🤖</span>
                <div>
                    <h3>অটোমেটিক আয়</h3>
                    <p>অফার সম্পন্ন করুন এবং স্বয়ংক্রিয়ভাবে পয়েন্ট পান।</p>
                </div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">👥</span>
                <div>
                    <h3>মেগা রেফারেল বোনাস</h3>
                    <p>বন্ধুকে রেফার করুন এবং তারা কাজ করলে আপনি বোনাস পাবেন।</p>
                </div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🔒</span>
                <div>
                    <h3>১০০% নিরাপদ</h3>
                    <p>আপনার একাউন্ট ও তথ্য সম্পূর্ণ এনক্রিপ্টেড এবং সুরক্ষিত।</p>
                </div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <div>
                    <h3>মোবাইল ফ্রেন্ডলি</h3>
                    <p>যেকোনো স্মার্টফোন থেকে কাজ করুন। কোনো অ্যাপ ডাউনলোডের দরকার নেই।</p>
                </div>
            </div>
            <div class="feature-card">
                <span class="feature-icon">💰</span>
                <div>
                    <h3>শূন্য বিনিয়োগ</h3>
                    <p>রেজিস্ট্রেশন থেকে উইথড্র পর্যন্ত কোনো টাকা লাগবে না।</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════ CTA ════════════════════════════════════════════════════ -->
    <div class="cta-section">
        <h2>আজই শুরু করুন এবং<br><span style="color:var(--green2)">প্রথম দিনেই আয় করুন!</span></h2>
        <p>হাজার হাজার বাংলাদেশি ইতিমধ্যে আয় করছেন। আপনিও সুযোগ নিন।</p>
        <a href="{{ route('register') }}" class="btn-hero">🎉 বিনামূল্যে যোগ দিন</a>
    </div>

    <!-- ═══════════════ FOOTER ════════════════════════════════════════════════ -->
    <footer>
        <div class="footer-links">
            <a href="{{ route('terms') }}">শর্তাবলী</a>
            <a href="{{ route('privacy') }}">গোপনীয়তা</a>
            <a href="{{ route('faq') }}">সাধারণ প্রশ্ন</a>
            <a href="{{ route('contact') }}">যোগাযোগ</a>
        </div>
        <p class="footer-copy">© {{ date('Y') }} EasyTSK — সর্বস্বত্ব সংরক্ষিত।</p>
    </footer>

    <!-- ═══════════════ SCRIPTS ════════════════════════════════════════════════ -->
    <script>
        // Live Stats via Ajax with counting animation
        function animateValue(id, start, end, duration, prefix = '', suffix = '') {
            if (start === end) {
                document.getElementById(id).textContent = prefix + end.toLocaleString() + suffix;
                return;
            }
            const obj = document.getElementById(id);
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const current = Math.floor(progress * (end - start) + start);
                obj.textContent = prefix + current.toLocaleString() + suffix;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        let firstLoad = true;

        function loadStats() {
            fetch('/api/stats')
                .then(r => r.json())
                .then(d => {
                    if (firstLoad) {
                        animateValue('stat-members', 0, d.raw.members, 1500, '', '+');
                        animateValue('stat-paid', 0, d.raw.paid, 2000, '৳ ', '+');
                        animateValue('stat-tasks', 0, d.raw.tasks, 1200, '', '+');
                        firstLoad = false;
                    } else {
                        document.getElementById('stat-members').textContent = d.formatted.members;
                        document.getElementById('stat-paid').textContent = d.formatted.paid;
                        document.getElementById('stat-tasks').textContent = d.formatted.today_tasks;
                    }
                })
                .catch(() => {
                    document.getElementById('stat-members').textContent = '১০,০০০+';
                    document.getElementById('stat-paid').textContent = '৳ ৫০,০০০+';
                    document.getElementById('stat-tasks').textContent = '৫০০+';
                });
        }

        loadStats();
        setInterval(loadStats, 60000);
    </script>

    <!-- AdBlock Detector Modal -->
    <div id="adblock-modal" style="display: none;"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-dark/95 backdrop-blur-md p-4">
        <div
            style="background: #1a2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 40px; padding: 2rem; text-align: center; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            <div
                style="width: 80px; height: 80px; background: rgba(244, 63, 94, 0.1); border-radius: 24px; display: flex; align-items: center; justify-content: center; color: #f43f5e; margin: 0 auto 1.5rem;">
                <svg style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h2
                style="font-size: 1.5rem; font-weight: 900; color: white; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: -0.025em;">
                অ্যাড-ব্লকার শনাক্ত হয়েছে!</h2>
            <p style="color: #94a3b8; font-weight: 500; margin-bottom: 2rem; line-height: 1.6;">
                আমাদের প্ল্যাটফর্মটি সম্পূর্ণ ফ্রি এবং আমরা শুধুমাত্র বিজ্ঞাপনের মাধ্যমে টিকে আছি। অনুগ্রহ করে আপনার
                অ্যাড-ব্লকারটি বন্ধ করুন এবং পেজটি রিফ্রেশ দিন।
            </p>
            <button onclick="window.location.reload()"
                style="width: 100%; padding: 1rem; background: #00c853; color: white; font-weight: 900; border: none; border-radius: 1rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 15px -3px rgba(0, 200, 83, 0.4);">
                বিজ্ঞাপন বন্ধ করে রিলোড দিন 🔄
            </button>
        </div>
    </div>

    <div id="ab-honeypot" class="adsbox ad-zone ad-unit"
        style="position:absolute;z-index:-1;height:1px;width:1px;left:-1px;top:-1px;visibility:hidden;">&nbsp;</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const honeypot = document.getElementById('ab-honeypot');
                const modal = document.getElementById('adblock-modal');

                if (!honeypot || honeypot.offsetParent === null || honeypot.offsetHeight === 0 || window.getComputedStyle(honeypot).display === 'none') {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            }, 1000);
        });
    </script>
</body>

</html>