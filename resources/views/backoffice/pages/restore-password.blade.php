@extends('backoffice/layouts/default-out')

@section('content')
<div class="container-fluid">
  <div class="login">
    <a href="{{ route('homePageV2') }}"><img class="login-logo" src="{{ asset('/backoffice/img/icons/logo.svg') }}"></a>
    <form id="restorePasswordForm" method="POST" action="{{ route('restorePasswordFormB') }}">
      {{ csrf_field() }}
      <div id="forgot">
        <p class="text-center">{{ trans('backoffice.resetPassword') }} <a href="{{ route('loginPageB') }}" class="tx-verde">{{ trans('backoffice.youKnow') }}</a></p>
        <label class="lb">{{ trans('backoffice.newPassword') }}</label>
        <input class="ip" type="hidden" name="token" value="{{ $token }}">
        <input class="ip" type="password" name="password" value="" placeholder="{{ trans('backoffice.newPassword') }}">
        <div class="height-20"></div>
        <button class="bt-100 bt-azul" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
        <div class="height-20"></div>
        <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
          <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </div>
    </form>
    <div class="height-20"></div>
    <!--
    <p class="text-center">
      <a href="{ { route('setLanguageB',['lang'=>'en']) }}" class="display-inline">English</a>&emsp;
      <a href="{ { route('setLanguageB',['lang'=>'pt']) }}" class="display-inline">Português</a>
    </p>
    -->
  </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
$('#restorePasswordForm').on('submit',function(e) {
  $("#labelSucesso").hide();
  $("#labelErros").hide();
  var form = $(this);
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: form.attr('action'),
    data: form.serialize(),
  })
  .done(function(resposta) {
    if(resposta){
      $("#spanErro").html(resposta);
      $("#labelErros").show();
    }
    else
    {
      $("#labelSucesso").show();
      $("#restorePasswordForm")[0].reset();
      setTimeout(function(){ window.location="{{ route('loginPageB') }}"; },2000);
      //return;
    }
  });
});
</script>
@stop