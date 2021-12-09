<!DOCTYPE html>
<html lang="ru">
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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <!-- Page Heading -->
                @include('layouts.navigation')

            <!-- Page Content -->
            <main style="padding-bottom: 40px;">
                {{ $slot }}
            </main>
        </div>
        @include('layouts.footer')
    </body>
</html>
