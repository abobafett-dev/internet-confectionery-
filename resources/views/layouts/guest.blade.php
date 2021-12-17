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
        <link rel="shortcut icon" href="{{\Illuminate\Support\Facades\Storage::url('logo/Logo.png')}}" type="image/png">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <style>
            html, body {
                height: 100%;
                scroll-behavior: smooth;
                min-width: 1260px;
                display: flex;
                flex-direction:column;
                justify-content: space-between;
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>

        <div style="position:absolute; width: 100%">
            @include('layouts.navigation')
        </div>
        <div class="font-sans text-gray-900 antialiased" style="height: 100%;">
            {{ $slot }}
        </div>
        @include('layouts.footer')
    </body>
</html>
