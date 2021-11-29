@extends('site_v2/layouts/default')
@section('content')
<div style="min-height:calc(100% - 192px);background:pink;position:relative;">

	<div class="div-snow"></div>
	<div class="div-blue div-blue-password"></div>

	<div class="container"> 
		<div class="row">
			<div class="col-md-4">
				<div class="div-left">
					<h2 class="tx-navy">{!! $login['login_tit'] !!}</h2>
					<p class="margin-top40">{!! $login['login_txt'] !!}</p>
					<li>{{ trans('site_v2.No_account_yet_pg') }} <a href="{{ route('formRegisterClientPageV2') }}"><span class="tx-navy">{{ trans('site_v2.Create_a') }}.</span></a> </li>
				</div>	
			</div>
			
			<div class="offset-md-1 col-md-7">
				<div class="div-right">
					
					<h2>{{ trans('site_v2.Retrieve_your_account') }}</h2>

					
					<form id="form-pass" action="{{ route('restorePasswordPost') }}" name="form" method="post">
						{{ csrf_field() }}

						<input class="ip" type="hidden" name="token" value="{{ $token }}">
						<label>{{ trans('site_v2.New_Password') }}</label>
						<div class="div-40 text-center">
							<input id="showPass" class="ip" type="password" name="password" placeholder="{{ trans('site_v2.Required') }}">
						</div>

	      				<a onclick="hidePass();" id="eye" class="display-none"><i class="far fa-eye"></i></a>
		      			<a onclick="showPass();" id="eye-slash"><i class="fas fa-eye-slash"></i></a>
						
	      				<a href="{{ route('loginPageV2') }}" class="login-link">{{ trans('site_v2.Reset_password_txt') }}</a>
	      				<div class="height20"></div>
						<button class="bt-gray" type="submit">{{ trans('site_v2.SAVE') }}</button>

						<div class="height20"></div>
						<label id="labelSucesso" class="alert-success av-100 display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.Send_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
            			<label id="labelErros" class="alert-danger av-100 display-none" role="alert">
            				<span id="spanErro"></span>
            				<i class="fas fa-times" onclick="$(this).parent().hide();"></i>
            			</label>
					</form>
					
				</div>
			</div>
		</div>
	</div>	
</div>
@stop

@section('css')
@stop

@section('javascript')
	<script>
		$('.header-span').css('color','#333');
		$('.header-span a').css('color','#333');
		$('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
		$('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
		$('.header-submenu a').css('color','#333');
		$('.header').css('background-color','#fff');
		$('header').css('height','100px');

	</script>

	<script>
		function showPass(){
		    document.getElementById("showPass").type = 'text';
		    $("#eye-slash").hide();
		    $("#eye").show();
		}

		function hidePass(){
		    document.getElementById("showPass").type = 'password';
		    $("#eye-slash").show();
		    $("#eye").hide();
		}
	</script>

	<script type="text/javascript">
		$('#form-pass').on('submit',function(e) {
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
		    try{ resp=$.parseJSON(resposta); }
		    catch (e){
				if(resposta){ $("#spanErro").html(resposta); }
				else{ $("#spanErro").html('ERROR'); }
				$("#labelErros").show();
				return;
		    }
		    if(resp.estado=='sucesso')
		    {
				$("#labelSucesso").show();
				$("#form-pass")[0].reset();
				setTimeout(function(){ window.location="{{ route('loginPageV2') }}"; },2000);
		    }
		    else if(resp.estado=='sucesso')
		    {
				$("#spanErro").html(resposta);
				$("#labelErros").show();
		    }
		  });
		});
	</script>
@stop