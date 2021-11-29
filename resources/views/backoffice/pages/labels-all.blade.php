@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.AllLabels') => route('labelsPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.AllLabels') }}</div>

  <a href="{{ route('labelsNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.AddLabel') }}</a>
  <a href="{{ route('labelsGeneratePageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-check-double"></i> {{ trans('backoffice.GenerateLabels') }}</a>
  <a href="{{ route('labelsExportPageB') }}" class="abt bt-azul modulo-botoes"><i class="fas fa-file-export"></i> {{ trans('backoffice.ExportLabels') }}</a>
  <a href="{{ route('labelsIdentifyPageB') }}" class="abt bt-amarelo modulo-botoes"><i class="fas fa-fingerprint"></i> {{ trans('backoffice.IdentifyLabels') }}</a>

  <div class="modulo-table">
    <div class="modulo-scroll">
      <table id="dataTable" class="modulo-body" cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('backoffice.Label') }}</th>
            <th>{{ trans('backoffice.Serie') }}</th>
            <th>{{ trans('backoffice.Points') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteUser') !!}</div>
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
      data: { tabela:'rotulos', id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        //$('#linha_'+id).slideUp();
        $('#del'+id).parent().parent().slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }
  //<!-- PAGINAR -->
  $(document).ready(function(){
    var dataTable = $('#dataTable').DataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [6] }],
      lengthMenu: [[20,50,100,200], [20,50,100,200]],
      "order": [[ '0', "desc" ]],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :'{{ route('labelsListB') }}', // json datasource
        type: "post",  // method , by default get
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' },
        error: function(){  // error handling
          $(".dataTable-error").html("");
          $("#dataTable").append('<tbody class="dataTable-error"><tr><th colspan="7">{{ trans('backoffice.Nodata') }}</th></tr></tbody>');
          $("#dataTable_processing").css("display","none");
        }
      }
    } );
  } );
</script>
@stop