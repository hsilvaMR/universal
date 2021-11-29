@extends('site_v2/layouts/default')
@section('content')

<div id="home" class="swiper-container swiper-slide-height">
  <div class="swiper-wrapper">
    @foreach($conteudo_slide as $val)
      @if($val['tipo'] == 'img')
        <a href="{{ $val['url'] }}">
          <div id="slide-passatempo" class="swiper-slide" style="background-image: {{ $val['fundo_cor'] }};">
            <div class="container">

              <img class="slide-fd-img" src="{{ $val['img'] }}">
              
              <div class="row">
                <div class="slide-ppq-xs">
                  <div class="col-md-4 offset-md-1"><img class="slide-prod-img" src="{{ $val['img_xs'] }}"></div>
                  <div class="offset-md-1 col-md-5">
                    <div class="slide-div-txt">
                      <h1 class="slide-prod-tit tx-white">{!! $val['titulo'] !!}</h1>
                      <h3 class="slide-prod-txt tx-white">{!! $val['texto'] !!}</h3>
                    
                      <a href="{{ $val['url'] }}"><button class="bt-blue">{!! $val['bt_texto'] !!}</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      @elseif($val['tipo'] == 'img_texto')
        <div class="swiper-slide" style="background-image: {{ $val['fundo_cor'] }};">
          <div class="container">
            <div class="row">
              <div class="col-md-4 offset-md-1"><img class="slide-prod-img" src="{{ $val['img'] }}"></div>
              <div class="offset-md-1 col-md-5">
                <div class="slide-div-txt">
                  <h1 class="slide-prod-tit tx-white">{!! $val['titulo'] !!}</h1>
                  <h3 class="slide-prod-txt tx-white">{!! $val['texto'] !!}</h3>
                  <a href="{{ $val['url'] }}"><button class="bt-blue">{!! $val['bt_texto'] !!}</button></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    @endforeach
  </div>

  <!-- Add Arrows -->
  <div class="container position-relative">
    <div id="swiper_home" class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>

  <div class="mod-frame"></div>
</div>


<div class="mod-padding120 bg-gray tx-center"> <!--style="position:relative;"-->
  <!--<img src="img/site/bg1.png" height="250" style="float:right;top: 10px;
    position: absolute;
    right: 0;">-->
  <!--<img src="img/site/bg2.png" height="300" style="float:left;">-->
  <div class="container">
    <img class="home-section2-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}">
    <h3 class="home-section2-txt">{!! $conteudos['sect1_home_txt'] !!}</h3>
    <a href="{{ route('universalPageV2') }}"><button class="bt-blue">{{ $conteudos['sect1_home_bt'] }}</button></a>
  </div>
</div>

<div class="container">
  <div class="mod-padding120 bg-white tx-center">
    <div class="row">
      <div class="col-lg-3 offset-lg-2">
        <img class="home-section3-img" src="{{ asset('site_v2/img/site/queijo-branco.png') }}">
        <h2 class="tx-navy">{{ $conteudos['sect2_home_cheese_tit'] }}</h2>
        <h3 class="home-section3-txt">{{ $conteudos['sect2_home_cheese_txt'] }}</h3>
        <a href="{{ route('productPageV2','cheese') }}"><button class="bt-blue">{{ $conteudos['sect2_home_cheese_bt'] }}</button></a>
      </div>
      <div class="col-lg-3 offset-lg-2">
        <img class="home-section3-img" src="{{ asset('site_v2/img/site/manteiga-branco.png') }}">
        <h2 class="tx-navy">{{ $conteudos['sect2_home_butter_tit'] }}</h2>
        <h3 class="home-section3-txt">{{ $conteudos['sect2_home_butter_txt'] }}</h3>
        <a href="{{ route('productPageV2','butter') }}"><button class="bt-blue">{{ $conteudos['sect2_home_butter_bt'] }}</button></a>
      </div>
    </div>
  </div>
</div>

<div class="mod-newsletter">
  <div class="container">
    
    <form id="form-subscrever" action="{{ route('subscreverPost') }}" name="form" method="post">
      {{ csrf_field() }}
      <p class="mod-newsletter-tit">{{ $conteudos['sect3_home_news_txt'] }}</p>
      <input id="newsletter" class="mod-newsletter-ip" type="email" name="newsletter" placeholder="{{ trans('site_v2.Email_address') }}" required>
      <button id="bt-subscrever" class="mod-newsletter-bt">{{ trans('site_v2.SUBSCRIBE') }}</button>

      <br>
      <label id="labelSucesso" class="av-50 alert-success display-none" role="alert">
          <span>{{ trans('site_v2.Successfully_subscribed') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
      </label>
      <label id="labelErros" class="av-50 alert-danger display-none" role="alert">
          <span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
      </label>
    </form>
  </div>
</div>

<!-- SnapWidget -->
<!--<iframe src="https://snapwidget.com/embed/816635" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden;  width:100%; height:220px"></iframe>-->

<!-- elfsight -->
<div style="overflow:hidden;">
  <script src="https://apps.elfsight.com/p/platform.js" defer></script>
  <div class="universal_insta">
    <div class="elfsight-app-da0187a8-9450-42df-82b6-876bfb8ad044"></div>
  </div>
  
</div>


<!--<div class="container-fluid">
  <section class="social_photo_max">
    <iframe src="//lightwidget.com/widgets/f8ab27b4ebc35d2884b335dbb24c1e30.html" scrolling="no" allowtransparency="true" class="lightwidget-widget"></iframe>
  </section>

  <section class="social_photo_phone">
    <iframe src="//lightwidget.com/widgets/45b2bea870d85ee4bb8f12980f283021.html" scrolling="no" allowtransparency="true" class="lightwidget-widget"></iframe>
  </section>
</div>-->
@stop

@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
@stop

@section('javascript')
<!-- LightWidget WIDGET -->
<script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script>

<!-- Swiper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>

<script>

  var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    direction: 'horizontal',
    clickable: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
      clickable: true,
    },

  });

  $('.header-span a').css('color','#fff');
  $('.header-submenu a').css('color','#fff');
  $('.header-area-span').css('color','#fff');

  $(function() {
    $('.header-menu').hover( function(){ $(this).css('color', '#333'); },
    function(){ $(this).css('color', '#fff'); });
  });

  $(function() {
    $('.header-submenu a').hover( function(){ $(this).css('color', '#333'); },
    function(){$(this).css('color', '#fff');});
  });

  

  /*swiper.on('slideChange', function () {

    if ((swiper.realIndex == 0)) { 

      $('.header-span a').css('color','#fff');
      $('.header-submenu a').css('color','#fff');
      $('.header-menu').css('color','#fff');

      $(function() {
         $('.header-menu').hover( function(){ $(this).css('color', '#333'); },
         function(){ $(this).css('color', '#fff'); });
      });

      $(function() {
         $('.header-submenu a').hover( function(){ $(this).css('color', '#333'); },
         function(){ $(this).css('color', '#fff'); });
      });
    }
    else{ 
      $('.header-span a').css('color','#000');
      $('.header-submenu a').css('color','#333');
      $('.header-menu').css('color','#333');

      $(function() {
        $('.header-menu').hover( function(){ $(this).css('color', '#1974D8'); },
        function(){ $(this).css('color', '#333'); });
      });

      $(function() {
        $('.header-submenu a').hover( function(){ $(this).css('color', '#1974D8'); },
        function(){ $(this).css('color', '#333');});
      });
    }
  });*/
</script>

<script type="text/javascript">
  $('#form-subscrever').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();
    var form = $(this);
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: form.attr('action'),
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false
    })
    .done(function(resposta){
      console.log(resposta);
      try{ resp=$.parseJSON(resposta); }
      catch (e){
        if(resposta){ $("#spanErro").html(resposta); }
        else{ $("#spanErro").html('ERROR'); }
        $("#labelErros").show();
        $('#bt-subscrever').show();
        return;
      }
      if(resp.estado=='sucesso'){
        $('#id').val(resp.id);
        $('#labelSucesso').show();
        document.getElementById("form-subscrever").reset();
      }else if(resposta){
        $("#spanErro").html(resposta);
        $("#labelErros").show();
      }
    });
  });
</script>
@stop