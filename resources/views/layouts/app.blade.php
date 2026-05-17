<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Custom Header Scripts -->
    {!! \App\Models\Setting::get('header_script') !!}
</head>

<body class="font-sans antialiased">
    <!-- Custom Body Scripts -->
    {!! \App\Models\Setting::get('body_script') !!}
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
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