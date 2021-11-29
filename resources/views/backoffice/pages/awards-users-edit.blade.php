@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.awardsRequested') => route('allUserAwardsPageB'), trans('backoffice.editRequested') => route('editUserAwardsPageB',$id) ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.editRequested') }}@endif</div>

  <form id="editAwards" method="POST" enctype="multipart/form-data" action="{{ route('editUserAwardsFormB') }}">
    {{ csrf_field() }}
  	
  	@foreach($carrinho as $car)
  		<input type="hidden" name="id_car" value="{{ $id }}">
	  	<div class="row">
	  		<div class="col-md-8">
	  			<label class="lb">{{ trans('backoffice.User') }}</label>
	  			<input class="ip" value="#{{ $car['id_utilizador'] }} {{ $car['nome'] }}" disabled>
	  		</div>
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.DateRequest') }}</label>
	  			<input class="ip" value="{{ $car['data_pedido'] }}" disabled>
	  		</div>
	  	</div>

	  	<div class="row">
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.PointsUsed') }}</label>
	  			<input class="ip" value="{{ $car['pontos_utilizados'] }}" disabled>
	  		</div>
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.PointsNeeded') }}</label>
	  			<input class="ip" value="{{ $car['pontos_necessarios'] }}" disabled>
	  		</div>
	  		<div class="col-md-4">
	  			<label class="lb">{{ trans('backoffice.AmountPaid') }}</label>
	  			<input class="ip" value="{{ $car['valor'] }}" disabled>
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
	          <th>{{ trans('backoffice.PointsUsed') }}</th>
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
		        <label class="lb">{{ trans('backoffice.DataBilling') }}</label>
		        <br>
		        <label class="lb">{{ trans('backoffice.Name') }}</label>
		        <input class="ip" type="text" name="nome_fact" value="{{ $car['nome_fact'] }}">
		          
		        <label class="lb">{{ trans('backoffice.Email') }}</label>
		        <input class="ip" type="email" name="email_fact" value="{{ $car['email_fact'] }}">

		        <div class="row">
		          <div class="col-md-6">
		            <label class="lb">{{ trans('backoffice.Contact') }}</label>
		            <input class="ip" type="text" name="contact_fact" value="{{ $car['contacto_fact'] }}">
		          </div>
		          <div class="col-md-6">
		            <label class="lb">{{ trans('backoffice.NIF') }}</label>
		            <input class="ip" type="text" name="nif" value="{{ $car['nif_fact'] }}">
		          </div>
		        </div>

		        <label class="lb">{{ trans('backoffice.Adress') }}</label>
		        <input class="ip" type="text" name="morada_fact" value="{{ $car['morada_fact'] }}">
		        <input class="ip" type="text" name="morada_opc_fact" value="{{ $car['morada_opc_fact'] }}">

		        <div class="row">
		          <div class="col-md-4">
		            <label class="lb">{{ trans('backoffice.Postal_Code') }}</label>
		            <input id="code-postal-fact" maxlength="11" class="ip" type="text" name="code_postal_fact" value="{{ $car['code_post_fact'] }}">
		          </div>
		          <div class="col-md-4">
		            <label class="lb">{{ trans('backoffice.City') }}</label>
		            <input class="ip" type="text" name="cidade_fact" value="{{ $car['cidade_fact'] }}">
		          </div>
		          <div class="col-md-4">
		            <label class="lb">{{ trans('backoffice.Country') }}</label>
		            <input class="ip" type="text" name="pais_fact" value="{{ $car['pais_fact'] }}">
		          </div>
		        </div>
	      	</div>

	      	<div class="col-md-6">
		        <label class="lb">{{ trans('backoffice.DeliveryData') }}</label>
		        <br>
		        <label class="lb">{{ trans('backoffice.Name') }}</label>
		        <input class="ip" type="text" name="nome_entrega" value="{{ $car['nome_entrega'] }}">
		          
		        <label class="lb">{{ trans('backoffice.Email') }}</label>
		        <input class="ip" type="email" name="email_entrega" value="{{ $car['email_entrega'] }}">
		        
				<label class="lb">{{ trans('backoffice.Contact') }}</label>
				<input class="ip" type="text" name="contacto_entrega" value="{{ $car['contacto_entrega'] }}">
					
		        <label class="lb">{{ trans('backoffice.Adress') }}</label>
		        <input class="ip" type="text" name="morada_entrega" value="{{ $car['morada_entrega'] }}">
		        <input class="ip" type="text" name="morada_opc_entrega" value="{{ $car['morada_opc_entrega'] }}">

		        <div class="row">
					<div class="col-md-4">
						<label class="lb">{{ trans('backoffice.Postal_Code') }}</label>
						<input id="code-postal-entrega" maxlength="11" class="ip" type="text" name="code_postal_entrega" value="{{ $car['code_post_entrega'] }}">
					</div>
					<div class="col-md-4">
						<label class="lb">{{ trans('backoffice.City') }}</label>
						<input class="ip" type="text" name="cidade_entrega" value="{{ $car['cidade_entrega'] }}">
					</div>
					<div class="col-md-4">
						<label class="lb">{{ trans('backoffice.Country') }}</label>
						<input class="ip" type="text" name="pais_entrega" value="{{ $car['pais_entrega'] }}">
					</div>
		        </div>
	      	</div>
	    </div>

	    <label class="lb">{{ trans('backoffice.Obs') }}</label>
	    <textarea class="tx" name="nota">{{ $car['nota'] }}</textarea>

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
      <a href="{{ route('allUserAwardsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
					<a href="{{ route('allUserAwardsPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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