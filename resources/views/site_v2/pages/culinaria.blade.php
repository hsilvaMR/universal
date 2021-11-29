@extends('site_v2/layouts/default-culinaria')
@section('content')

<div class="mod-product culinaria-bg">
    <div class="mod-frame-product"></div>
</div>

<img class="culinaria-bola-direita" src="site_v2/img/culinaria/bola_direita.png">

<img class="culinaria-queijo-fatias" src="site_v2/img/culinaria/fatias.png">
<div class="bg-white culinaria-padding120">
	
	<img class="culinaria-tabela-xs" src="site_v2/img/culinaria/tabela_xs.png">
	<div class="container">
		<img class="culinaria-tabela" src="site_v2/img/culinaria/tabela.png">
	</div>
	<img class="culinaria-bola-esquerda" src="site_v2/img/culinaria/bola_esquerda.png">

	<div>
		<img class="culinaria-faca-manteiga" src="site_v2/img/culinaria/manteiga.png">
		<form id="form-culinaria" action="{{ route('sendEncCulinariaPost') }}" name="form" method="post">
            {{ csrf_field() }}

			<div class="container">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="culinaria-div">
							<div style="height:60px;">
								<h3 class="float-left">Encomenda</h3>
								<label id="labelSucesso" class="display-none culinaria-sucesso" role="alert">
									<i class="fas fa-check-circle"></i>
									<span id="spanSucesso">Encomenda registada com sucesso!</span>
								</label>
		            			<label id="labelErros" class="display-none culinaria-erro" role="alert">
		            				<i class="fas fa-times-circle" onclick="$(this).parent().hide();"></i>
		            				<span id="spanErro"></span>
		            			</label>
	            			</div>
							<div class="row">
								<div class="col-md-6">
									<label class="culinaria-label">Nome</label>
									<input class="culinaria-ip" type="text" name="nome" placeholder="Preencher com o Nome">
								</div>
								<div class="col-md-6">
									<label class="culinaria-label">E-mail</label>
									<input class="culinaria-ip" type="email" name="email" placeholder="Preencher com o E-mail">
								</div>
							</div>

							<label class="culinaria-label">Morada</label>
							<input class="culinaria-ip" type="text" name="morada" placeholder="Preencher com a Rua">

							<div class="row">
								<div class="col-md-6">
									<input class="culinaria-ip" type="text" name="numero_porta" placeholder="nº Apartamento / Casa / Vivenda">
									<input class="culinaria-ip" type="text" name="localidade" placeholder="Localidade">
								</div>
								<div class="col-md-4">
									<input class="culinaria-ip" type="text" name="codigo_postal" placeholder="Código Postal">
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<label class="culinaria-label" class="margin-top10">Código promocional</label>
									<div>
										<input id="promocao" class="culinaria-ip-codigo" type="text" name="promocao" placeholder="Preencher com o código promocional"> 
										<span id="spanErro_codigo" style="color:#2DAB66;font-size:10px;line-height:11px;margin-left:10px;display:none;">
											<i class="fas fa-check"></i> <span id="span_erro_codigo"></span>
										</span>
										<span id="spanSucesso_codigo" style="color:#E03F37;font-size:10px;line-height:11px;margin-left:10px;display:none;">
											<i class="fas fa-times"></i> código inválido
										</span>
									</div>
									
								</div>
								<div class="col-md-4">
									<label class="culinaria-label" class="margin-top10">Contacto</label>
									
									<input class="culinaria-ip" type="text" name="contacto" placeholder="Contacto">
								</div>
							</div>

							<label class="culinaria-label">Mensagem</label>
							<textarea class="tx culinaria-textarea" name="mensagem" placeholder="Texto com a encomenda..."></textarea>
						</div>
						<div class="tx-center margin-top20">
							<button class="bt culinaria-bt" type="submit">ENCOMENDAR</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<img class="culinaria-queijo-prato" src="site_v2/img/culinaria/prato_deitado_fatia.png">
	</div>
</div>

<!-- elfsight -->



<div style="overflow:hidden;">
  <div class="universal_insta">
    <div class="elfsight-app-da0187a8-9450-42df-82b6-876bfb8ad044"></div>
  </div>
</div>


@stop

@section('css')
@stop

@section('javascript')
<!-- LightWidget WIDGET -->
<script src="https://apps.elfsight.com/p/platform.js" defer></script>

<script>

	$("#promocao").change(function(){
		$("#spanErro_codigo").hide();
		$("#spanSucesso_codigo").hide();
    	var codigo = $('#promocao').val();
    
	    $.ajax({
	      type: "POST",
	      url: '{{ route('culinariaCodigoPost') }}',
	      data: { codigo:codigo },
	      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	      try{ resp=$.parseJSON(resposta); }
	        catch (e){
	            return;
	        }
	        if(resp.estado=='sucesso'){
	        	$("#spanErro_codigo").show();
	          	$("#span_erro_codigo").html(resp.mensagem);
	        }else if(resposta){
	          $("#spanSucesso_codigo").show();
	        }
	    });
  

	});


	$('#form-culinaria').on('submit',function(e) {
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
	        console.log(resposta);
	        try{ resp=$.parseJSON(resposta); }
	        catch (e){
	            if(resposta){ $("#spanErro").html(resposta); }
	            else{ $("#spanErro").html('ERROR'); }
	            $("#labelErros").show();
	            return;
	        }
	        if(resp.estado=='sucesso'){
	          	$('#id').val(resp.id);
	          	$("#labelSucesso").show();
	          	document.getElementById("form-culinaria").reset();
	          	$("#spanErro_codigo").hide();
				$("#spanSucesso_codigo").hide();
	        }else if(resposta){
	          	$("#spanErro").html(resposta);
	          	$("#labelErros").show();
	        }
      	});
    });
</script>
@stop