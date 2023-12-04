<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Stock Market | nahime</title>

    <!-- Styles -->
    @vite('resources/css/app.css')
    <!-- JS -->
    @vite('resources/js/app.js')
</head>
<body class="antialiased">
    {{ $slot }}
</body>
</html>
