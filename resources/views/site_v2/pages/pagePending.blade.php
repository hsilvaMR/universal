
@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">

  <div class="container">
    <div class="bg-white">

      @if((isset($comerciantes) && ($comerciantes->aprovacao != 'em_aprovacao')) || isset($utilizadores))
        <div class="data-border-form"><label>{{ trans('site_v2.Thank_you') }}</label></div>

        <div class="cart-status" style="padding:40px 20px;">
          <img height="75" src="site_v2/img/cart/carrinho-sucesso.png">
          <p class="cart-status-txt">{{ trans('site_v2.page_pending_txt') }}</p>

          <form id="form-validacaoEmail" action="{{ route('resendValidationPost') }}" name="form" method="post">
            {{ csrf_field() }}
            <button class="bt bt-blue" type="submit">{{ trans('site_v2.Resend_Activation_Email_tit') }}</button>

            <div class="height20"></div>
            <a href="{{ route('logoutPost') }}"><p class="cart-status-close">{{ trans('site_v2.Logout') }}</p></a>

            <div class="height20"></div> 
            <label id="labelSucesso" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.SuccessfullySubmitted') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
            <label id="labelErros" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
            
            <div class="clearfix"></div>
          </form>
        </div>
      @else
        <div class="data-border-form"><label>{{ trans('site_v2.Account_Approved') }}</label></div>

        <div class="cart-status" style="padding:40px 20px;">
          <img height="75" src="site_v2/img/site/warning.png">
          <p class="cart-status-txt">{{ trans('site_v2.Account_Approved_txt') }}</p>
          <a href="{{ route('logoutPost') }}"><p class="cart-status-close">{{ trans('site_v2.Logout') }}</p></a>
        </div>
      @endif
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

  <script type="text/javascript">
    $('#form-validacaoEmail').on('submit',function(e) {
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
            $("#labels").show();
            $("#labelErros").show();
            return;
          }
          if(resp.estado=='sucesso'){
            $('#id').val(resp.id);
            $("#labelSucesso").show();
          }else if(resposta){
            $("#labelErros").show();
          }
        });
      });
    </script>
@stop