@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.AllSettings') => route('settingsPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.AllSettings') }}</div>

  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Tag') }}</th>
            <th>{{ trans('backoffice.Description') }}</th>
            <th>{{ trans('backoffice.Value') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($array as $val)
            <tr id="linha_{{ $val['id'] }}">
              <td class="display-none"></td>
              <td>{!! $val['id'] !!}</td>
              <td>{!! $val['tag'] !!}</td>
              <td>{!! $val['descricao'] !!}</td>
              <td>{!! $val['valor'] !!}</td>
              <td class="table-opcao">
                <a href="{{ route('settingsEditPageB',$val['id']) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>
              </td>
            </tr>
          @endforeach
          @if(empty($array)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>



  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDelete">
    <input id="id_modal" type="hidden" name="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel2">{!! trans('backoffice.DeleteInformation') !!}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteInformation_txt') !!}</div>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script>
  function updateOnOffTM(id){
    $.ajax({
      type: "POST",
      url: '{{ route('updateOnOffTMB') }}',
      data: { tabela:'produtos', id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }
</script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,4 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop