@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.allCompanies') => route('companiesPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.allCompanies') }}</div>

  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Logotipo') }}</th>
            <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Email') }}</th>
            <th>{{ trans('backoffice.NIF') }}</th>
            <th>{{ trans('backoffice.dateOfRegistration') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($array as $val)
            <tr id="linha_{{ $val['id'] }}">
              <td class="display-none"></td>
              <td>{!! $val['id'] !!}</td>
              <td>{!! $val['logotipo'] !!}</td>
              <td>{!! $val['nome'] !!}</td>
              <td>{!! $val['email'] !!}</td>
              <td>{!! $val['nif'] !!}</td>
              <td>{!! $val['registo'] !!}</td>
              <td>{!! $val['estado'] !!}</td>
              <td class="table-opcao">
                <a href="{{ route('companiesEditPageB',['id'=>$val['id']]) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>&ensp;
                <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                </span>
              </td>
            </tr>
          @endforeach
          @if(empty($array)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal" @if(isset($val['id'])) value="{{ $val['id'] }}" @endif>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteCompany') !!}</div>
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

<script>
  //<!-- APAGAR EMPRESA -->
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('companiesDeleteB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      //console.log(resposta);
      if(resposta=='sucesso'){
        //$('#linha_'+id).slideUp();
        $('#linha_'+id).remove();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }

  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,2,8 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop