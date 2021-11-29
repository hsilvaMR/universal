@extends('site_v2/layouts/default')
@section('content')
<div class="codes-bg">
	<div class="codes-bg-img" style="background-image:url('/site_v2/img/site/code-hero-back.png');">
		<div class="container codes-bg-container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<img src="/site_v2/img/site/code-hero-feat.png">
					<p>{!! $conteudos['sect1_codes_tit'] !!}</p>
					<a href="{{ route('formRegisterClientPageV2') }}"><button class="bt-transparent">{{ trans('site_v2.CREATE_ACCOUNT') }}</button></a>
					<a href="{{ route('loginPageV2') }}"><button class="bt-blue">{{ trans('site_v2.LOGIN') }}</button></a>
				</div>
			</div>
		</div>
    </div>
</div>

<div class="universal-frame"></div>

<div class="mod-padding120 bg-gray tx-center">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<h1 class="tx-navy">{!! $conteudos['sect2_codes_slide_tit'] !!}</h1>
				<div class="ppq-swiper-bt-prev"></div>
				<div class="ppq-swiper-bt-next"></div>
				
				<div id="codes" class="swiper-container ppq-swiper-container">
					
					<div class="swiper-wrapper">
						@foreach($premium as $val)
							<div id="slide_{{ $val['id'] }}" class="codes-swiper-slide swiper-slide">
								<a href="{{ route('infoPremiumPageV2',$val['id']) }}">
									<div class="codes-swiper-div" style="background-image:url('{{ $val['img'] }}');">
										<!--<div class="codes-swiper-img" style="background-image:url('{ { $val['img'] }}');"></div>-->
									</div>
								</a>
							</div>
						@endforeach
				  	</div>

					<div id="ppq" class="swiper-pagination ppq-pagination"></div>
				</div>
				<div class="codes-div-premium">
					<h2 class="margin-bottom0" id="premium_name"></h2>
					<label id="premium_value" class="tx-navy"></label><br>
					<a href="{{ route('premiumPageV2') }}"><button class="bt-blue tx-transform">{!! $conteudos['sect2_codes_slide_bt'] !!}</button></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="codes-banner"><p>{!! $conteudos['sect3_codes_banner_txt'] !!}</p></div>

<div class="mod-padding120 bg-gray tx-center">
	<h1 class="tx-navy">{!! $conteudos['sect4_codes_tit'] !!}</h1>

	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="row">
					<div class="col-md-4">
						<div class="codes-section4-div20">
							<div class="col-md-8 offset-md-2"><img class="codes-section4-img" src="/site_v2/img/site/code-col-1.png"></div>
							<div class="codes-section4-txt">
								<h2 class="tx-navy">{!! $conteudos['sect4_codes_col1_tit'] !!}</h2>
								<p>{!! $conteudos['sect4_codes_col1_txt'] !!}</p>
							</div>
							<a href="{{ route('formRegisterClientPageV2') }}"><button class="bt-blue tx-transform">{!! $conteudos['sect4_codes_col1_bt'] !!}</button></a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="codes-section4-div40">
							<div class="col-md-8 offset-md-2"><img class="codes-section4-img" src="/site_v2/img/site/code-col-2.png"></div>
							<div class="codes-section4-txt">
								<h2 class="tx-navy">{!! $conteudos['sect4_codes_col2_tit'] !!}</h2>
								<p>{!! $conteudos['sect4_codes_col2_txt'] !!}</p>
							</div>
							<a href="{{ route('loginPageV2') }}"><button class="bt-blue tx-transform">{!! $conteudos['sect4_codes_col2_bt'] !!}</button></a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="codes-section4-div40">
							<div class="col-md-8 offset-md-2"><img  class="codes-section4-img" src="/site_v2/img/site/code-col-3.png"></div>
							<div class="codes-section4-txt">
								<h2 class="tx-navy">{!! $conteudos['sect4_codes_col3_tit'] !!}</h2>
								<p>{!! $conteudos['sect4_codes_col3_txt'] !!}</p>
							</div>
							<a href="{{ route('premiumPageV2') }}"><button class="bt-blue tx-transform">{!! $conteudos['sect4_codes_col3_bt'] !!}</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
@stop

@section('javascript')
	
	<!-- Swiper -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>

  	<!-- Initialize Swiper -->
 	<script>

 		
	    var swiper = new Swiper('.swiper-container', {
			zoom: { maxRatio: 1,},
			effect: 'coverflow',
			centeredSlides: true,
			slidesPerView: 'auto',
			breakpointsInverse: true,
			initialSlide: 1,
			//loop: true,
			coverflowEffect: {
				rotate: 0,
				stretch: 84,
				depth: 320,
				//modifier: 1,
			},
			navigation: {
		      nextEl: '.ppq-swiper-bt-next',
		      prevEl: '.ppq-swiper-bt-prev',
		      clickable: true,
		    },
		    breakpoints: {
		    	
		    	320: {
		    		slidesPerView: 1,
				    coverflowEffect: {
						rotate: 0,
						stretch: 0,
						depth: 0,
						modifier: 1,
						slideShadows : true,
					},
				},			 
			    576: {
			    	slidesPerView: 1,
			      	coverflowEffect: {
						rotate: 0,
						stretch: 0,
						depth: 0,
						modifier: 1,
						slideShadows : true,
					}
			    },
			    768: {
			    	//slidesPerView: 2,
			      	coverflowEffect: {
						rotate: 0,
						stretch: 65,
						depth: 700,
						slideShadows : true,
					}
			    },
			    992: {
			    	slidesPerView: 2,
			    	coverflowEffect: {
						stretch: 83.25,
					},
			    }
			},
	    });

	   
	    @foreach($premium as $val)
		  	if ($('#slide_'+{!! $val['id'] !!}).hasClass('swiper-slide-active')) {

				$('#premium_name').append('{!! $val['name'] !!}');
			  	$('#premium_value').append('{!! $val['value'] !!} {{ trans('site_v2.points') }}');
		  	}
		@endforeach

	    //mudar a descrição dos premios
	   	swiper.on('slideChange', function () {

			$('#premium_name').html('');
			$('#premium_value').html('');

			@foreach($premium as $val)
				if (swiper.activeIndex == {!! $val['index'] !!} ) {
				  	$('#premium_name').append('{!! $val['name'] !!}');
				  	$('#premium_value').append('{!! $val['value'] !!} {{ trans('site_v2.points') }}');
			  	}	
			@endforeach
		});

 	</script>

 	<script>
 		$('.swiper-slide-shadow-left').css('border-radius','50%');
 		$('.swiper-slide-shadow-right').css('border-radius','50%');
 		$('.header-xs i').css('color','#fff');
 		$('.header-area-span').css('color','#fff');
 		$('.header-span a').css('color','#fff');
 	
 	</script>
@stop