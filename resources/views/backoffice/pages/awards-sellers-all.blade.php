@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.awardsRequested') => route('awardsAllPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.awardsRequested') }}</div>
  
  <div class="modulo-table">
  	<div class="modulo-scroll">
  	  <table class="modulo-body" id="sortable">
  	    <thead>
  	      <tr>
            <th class="display-none"></th>
    		    <th>#</th>
            <th>{{ trans('backoffice.Company') }}</th>
            <th>{{ trans('backoffice.Points') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
    		  </tr>
    		</thead>
        @foreach($carrinho as $car)
      		<tbody>
            <tr id="linha_{{ $car['id'] }}">
              <td class="display-none"></td>
              <td>{{ $car['id'] }}</td>
              <td>#{{ $car['id_empresa'] }} {{ $car['nome_empresa'] }}</td>
              <td>{{ $car['pontos'] }}</td>
              <td>{{ $car['data'] }}</td>
              <td>{!! $car['estado'] !!}</td>
              <td class="table-opcao">
                <a href="{{ route('editSellerAwardsPageB',$car['id']) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>&ensp;
                <span class="table-opcao" onclick="$('#id_modal').val({{ $car['id'] }});" data-toggle="modal" data-target="#myModalDelete">
                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                </span>
              </td>
            </tr>
      	  </tbody>
        @endforeach
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
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,6 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });

  function apagarLinha(){
    var id = $('#id_modal').val();
    
    $.ajax({
      type: "POST",
      url: '{{ route('deleteSellerAwardsFormB') }}',
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