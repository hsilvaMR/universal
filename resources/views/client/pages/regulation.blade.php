@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  @include('client/includes/headerSubMenu')

  <div class="container">
    <div class="row">
        <div class="col-lg-3">
            @include('client/includes/menu')
        </div>
      <div class="col-lg-9">
        <div class="bg-white">
          <div class="data-border-form">
            <label>{{ trans('site_v2.Regulation') }}</label>
          </div>
          <div style="margin:40px 20px 0px 20px;background-color:#fff;padding-bottom:20px;font-family:'Roboto Slab';font-weight:300;" class="data-form-txt">{!! $passatempo->regulamento_pt !!}</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="bg-gradient-blue">
  <div class="premium-banner" style="background: url('/site_v2/img/site/code-hero-back.png')no-repeat center;">
    <div class="container">
      <h1>Pontos insuficientes?</h1>
      <button class="bt-gray tx-transform" style="font-weight:100;font-family:'Roboto';">COMPRE PONTOS</button>
    </div>
  </div>
</div>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>

  <script>
    function openRotulosInvalidos(){

      $('#rotulos_indisponivel_').show();
      $('#search_plus').hide();
      $('#search_minus').show();
    };

    function closeRotulosInvalidos(){

      $('#rotulos_indisponivel_').hide();
      $('#search_plus').show();
      $('#search_minus').hide();
    };
  </script>
@stop