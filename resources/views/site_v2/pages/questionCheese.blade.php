@extends('site_v2/layouts/default')
@section('content')
	
<div class="mod-ppq">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				  
				<div class="ppq-tit">
					{!! $conteudos['sect1_ppq_tit'] !!}
					<label class="float-right"><a href="{{ route('pastimePageV2') }}"> < {{ trans('site_v2.back_to_pastime') }}</a></label>
				</div>

				<img class="ppq-img" src="/site_v2/img/ppqueijinho/{!! $conteudos['sect1_ppq_img'] !!}">

				<p class="ppq-txt">{!! $conteudos['sect1_ppq_txt'] !!} @foreach($regulation as $val) <a href="{{ route('regulationPageV2',$val->id) }}" class="tx-navy">@endforeach {!! $conteudos['sect1_ppq_link'] !!}</a></p>

				<img class="ppq-divider" src="/site_v2/img/ppqueijinho/ppq-divider.svg">
			</div>
		</div>

		<div class="row">
			<div class="col-md-10 offset-md-1">
				<h2 class="ppq-h2">{!! $conteudos['sect2_ppq_tit'] !!}</h2>
				<div class="ppq-swiper-bt-prev"></div>
				<div class="ppq-swiper-bt-next"></div>
				
				<div class="swiper-container ppq-swiper-container">
					<div class="swiper-wrapper">
						@foreach($ppqueijinho as $val)
							<div id="slide{{ $val->id }}" class="ppq-swiper-slide swiper-slide">						
								<div class="ppq-swiper-slide" style="background-image:url('{{ $val->img }}');"></div>
							</div>
						@endforeach
			  		</div>
					<div id="ppq" class="swiper-pagination ppq-pagination"></div>
				</div>
			
				<div class="ppq-resp">
					<p id='ppq_date'></p>
					<span class="ppq-resp-tit">{{ trans('site_v2.Answer') }}:</span><br>
					<span id='ppq_resp' class="ppq-resp-txt"></span><br>
					<a id="ppq_fb" target="_blank"><i class="fab fa-facebook-square ppq-resp-fb"></i></a>
					<a id="ppq_insta"><i class="fab fa-instagram ppq-resp-insta"></i></a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-10 offset-md-1"><img class="ppq-divider" src="/site_v2/img/ppqueijinho/ppq-divider.svg"></div>
		</div>

		<h2 class="ppq-h2">{!! $conteudos['sect3_ppq_tit'] !!}</h2>
		<div class="row">
			@foreach($ppqPremios as $val)
				@if($val->tag == '2_premio')
				<div class="col-md-3 offset-md-1">
					<div class="ppq-prix">
						<img src="/site_v2/img/ppqueijinho/prize-01.png">
						<div class="ppq-prix-div150">
							<span>{!! nl2br($val->premio) !!}</span>
							<hr class="ppq-prix-hr1">
							<span class="ppq-prix-desc">{!! nl2br($val->desc_premio) !!}</span>
						</div>
						<button class="bt-blue margin-top20" onclick="openFaq('Estadia');">{{ trans('site_v2.DETAILS') }}</button>
					</div>
				</div>
				@elseif($val->tag == '1_premio')
				<div class="col-md-4">
					<div class="ppq-prix-winner">
						<img src="/site_v2/img/ppqueijinho/prize-02.png">
						<div class="ppq-prix-div120">
							<span>{!! nl2br($val->premio) !!}</span>
							<hr class="ppq-prix-hr2">
							<span class="ppq-prix-desc">{!! nl2br($val->desc_premio) !!}</span>
						</div>
						<button class="bt-blue margin-top20" onclick="openFaq('Viagem');">{{ trans('site_v2.DETAILS') }}</button>
					</div>
				</div>
				@elseif($val->tag == '3_premio')
				<div class="col-md-3">
					<div class="ppq-prix">
						<img src="/site_v2/img/ppqueijinho/prize-03.png">
						<div class="ppq-prix-div150">
							<span>{!! nl2br($val->premio) !!}</span>
							<hr class="ppq-prix-hr3">
							<span class="ppq-prix-desc">{!! nl2br($val->desc_premio) !!}</span>
						</div>
						<button class="bt-blue margin-top20" onclick="openFaq('participar');">{{ trans('site_v2.DETAILS') }}</button>
					</div>
				</div>
				@endif
			@endforeach
		</div>

		<div class="row">
			<div class="col-md-10 offset-md-1"><img class="ppq-divider" src="/site_v2/img/ppqueijinho/ppq-divider.svg"></div>
		</div>

		<div class="row">
			<div class="col-md-10 offset-md-1">
				<h2 class="ppq-h2">{!! $conteudos['sect4_ppq_tit'] !!}</h2>
				<div class="ppq-faq">
					@foreach($ppq_faqs as $val)
						<div class="ppq-faq-gray perguntaClick">
							<span>{!! $val->pergunta !!}</span>
							<div id="perg{!! $val->id !!}" class="ppq-icon-faq"></div>
						</div>

						<div id="resp{!! $val->id !!}" class="ppq-faq-white perguntaOpen">
							<span>{!! $val->resposta !!}</span>	
						</div>
					@endforeach
				</div>

				<img class="ppq-divider" src="/site_v2/img/ppqueijinho/ppq-divider.svg">
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

	<script>
		$('.header-span').css('color','#333');
		$('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
		$('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
		$('.header-submenu a').css('color','#333');
		$('.header').css('background-color','#fff');
		$('.header-xs').css('background-color','#fff');
	</script>

	<!--Open Faq in button details-->
	<script>		
		function openFaq(valor){

			$('.ppq-faq-white').hide();
			@foreach($ppq_faqs as $val)

				var pergunta   = '{!! $val->pergunta !!}';
				$('#perg'+{!! $val->id !!}).removeClass('ppq-icon-faq-close');

				if (valor == 'Estadia') {	
					// verifica se palavra consta em frase
					if(pergunta.indexOf(valor) !== -1){ 
						$('#resp'+{!! $val->id !!}).show(); 
						$('#perg'+{!! $val->id !!}).addClass('ppq-icon-faq-close');
					}
				}
				else if (valor == 'viagem'){
					// verifica se palavra consta em frase
					if(pergunta.indexOf(valor) !== -1){ 
						$('#perg'+{!! $val->id !!}).addClass('ppq-icon-faq-close');
						$('#resp'+{!! $val->id !!}).show(); 
					}
				}
				else{
					// verifica se palavra consta em frase
					if(pergunta.indexOf(valor) !== -1){ 
						$('#resp'+{!! $val->id !!}).show();
						$('#perg'+{!! $val->id !!}).addClass('ppq-icon-faq-close'); 
					}
				}
			@endforeach
			$('html,body').animate({scrollTop: $(".ppq-faq").offset().top},'slow');
		}
	</script>

  	<!-- Initialize Swiper -->
 	<script>
	    var swiper = new Swiper('.swiper-container', {
			zoom: { maxRatio: 1,},
			effect: 'coverflow',
			centeredSlides: true,
			slidesPerView: 'auto',
			slideActiveClass: 'swiper-slide-active',
			breakpointsInverse: true,
			direction: 'horizontal',
			slideToClickedSlide: true,
    		coverflowEffect: {
				rotate: 0,
				stretch: 85,
				depth: 250,
				modifier: 1,
				slideShadows : true,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
				paginationClickable: true,
				autoplayDisableOnInteraction: false,
		        renderBullet: function (index, className) {
		        	/*var menu = [ @ foreach($ppqueijinho as $val) '{ !! date('M',$val->data_publicacao) !!}', @ endforeach ];*/
		        	var chaves = ['FEV', 'MAR', '', 'ABR','','MAI','','','','JUN','','JUL','','AGO','','SET','','OUT','','NOV','','','DEZ',''];
		        	var slidePag = ['0', '1', '1', '3','3','5','5','5','5','9','9','11','11','13','13','15','15','17','17','19','19','19','22','22'];
		        	var aux='';

		        	if(chaves[index]){ aux=''; } else { aux='style="display:none;"'; }

					return '<span id="slide_pag'+index+'" '+aux+' class="'+className+'" style="text-align:center; padding:10px; display:inline-table; margin-bottom:10px !important;"><input type="hidden" id="index_pag'+index+'" value="'+slidePag[index]+'">'+chaves[index]+'</span>';
		        },
			},
			navigation: {
		      nextEl: '.ppq-swiper-bt-next',
		      prevEl: '.ppq-swiper-bt-prev',
		      clickable: true,
		    },
		    breakpoints: {
		    	
		    	320: {
				    coverflowEffect: {
						rotate: 0,
						stretch: 85,
						depth: 0,
						modifier: 1,
						slideShadows : true,
					},
				},			 
			    576: {
			      	coverflowEffect: {
						rotate: 0,
						stretch: 0,
						depth: 0,
						modifier: 1,
						slideShadows : true,
					}
			    },
			    768: {
			      	coverflowEffect: {
						rotate: 0,
						stretch: 205,
						depth: 150,
						modifier: 1,
						slideShadows : true,
					}
			    },
			    992: {
			      	coverflowEffect: {
						rotate: 0,
						stretch: 105,
						depth: 250,
						modifier: 1,
						slideShadows : true,
					}
			    }   
			},
	    });

		@foreach($ppqueijinho as $val)
			if (swiper.activeIndex == {!! $val->index !!}) {
			  	$('#ppq_date').append('{!! date('d/m/y',$val->data_publicacao)!!}');

			  	var resposta = '{!! $val->resposta !!}';

			  	if (resposta == '') { 
			  		$('#ppq_resp').append('???');
			  		$('#ppq_fb').css('color','#ccc');
			  		$('#ppq_insta').css('color','#ccc');
			  	}
			  	else{ 	
			  		$('#ppq_resp').append('{!! $val->resposta !!}'); 
			  		$('#ppq_fb').attr('href','{!! $val->link_fb !!}');
			  		$('#ppq_insta').attr('href','{!! $val->link_insta!!}');
			  	}
			}
		@endforeach

	 	swiper.on('slideChange', function () {
			//mudar cor do botão de paginação
			var indexPag = $('#index_pag'+swiper.activeIndex).val();
			var indexPreviousPag = $('#index_pag'+swiper.previousIndex).val();


			$('#slide_pag'+indexPag).css('background','#1974d8');
			$('#slide_pag'+indexPag).css('color','#fff');


			if (indexPag != indexPreviousPag) {

				$('#slide_pag'+indexPreviousPag).css('background','#fbfbfb');
				$('#slide_pag'+indexPreviousPag).css('color','#ccc');
			}


			$('#ppq_resp').html('');
			$('#ppq_date').html('');
			$('#ppq_fb').attr('href','');
			$('#ppq_insta').attr('href','');
			$('#ppq_fb').css('color','#333');
			$('#ppq_insta').css('color','#333');

			@foreach($ppqueijinho as $val)

				if (swiper.activeIndex == {!! $val->index !!}) {
				  	$('#ppq_date').append('{!! date('d/m/y',$val->data_publicacao)!!}');

				  	var resposta = '{!! $val->resposta !!}';

				  	if (resposta == '') { 
				  		$('#ppq_resp').append('???');
				  		$('#ppq_fb').css('color','#ccc');
			  			$('#ppq_insta').css('color','#ccc');
				  	}
				  	else{ 
				  		$('#ppq_resp').append('{!! $val->resposta !!}'); 
				  		$('#ppq_fb').attr('href','{!! $val->link_fb !!}');
			  			$('#ppq_insta').attr('href','{!! $val->link_insta!!}');
			  		}
				}
			@endforeach
		});

 		$(".perguntaClick").click(function(){
 			@foreach($ppq_faqs as $val) $('#perg'+{!! $val->id !!}).removeClass('ppq-icon-faq-close'); @endforeach

	    	var submenu = $(this).next();
	        $(".perguntaOpen").not(submenu).slideUp();
	        submenu.slideToggle();

	        var seta = $(this).find('.ppq-icon-faq');
	        $(".ppq-icon-faq").not(seta).removeClass('rodar180');
        	seta.toggleClass('rodar180');
    	});
 	</script>
@stop