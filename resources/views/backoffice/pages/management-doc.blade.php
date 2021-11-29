@extends('backoffice/layouts/default')

@section('content')
	<?php $arrayCrumbs = [ trans('backoffice.AllDocuments') => route('managementDocPageB') ]; ?>
	@include('backoffice/includes/crumbs')
	
	<div class="page-titulo">{{ trans('backoffice.AllDocuments') }}</div>
	@if(isset($tipo_doc_criar))
		<a href="{{ route('creatDocumentPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>
	@endif
	
 	<div class="modulo-table">
	  	<div class="modulo-scroll">
	  	  <table class="modulo-body" id="sortable">
	  	    <thead>
				<tr>
					<th class="display-none"></th>
					<th>#</th>
					<th>{{ trans('backoffice.Reference') }}</th>
					<th>{{ trans('backoffice.Name') }}</th>
					<th>{{ trans('backoffice.Version') }}</th>
					<th>{{ trans('backoffice.Date') }}</th>
					<th>{{ trans('backoffice.Option') }}</th>
				</tr>
	  		</thead>
	  		<tbody>
	  			@foreach($documentos as $val)
		  			<tr id="linha_{{ $val['id'] }}">
		  				<td class="display-none"></td>
		  				<td>{{ $val['id'] }}</td>
		  				<td>{{ $val['referencia'] }}</td>
		  				<td>@if($val['ficheiro'])<a href="{{ $val['ficheiro'] }}" download>{{ $val['nome'] }}</a>@endif</td>
		  				<td>
		  					@if($val['estado'] == 'em_aprovacao') 
		  						<span class="tag tag-amarelo nowrap">{{ trans('backoffice.OnApproval') }}</span>
		  					@elseif($val['estado'] == 'reprovado') 
		  						<span class="tag tag-vermelho nowrap">{{ trans('backoffice.Disapproved') }}</span>
		  					@else
		  						{{ $val['versao'] }}  
		  					@endif
		  				</td>
		  				<td>{{ date('Y-m-d',$val['data']) }}</td>
		  				<td class="table-opcao">
		  					@if(isset($tipo_doc_rever))
			  					<a href="{{ route('editDocumentPageB',$val['id']) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{ trans('backoffice.Edit') }}</a>&ensp;
			  				@endif
			  				<a href="{{ route('versionsAllPageB',$val['id']) }}" class="table-opcao"><i class="far fa-copy"></i>&nbsp;{{ trans('backoffice.Versions') }}</a>&ensp;
			  				<span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
			  				  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
			  				</span>
			  			 </td>
		  			</tr>
	  			@endforeach
	  	  	</tbody>
	  	  </table>
	  	</div>
  	</div>

  	<!-- Modal Delete -->
	<div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<input type="hidden" name="id_modal" id="id_modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4>
				</div>
				<div class="modal-body">{!! trans('backoffice.DeleteLine') !!}</div>
				<div class="modal-footer">
					<button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
					<button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarLinha();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
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
	function apagarLinha(){
	    var id = $('#id_modal').val();
	    $.ajax({
	      type: "POST",
	      url: '{{ route('deleteLineDocFormB') }}',
	      data: { id:id },
	      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	      if(resposta=='sucesso'){
	        $('#linha_'+id).slideUp();
	        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
	      }else{
	        $.notific8(resposta, {color:'ruby'});
	      }
	    });
  	}
	//<!-- PAGINAR -->
	$(document).ready(function(){
		$('#sortable').dataTable({
		  aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,6 ] }],
		  lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
		});
	});
</script>
@stop