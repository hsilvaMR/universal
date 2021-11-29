@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.awardsRequested') => route('allSellerAwardsPageB'), trans('backoffice.editRequested') => route('editSellerAwardsPageB',$id) ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.editRequested') }}@endif</div>

  <form id="editAwards" method="POST" enctype="multipart/form-data" action="{{ route('editSellerAwardsFormB') }}">
    {{ csrf_field() }}
  	
  	@foreach($carrinho as $car)
  		<input type="hidden" name="id" value="{{ $id }}">
	  	<div class="row">
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.Company') }}</label>
	  			<input class="ip" value="#{{ $car['id_empresa'] }} {{ $car['nome_empresa'] }}" disabled>
	  		</div>
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.DateRequest') }}</label>
	  			<input class="ip" value="{{ $car['data_pedido'] }}" disabled>
	  		</div>
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.PointsUsed') }}</label>
	  			<input class="ip" value="{{ $car['pontos'] }}" disabled>
	  		</div>
	  	</div>

	  	<label class="lb margin-bottom10">{{ trans('backoffice.Awards') }}</label> 
	    <table class="modulo-body" id="sortable">
	        <thead>
	          <tr>
	          <th class="display-none"></th>
	          <th>#</th>
	          <th>{{ trans('backoffice.Award') }}</th>
	          <th>{{ trans('backoffice.Variant') }}</th>
	          <th>{{ trans('backoffice.Amount') }}</th>
	          <th>{{ trans('backoffice.Points') }}</th>
	        </tr>
	      </thead>
	      @foreach($car['produtos'] as $val)
	        <tbody>
	          <td>{{ $val['id_linha'] }}</td>
	          <td><img class="table-img-circle margin-right10" src="{{ $val['img'] }}"> {{ $val['nome'] }}</td>
	          <td>{{ $val['variante'] }}</td>
	          <td>{{ $val['quantidade'] }}</td>
	          <td>{{ $val['pontos'] }}</td>
	        </tbody>
	      @endforeach
	    </table>


	    <div class="row">
	    	<div class="col-md-6">
	    		<label class="lb">{{ trans('backoffice.SendDate') }}</label>
        		<input id="entrega" class="ip" type="text" name="data_entrega" maxlength="10" value="@if(isset($car['data_envio']) && ($car['data_envio'] != 0)){{ date('Y-m-d',$car['data_envio']) }}@endif">
	    	</div>
	    	<div class="col-md-6">
	    		<label class="lb">{{ trans('backoffice.dateConclusion') }}</label>
        		<input id="conclusao" class="ip" type="text" name="data_conclusao" maxlength="10" value="@if(isset($car['data_conclusao']) && ($car['data_conclusao'] != 0)){{ date('Y-m-d',$car['data_conclusao']) }}@endif">
	    	</div>
	    </div>
	    

	    <label class="lb">{{ trans('backoffice.StatusRequest') }}</label>
        <select class="select2" name="estado">
			<option @if($car['estado'] == 'processamento') selected @endif value="processamento">
				{{ trans('backoffice.Processing') }}
			</option>
			<option @if($car['estado'] == 'enviado') selected @endif value="enviado">
				{{ trans('backoffice.Sent') }}
			</option>
			<option @if($car['estado'] == 'concluido') selected @endif value="concluido">
				{{ trans('backoffice.Concluded') }}
			</option>
        </select>
  	@endforeach

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('allSellerAwardsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Save -->
  	<div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
	    <div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.Saved') }}</h4></div>
				<div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
				<div class="modal-footer">
					<a href="{{ route('allSellerAwardsPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
					<a href="javascript:;" class="abt bt-verde" onclick="location.reload();"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
				</div>
			</div>
	    </div>
	</div>

@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@stop

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>
  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
  <script type="text/javascript">
    $('.select2').select2();
  </script>
  <script type="text/javascript">

    $('#entrega').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    $('#conclusao').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    $(document).ready(function () { 
      var $campo = $("#code-postal-fact");
      $campo.mask('AAAA - AAA', {reverse: false});
    });

    $(document).ready(function () { 
      var $campo = $("#code-postal-entrega");
      $campo.mask('AAAA - AAA', {reverse: false});
    });

    
    $('#editAwards').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false
      })
      .done(function(resposta) {
      	console.log(resposta);
        resp = $.parseJSON(resposta);
        if (resp.estado == 'sucesso') {
         if(resp.reload){ $('#myModalSave').modal('show'); }
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
          $("#labelErros").hide();
        }
        else if (resp.estado == 'erro') {
          $("#spanErro").html(resp.mensagem);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").hide();
        }
      });
    });

  </script>
@stop