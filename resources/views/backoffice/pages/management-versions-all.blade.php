@extends('backoffice/layouts/default')

@section('content')
	<?php $arrayCrumbs = [ trans('backoffice.AllDocuments') => route('managementDocPageB'),trans('backoffice.AllVersions') => route('versionsAllPageB',$id) ]; ?>
	@include('backoffice/includes/crumbs')
	
	<div class="page-titulo">{{ trans('backoffice.AllVersions') }}</div>
	
 	<div class="modulo-table">
	  	<div class="modulo-scroll">
	  	  <table class="modulo-body" id="sortable">
	  	    <thead>
				<tr>
					<th class="display-none"></th>
					<th>#</th>
					<th>{{ trans('backoffice.Achievement') }}</th>
					<th>{{ trans('backoffice.Review') }}</th>
					<th>{{ trans('backoffice.Document') }}</th>
					<th>{{ trans('backoffice.Notes') }}</th>
					<th>{{ trans('backoffice.Date') }}</th>
					<th>{{ trans('backoffice.Status') }}</th>
					@if($cont != 0)<th>{{ trans('backoffice.Option') }}</th>@endif
				</tr>
	  		</thead>
	  		<tbody>
	  			@foreach($v_docs as $val)
	  				<tr id="linha_{{ $val['id'] }}">
	  					<td class="display-none"></td>
	  					<td>{{ $val['id'] }}</td>
	  					<td>{{ $val['quem_fez'] }}</td>
	  					<td id="revio_{{ $val['id'] }}">{{ $val['quem_revio'] }}</td>
	  					<td><a href="{{ $val['file_href']}}" download>{{ $val['ficheiro'] }}</a></td>
	  					<td>
	  						@if(!empty($val['nota'])){{ $val['nota'] }}<br>@endif
	  						@foreach( $val['doc_aux'] as $doc)
	  							<a href="/backoffice/gestao_documental/doc_aux/{{ $doc['ficheiro'] }}" download>{{ $doc['ficheiro'] }}</a><br>
	  						@endforeach
	  					</td>
	  					<td>{{ $val['data'] }}</td>
	  					<td id="estado_{{ $val['id'] }}">{!! $val['estado_html'] !!}</td>
	  					@if($cont != 0)
		  					<td id="opcao_{{ $val['id'] }}" class="table-opcao">
				  				@if($val['estado'] != 'aprovado')
				  					<label onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalAprovation" class="table-opcao"><i class="fas fa-check"></i>&nbsp;{{ trans('backoffice.Approve') }}</label>
				  					&nbsp;
				  					<label onclick="$('#id_modal_r').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalReprobation" class="table-opcao"><i class="fas fa-times"></i>&nbsp;{{ trans('backoffice.Disapprove') }}</label>
				  					&nbsp;
				  					<span class="table-opcao" onclick="$('#id_modal_delete').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
					                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
					                </span>
				  				@endif
				  			</td>
				  		@else
				  			<td class="display-none"></td>
			  			@endif
	  				</tr>
	  			@endforeach
	  	  	</tbody>
	  	  </table>
	  	</div>
  	</div>

  	<!-- Modal Delete -->
	<div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDelete">
	    <input id="id_modal_delete" type="hidden" name="id_modal_delete">
	    <div class="modal-dialog" role="document">
	      <div class="modal-content">
	        <div class="modal-header"><h4 class="modal-title" id="myModalLabel2">{!! trans('backoffice.Delete') !!}</h4></div>
	        <div class="modal-body">{!! trans('backoffice.DeleteLine') !!}</div>
	        <div class="modal-footer">
	          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
	          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarLinha();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
	        </div>
	      </div>
	    </div>
	</div>

  	<!-- Modal Aprovation -->
	<div class="modal fade" id="myModalAprovation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal" id="id_modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">
						{{ trans('backoffice.ApprovalDocument') }}
					</h4>
				</div>
				<div class="modal-body">{{ trans('backoffice.ApprovalDocument_txt') }}</div>
				<div class="modal-footer">
					<button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
					<button type="button" class="bt bt-verde" data-dismiss="modal" onclick="aprovarDocumento();"><i class="fas fa-check"></i> {{ trans('backoffice.Approve') }}</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Reprovation -->
	<div class="modal fade" id="myModalReprobation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal_r" id="id_modal_r">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">
						{{ trans('backoffice.DocumentDisapproval') }}
					</h4>
				</div>
				<div class="modal-body">{{ trans('backoffice.DocumentDisapproval_txt') }}</div>
				<div class="modal-footer">
					<button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
					<button type="button" class="bt bt-verde" data-dismiss="modal" onclick="reprovarDocumento();"><i class="fas fa-check"></i> {{ trans('backoffice.Disapprove') }}</button>
				</div>
			</div>
		</div>
	</div>
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{ json_decode(\Cookie::get('admin_cookie'))->lingua }}.js"></script>
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript">
	//<!-- PAGINAR -->
	$(document).ready(function(){
		$('#sortable').dataTable({
		  aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,7,8 ] }],
		  lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
		});
	});

	function aprovarDocumento(){
    	var id = $('#id_modal').val();

    	$.ajax({
	    	type: "POST",
		    url: '{{ route('versionsAprovationFormB') }}',
		    data: { id:id },
		    headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	    	resp = $.parseJSON(resposta);
	        if(resp.estado=='sucesso'){     
	       
	            $('#revio_'+resp.id).html('');
	            $('#revio_'+resp.id).html(resp.quem_revio);
	   			
	   			$('#estado_'+resp.id).html('');
	   			$('#estado_'+resp.id).html(resp.aprovado);

	   			$('#opcao_'+resp.id).html('');

		      	$('#myModalAprovation').modal('hide');
	    	}
	    });
    }

    function reprovarDocumento(){
    	var id = $('#id_modal_r').val();

    	console.log(id);
    	$.ajax({
	    	type: "POST",
		    url: '{{ route('versionsReprobationFormB') }}',
		    data: { id:id },
		    headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	    	resp = $.parseJSON(resposta);
	        if(resp.estado=='sucesso'){     
	       
	            $('#revio_'+resp.id).html('');
	            $('#revio_'+resp.id).html(resp.quem_revio);
	   			
	   			$('#estado_'+resp.id).html('');
	   			$('#estado_'+resp.id).html(resp.aprovado);

	   			$('#opcao_'+resp.id).html('');

		      	$('#myModalReprobation').modal('hide');
	    	}
	    });
    }

 
  	function apagarLinha(){
    	var id = $('#id_modal_delete').val();
	    $.ajax({
	      type: "POST",
	      url: '{{ route('versionDeleteFormB') }}',
	      data: { id:id },
	      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	      if(resposta=='sucesso'){
	        $('#linha_'+id).slideUp();
	        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
	      }else{ $.notific8(resposta, {color:'ruby'}); }
	    });
  	}

</script>
@stop