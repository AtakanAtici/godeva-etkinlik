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
        @vite(['resources/css/app.css'])
        <!-- Skip app.js to avoid WebSocket/Echo issues -->
    @endif
    @livewireStyles
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    @yield('content')
    @livewireScripts
</body>
</html>