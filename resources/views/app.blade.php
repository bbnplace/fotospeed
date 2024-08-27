<!DOCTYPE html>
<html lang="en">
    <head>
        <title inertia>{{ config('app.name', 'Indigo OMS') }}</title>
        <!-- <link rel="stylesheet" href="{{ env('APP_URL') }}/build/assets/app-Km4sWmCp.css"> -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
         @inertia
         <div id="mod"></div>
    </body>
    <!-- <script src="{{ env('APP_URL') }}/build/assets/app-MQDkQIh0.js" type="module"></script> -->
