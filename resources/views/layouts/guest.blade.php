<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::get('site_name', 'EasyTSK') }} — প্রবেশ করুন</title>
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
    <style>
        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            background-color: #0a0f1e;
            color: #e2e8f0;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-green {
            background: linear-gradient(135deg, #00c853, #00a843);
            color: #000;
            box-shadow: 0 4px 20px rgba(0, 200, 83, 0.3);
        }

        .btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 200, 83, 0.5);
        }

        input:focus {
            border-color: #00c853;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 200, 83, 0.2);
        }
    </style>
    <!-- Custom Header Scripts -->
    @if(\App\Models\Setting::get('header_script'))
        {!! \App\Models\Setting::get('header_script') !!}
    @endif
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
</head>

<body class="antialiased selection:bg-green-500/30">
    <!-- Tawk.to: Hide on login/register pages (guests don't need live chat) -->
    <script>var Tawk_API = Tawk_API || {}; Tawk_API.onLoad = function () { Tawk_API.hideWidget(); };</script>
    <!-- Custom Body Scripts -->
    @if(\App\Models\Setting::get('body_script'))
        {!! \App\Models\Setting::get('body_script') !!}
    @endif
    <div class="min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <!-- Background Orbs -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-green-500/10 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none">
        </div>

        <div class="w-full max-w-md relative">
            <div class="text-center mb-10">
                <a href="{{ auth()->check() ? route('dashboard') : '/' }}"
                    class="inline-flex items-center gap-2 text-2xl font-black text-green-500 tracking-tighter uppercase mb-2">
                    @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" class="h-12 w-auto">
                    @else
                        <span class="text-3xl">💼</span>
                    @endif
                    {{ \App\Models\Setting::get('site_name', 'EasyTSK') }}
                </a>
                <p class="text-slate-400 text-sm font-medium">বাংলাদেশের ১ নম্বর নির্ভরযোগ্য মাইক্রো জব সাইট</p>
            </div>

            <div class="glass-card p-10 rounded-3xl shadow-2xl relative">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">© {{ date('Y') }}
                    {{ \App\Models\Setting::get('site_name', 'EasyTSK') }} — All Rights Reserved
                </p>
            </div>
        </div>
    </div>
    <!-- Conversion Tracking Events -->
    @if(session('fire_event'))
        <script>
            window.addEventListener('load', function () {
                const eventName = "{{ session('fire_event') }}";
                console.log('Firing Conversion Event:', eventName);
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    'event': eventName,
                    'user_id': "{{ auth()->id() }}",
                    'timestamp': new Date().getTime()
                });
                document.dispatchEvent(new CustomEvent('TrackingEvent', { detail: { name: eventName } }));
                if (typeof fbq === 'function') {
                    if (eventName === 'CompleteRegistration') fbq('track', 'CompleteRegistration');
                    else fbq('trackCustom', eventName);
                }
            });
        </script>
    @endif
</body>

</html>