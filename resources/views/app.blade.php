<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Indigo OMS') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        {{--  <script type="module" src="{{env('APP_URL')}}/build/assets/app-GffVBZX0.js"></script>  --}}
        @inertiaHead
    </head>
    <body>
         @inertia
         <div id="mod"></div>
    </body>
</html>
