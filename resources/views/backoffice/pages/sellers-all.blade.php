@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.allSellers') => route('sellersPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.allSellers') }}</div>

  <!--<a href="{ { route('sellersNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> { { trans('backoffice.addNew') }}</a>-->
  
  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
          <th class="display-none"></th>
          <th>#</th>
          <th>{{ trans('backoffice.Image') }}</th>
          <th>{{ trans('backoffice.Name') }}</th>
          <th>{{ trans('backoffice.Email') }}</th>
          <th>{{ trans('backoffice.Company') }}</th>
          <th>{{ trans('backoffice.lastAcess') }}</th>
          <th>{{ trans('backoffice.Type') }}</th>
          <th>{{ trans('backoffice.Status') }}</th>
          <th>{{ trans('backoffice.Option') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($array as $val)
        <tr id="linha_{{ $val['id'] }}">
          <td class="display-none"></td>
          <td>{!! $val['id'] !!}</td>
          <td>{!! $val['avatar'] !!}</td>
          <td>{!! $val['nome'] !!}</td>
          <td>{!! $val['email'] !!}</td>
          <td>{!! $val['empresa'] !!}</td>
          <td>{!! $val['data'] !!}</td>
          <td>{!! $val['tipo'] !!}</td>
          <td>{!! $val['estado'] !!}</td>
          <td class="table-opcao">
            <a href="{{ route('sellersEditPageB',['id'=>$val['id']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
            <a href="{{ route('sellersLoginB',['id'=>$val['id']]) }}" class="table-opcao" target="_blank"><i class="fas fa-sign-in-alt"></i>&nbsp;{{trans('backoffice.logIn')}}</a>&ensp;
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
              <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>
          </td>
        </tr>
        @endforeach
        @if(empty($array)) <tr><td colspan="11">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Resend Email -->
  <div class="modal fade" id="myModalResendEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modalRE" id="id_modalRE">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Resend') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.resendActivationEmail') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-verde" data-dismiss="modal" onclick="reenviarEmail();"><i class="fas fa-send"></i> {{ trans('backoffice.Resend') }}</button>
        </div>
      </div>
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
      url: '{{ route('sellersDeleteB') }}',
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
  function reenviarEmail(){
    var id = $('#id_modalRE').val();
    $.ajax({
      type: "POST",
      url: '{{ route('sellersResendEmailB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $.notific8('{{ trans('backoffice.sentSuccessfully') }}');
      }else{ $.notific8(resposta, {color:'ruby'}); }
    });
  }
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,2,9 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop