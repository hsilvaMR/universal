<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('site_v2/includes/head')

        @yield('css')

        @include('site_v2/includes/analytics')
    </head>
    <body>
      
        @yield('content')

        @include('site_v2/includes/assets')

        @yield('javascript')
    </body>
</html>