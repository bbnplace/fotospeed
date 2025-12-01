<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Fotospeed') }}</title>

        

        <!-- Scripts -->
        @php
        // dd(session('theme'));
            $routePrefix = request()->route() ? request()->route()->getPrefix() : '';
            // dd($routePrefix);
            // $theme = session('theme', 'default'); // Or get from user/settings
        @endphp

        @if($routePrefix == '/showroom')
            <link rel="stylesheet" href="{{ env('APP_URL') }}/build/showroom/assets/app-D2vqdXq0.css">
            {{-- @vite(['resources/themes/fotospeed/app.js', "resources/themes/fotospeed/Pages/{$page['component']}.vue"]) --}}
        @else
            <link rel="stylesheet" href="{{ env('APP_URL') }}/build/oms/assets/app-ji99nVhz.css">
            {{-- @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"]) --}}
        @endif

        @routes
        @inertiaHead
    </head>
    <body>
         @inertia
         {{-- {{ $routePrefix }} --}}
         <div id="mod"></div>
    </body>
    @if($routePrefix == '/showroom')
        <!-- Load WOW.js before the main bundle to ensure it's available globally -->
        <script src="{{ env('APP_URL') }}/assets/js/wow.min.js"></script>
        <script src="{{ env('APP_URL') }}/assets/js/gsap/gsap.js"></script>
        <script src="{{ env('APP_URL') }}/assets/js/gsap/gsap-scroll-to-plugin.js"></script>
        <script src="{{ env('APP_URL') }}/assets/js/gsap/gsap-scroll-smoother.js"></script>
        <script src="{{ env('APP_URL') }}/assets/js/gsap/gsap-scroll-trigger.js"></script>
        <script src="{{ env('APP_URL') }}/assets/js/gsap/gsap-split-text.js"></script>
        <script src="{{ env('APP_URL') }}/build/showroom/assets/app-D6julI6s.js" type="module"></script>
    @else
        <script src="{{ env('APP_URL') }}/build/oms/assets/app-DYjKnJN9.js" type="module"></script>
    @endif
    
</html>