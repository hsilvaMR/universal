<!doctype html>
<html lang="pt-PT">
    <head>
        @include('backoffice/includes/head')

        @yield('css')
    </head>
    <body>
        @include('backoffice/includes/header')

        <article>
            <nav class="article-menu">
                @include('backoffice/includes/menu')
            </nav>
            <div class="article-conteudo">
                @yield('content')
            </div>
        </article>

        @include('backoffice/includes/footer')

        @include('backoffice/includes/assets')

        @yield('javascript')
    </body>
</html>