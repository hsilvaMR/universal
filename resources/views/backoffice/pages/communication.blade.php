@extends('backoffice/layouts/default')

@section('content')
<?php $arrayCrumbs = [ trans('backoffice.comuniBradCromb') => route('mainPageComun') ]; ?>
@include('backoffice/includes/crumbs')

<div class="page-titulo">{{ trans('backoffice.comuniBradCromb') }}</div>
<a href="{{ route('comunAdd') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i>
  {{ trans('backoffice.commuBtnAdd') }}</a>
{{-- table  --}}
<div class="modulo-table">
  <div class="modulo-scroll">
    <table class="modulo-body" id="sortable">
      <thead>
        <tr>
          <th class="display-none"></th>
          <th>#</th>
          <th>{{ trans('backoffice.communiFile') }}</th>
          <th>{{ trans('backoffice.communNome') }}</th>
          <th>{{ trans('backoffice.comunDesc') }}</th>
          <th>{{ trans('backoffice.communUpdate') }}</th>
          <th>{{ trans('backoffice.commuLink') }}</th>
          <th class="text-center">{{ trans('backoffice.commuAction') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($array as $val)
        <tr id="linha_{{ $val['id'] }}">
          <td class="display-none"></td>
          <td>{!! $val['id'] !!}</td>
          <td>{!! $val['ficheiro'] !!}</td>
          <td>{!! $val['nome'] !!}</td>
          <td>{!! $val['descr'] !!}</td>
          <td>{!! $val['update'] !!}</td>
          <td>{!! $val['link'] !!}</td>
          {{-- action edit | delete | download | share --}}
          <td class="table-opcao">
            {{--  editar  --}}
            <a href="{{ route('companiesEditPageB',['id'=>$val['id']]) }}" class="table-opcao">
              <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
            </a>&ensp;
            {{-- apagar  --}}
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
              data-target="#myModalDelete">
              <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>&ensp;
            {{-- download --}}
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
              data-target="#myModalDelete">
              <i class="fas fa-file-download"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>&ensp;
            {{-- share link  --}}
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
              data-target="#myModalDelete">
              <i class="fas fa-cloud-download-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>
          </td>
        </tr>
        @endforeach
        @if(empty($array)) <tr>
          <td colspan="10">{{ trans('backoffice.noRecords') }}</td>
        </tr> @endif
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <input type="hidden" name="id_modal" id="id_modal" @if(isset($val['id'])) value="{{ $val['id'] }}" @endif>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('backoffice.Delete') }}</h4>
      </div>
      <div class="modal-body">{!! trans('backoffice.comuniNotifiDeleteMessage') !!}</div>
      <div class="modal-footer">
        <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i>
          {{ trans('backoffice.Cancel') }}</button>&nbsp;
        <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarLinha();"><i
            class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
      </div>
    </div>
  </div>
</div>

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
      url: '{{ route('comuniDelete') }}',
      data: { id:id },
      dataType : 'json', // use json instead of text
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' },
      error: function (xhr,status, error) {
            //alert(ajaxContext.responseText)
          console.log( xhr.status)
          console.log( xhr.statusText )
          console.log( xhr.readyState )
          console.log( xhr.responseText )     
       }
    })

    .done(function(resposta) {

      response=$.parseJSON(resposta);
      // if(resp.success=='success'){
      //console.log(resposta);
      if(response.success=='success'){
        //$('#linha_'+id).slideUp();
        alert(response.success);
        //$('#linha_'+id).remove();
        $.notific8('{{ trans('backoffice.comuniNotifiDelete') }}');
      }else{
        alert(response.error);
       // $.notific8(resposta, {color:'ruby'});
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