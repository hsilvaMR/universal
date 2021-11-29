<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('site_v2/includes/head')

        @yield('css')

        @include('site_v2/includes/analytics')
    </head>
    <body>
        @include('site_v2/includes/header')

        @yield('content')

        @include('site_v2/includes/footer')

        @include('site_v2/includes/assets')

        @yield('javascript')
    </body>
</html>