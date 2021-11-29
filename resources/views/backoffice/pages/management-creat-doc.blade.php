@extends('backoffice/layouts/default')

@section('content')
	@if($funcao == 'new')
		<?php $arrayCrumbs = [ trans('backoffice.AllDocuments') => route('managementDocPageB'),trans('backoffice.CreatDocument') => route('creatDocumentPageB')]; ?>
	@else 
		<?php $arrayCrumbs = [ trans('backoffice.AllDocuments') => route('managementDocPageB'),trans('backoffice.EditDocument') => route('editDocumentPageB',$id) ]; ?>
	@endif
	@include('backoffice/includes/crumbs')

	
	<div class="page-titulo">@if($funcao == 'new') {{ trans('backoffice.CreatDocument') }} @else {{ trans('backoffice.EditDocument') }} @endif</div>
	<form id="creatDoc" method="POST" enctype="multipart/form-data" action="{{ route('creatDocFormB') }}">
		{{ csrf_field() }}
		<input id="id_doc" type="hidden" name="id_doc" @if(isset($id)) value="{{ $id }}" @endif>
		<input id="id_versoes" type="hidden" name="id_versoes" @if(isset($ficheiro['id_versao'])) value="{{ $ficheiro['id_versao'] }}" @endif>
		<input type="hidden" name="tipo_page" value="{{ $funcao }}">
		
		<div class="row">
			<div class="col-md-4">
				<label class="lb">{{ trans('backoffice.Reference') }}</label>
				<input id="referencia" class="ip" type="text" name="referencia" @if(isset($obj->referencia)) value="{{ $obj->referencia }}" @endif>
			</div>
			<div class="col-md-8">
				<label class="lb">{{ trans('backoffice.Name') }}</label>
				<input id="nome" class="ip" type="text" name="nome" @if(isset($obj->nome)) value="{{ $obj->nome }}" @endif>
			</div>
		</div>

		@if($funcao == 'edit' && ($ficheiro['estado'] != 'aprovado'))
			<label class="lb">{{ trans('backoffice.File') }} ({{ trans('backoffice.Current') }})</label>
			<a href="{{ $obj->ficheiro }}" download><input class="ip" value="{{ $obj->nome }}" disabled=""></a>

			<label class="lb">{{ trans('backoffice.FileInApproval') }}</label>
			<a href="{{ $ficheiro['file']}}" download><input class="ip" value="{{ $ficheiro['nome'] }}" disabled=""></a>
		@endif
		

		<input type="hidden" name="documento_antigo" value="@if(isset($ficheiro)){{ $ficheiro['file'] }}@endif">
		<input type="hidden" id="doc_antiga" name="doc_antiga" value="@if(isset($ficheiro)){{ $ficheiro['file'] }}@endif">
		<input type="hidden" id="url_doc" name="url_doc" value="">
		<input id="estado_ficheiro" type="hidden" name="estado_ficheiro" value="@if(isset($ficheiro['estado'])){{ $ficheiro['estado'] }}@endif">

		@if(($funcao == 'edit' && $ficheiro['estado'] == 'aprovado') || ($funcao == 'new'))
		<label class="lb">{{ trans('backoffice.File') }}</label>
			@if(isset($ficheiro)) 
				<div class="row">
					<div class="col-md-12">
						<div>
							<div class="div-50" id="doc">
								<label class="a-dotted-white" id="doc_upload">
									<a href="{{ $ficheiro['file'] }}" download>@if(isset($obj->nome)){{ $obj->nome }}@endif</a>
								</label>
							</div>
							<label for="selecao-doc" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
							<input id="selecao-doc" name="doc" @if($ficheiro['estado'] != 'em_aprovacao') type="file" onchange="lerFicheiros(this,'doc_upload');"@else type="hidden" @endif>        
						</div>
						@if($ficheiro['estado'] != 'em_aprovacao')
							<label class="lg">{!! trans('backoffice.CreatDiagramTx') !!}</label>
						@endif
					</div>
				</div>
			@else
				<div class="row">
					<div class="col-md-12">
						<div>
							<div class="div-50" id="doc">
								<label class="a-dotted-white" id="doc_upload">&nbsp;</label>
							</div>
							<label for="selecao-doc" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
							<input id="selecao-doc" name="doc" type="file" onchange="lerFicheiros(this,'doc_upload');">         
						</div>
						<label class="lg">{!! trans('backoffice.CreatDiagramTx') !!}</label>
					</div>
				</div>
			@endif
		@endif

		@if(($funcao== 'edit' && $ficheiro['estado'] == 'aprovado') || ($funcao == 'new'))
			<label class="lb">{{ trans('backoffice.AuxiliaryFiles') }}</label>
			@if(isset($doc_aux))
				<?php $count = 1; ?>
				@foreach($doc_aux as $val)
					<div id="div_{{ $val->id }}" class="row">
						<div class="col-md-12">
							<div>
								<div class="div-50">
									<div class="div-40" id="doc_aux_{{ $val->id }}">
										@if($val->tipo == 'diagrama')
											<label class="a-dotted-white cursor-pointer" id="{{ $val->id }}_upload" onclick="showDiagramExist({{ $val->id }});">
												@if($val->ficheiro) ficheiro_auxiliar_{{ $count}} @else &nbsp; @endif
											</label>

											<img id="aux_img{{ $val->id }}" onclick="DiagramEditor.editElement(this);" src="{{ $val->ficheiro }}" class=" display-none cursor-pointer" style="">
										@else
											<label class="a-dotted-white cursor-pointer" id="{{ $val->id }}_upload">@if($val->ficheiro)<a href="/backoffice/gestao_documental/doc_aux/{{ $val->ficheiro }}"> {{ $val->ficheiro }}</a> @else &nbsp; @endif</label>
										@endif

									</div>
								</div>
								<label class="lb-40 bt-azul float-right" onclick="$('#id_modal_file').val({{$val->id}});$('#tabela').val('gest_doc_aux');" data-toggle="modal" data-target="#myModalDeleteFile"><i class="fa fa-trash-alt"></i></label>          
							</div>
						</div>
					</div>
					<?php $count++; ?>
				@endforeach
			@endif

			<div id="files_aux">
				<input id="files_aux_ip" type="hidden" name="files_aux_ip" value="">
			</div>

			<div class="row">
				<div class="col-md-12">
					<div>
						<div class="div-50">
							<div class="div-50" id="doc_aux">
								<label class="a-dotted-white" id="doc_aux_upload">&nbsp;</label>
							</div>
							<label for="selecao-doc_aux" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
							<input id="selecao-doc_aux" type="file" name="doc_aux[]" onchange="lerFicheiros(this,'doc_aux_upload');" multiple>
						</div>
						<label class="lb-40 bt-azul float-right" onclick="limparFicheiros('doc_aux');"><i class="fa fa-trash-alt"></i></label>          
					</div>
				</div>
			</div>
		@endif
		
		<input id="url_img" type="hidden" name="url_img" value="">
		<input id="diagram_img" type="hidden" name="diagram_img" value="">
		<body>
			<div id="diagram" class="margin-top20 display-none">
				<img id="img_diagram" onclick="DiagramEditor.editElement(this);" src="" class="cursor-pointer">
			</div>
		</body>

		@if((isset($ficheiro['estado']) && $ficheiro['estado'] == 'aprovado') ||($funcao == 'new'))
			<label class="lb">{{ trans('backoffice.WhoRevisesDocument') }}</label>
	        <div class="row">
        		<div class="col-md-6">
        			<select id="id_revisor" class="select2" name="id_revisor">
	          			<option value="" selected>&nbsp;</option>
			          	@foreach($admin as $val)
			          		<option @if(isset($ficheiro['revisao']) && ($ficheiro['revisao'] == $val->id)) selected @endif value="{{ $val->id }}">#{{ $val->id }} {{ $val->nome }}</option>
			        	@endforeach
	        		</select>
        		</div>
        		<div class="col-md-6">
        			<input id="email_revisao" class="ip" type="email" name="email_revisao" placeholder="email" @if(isset($ficheiro['email_revisor'])) value="{{ $ficheiro['email_revisor'] }}" @endif>
        		</div>
        	</div>

        	<!--Link de aprovacao-->
        	@if(isset($ficheiro['estado']) && $ficheiro['estado'] == 'aprovado')
	        	<div>
	        		<label class="lb">{{ trans('backoffice.ShareLinkDocument_txt') }}</label><br>
	        		<a class="tx-verde" href="https://universal.com.pt/version/{{ $ficheiro['token'] }}" target="_black">www.universal.com.pt/version/{{ $ficheiro['token'] }}</a>
	        	</div>
        	@endif
        @endif

        @if(isset($ficheiro['estado']) && $ficheiro['estado'] == 'aprovado' || ($funcao=='new'))
			
			<div id="botoes">
				<button class="bt bt-verde float-right" type="button" onclick="verificarForm();"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
		    </div>
			<div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
		    <div class="clearfix"></div>
		    <div class="height-20"></div>
		    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
		    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
	    @endif
    </form>

    @if(($funcao == 'edit') && (isset($ficheiro['estado']) && $ficheiro['estado'] != 'aprovado'))
        <form id="aprovedDoc" method="POST" enctype="multipart/form-data" action="{{ route('versionStatusFormB') }}">
        	{{ csrf_field() }}
        	<input id="tipo_status" type="hidden" name="tipo_status" value="">
        	<input type="hidden" name="id_versao" value="{{ $ficheiro['id_versao'] }}">
        	<input type="hidden" name="id_documento" value="{{ $id }}">

        	<label class="lb">{{ trans('backoffice.WhoRevisesDocument') }}</label>

        	<div class="row">
        		<div class="col-md-6">
        			<select id="id_revisor" class="select2" name="id_revisor" disabled>
	          			<option value="" selected>&nbsp;</option>
			          	@foreach($admin as $val)
			          		<option @if(isset($ficheiro['revisao']) && ($ficheiro['revisao'] == $val->id)) selected @endif value="{{ $val->id }}">#{{ $val->id }} {{ $val->nome }}</option>
			        	@endforeach
	        		</select>
        		</div>
        		<div class="col-md-6">
        			<input id="email_revisao" class="ip" type="email" name="email_revisao" placeholder="email" @if(isset($ficheiro['email_revisor'])) value="{{ $ficheiro['email_revisor'] }}" @endif disabled>
        		</div>
        	</div>

        	<label class="lb">{{ trans('backoffice.ReprovedAproved_txt') }}</label>

        	@if(isset($version_aux))
	        	
		        @foreach($version_aux as $aux)
		        	<div id="div_aux_{{ $aux->id }}" class="margin-top10">
		        		<i class="fas fa-times tx-vermelho margin-right10 cursor-pointer" onclick="$('#id_modal_file').val({{$aux->id}});$('#tabela').val('gest_doc_versoes_aux');" data-toggle="modal" data-target="#myModalDeleteFile"></i><a class="tx-verde" href="/backoffice/gestao_documental/doc_aux/{{ $aux->ficheiro }}" download><i class="far fa-file"></i> {{ $aux->ficheiro }}</a>
		          	</div>
		        @endforeach
		    @endif

			<div class="row">
		        <div class="col-md-12">
		          	<div>
		            	<div class="div-50">
		              		<div class="div-50" id="doc-aux">
		                		<label class="a-dotted-white" id="doc_upload_aux">&nbsp;</label>
		              		</div>
		              		<label for="selecao-doc-aux" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
		              		<input id="selecao-doc-aux" type="file" name="doc_aux_versao[]" onchange="lerFicheiros(this,'doc_upload_aux');" multiple>
		            	</div>
		            	<label class="lb-40 bt-azul float-right" onclick="limparFicheiros('doc-aux');"><i class="fa fa-trash-alt"></i></label>          
		          	</div>
		        </div>
		    </div>

	        <textarea class="tx" name="nota_versao" rows="3">{{ $ficheiro['nota'] }}</textarea>

	        <!--Link de aprovacao-->
        	<div>
        		<label class="lb">{{ trans('backoffice.ShareLinkDocument_txt') }}</label><br>
        		<a class="tx-verde" href="https://universal.com.pt/version/{{ $ficheiro['token'] }}" target="_black">www.universal.com.pt/version/{{ $ficheiro['token'] }}</a> 
        	</div>

	        <div>
				<button class="bt bt-vermelho float-right margin-left10" type="button" onclick="aprovarDoc('reprovado');"><i class="fas fa-times"></i> {{ trans('backoffice.Disapprove') }}</button>
				<button class="bt bt-verde float-right" type="button" onclick="aprovarDoc('aprovado');"><i class="fas fa-check"></i> {{ trans('backoffice.Approve') }}</button>
	    	</div>
        </form>
    @endif

    <!-- Modal Save -->
	<div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal" id="id_modal">
		<div class="modal-dialog" role="document">
		  	<div class="modal-content">
		    	<div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4></div>
		    	<div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
			    <div class="modal-footer">
			      	<a href="{{ route('managementDocPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
			      	<a id="page_reload" href="javascript:;" class="abt bt-verde" onclick="location.reload(true);"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
			    </div>
		  	</div>
		</div>
	</div>

	<!-- Modal Review -->
	<div class="modal fade" id="myModalReview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
		  	<div class="modal-content">
		    	<div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.DocumentReview') }}</h4></div>
		    	<div class="modal-body">{{ trans('backoffice.DocumentReview_txt') }}</div>
			    <div class="modal-footer">
			      	<a onclick="$('#myModalReview').modal('toggle');" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
			      	<a class="abt bt-verde" onclick="$('#creatDoc').submit();$('#myModalSave').modal('show');$('#myModalReview').hide();"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</a>
			    </div>
		  	</div>
		</div>
	</div>

	<!--Modal Delete File-->
	<div class="modal fade" id="myModalDeleteFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal_file" id="id_modal_file">
		<input type="hidden" name="tabela" id="tabela">
		<div class="modal-dialog" role="document">
		  	<div class="modal-content">
		    	<div class="modal-header"><h4 class="modal-title" id="myModalLabel"> {{ trans('backoffice.Delete') }}</h4></div>
		    	<div class="modal-body">{!! trans('backoffice.DeleteFile') !!}</div>
			    <div class="modal-footer">
			    	<a onclick="$('#myModalDeleteFile').modal('toggle');" class="abt bt-cinza"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>&nbsp;
			      	<a class="abt bt-verde" onclick="apagarFile();"><i class="fas fa-check"></i> {{ trans('backoffice.Delete') }}</a>
			    </div>
		  	</div>
		</div>
	</div>

	<!--Modal Aprovation-->
	<div class="modal fade" id="myModalAproved" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal_aproved" id="id_modal_aproved">
		<div class="modal-dialog" role="document">
		  	<div class="modal-content">
		    	<div class="modal-header"><h4 class="modal-title" id="myModalLabel"> {{ trans('backoffice.ApprovalDocument') }}</h4></div>
		    	<div class="modal-body"><p>{!! trans('backoffice.ApprovalDocument_txt') !!}</p></div>
			    <div class="modal-footer">
			    	<a onclick="$('#myModalAproved').modal('toggle');" class="abt bt-cinza"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>&nbsp;
			      	<a class="abt bt-verde" onclick="$('#aprovedDoc').submit();$('#myModalAproved').modal('hide');"><i class="fas fa-check"></i> {{ trans('backoffice.Approve') }}</a>
			    </div>
		  	</div>
		</div>
	</div>

	<!--Modal Disapprove-->
	<div class="modal fade" id="myModalRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal_aproved" id="id_modal_aproved">
		<div class="modal-dialog" role="document">
		  	<div class="modal-content">
		    	<div class="modal-header"><h4 class="modal-title" id="myModalLabel"> {{ trans('backoffice.DocumentDisapproval') }}</h4></div>
		    	<div class="modal-body"><p>{!! trans('backoffice.DocumentDisapproval_txt') !!}</p></div>
			    <div class="modal-footer">
			    	<a onclick="$('#myModalRep').modal('toggle');" class="abt bt-cinza"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>&nbsp;
			      	<a class="abt bt-verde" onclick="$('#aprovedDoc').submit();$('#myModalRep').modal('hide');"><i class="fas fa-check"></i> {{ trans('backoffice.Disapprove') }}</a>
			    </div>
		  	</div>
		</div>
	</div>
	
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.css') }}">
@stop

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>


<script type="text/javascript">

	function showDiagram(){
		$('#img_diagram').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAACsXRFWHRteGZpbGUAJTNDbXhmaWxlJTIwZXRhZyUzRCUyMk1WQm8tUFpBdXBJR1ZucTNSMnpZJTIyJTIwYWdlbnQlM0QlMjI1LjAlMjAoV2luZG93cyUyME5UJTIwMTAuMCUzQiUyMFdpbjY0JTNCJTIweDY0KSUyMEFwcGxlV2ViS2l0JTJGNTM3LjM2JTIwKEtIVE1MJTJDJTIwbGlrZSUyMEdlY2tvKSUyMENocm9tZSUyRjgxLjAuNDA0NC4xMjIlMjBTYWZhcmklMkY1MzcuMzYlMjIlMjBtb2RpZmllZCUzRCUyMjIwMjAtMDQtMjdUMTMlM0E1MCUzQTA0Ljg3MlolMjIlMjBob3N0JTNEJTIyd3d3LmRyYXcuaW8lMjIlMjB2ZXJzaW9uJTNEJTIyMTMuMC4yJTIyJTNFJTNDZGlhZ3JhbSUyMGlkJTNEJTIyclV1eHZtYW1kTloxenJMWE9sXzYlMjIlMjBuYW1lJTNEJTIyUGFnZS0xJTIyJTNFbGRGQkQ0SWdGQURnWDhQUkRhR2ExNlpXbDFyTFE1czNKb1JzNkhOSTAlMkZyMTZjU01kYWtUOFBGNDhCNkl4bFclMkZONndwajhDRlJnVHpIdEVFRVJKRzBXWVlSbmxNRW9YUkJOSW83b0lXeU5SVE9NUk83NHFMMWd1MEFOcXF4c2NDNmxvVTFqTm1ESFIlMkIyQTIwZjJ2RHBQaUNyR0Q2VzYlMkJLMjlKVnNjYUxINFNTNVh4emlOMU94ZVpnQjIzSk9IUWZSRk5FWXdOZ3AxblZ4MEtQelp2N2NnclB5WXJRUzFEbnVKQmttJTJCWlNCVk95M1Q5SDNpVVlVZHRmVXclMkJUNVduRHd2dGdtcjRBJTNDJTJGZGlhZ3JhbSUzRSUzQyUyRm14ZmlsZSUzRVIewYoAAAALSURBVBhXY2AAAgAABQABqtXIUQAAAABJRU5ErkJggg==');

		$('#img_diagram').click();
		$('.select2-selection').hide();
		$('#selecao-doc').val('');
		$('#doc_antiga').val('');
	}

	function verificarForm(){
		var id_versoes = $('#id_revisor').val();
		var email_revisao = $('#email_revisao').val();
		var referencia = $('#referencia').val();
		var nome = $('#nome').val();
		var doc = $('#selecao-doc').val();
		var doc_antiga = $('#doc_antiga').val();
		var diagrama = $("#img_diagram").attr('src');

		console.log(doc);
		console.log(doc_antiga);
		console.log(diagrama);

		$("#labelErros").hide();
		if (referencia == '') {
			$("#labelErros").show();
			$("#spanErro").html('{{ trans('backoffice.FieldRef_txt') }}');
		}
		else if (nome == '') {
			$("#labelErros").show();
			$("#spanErro").html('{{ trans('backoffice.Field_Name_txt') }}');
		}
		else if(((doc == '') && (diagrama == '')) && (doc_antiga == '')){
			$("#labelErros").show();
			$("#spanErro").html('{{ trans('backoffice.FieldDoc_txt') }}');
		}
		else if((doc && diagrama) || (doc && doc_antiga) || (diagrama && doc_antiga)){
			$("#labelErros").show();
			$("#spanErro").html('{{ trans('backoffice.AddFileDiagram_txt') }}');
		}
		else if(id_versoes && email_revisao){
			$("#labelErros").show();
			$("#spanErro").html('Para rever o documento só poderá selecionar uma pessoa, ou introduzir um email.');
		}
		else if (id_versoes == '' && (email_revisao == '')) {
			$('#myModalReview').modal('show');
		}
		else{
			$('#creatDoc').submit();
		}
	}

	$('#creatDoc').on('submit',function(e) {
		$("#labelSucesso").hide();
		$("#labelErros").hide();
		$('#loading').show();
		$('#botoes').hide();

		var estado_ficheiro = $('#estado_ficheiro').val();
      	
      	if(estado_ficheiro != 'em_aprovacao'){

      		var imagem = $("#img_diagram").attr('src');
      		//var imagem_exit = $("#img_diagram_exit").attr('src');

	      	if (imagem) {
	      		$('#diagram_img').val(imagem);
	      	}/*else{
	      		$('#diagram_img').val(imagem_exit);
	      	}*/
	    	$('#doc_antiga').val('');
      	}
      	

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
	        	$('#loading').hide();
	         	$('#botoes').show();
	         	$('#id_doc').val(resp.id_doc);
	         	$('#id_versoes').val(resp.id_versoes);
	         	//$('#url_img').val(resp.url_img);
	         	//$('#url_doc').val(resp.url_doc);

	          	/*if (resp.estado_revisao == 'aprovado') {
	          		$('#myModalReview').modal('show');
	          	}else{*/
	          		$("#labelSucesso").show();
	          		if(resp.reload){ 
	          			$('#myModalSave').modal('show');
	          			$('#page_reload').attr('href','/admin/management-edit-document/'+resp.id_doc);
	          		}
	          	//}
	        }else if(resposta){
	          $("#spanErro").html(resposta);
	          $("#labelErros").show();
	          $('#loading').hide();
	          $('#botoes').show();
	        }
      	});
    });

    function lerFicheiros(input,id) {
    	var quantidade = input.files.length;
      	var nome = input.value;
      	if(quantidade==1){$('#'+id).html(nome);}
      	else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}

      	$("#img_diagram").attr('src','');
      	$("#diagram_img").val('');

      	if (id == 'doc_upload') {
      		$("#doc_antiga").val('');
      	}
    
    }
    function limparFicheiros(id) {
      	$('#selecao-'+id).val('');
      	$('#'+id+'_upload').html('&nbsp;');
      	$('#'+id+'_antiga').val('');
      	$('#'+id).html('<label class="a-dotted-white" id="'+id+'_upload">&nbsp;</label>');
      	$('#div_'+id).hide();
    }

    function apagarFile(){
    	var id = $('#id_modal_file').val();
    	var tabela = $('#tabela').val();

    	$.ajax({
	    	type: "POST",
		    url: '{{ route('deleteDocAuxFormB') }}',
		    data: { id:id, tabela:tabela },
		    headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	    	if(resposta=='sucesso'){

	    		if(tabela == 'gest_doc_aux'){ $('#div_'+id).remove(); }
	    		else if(tabela == 'gest_doc_versoes_aux'){ $('#div_aux_'+id).remove(); }
		      	
		      	$('#myModalDeleteFile').modal('hide');
	    	}
	    });
    }

    function aprovarDoc(tipo){

		if (tipo == 'reprovado') {
			$('#myModalRep').modal('show');
		}

		if (tipo == 'aprovado') {
			$('#myModalAproved').modal('show');
		}

		$('#tipo_status').val(tipo);
    }

    $('#aprovedDoc').on('submit',function(e) {
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
	        	$('#loading').hide();
	         	$('#botoes').show();
	         	location.reload(true);
	        }else if(resposta){
	          $("#spanErro").html(resposta);
	          $("#labelErros").show();
	          $('#loading').hide();
	          $('#botoes').show();
	        }
      	});
    });

</script>
@stop