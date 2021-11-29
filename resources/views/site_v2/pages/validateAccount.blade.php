
@extends('site_v2/layouts/default')
@section('content')
  <section class="min-height data-mod">
    <div class="container">
      <div class="bg-white">

        <div class="data-border-form"><label>{{ trans('site_v2.Thank_you') }}</label></div>

        <div class="cart-status"> 
          @if(isset($variavel))
            <img height="75" src="/site_v2/img/site/warning.png">
            <p class="cart-status-txt">{{ trans('site_v2.The_email_validated_txt') }}</p> 
          @else
            <img height="75" src="/site_v2/img/cart/carrinho-sucesso.png">
            <p class="cart-status-txt">{{ trans('site_v2.AccountRegister') }}</p>
          @endif
          <div class="clearfix"></div>
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
    $('.header').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
  </script>
@stop