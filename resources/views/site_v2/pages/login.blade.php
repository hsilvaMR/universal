@extends('site_v2/layouts/default')
@section('content')

<div style="min-height:calc(100% - 192px);background:pink;position:relative;">
	<div class="div-snow"></div>
	<div class="div-blue"></div>

	<div class="container">
		<div class="row">
			<div class="col-md-4" style="background-color:#fbfbfb;">
				<div class="div-left">
					<h2 class="tx-navy">{!! $login['login_tit'] !!}</h2>
					<p class="margin-top40">{!! $login['login_txt'] !!}</p>
					<li>
						{{ trans('site_v2.No_account_yet_pg') }} 
						<a href="{{ route('formRegisterClientPageV2') }}">
							<span class="tx-navy">{{ trans('site_v2.Create_a') }}.</span>
						</a>
					</li>
				</div>
			</div>
			<div class="offset-md-1 col-md-7" style="background-color: #1974d8;">
				<div class="div-right">
					<div id="div-acess">
						<h2>{!! $login['login_register_tit'] !!}</h2>

						<form id="form-login" action="{{ route('formLoginPost') }}" name="form" method="post">
							{{ csrf_field() }}

							<label>{{ trans('site_v2.Email') }}</label>
							<input class="ip" type="email" name="email" placeholder="{{ trans('site_v2.Required') }}">

							<label>{{ trans('site_v2.Password') }}</label>
							<div class="div-40 text-center">
								<input id="showPass" class="ip" type="password" name="password" placeholder="{{ trans('site_v2.Required') }}">
							</div>
							
							<a onclick="hidePass();" id="eye" class="display-none"><i class="far fa-eye"></i></a>
		      				<a onclick="showPass();" id="eye-slash"><i class="fas fa-eye-slash"></i></a>

		      				<span class="login-link" onclick="hideDivAcess();">{{ trans('site_v2.forgot_password') }}</span>
		      				<div class="height20"></div>
							<button class="bt-gray" type="submit"> {{ trans('site_v2.LOGIN') }}</button>

							<div class="height20"></div>
							<label id="labelSucesso" class="alert-success av-100 display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.Login_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
		        			<label id="labelErros" class="alert-danger av-100 display-none" role="alert">
		        				<span id="spanErro"></span>
		        				<i class="fas fa-times" onclick="$(this).parent().hide();"></i>
		        			</label>
						</form>
					</div>
					<div id="div-restore-pass">
						<h2>{{ trans('site_v2.Retrieve_Password') }}</h2>

						<form id="form-restore-pass" action="{{ route('sendEmailPassPost') }}" name="form" method="post">
							{{ csrf_field() }}

							<label>{{ trans('site_v2.Email') }}</label>				
							<input class="ip" type="email" name="email" placeholder="{{ trans('site_v2.Required') }}">
							
		      				<span class="login-link" onclick="showDivAcess();">{{ trans('site_v2.Do_you_know_the_password') }}</span>
		      				<div class="height20"></div>
							<button class="bt-gray" type="submit">{{ trans('site_v2.RETRIEVE') }}</button>

							<div class="height20"></div>
							<label id="labelSucessoPass" class="alert-success av-100 display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.SendSucessEmail_txt') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
	            			<label id="labelErrosPass" class="alert-danger av-100 display-none" role="alert">
	            				<span id="spanErroPass"></span>
	            				<i class="fas fa-times" onclick="$(this).parent().hide();"></i>
	            			</label>
						</form>
					</div>
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

	<script>
		function hideDivAcess(){
			$('#div-acess').hide();
			$('#div-restore-pass').show();
		}
		function showDivAcess(){
			$('#div-acess').show();
			$('#div-restore-pass').hide();
		}	
	</script>

	<script type="text/javascript">
  		$('#form-login').on('submit',function(e) {
			$("#labelSucesso").hide();
			$("#labelErros").hide();
			$('#loading').show();
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

				try{ resp=$.parseJSON(resposta); }

				catch (e){
				    if(resposta){ $("#spanErro").html(resposta); }
				    else{ $("#spanErro").html('ERROR'); }
				    $("#labelErros").show();
				    $('#loading').hide();
				    return;
				}
				if(resp.estado == 'sucesso'){
					$("#labelSucesso").show();
					window.location="{{ route('areaReservedV2') }}";
					console.log('client sucess');
				}
				else if (resp.estado == 'sucesso_comerciante') {
					$("#labelSucesso").show();
					
					window.location="{{ route('dashboardV2') }}";
					console.log('seller : empresa');
				}
				else if(resposta){
					$("#spanErro").html(resposta);
					$("#labelErros").show();
				}
				$('#loading').hide();
			});
    	});
	</script>

	<script type="text/javascript">
  		$('#form-restore-pass').on('submit',function(e) {
			$("#labelSucessoPass").hide();
			$("#labelErrosPass").hide();
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
				try{ resp=$.parseJSON(resposta); }
				catch (e){
				    if(resposta){ $("#spanErroPass").html(resposta); }
				    else{ $("#spanErroPass").html('ERROR'); }
				    $("#labelErrosPass").show();
				    return;
				}
				if(resp.estado == 'sucesso'){
					$("#labelSucessoPass").show();
					document.getElementById("form-restore-pass").reset();
				}
				else if(resposta){
					$("#spanErroPass").html(resposta);
					$("#labelErrosPass").show();
				}
			});
    	});
	</script>
@stop