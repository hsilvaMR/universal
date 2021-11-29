@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allUsers') => route('usersPageB'), trans('backoffice.newUser') => route('usersNewPageB') ]; ?>
  @else
    <?php $arrayCrumbs = [ trans('backoffice.allUsers') => route('usersPageB',$id_utilizador), trans('backoffice.allAwards') => route('usersAwardsB',$id_utilizador),trans('backoffice.EditRequest') => route('usersAwardEditB',$id) ]; ?>
  @endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.EditRequest') }}@endif</div>
  
  <form id="usersAwardsFormB" method="POST" enctype="multipart/form-data" action="{{ route('usersFormAwardB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_carrinho" value="{{ $id }}">
    <div class="row">
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.StatusRequest') }}</label>
        <select class="select2" name="estado">
          <option @if($car_data->estado == 'em_processamento') selected @endif value="em_processamento">{{ trans('backoffice.In_processing') }}</option>
          <option @if($car_data->estado == 'enviado') selected @endif value="enviado">{{ trans('backoffice.Send') }}</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.DateOfRequest') }}</label>
        <input class="ip" type="text" name="" value="{{ date('Y-m-d',$car_data->data) }}" disabled>
      </div>
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.SendDate') }}</label>
        <input id="entrega" class="ip" type="text" name="data_entrega" maxlength="10" value="@if(!empty($car_data->data_entrega)){{ date('Y-m-d',$car_data->data_entrega) }}@endif">
      </div>
      
    </div>
    

    <label class="lb">{{ trans('backoffice.Awards') }}</label>    
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
      @foreach($array as $val)
        <tbody>
          <td>{{ $val['id'] }}</td>
          <td><img style="border-radius:50%;margin-right:10px;" height="30" src="{{ $val['img'] }}"> {{ $val['nome_premio'] }}</td>
          <td>{{ $val['variante'] }}</td>
          <td>{{ $val['quantidade'] }}</td>
          <td>{{ $val['pontos_utilizados'] }}</td>
        </tbody>
      @endforeach
    </table>
    <br>
    
    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.DataBilling') }}</label>
        <div class="row">
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Name') }}</label>
            <input class="ip" type="text" name="nome_fact" value="{{ $query_car->nome_fact }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Nickname') }}</label>
            <input class="ip" type="text" name="nome_fact" value="{{ $query_car->apelido_fact }}" disabled>
          </div>
        </div>
        <label class="lb">{{ trans('backoffice.Email') }}</label>
        <input class="ip" type="text" name="email" value="{{ $query_car->email_fact }}" disabled>

        <div class="row">
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Contact') }}</label>
            <input class="ip" type="text" name="contact" value="{{ $query_car->contacto_fact}}" disabled>
          </div>
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.NIF') }}</label>
            <input class="ip" type="text" name="contact" value="{{ $query_car->nif_fact}}" disabled>
          </div>
        </div>

        <label class="lb">{{ trans('backoffice.Adress') }}</label>
        <input class="ip" type="text" name="morada" value="{{ $query_car->morada_fact }}" disabled>
        <input class="ip" type="text" name="morada" value="{{ $query_car->morada_opc_fact }}" disabled>

        <div class="row">
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.Postal_Code') }}</label>
            <input class="ip" type="text" name="code_postal" value="{{ $query_car->code_post_fact }}" disabled>
          </div>
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.City') }}</label>
            <input class="ip" type="text" name="city" value="{{ $query_car->cidade_fact }}" disabled>
          </div>
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.Country') }}</label>
            <input class="ip" type="text" name="pais" value="{{ $query_car->pais_fact }}" disabled>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.DeliveryData') }}</label>
        <div class="row">
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Name') }}</label>
            <input class="ip" type="text" name="nome_fact" value="{{ $query_car->nome_entrega }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Nickname') }}</label>
            <input class="ip" type="text" name="nome_fact" value="{{ $query_car->apelido_entrega }}" disabled>
          </div>
        </div>
        <label class="lb">{{ trans('backoffice.Email') }}</label>
        <input class="ip" type="text" name="email" value="{{ $query_car->email_entrega }}" disabled>

        <div class="row">
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.Contact') }}</label>
            <input class="ip" type="text" name="contact" value="{{ $query_car->contacto_entrega}}" disabled>
          </div>
          <div class="col-md-6">
            <label class="lb">{{ trans('backoffice.NIF') }}</label>
            <input class="ip" type="text" name="contact" value="{{ $query_car->nif_entrega}}" disabled>
          </div>
        </div>

        <label class="lb">{{ trans('backoffice.Adress') }}</label>
        <input class="ip" type="text" name="morada" value="{{ $query_car->morada_entrega }}" disabled>
        <input class="ip" type="text" name="morada" value="{{ $query_car->morada_opc_entrega }}" disabled>

        <div class="row">
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.Postal_Code') }}</label>
            <input class="ip" type="text" name="code_postal" value="{{ $query_car->code_post_entrega }}" disabled>
          </div>
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.City') }}</label>
            <input class="ip" type="text" name="city" value="{{ $query_car->cidade_entrega }}" disabled>
          </div>
          <div class="col-md-4">
            <label class="lb">{{ trans('backoffice.Country') }}</label>
            <input class="ip" type="text" name="pais" value="{{ $query_car->pais_entrega }}" disabled>
          </div>
        </div>
      </div>
    </div>

    <label class="lb">{{ trans('backoffice.Obs') }}</label>
    <textarea class="tx">{{ $query_car->nota }}</textarea>
    

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteAvatar') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarAvatar();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.css') }}">
@stop

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>
  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">
    $('.select2').select2();
  </script>
  <script>
    $('#entrega').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
  </script>
  <script type="text/javascript">
    
    $('#usersAwardsFormB').on('submit',function(e) {
      var form = $('#usersAwardsFormB');
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta) {
        //console.log(resposta);
        resp = $.parseJSON(resposta);
        if(resp.estado=='sucesso'){
          if(resp.reload && !resp.erro){ location.reload(); }
          else{
            $('#loading').hide();
            $('#botoes').show();
            if(resp.erro){
              $("#spanErro").html(resp.erro);
              $("#labelErros").show();
            }else{
              $("#labelSucesso").show();
            }            
          }
        }else
          if(resposta){
            $("#spanErro").html(resposta);
            $("#labelErros").show();
          }
      });
    });
  </script>
  <script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tinymce.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tm.tinymce.js') }}"></script>
@stop