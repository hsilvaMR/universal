@extends('site_v2/layouts/default')
@section('content')
<div class="mod-pastime">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="regulation-title">
					{!! $obj->titulo !!} 
					<span class="float-right"><a href="{{ route('pastimePageV2') }}"> < {{ trans('site_v2.back_to_pastime') }}</a></span>
				</div>
				<img class="regulation-img" src="{{ $obj->imagem }}">

				<div class="regulation-desc">
					<span>{{ trans('site_v2.Period') }}: {{ trans('site_v2.in') }} {{ date('d/m',$obj->data_inicio) }} {{ trans('site_v2.the') }} {{ date('d/m/Y',$obj->data_fim) }}</span>
					<a href="{!! $obj->link_insta !!}" target="_blank"><i class="fab fa-instagram regulation-icon"></i></a>
					<a href="{!! $obj->link_fb !!}" target="_blank"><i class="fab fa-facebook-square regulation-icon"></i></a><br>
					<span>{{ trans('site_v2.Award(s)') }}: <span class="tx-navy"><i class="fas fa-gift"></i> {{ $obj->premio }}</span></span>
				</div>

				<h2>{{ trans('site_v2.Regulation') }}</h2>

				<div class="regulation-txt">{!! $obj->regulamento !!}</div>
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
		$('.header').css('background-color','#fff');
		$('.header-xs').css('background-color','#fff');
	</script>
@stop