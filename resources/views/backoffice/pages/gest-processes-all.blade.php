@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'),trans('backoffice.allProcesses') => route('processesPageB',$id_cert) ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.allProcesses') }}</div>

  <a href="{{ route('processesNewPageB',$id_cert) }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>
  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Reference') }}</th>
            <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Diagram') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Online') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dados as $val)
            <tr id="linha_{{ $val['id'] }}">
              <td class="display-none"></td>
              <td>{!! $val['id'] !!}</td>
              <td>{!! $val['referencia'] !!}</td>
              <td>{!! $val['nome'] !!}</td>
              <td>{!! $val['diagrama'] !!}</td>
              <td>{!! $val['data'] !!}</td>
              <td class="check-small">
                <input type="checkbox" name="online" id="checkOn{{ $val['id'] }}" value="1" onchange="updateOnOffTM({{ $val['id'] }});" @if($val['online']) checked @endif>
                <label for="checkOn{{ $val['id'] }}"><span></span></label>
              </td>
              <td class="table-opcao">
                <a href="{{ route('processesEditPageB',[$id_cert,$val['id']]) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>&ensp;
                <a href="{{ route('activitiesPageB',$val['id']) }}" class="table-opcao">
                  <i class="fas fa-tasks"></i>&nbsp;{{trans('backoffice.Activities')}}
                </a>&ensp;
                <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                </span>
              </td>
            </tr>
          @endforeach
          @if(empty($dados)) <tr><td colspan="8">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>



  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDelete">
    <input id="id_modal" type="hidden" name="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel2">{!! trans('backoffice.Delete') !!}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteLine') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="deleteLineTM();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
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

<script>
  function updateOnOffTM(id){
    $.ajax({
      type: "POST",
      url: '{{ route('updateOnOffTMB') }}',
      data: { tabela:'gest_processos', id:id },
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

  function deleteLineTM(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineTMB') }}',
      data: { tabela:'gest_processos', id:id },
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
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,6,7 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop