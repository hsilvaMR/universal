@extends('site_v2/layouts/default')
@section('content')

<div class="mod-incentives">
	<div class="container">
		<div class="row">
			<div class="col-md-3"> @include('site_v2/includes/menu-incentives')</div>
			<div class="col-md-9">
				<h2 class="tx-navy">{{ trans('site_v2.Qualification') }}</h2>
				<div class="mod-incentives-txt">{!! $qualification['qualification_txt']!!}</div>
				<img style="max-width:650px;" src="{{ asset('site_v2/img/site/norte2020.jpg') }}">
				<div class="mod-incentives-angle">
					<a href="{{ route('innovationPageV2') }}"><i class="fas fa-chevron-left"></i> {{ trans('site_v2.Innovation') }}</a>
					<a href="{{ route('internationalizationPageV2') }}" class="float-right">{{ trans('site_v2.Internalization') }} <i class="fas fa-chevron-right"></i></a>
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
@stop