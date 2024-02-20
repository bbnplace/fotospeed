<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Indigo OMS') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
         @inertia
    </body>
</html>
