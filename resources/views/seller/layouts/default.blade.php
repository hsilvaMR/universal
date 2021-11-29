<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('seller/includes/head')

        @yield('css')
    </head>
    <body>
        @include('seller/includes/header')

        @yield('content')

        @include('seller/includes/footer')

        @include('seller/includes/assets')

        @yield('javascript')
    </body>
</html>