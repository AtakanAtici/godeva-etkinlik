<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Godeva Etkinlik')</title>
    @if(app()->environment('production'))
        <!-- TailwindCSS CDN for Production -->
        <script src="https://cdn.tailwindcss.com"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
    <script>
        window.pusher = {
            key: "{{ config('broadcasting.connections.pusher.key') }}",
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            host: "{{ config('broadcasting.connections.pusher.options.host') }}",
            port: {{ config('broadcasting.connections.pusher.options.port') }},
            scheme: "{{ config('broadcasting.connections.pusher.options.scheme') }}"
        };
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    @yield('content')
    @livewireScripts
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</body>
</html>