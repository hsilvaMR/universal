<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('client/includes/head')

        @yield('css')
    </head>
    <body>
        @include('client/includes/header')
        

        @yield('content')

        @include('client/includes/footer')

        @include('client/includes/assets')

        @yield('javascript')
    </body>
</html>