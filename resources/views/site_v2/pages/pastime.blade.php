@extends('site_v2/layouts/default')
@section('content')
<div class="mod-pastime">
	<div class="container">
		<h2>{{ trans('site_v2.Pastimes') }}</h2>
		<h3>{!! $conteudos['pastime_txt'] !!}</h3>
		
		<a href="{{ route('regulationPageV2',$regulation->id) }}">
					<div class="pastime-img-ativo" style="background: url('{{ $regulation->imagem }}') center;background-size:cover;height:450px;">
						<div class="pastime-bt">
							@if($regulation->estado == 'ativo')<button class="bt-green">{{ trans('site_v2.TO_PARTICIPATE') }}</button> 
							@else<button class="bt-red">{{ trans('site_v2.CLOSED') }}</button>@endif
						</div>
					</div>
				</a>
		<!--<div class="row">
			<div class="col-lg-8">
				<a href="{{ route('regulationPageV2',$regulation->id) }}">
					<div class="pastime-img-ativo" style="background: url('{{ $regulation->imagem }}') center;background-size:cover;">
						<div class="pastime-bt">
							@if($regulation->estado == 'ativo')<button class="bt-green">{{ trans('site_v2.TO_PARTICIPATE') }}</button> 
							@else<button class="bt-red">{{ trans('site_v2.CLOSED') }}</button>@endif
						</div>
					</div>
				</a>
			</div>

			<div class="col-lg-4">
				<a href="{{ route('questionCheesePageV2') }}">
					<div class="pastime-bg-pergunta">
						
						<div class="pastime-div">

							<div class="pastime-div-top"></div>

							<div class="pastime-quarter-circle-top-left"><div class="pastime-quarter-circle-top-inner-left"></div></div>
							<div class="pastime-quarter-circle-top-right"><div class="pastime-quarter-circle-inner-right"></div></div>

							<div class="pastime-div-right-left">
								<div class="pastime-div-ppq">
									<p class="pastime-ppq-tit">{!! $conteudos['ppq_tit'] !!}</p>
									<hr>

									<p class="pastime-ppq-txt">{!! $conteudos['ppq_txt'] !!}</p>
									<button class="bt-blue">{!! $conteudos['ppq_bt'] !!}</button>
								</div>
							</div>

							<div class="pastime-quarter-circle-bottom-left"><div class="pastime-quarter-circle-bottom-inner-left"></div></div>
							<div class="pastime-quarter-circle-bottom-right"><div class="pastime-quarter-circle-bottom-inner-right"></div></div>

							<div class="pastime-div-bottom"></div>
						</div>
					</div>
				</a>
			</div>
		</div>-->

		<h2 class="pastime-old">{{ trans('site_v2.Previous_pastimes') }}</h2>

		<div class="row">	
			@foreach($pastime as $val)
			<div class="col-lg-3 col-sm-6">
				<div class="tx-center">
					<div  class="pastime-img-desativo" style="background-image:url('{{ $val->img }}');">
						<div  class="pastime-vencedor">
							<div class="pastime-vencedor-name">
								@switch($val->tipo_vencedor)
								    @case('nome')
								        <span>{{ trans('site_v2.WINNER') }}</span>
							    		<p>{!! $val->vencedor !!}</p>
								        @break
								    @case('nomes')
								        <span>{{ trans('site_v2.WINNERS') }}</span>
							    		<p>{!! nl2br($val->vencedor) !!}</p>
								        @break
								    @case('nenhum')
								        <p>{{ trans('site_v2.NOWINNERS') }}</p>
								        @break
								    @case('muitos')
							    		<p>{!! $val->vencedor !!} {{ trans('site_v2.WINNERS') }}</p>
								        @break
								    @default
								@endswitch
							</div>
						</div>
					</div>

					<div class="pastime-desc">
						<span class="pastime-titulo">{{ $val->tit_passatempo }}</span><br>
						<span>{{ trans('site_v2.in') }} {{ date('d/m',$val->data_inicio) }} {{ trans('site_v2.the') }} {{ date('d/m/Y',$val->data_fim) }}</span><br>
						<span class="tx-navy"><i class="fas fa-gift"></i> {{ $val->desc_premio }}</span>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@stop

@section('css')
@stop

@section('javascript')
	<script>
		$('.header-span').css('color','#333');
		$('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
		$('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
		$('.header-submenu a').css('color','#333');
		$('.header').css('background-color','#fff');
		$('.header-xs').css('background-color','#fff');
	</script>
@stop