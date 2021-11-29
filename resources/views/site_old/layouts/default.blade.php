<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('site_old/includes/head')

        @yield('css')
    </head>
    <body>
        @include('site_old/includes/header')

        @yield('content')

        @include('site_old/includes/footer')

        @include('site_old/includes/assets')

        @yield('javascript')
    </body>
</html>