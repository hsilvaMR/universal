@extends('site_v2/layouts/default')
@section('content')
<div style="min-height:calc(100% - 192px);background:pink;position:relative;">

	<div class="div-snow"></div>
	<div class="div-blue div-blue-seller"></div>

	<div class="container"> 
		<div class="row">
			<div class="col-md-4" style="background-color:#fbfbfb;">
				<div class="div-left">
					<h2 class="tx-navy">{!! $register['register_tit'] !!}</h2>
					<p class="margin-top40">{!! $register['register_txt'] !!}</p>
					<label>{!! $register['register_subtxt'] !!}</label>
					<li>{{ trans('site_v2.Already_have_account') }} <a href="{{ route('loginPageV2') }}"><span class="tx-navy">{{ trans('site_v2.Sign_in') }}.</span></a></li>
				</div>	
			</div>
			
			<div class="offset-md-1 col-md-7" style="background-color: #1974d8;">
				<div class="div-right">
					<h2>{{ trans('site_v2.Registration_form') }}</h2>

					<form id="form-register-seller" action="{{ route('registerSellerPost') }}" name="form" method="post">

	           			{{ csrf_field() }}

						<label>{{ trans('site_v2.Name_of_Representative') }}</label>
						<input class="ip" type="text" name="nome_resp" placeholder="{{ trans('site_v2.Required') }}">

						<div class="row">
							<div class="col-lg-7">
								<label>{{ trans('site_v2.Company') }}</label>
								<input class="ip" type="text" name="nome_empresa" placeholder="{{ trans('site_v2.Required') }}">
							</div>
							<div class="col-lg-5">
								<label>CAE</label>
								<input class="ip" type="text" name="cae" placeholder="{{ trans('site_v2.Required') }}">
							</div>
						</div>

						<div class="row">
							<div class="col-lg-7">
								<label>{{ trans('site_v2.Email') }}</label>
								<input class="ip" type="email" name="email" placeholder="{{ trans('site_v2.Required') }}">
							</div>
							<div class="col-lg-5">
								<label>{{ trans('site_v2.Password') }}</label>
								<div class="div-40 text-center">
									<input id="showPass" class="ip" type="password" name="password" placeholder="{{ trans('site_v2.Required') }}">
								</div>
							
								<a onclick="hidePass();" id="eye" class="display-none"><i class="far fa-eye"></i></a>
		      					<a onclick="showPass();" id="eye-slash"><i class="fas fa-eye-slash"></i></a>
							</div>
						</div>
					
						<input type="checkbox" id="termos_cond" name="termos_cond">
						<label for="termos_cond" class="margin-bottom20 register-termos-txt">
							<span class="bg-white"></span> {{ trans('site_v2.I_agree_with_the') }} <a class="tx-mikado-yellow" href="{{ route('termsPageV2') }}">{{ trans('site_v2.terms_conditions') }}</a> {{ trans('site_v2.from_Universal') }}.
						</label>
						<br>
						<button class="bt-gray">{{ trans('site_v2.REGISTER') }}</button>
						<div class="height20"></div>
						<label id="labelSucesso" class="alert-success av-100 display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.RegisterSucessEmail_txt') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
            			<label id="labelErros" class="alert-danger av-100 display-none" role="alert">
            				<i class="fas fa-times" onclick="$(this).parent().hide();"></i>
            				<span id="spanErro"></span>
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

		$("#termos_cond").on('change', function() {
		    if ($(this).is(':checked')) { $(this).attr('value', '1'); } 
		    else { $(this).attr('value', '0'); }
		});


	  	$('#form-register-seller').on('submit',function(e) {
			$("#labelSucesso").hide();
			$("#labelErros").hide();
			$('#loading').show();
			$('#botoes').hide();
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
		            $('#loading').hide();
		            $('#botoes').show();
		            return;
		        }
		        if(resp.estado=='sucesso'){
		          $('#id').val(resp.id);
		          $("#labelSucesso").show();
		          document.getElementById("form-register-seller").reset();
		        }else if(resposta){
		          $("#spanErro").html(resposta);
		          $("#labelErros").show();
		        }
		        $('#loading').hide();
		        $('#botoes').show();
	      	});
	    });
	</script>
@stop