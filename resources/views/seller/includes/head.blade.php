<!-- 84246 36 22766 337626337 636337 -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; utf-8">
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1, shrink-to-fit=no">

<title>@if(isset($headTitulo)){{ $headTitulo }}@else{!! trans('site_v2.t_site') !!}@endif</title>
<meta name="keywords" content="@if(isset($headPalavras)){{ $headPalavras }}@else{!! trans('site_v2.p_site') !!}@endif">
<meta name="description" content="@if(isset($headDescricao)){{ $headDescricao }}@else{!! trans('site_v2.d_site') !!}@endif">

<!--<meta name="Robots" content="index,follow">-->
<meta name="geo.region" content="{{ app()->getLocale() }}">
<meta name="geo.position" content="{{ app()->getLocale() }}">
<meta name="geo.placename" content="{{ app()->getLocale() }}">

<!-- FAVICON -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('site_v2/img/favicons/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" href="{{ asset('site_v2/img/favicons/favicon-32x32.png') }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ asset('site_v2/img/favicons/favicon-16x16.png') }}" sizes="16x16">
<link rel="manifest" href="{{ asset('site_v2/img/favicons/manifest.json') }}">
<link rel="mask-icon" href="{{ asset('site_v2/img/favicons/safari-pinned-tab.svg') }}" color="#00b1e3">
<meta name="theme-color" content="#ffffff">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<!--FONTS -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet"><!-- font-family:'Roboto',sans-serif; -->
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,300" rel="stylesheet"><!-- font-family:'Roboto Slab',serif; -->

<!-- STYLE -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/seller.css') }}">

<!-- token-laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- FACEBOOK -->
<meta property="og:url" content="{{ \Request::fullUrl() }}" />
<meta property="og:title" content="@if(isset($faceTitulo)){{ strip_tags($faceTitulo) }}@else{{ strip_tags(trans('site_v2.t_site')) }}@endif" />
<meta property="og:description" content="@if(isset($faceDescricao)){{ strip_tags($faceDescricao) }}@else{{ strip_tags(trans('site_v2.d_site')) }}@endif" />
<meta property="og:image" content="@if(isset($faceImagem)){{ $faceImagem }}@else{{ asset('site_v2/img/site/faceImagem.png') }}@endif" />
<meta property="og:type" content="@if(isset($faceTipo)){{ $faceTipo }}@else{{'article'}}@endif" />
<!--<meta property="fb:app_id" content="1802761740038977"/>-->