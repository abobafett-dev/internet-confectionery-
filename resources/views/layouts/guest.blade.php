<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>«Мечтай»</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css')}}">

        <!-- Logo -->
        <link rel="shortcut icon" href="/storage/logo/Logo.png" type="image/png">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>

        <div style="position:absolute; width: 100%">
            @include('layouts.navigation')
        </div>
        <div class="font-sans text-gray-900 antialiased" style="min-height: 830px; height: 100%;">
            {{ $slot }}
        </div>
    </body>
</html>
