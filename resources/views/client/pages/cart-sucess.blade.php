@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  
  <div class="container">
    <div class="bg-white">
      <div class="data-border-form"><label>{{ trans('site_v2.Thank_you') }}</label></div>

      <div class="cart-status">
        <img height="75" src="site_v2/img/cart/carrinho-sucesso.png">
        <p class="cart-status-txt" style="">{!! trans('site_v2.Register_Enc_desc') !!} <a href="{{ route('historyPremiumV2') }}" class="tx-navy">{{ trans('site_v2.account') }}.</a></p>

        <a href="{{ route('homePageV2') }}"><button class="bt bt-blue">{{ trans('site_v2.Continue_for_Site') }}</button></a>
      </div>
    </div>
  </div>

</section>
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
@stop