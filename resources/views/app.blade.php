<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Fotospeed') }}</title>
        <link rel="stylesheet" href="{{ env('APP_URL') }}/build/assets/app-DoJU4k58.css">

        <!-- Scripts -->
        @php
        // dd(session('theme'));
            $routePrefix = request()->route() ? request()->route()->getPrefix() : '';
            // dd($routePrefix);
            // $theme = session('theme', 'default'); // Or get from user/settings
        @endphp

        {{-- @if($routePrefix == '/showroom')
            @vite(['resources/themes/fotospeed/app.js', "resources/themes/fotospeed/Pages/{$page['component']}.vue"])
        @else
            @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @endif --}}

        @routes
        @inertiaHead
    </head>
    <body>
         @inertia
         {{-- {{ $routePrefix }} --}}
         <div id="mod"></div>
    </body>
    <script src="{{ env('APP_URL') }}/build/assets/app-BwdOb-Ii.js" type="module"></script>
</html>