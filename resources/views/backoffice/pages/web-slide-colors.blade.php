@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ 'Todas as cores' => route('colorsPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">Todas as cores</div>

  <a href="{{ route('colorsNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>
  
  <div class="modulo-table">
  	<div class="modulo-scroll pointer">
  	  <table class="modulo-body" id="sortable">
  	    <thead>
  	      <tr>
          <th class="display-none"></th>
          <th>#</th>
          <th>Nome</th>
  			  <th>Cor</th>
  			  <th>Gradiente 1 </th>
  			  <th>Gradiente 2 </th>
  			  <th>{{ trans('backoffice.Option') }}</th>
  		  </tr>
  		</thead>
  		<tbody>
  		  @foreach($colors as $val)
  		  <tr id="linha_{{ $val->id }}">
          <input type="hidden" class="ordem_atual" name="ordem" value="{{ $val->ordem }}">
  		    <td class="display-none"></td>
          <td>{{ $val->id }}</td>
          <td>{{ $val->nome }}</td>
          <td><div style="height:40px;background: linear-gradient(135deg, {{ $val->gradiente_1 }}, {{ $val->gradiente_2 }});"></div></td>
  			  <td><label style="line-height:40px;">{{ $val->gradiente_1 }}</label> <label class="lb-40 bt-branco float-right" style="margin-top:0px;background:{{ $val->gradiente_1 }};"></label></td>
  			  <td><label style="line-height:40px;">{{ $val->gradiente_2 }}</label> <label class="lb-40 bt-branco float-right" style="margin-top:0px;background:{{ $val->gradiente_2 }};"></label></td>
  			  <td class="table-opcao">
  				<a href="{{ route('colorsEditPageB',$val->id) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
  				<span class="table-opcao" onclick="$('#id_modal').val({{ $val->id}});" data-toggle="modal" data-target="#myModalDelete">
  				  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
  				</span>
  			  </td>
  			</tr>
  		  @endforeach
  		  @if(empty($colors)) <tr><td colspan="5">{{ trans('backoffice.noRecords') }}</td></tr> @endif
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

    <!-- Alert 
  <div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    <strong>{ { trans('backoffice_v2.Sucesso') }}</strong> { { trans('backoffice.changeSuccessfully') }}!
  </div>-->

@stop

@section('css')
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<!-- ORDENAR -->
<script src="{{ asset('backoffice/vendor/sortable/jquery-ui.min.js') }}"></script>
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineTMB') }}',
      data: { tabela:'conteudos_slide_cor', id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_'+id).slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }
    });
  }

  // ORDENAR
  $("#sortable tbody").sortable({
      opacity: 0.6, cursor: 'move',
      update: function() {
        var valor;
        var arrayOrder = [];

        $('.ordem_atual').each(function(){
          valor=$(this).val();
          arrayOrder.push(valor);
        });

        arrayOrder.sort(function(a, b){return a-b});
        var order = $(this).sortable("serialize")+'&ordem='+arrayOrder+'&tabela=conteudos_slide_cor';

        $.ajax({
          type: "POST",
          url: '{{ route('orderTableTMB') }}',
          data: { tabela:'conteudos_slide_cor', order:order },
          headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
        })
        .done(function(resposta) {
         console.log(resposta);
        });



        $('.alert').show();
        setTimeout(function(){ $('.alert').hide(); }, 6000);
      }
  }).disableSelection();

  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,5 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop