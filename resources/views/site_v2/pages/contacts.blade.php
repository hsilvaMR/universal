@extends('site_v2/layouts/default')
@section('content')
<div style="min-height:calc(100% - 192px);background:pink;position:relative;">
	<div class="div-snow-contact">
		<div class="map" id="map-lg">
			<div class="map-container"><div id="mapa-lg"></div></div>
		</div>
	</div>
	<div style="clear:both;"></div>
	<div class="div-blue-contact div-blue-contact"></div>

	<div class="container">
		<div class="row">
			<div class="col-md-4" style="background-color:#fbfbfb;">
				<div class="div-left" style="margin-top:100px;">
					<h2 class="tx-navy">{{ trans('site_v2.Universal') }}</h2>
					<p class="contact-txt">{{ trans('site_v2.LA_Products') }}</p>

					<label class="contact-tit">{{ trans('site_v2.Address') }}</label><br>
					<label class="contact-txt">{!! $contacts['adress_txt'] !!}</label>
					<br><br>
					<label class="contact-tit">{{ trans('site_v2.Telephone') }}</label>
					<p class="contact-txt">{!! $contacts['tlm_txt'] !!}</p>
				</div>
			</div>

			<div class="mapa-xs"><div class="map"><div class="map-container"><div id="mapa"></div></div></div></div>
			
			<div class="offset-md-1 col-md-7">
				<div class="div-right" style="margin-top:100px;">

					<form id="form-contacto" action="{{ route('sendcontactPost') }}" name="form" method="post">
            			{{ csrf_field() }}

						<h2>{!! $contacts['contact_tit'] !!}</h2>

						<label>{{ trans('site_v2.Name') }}</label>
						<input class="ip" type="text" name="name" placeholder="{{ trans('site_v2.Required') }}">

						<label>{{ trans('site_v2.Email') }}</label>
						<input class="ip" type="email" name="email" placeholder="{{ trans('site_v2.Required') }}">

						<label>{{ trans('site_v2.Message') }}</label>
						<textarea class="tx margin-bottom20" name="mensage" placeholder="{{ trans('site_v2.Required') }}"></textarea>


						<input type="checkbox" id="termos_cond" name="termos_cond">
						<label for="termos_cond" class="margin-bottom20">
							<span class="bg-white"></span> <label class="contact-termos-txt">{!! $contacts['contact_terms'] !!} <a class="tx-mikado-yellow" href="{{ route('termsPageV2') }}">{{ trans('site_v2.terms_conditions') }}</a>.</label>
						</label>
						<br>
						<button class="bt-gray" type="submit"> {{ trans('site_v2.SEND') }}</button>
						<label id="labelSucesso" class="alert-success av-100 display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.Contact_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
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
	</script>


	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeP3zRZhKETZZO8MaVk-DX-1s2We9WMCI&callback=initMap"></script>
	<script>
	  function initMap() {

	  	/*MAPA-XS*/
	    var myLatLng  = {lat: 40.817827, lng: -8.483574}; 
	    var map = new google.maps.Map(document.getElementById('mapa'), {
	      center: myLatLng ,
	      zoom: 14,
	      disableDefaultUI: true,
	      styles: [{"featureType": "landscape","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 0},{"gamma": 1}]},{"featureType": "road.highway","stylers": [{"hue": "#333"},{"saturation": -73},{"lightness": 40},{"gamma": 1}]},{"featureType": "road.arterial","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 0},{"gamma": 1}]},{"featureType": "road.local","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 30},{"gamma": 1}]},{"featureType": "water","stylers": [{"hue": "#333"},{"saturation": 6},{"lightness": 8},{"gamma": 1}]},{"featureType": "poi","stylers": [{"hue": "#002d73"},{"saturation": 33.4},{"lightness": -25.4},{"gamma": 1}]}]});

	     map.setCenter({lat: 40.815999, lng: -8.48241900});
	    var icon = {
		    url: "/site_v2/img/site/gmaps-pin.png",
		    scaledSize: new google.maps.Size(31, 45), 
		    origin: new google.maps.Point(0,0), 
		    anchor: new google.maps.Point(0, 0) 
		};
			    
	    var marker = new google.maps.Marker({
	      position: myLatLng ,
	      map: map,
	      title: 'Universal',
	      icon: icon
	    });

	    /*MAPA-LG*/
	    var myLatLng2  = {lat: 40.817827, lng: -8.483574}; 
	    var map2 = new google.maps.Map(document.getElementById('mapa-lg'), {
	      center: myLatLng ,
	      zoom: 14,
	      disableDefaultUI: true,
	      styles: [{"featureType": "landscape","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 0},{"gamma": 1}]},{"featureType": "road.highway","stylers": [{"hue": "#333"},{"saturation": -73},{"lightness": 40},{"gamma": 1}]},{"featureType": "road.arterial","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 0},{"gamma": 1}]},{"featureType": "road.local","stylers": [{"hue": "#333"},{"saturation": 0},{"lightness": 30},{"gamma": 1}]},{"featureType": "water","stylers": [{"hue": "#333"},{"saturation": 6},{"lightness": 8},{"gamma": 1}]},{"featureType": "poi","stylers": [{"hue": "#002d73"},{"saturation": 33.4},{"lightness": -25.4},{"gamma": 1}]}]});

	     map2.setCenter({lat: 40.815999, lng: -8.48241900});
	    var icon2 = {
		    url: "/site_v2/img/site/gmaps-pin.png",
		    scaledSize: new google.maps.Size(31, 45), 
		    origin: new google.maps.Point(0,0), 
		    anchor: new google.maps.Point(0, 0) 
		};
			    
	    var marker = new google.maps.Marker({
	      position: myLatLng2 ,
	      map: map2,
	      title: 'Universal',
	      icon: icon2
	    });
	  }
	</script>
	<script type="text/javascript">

		$("#termos_cond").on('change', function() {
		    if ($(this).is(':checked')) { $(this).attr('value', '1'); } 
		    else { $(this).attr('value', '0'); }
		});


	  	$('#form-contacto').on('submit',function(e) {
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
		          document.getElementById("form-contacto").reset();
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