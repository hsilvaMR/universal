@extends('site_v2/layouts/default')
@section('content')
<div class="premium-bg">
	<div class="premium-hero">
		<div class="container premium-txt-hero">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<h1>{!! $conteudos['sect_premium_tit'] !!}</h1>
					<p>{!! $conteudos['sect_premium_txt'] !!}</p>
					<a href="{{ route('formRegisterClientPageV2') }}"><button class="bt-transparent">{{ trans('site_v2.CREATE_ACCOUNT') }}</button></a>
					<a href="{{ route('loginPageV2') }}"><button class="bt-blue">{{ trans('site_v2.LOGIN') }}</button></a>
				</div>
			</div>
		</div>
    </div>
</div>

<div class="universal-frame"></div>


@include('site_v2/includes/all-premium')
@include('site_v2/includes/premium-banner')
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
@stop

@section('javascript')
	<!-- Swiper -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>
	<!--Pagination-->
 	<script src="{{ asset('site_v2/js/pagination.js') }}"></script>

 	<script>
 		$('.swiper-slide-shadow-left').css('border-radius','50%');
 		$('.swiper-slide-shadow-right').css('border-radius','50%');
 		$('.header-xs i').css('color','#fff');
 		@if(Cookie::get('cookie_user_id') || Cookie::get('cookie_comerc_id')) $('.header-span a').css('color','#333');
 		@else $('.header-span a').css('color','#fff');@endif
 	</script>
@stop