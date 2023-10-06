<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Login') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/all.min.css')}}">

        <!-- Lineawesome CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/line-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/material.css')}}">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

        <!-- Scripts -->
        <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <!-- Main Wrapper -->
            <div class="main-wrapper">
                <div class="account-content">
                    <div class="container">
                        
                        <!-- Account Logo -->
                        <div class="account-logo">
                            <a href="#">
                                <img src="assets/img/logo1.png" alt="" class="w-20 h-20 fill-current text-gray-500">
                            </a>
                        </div>
                        <!-- /Account Logo -->

                        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                            {{ $slot }}
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </body>


    <!-- jQuery -->
    <script src="{{asset('assets/js/jquery-3.6.1.min.js')}}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Theme Settings JS -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <script src="{{asset('assets/js/theme-settings.js')}}"></script>
    <script src="{{asset('assets/js/greedynav.js')}}"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}"></script>

    <script src="{{asset('assets/js/app.js')}}"></script>

</html>
