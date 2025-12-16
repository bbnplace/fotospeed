<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Fotospeed') }}</title>


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content: blob:; script-src * 'self' 'unsafe-inline' 'unsafe-eval' blob:; connect-src * 'self' 'unsafe-inline' blob:;">


        <!-- Scripts -->
        @php
        // dd(session('theme'));
            $routePrefix = request()->route() ? request()->route()->getPrefix() : '';
            // dd($routePrefix);
            // $theme = session('theme', 'default'); // Or get from user/settings
        @endphp

        @if($routePrefix == '/showroom')
            <link rel="stylesheet" href="{{ env('APP_URL') }}/build/showroom/assets/app-DVspoKKH.css">
            {{--@vite(['resources/themes/fotospeed/app.js', "resources/themes/fotospeed/Pages/{$page['component']}.vue"])--}}
        @else
            <link rel="stylesheet" href="{{ env('APP_URL') }}/build/oms/assets/app-Bqchj5Wc.css">
            {{--@vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])--}}
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
        <script src="{{ env('APP_URL') }}/build/showroom/assets/app-uhJ5-oeG.js" type="module"></script>
    @else
        <script src="{{ env('APP_URL') }}/build/oms/assets/app-8EVhuuPK.js" type="module"></script>
    @endif
    
</html>