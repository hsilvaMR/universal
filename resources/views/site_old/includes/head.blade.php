<!-- 84246 36 22766 337626337 636337 -->
<!-- FAVICON -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Tiago Mendes">
<!-- Custom Fonts -->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Favicon -->
<link rel="icon" href="/img/favicons/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
<link rel="manifest" href="/img/favicons/site.webmanifest">
<link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#002d73">
<meta name="msapplication-TileColor" content="#002d73">
<meta name="theme-color" content="#002d73">
<!-- Style -->
<link rel="stylesheet" href="/style.css">
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
<!--< ? include('analytics.php'); ?>

< ?php
$lang = isset($_COOKIE['lingua']) ? $_COOKIE['lingua'] : 'pt';
//echo "<script>createCookie('lingua','en',720);</script>";
?>-->

<!-- token-laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}"> 

<!-- STYLE -->
<link href="{{ asset('site_old/css/style.css') }}" rel="stylesheet">


<title>Universal</title>
<meta name="description" content="A marca Universal nasce através da associação de 11 pequenos produtores de manteiga da zona de Oliveira de Azeméis a 30 de Dezembro de 1940. Nos primeiros dois anos a marca UNIVERSAL tinha apenas a manteiga como produto associado. O processo de fabrico era manual, assim como o de embalagem e de pesagem. Foi com essa manteiga, embrulhada em pacotes de papel vegetal de 125g e 250g, que a marca UNIVERSAL se tornou conhecida e se foi tornando indispensável nas melhores pastelarias e charcutarias do mercado de Lisboa.">
<meta name="keywords" content="universal, queijo universal, queijo prato, lacticinios, lacticinios de azemeis, queijo, manteiga, manteiga universal, oliveira de azemeis, queijo bola">


<!-- FACEBOOK -->
<meta property="og:url" content="{{ \Request::fullUrl() }}" />
<meta property="og:title" content="Universal" />
<meta property="og:description" content="A marca Universal nasce através da associação de 11 pequenos produtores de manteiga da zona de Oliveira de Azeméis a 30 de Dezembro de 1940. Nos primeiros dois anos a marca UNIVERSAL tinha apenas a manteiga como produto associado. O processo de fabrico era manual, assim como o de embalagem e de pesagem. Foi com essa manteiga, embrulhada em pacotes de papel vegetal de 125g e 250g, que a marca UNIVERSAL se tornou conhecida e se foi tornando indispensável nas melhores pastelarias e charcutarias do mercado de Lisboa." />
<meta property="og:image" content="{{ asset('site_old/img/site/faceImagem.png') }}" />
<meta property="og:type" content="website" />
<meta property="fb:app_id" content="1802761740038977"/>