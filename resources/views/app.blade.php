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
            <!-- <link rel="stylesheet" href="{{ env('APP_URL') }}/build/showroom/assets/app-B4Ltn98g.css"> -->
            @vite(['resources/themes/fotospeed/app.js', "resources/themes/fotospeed/Pages/{$page['component']}.vue"])
        @else
            <!-- <link rel="stylesheet" href="{{ env('APP_URL') }}/build/oms/assets/app-BMcj8y2u.css"> -->
            @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
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
        <!-- <script src="{{ env('APP_URL') }}/build/showroom/assets/app-BCfMDDsl.js" type="module"></script> -->
    @else
        <!-- <script src="{{ env('APP_URL') }}/build/oms/assets/app-DeQ-qCBF.js" type="module"></script> -->
    @endif
    
</html>