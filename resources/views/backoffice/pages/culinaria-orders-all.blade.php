@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ 'Encomendas' => route('cookingOrdersPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">Encomendas</div>

  <a href="{{ route('newOrdercookingPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>

  <div class="modulo-table">
  	<div class="modulo-scroll">
  	  <table class="modulo-body" id="sortable">
  	    <thead>
  	      <tr>
            <th class="display-none"></th>
    			  <th>#</th>
    			  <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Adress') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
    			  <th>{{ trans('backoffice.Option') }}</th>
  		    </tr>
  		</thead>
  		<tbody>
  		  @foreach($encomenda as $value)
  		  <tr id="linha_{{ $value->id }}">
  		    <td class="display-none"></td>
          <td>{{ $value->id }}</td>
          <td>{{ $value->nome }}</td>
          <td>{{ $value->morada }} {{ $value->numero_porta }}, {{ $value->codigo_postal }} {{ $value->localidade }}</td>
          <td>@if($value->data != 0){{ date('Y-m-d - H:i:s',$value->data) }}@else - @endif</td>
          <td>
            @if($value->estado == 'realizada') <span class="tag tag-azul">Encomenda realizada</span>
            @elseif($value->estado == 'em_processamento') <span class="tag tag-amarelo">Encomenda em processamento</span>
            @elseif($value->estado == 'enviada') <span class="tag tag-verde">Encomenda enviada</span>
            @else <span class="tag tag-vermelho">Encomenda cancelada</span>
            @endif
          </td>
  			  <td class="table-opcao">
            <a href="{{ route('editOrdersCookingPageB',$value->id) }}" class="table-opcao">
              <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
            </a>&ensp;
    				<span class="table-opcao" onclick="$('#id_modal').val({{ $value->id }});" data-toggle="modal" data-target="#myModalDelete">
    				  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
    				</span>
  			  </td>
  			</tr>
  		  @endforeach
  		  @if(empty($encomenda)) <tr><td colspan="6">{{ trans('backoffice.noRecords') }}</td></tr> @endif
  	  	</tbody>
  	  </table>
  	</div>
  </div>

  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
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
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineTMB') }}',
      data: { tabela:'culinaria_encomenda', id:id },
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