<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        {{--@vite(['resources/css/app.css', 'resources/js/app.js'])--}}
        <link rel="preload" as="style" href="{{url('build/assets/app-8c73b358.css')}}" />
        <link rel="modulepreload" href="{{url('build/assets/app-619f552e.js')}}" />
        <link rel="stylesheet" href="{{url('build/assets/app-8c73b358.css')}}" />
        <script type="module" src="{{url('build/assets/app-619f552e.js')}}"></script>

       <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
       <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
               
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
