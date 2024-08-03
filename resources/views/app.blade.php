<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Indigo OMS') }}</title>
        <!-- <link rel="stylesheet" href="{{ env('APP_URL') }}/build/assets/app-lGKGFv_a.css"> -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
         @inertia
         <div id="mod"></div>
    </body>
    <!-- <script src="{{ env('APP_URL') }}/build/assets/app-G_ZitaOb.js" type="module"></script> -->
