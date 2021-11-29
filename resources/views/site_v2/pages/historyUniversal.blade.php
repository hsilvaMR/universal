@extends('site_v2/layouts/default')
@section('content')

<div class="universal-bg" style="background:url('/site_v2/img/site/{!! $universal['universal_bg'] !!}')no-repeat center;background-size:cover;">
	<div class="container z-index500">
		<h1>{!! $universal['universal_tit'] !!}</h1>
		<p>{!! $universal['universal_txt'] !!}</p>
		<button id="bt-more" class="bt-blue tx-transform">{!! $universal['universal_bt'] !!}</button>
    </div>   
</div>
<div class="universal-frame"></div>

<div class="universal-bg-opacity" style="background:url('/site_v2/img/site/{!! $universal['universal_bg'] !!}')no-repeat center;background-size:cover;">
</div>

<div class="container" id="div-info">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<div class="universal-txt">
				<div class="tx-center"><img src="/site_v2/img/site/{!! $universal['universal_logo'] !!}"></div>
				<p>{!! $universal['universal_info'] !!}</p>
			</div>
		</div>
	</div>
</div>
@stop

@section('css')
@stop

@section('javascript')
<script>
	$('footer').css('position','relative');
	$('footer').css('height','90px');
	$('footer').css('z-index','1000');
	$('.header').css('z-index','1000');
	$('.header-xs').css('z-index','1000');
	$('.header-submenu').css('z-index','1000');
	$('.header-area-span').css('color','#fff');
</script>
<script>
	$('.header-menu').css('color','#fff');
	$(function() {
	    $('.header-menu').hover( function(){ $(this).css('color', '#333'); },function(){ $(this).css('color', '#fff'); });
	    $('.header-menu-tipo').hover( function(){ $(this).css('color', '#333'); },function(){ $(this).css('color', '#fff'); });
	});
	
	$('.header-submenu').css('background-color','rgba(0,0,0,0.4)');
	$('.header-submenu a').css('color','#333');
	$('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.4)');
	$('.header-menu-tipo').css('color','#fff');

	$("#bt-more").click(function() {
	    $('html,body').animate({ scrollTop: $("#div-info").offset().top},'slow');
	});
</script>
@stop