@extends('site_v2/layouts/default')
@section('content')
<div class="bg-gradient-blue height200"></div>

<div class="universal-frame"></div>

<div class="bg-gray">
	<div class="container">
		<div class="mod-padding120">
			<div class="row">
				<div class="col-md-5 offset-md-1">
					<div class="premium-info-img" style="background-image:url({{ $info_premium->img }});"></div>
				</div>

				<div class="col-md-4 offset-md-1">
					<div class="premium-info-desc">
						<h1>{{ $info_premium->name }}</h1>
						<p class="tx-jet">{{ $info_premium->desc }}</p>
						<p>{{ $info_premium->valor_cliente }} {{ trans('site_v2.points') }}</p>

						@if(Cookie::get('cookie_user_id'))
							<form id="form-add-premium" action="{{ route('addPremioPostV2') }}" name="form" method="post">
								{{ csrf_field() }}

								<input type="hidden" name="id_premio" value="{{ $info_premium->id }}">
								<input type="hidden" name="valor_premio" value="{{ $info_premium->valor_cliente }}">

								<div class="custom-select">
									<select name="quantidade">
										<option id="qtd" selected>{{ trans('site_v2.AMOUNT') }}</option>
									  	<option value="1">1</option>
									  	<option value="2">2</option>
									  	<option value="3">3</option>
									  	<option value="4">4</option>
									</select>
								</div>
								<br>
								@if($variantes)
									<div class="custom-select">
										<select name="variante">
											<option value="VARIANTE" selected>{{ $name_var->var_name }}</option>
											@foreach($variantes as $val)
												<option value="{{ $val['id'] }}">{{ $val['valor'] }}</option>
											@endforeach
										</select>
									</div>
								@endif
								<br>
								
								
								<button class="bt-blue margin-top20 tx-transform">{!! $conteudos['sect1_info_bt'] !!}</button>
								<div class="height20"></div>
								<div style="width:100%;">
									<label id="labelSucesso" class="av-100 alert-success display-none float-right" role="alert"><span id="spanSucesso">{{ trans('site_v2.Successfully_Added') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
              						<label id="labelErros" class="av-100 alert-danger display-none float-right" role="alert"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
								</div>
								<div class="height20"></div>
							</form>
						@else
							<div class="custom-select">
								<select name="quantidade">
									<option id="qtd" selected>{{ trans('site_v2.AMOUNT') }}</option>
								  	<option value="1">1</option>
								  	<option value="2">2</option>
								  	<option value="3">3</option>
								  	<option value="4">4</option>
								</select>
							</div>
							<br>
							@if($variantes)
								<div class="custom-select">
									<select name="variante">
										<option value="VARIANTE" selected>{{ $name_var->var_name }}</option>
										@foreach($variantes as $val)
											<option value="{{ $val['valor'] }}">{{ $val['valor'] }}</option>
										@endforeach
									</select>
								</div>
							@endif
							<br>
								
								
							<a href="{{ route('loginPageV2') }}"><button class="bt-blue margin-top20 tx-transform">{!! $conteudos['sect1_info_bt'] !!}</button></a>
						@endif
					</div>
				</div>
				
				
			</div>
		</div>

		<div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="premium-slide">
					<h1 class="tx-navy margin-bottom30">{!! $conteudos['sect1_info_tit'] !!}</h1>

					<div class="swiper-container premium-slide-container">
						<div  class="swiper-wrapper">
							@foreach($premium as $val)
								<div class="swiper-slide premium-swiper">
									<div class="premium-slide-bg" style="background-image:url({{ $val['img'] }});"></div>
									<div class="premium-slide-desc">
										<span>{{ $val['name'] }}</span>
										<p class="tx-navy">{{ $val['value'] }} {{ trans('site_v2.Points') }}</p>
										
										<a href="{{ route('infoPremiumPageV2',$val['id']) }}"><button class="bt-blue tx-transform">{{ trans('site_v2.TO_EXCHANGE') }}</button></a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container position-relative">
			<div class="premium-button-prev"></div>
			<div class="premium-button-next"></div>
		</div>
	</div>

	 @include('site_v2/includes/premium-banner')
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
@stop

@section('javascript')
	<!-- Swiper -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>

	<script src="{{ asset('site_v2/js/select.js') }}"></script>

 	<script>
 		$('.swiper-slide-shadow-left').css('border-radius','50%');
 		$('.swiper-slide-shadow-right').css('border-radius','50%');
 		$('.header-xs i').css('color','#fff');
 		
 		@if(Cookie::get('cookie_user_id') || Cookie::get('cookie_comerc_id')) $('.header-span a').css('color','#333');
 		@else $('.header-span a').css('color','#fff');@endif
 	</script>

 	<!-- Initialize Swiper -->
 	<script>
	    var mySwiper = new Swiper ('.swiper-container', {

		    slidesPerView: 1,
		    spaceBetween: 10,
			breakpointsInverse: true,
			breakpoints: {
			    320: {
			      slidesPerView: 1,
			      spaceBetween: 30
			    },
			    580: {
			      slidesPerView: 2,
			      spaceBetween: 10
			    },
			    768: {
			      slidesPerView: 3,
			      spaceBetween: 10
			    },
			    992: {
			      slidesPerView: 4,
			      spaceBetween: 10
			    }
			  },
		    navigation: {
		      nextEl: '.premium-button-next',
		      prevEl: '.premium-button-prev',
		      clickable: true,
		    },
		});      
	</script>

	<script>
		$('#form-add-premium').on('submit',function(e) {
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

		        try{ resp=$.parseJSON(resposta); }
		        catch (e){
		            if(resposta){ $("#spanErro").html(resposta); }
		            else{ $("#spanErro").html('ERROR'); }
		            $("#labelErros").show();
		            return;
		        }
		        if(resp.estado=='sucesso'){ 
		        	$("#labelSucesso").show();
		        	$('#qtd').select;
		        	//$('#points_header').html(resp.pontos_user);
          			$('#cont_cart').html(resp.sum_cart);
          			$('#cont_cart_client').html(resp.sum_cart);
		    	}
		        else{
		          $("#spanErro").html(resposta);
		          $("#labelErros").show();
		        }
	      	});
	    });
	</script>
@stop