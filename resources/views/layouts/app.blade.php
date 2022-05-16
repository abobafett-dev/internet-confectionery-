@php
use Illuminate\Support\Facades\URL;
@endphp

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
        <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/normalize.css')}}" type="text/css">
        <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/app.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/style.css')}}" type="text/css">

        <!-- Logo -->
        <link rel="shortcut icon" href="{{\Illuminate\Support\Facades\Storage::url('logo/Logo.png')}}" type="image/png">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body class="font-sans antialiased" style="">
        <div class="min-h-screen bg-gray-100" style="padding-bottom: 325px;">
            <!-- Page Heading -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <main style="padding-bottom: 40px;">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="bg-white border-gray-200">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- Page Footer -->
        <div style="margin-top: -325px;">
        @include('layouts.footer')
        </div>
    </body>
</html>
