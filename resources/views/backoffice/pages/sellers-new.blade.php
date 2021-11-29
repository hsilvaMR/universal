@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allSellers') => route('sellersPageB'), trans('backoffice.newSeller') => route('sellersNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allSellers') => route('sellersPageB'), trans('backoffice.editSeller') => route('sellersEditPageB',['id'=>$dados['id']]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newSeller') }}@else{{ trans('backoffice.editSeller') }}@endif</div>
  <form id="sellersUploadPhotoFormB" method="POST" enctype="multipart/form-data" action="{{ route('sellersUploadPhotoFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
    <div class="row text-center">
      <div class="col-lg-12">
        <div id="photoProfile" class="conta-avatar" @if(isset($dados['foto']) && $dados['foto']) style="background-image:url(/img/comerciantes/{{ $dados['foto'] }});" @endif></div>
        <div class="clearfix"></div>
        <div id="botoesImg">
          <div class="conta-col-esq">
            <label for="selecao-arquivo" class="lb-40 bt-azul"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" accept="image/*" name="ficheiro" onchange="$(this).submit();">
          </div>
          <div class="conta-col-dir">
            <button class="bt-40 bt-azul" type="button" data-toggle="modal" data-target="#myModalDelete"><i class="fas fa-trash-alt"></i></button>
          </div>
        </div>
        <div id="loadingImg" class="loading-center"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
        <div class="clearfix height-10"></div>
        <label id="labelErrosImg" class="av-100 av-vermelho display-none"><span id="spanErroImg"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </div>
    </div>    
  </form>

  <div class="tabs">
    <div id="TAB1" class="tab tab-active" onClick="mudarTab(1);"><span class="visible-xs">{{ trans('backoffice.AI') }}</span><span class="hidden-xs">{{ trans('backoffice.AccountInformation') }}</span></div>
    <div id="TAB2" class="tab" onClick="mudarTab(2);"><span class="visible-xs">{{ trans('backoffice.PI') }}</span><span class="hidden-xs">{{ trans('backoffice.PersonalInformation') }}</span></div>
    <div id="TAB3" class="tab" onClick="mudarTab(3);"><span class="visible-xs">{{ trans('backoffice.C') }}</span><span class="hidden-xs">{{ trans('backoffice.Company') }}</span></div>
  </div>

  <div id="INF1">
    <form id="sellersInfoAccountFormB" method="POST" action="{{ route('sellersInfoAccountFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="name" value="@if(isset($dados['nome'])){{ $dados['nome'] }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Token') }}</label>
          <input class="ip" type="text" name="token" value="@if(isset($dados['token'])){{ $dados['token'] }}@endif" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Company') }}</label>
          <input class="ip" type="text" name="nome_empresa" value="@if(isset($dados['nome_empresa'])){{ $dados["nome_empresa"] }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Lang') }}</label>
          <select class="select2" name="lingua" disabled>
            <option value="en" @if(isset($dados['lingua']) && $dados['lingua']=='en') selected @endif>{{ trans('backoffice.English') }}</option>
            <option value="pt" @if(isset($dados['lingua']) && $dados['lingua']=='pt') selected @endif>{{ trans('backoffice.Portuguese') }}</option>
            <option value="es" @if(isset($dados['lingua']) && $dados['lingua']=='es') selected @endif>{{ trans('backoffice.Spanish') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Lastacess') }}</label>
          <input class="ip" type="text" name="ultimo_acesso" value="@if(isset($dados['ultimo_acesso'])){{ date('Y-m-d H:i',$dados["ultimo_acesso"]) }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.dateOfRegistration') }}</label>
          <input class="ip" type="text" name="data_registo" value="@if(isset($dados['data_registo'])){{ date('Y-m-d H:i',$dados["data_registo"]) }}@endif" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Comments') }}</label>
          <textarea class="tx" name="obs" rows="3">@if(isset($dados['obs'])){{ $dados['obs'] }}@endif</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Status') }}</label>
          <select class="select2" name="estado">
            <option value="ativo" @if(isset($dados['estado']) && $dados['estado']=='ativo') selected @endif>{{ trans('backoffice.Active') }}</option>
            <option value="pendente" @if(isset($dados['estado']) && $dados['estado']=='pendente') selected @endif>{{ trans('backoffice.Pending') }}</option>
          </select>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Additional') }}</label>
          <div class="clearfix height-10"></div>
          <input type="checkbox" name="newsletter" id="newsletter" value="1" @if(isset($dados['newsletter']) && $dados['newsletter']) checked @endif>
          <label for="newsletter"><span></span>{{ trans('backoffice.Newsletter') }}</label>
        </div>
      </div>

      <div class="clearfix height-20"></div>
      <div id="botoes">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>


  <div id="INF2" class="display-none">
    <form id="sellersInfoPersonalFormB" method="POST" enctype="multipart/form-data" action="{{ route('sellersInfoPersonalFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <input type="hidden" name="id_empresa" value="@if(isset($dados['id_empresa'])){{ $dados['id_empresa'] }}@endif">
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome" value="@if(isset($dados['nome'])){{ $dados['nome'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Phone') }}</label>
          <input class="ip" type="text" name="telefone" value="@if(isset($dados['telefone'])){{ $dados['telefone'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Email') }}</label>
          <input class="ip" type="text" name="email" value="@if(isset($dados['email'])){{ $dados['email'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Changeemail') }}</label>
          <input class="ip" type="text" name="email_alteracao" value="@if(isset($dados['email_alteracao'])){{ $dados['email_alteracao'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.File') }}</label>
          <input type="hidden" id="ficheiro_antigo" name="ficheiro_antigo" value="@if(isset($dados['ficheiro'])){{ $dados['ficheiro'] }}@endif">
          <div>
            <div class="div-50">
              <div class="div-50" id="ficheiro">
                @if(isset($dados['ficheiro']) && $dados['ficheiro'])<a href="/doc/companies/{{ $dados['ficheiro'] }}" target="_blank" class="a-dotted-white" download>{{ $dados['ficheiro'] }}</a>@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
              </div>
              <label for="selecao-ficheiro" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
              <input id="selecao-ficheiro" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');">
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros();"><i class="fa fa-trash-alt"></i></label>          
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Type') }}</label>
          <select class="select2" name="tipo">
            <option value="gestor" @if(isset($dados['tipo']) && $dados['tipo']=='gestor') selected @endif>{{ trans('backoffice.Manager') }}</option>
            <option value="comerciante" @if(isset($dados['tipo']) && $dados['tipo']=='comerciante') selected @endif>{{ trans('backoffice.Seller') }}</option>
            <option value="admin" @if(isset($dados['tipo']) && $dados['tipo']=='admin') selected @endif>{{ trans('backoffice.Administrator') }}</option>
          </select>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Status') }}</label>
          <input type="hidden" name="aprovacao_antiga" value="@if(isset($dados['aprovacao'])){{ $dados['aprovacao'] }}@endif">
          <select class="select2" name="aprovacao">
            <option value="" @if(isset($dados['aprovacao']) && $dados['aprovacao']=='') selected @endif>&nbsp;</option>
            <option value="em_aprovacao" @if(isset($dados['aprovacao']) && $dados['aprovacao']=='em_aprovacao') selected @endif>{{ trans('backoffice.OnApproval') }}</option>
            <option value="aprovado" @if(isset($dados['aprovacao']) && $dados['aprovacao']=='aprovado') selected @endif>{{ trans('backoffice.Approved') }}</option>
            <option value="reprovado" @if(isset($dados['aprovacao']) && $dados['aprovacao']=='reprovado') selected @endif>{{ trans('backoffice.Disapproved') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.NoteSellTx') }}</label>
          <textarea class="tx" name="nota" rows="3">@if(isset($dados['nota'])){{ $dados['nota'] }}@endif</textarea>
        </div>
      </div>

      <div class="clearfix height-20"></div>
      <div id="botoes2">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading2" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso2" class="av-100 av-verde display-none"><span id="spanSucesso2">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros2" class="av-100 av-vermelho display-none"><span id="spanErro2"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>

  <div id="INF3" class="display-none">
    <form id="sellersCompanyFormB" method="POST" action="{{ route('sellersCompanyFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome_empresa" value="@if(isset($dados['nome_empresa'])){{ '#'.$dados['id_empresa'].' '.$dados['nome_empresa'] }}@endif" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Addresses') }}</label>
          <div class="clearfix height-10"></div>
          @foreach($compras as $val)
            <input type="checkbox" name="addresses[]" id="checkC{{ $val['id'] }}" value="{{ $val['id'] }}" @if($val['check']) checked @endif>
            <label for="checkC{{ $val['id'] }}"><span></span>{{ '#'.$val['id'].' '.$val['nome'] }}</label>
            <label class="width-30"></label>
          @endforeach
        </div>
      </div>

      <div class="clearfix height-20"></div>
      <div id="botoes3">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading3" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso3" class="av-100 av-verde display-none"><span id="spanSucesso3">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros3" class="av-100 av-vermelho display-none"><span id="spanErro3"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>


  <!-- Modal Delete Avatar -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteAvatar') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarFoto();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('sellersPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" onclick="location.reload();"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
  <script type="text/javascript">
  function mudarTab(numero) {
    var tabs = $('.tabs').find('.tab').length;
    for (var i=tabs; i>0; i--) {
      if(i==numero){
        $("#TAB"+i).addClass("tab-active");
        $("#INF"+i).css("display","block");
      }
      else{
        $("#TAB"+i).removeClass("tab-active");
        $("#INF"+i).css("display","none");
      }
    }
  }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
  <script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">
    $('.datePicker').datepicker({
      format:'dd-mm-yyyy',
      //viewMode:2,
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
  </script>
  <script type="text/javascript">
    function lerFicheiros(input,id) {
      var quantidade = input.files.length;
      var nome = input.value;
      if(quantidade==1){$('#'+id).html(nome);}
      else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
    }
    function limparFicheiros() {
      $('#selecao-ficheiro').val('');
      $('#uploads').html('&nbsp;');
      $('#ficheiro_antigo').val('');
      $('#ficheiro').html('<label class="a-dotted-white" id="uploads">&nbsp;</label>');
    }

    $('#sellersUploadPhotoFormB').on('submit',function(e) {
      $("#labelErrosImg").hide();
      $('#loadingImg').show();
      $('#botoesImg').hide();
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
        //console.log(resposta);
        $('#selecao-arquivo').val('');
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            $("#spanErroImg").html(resposta);
            $("#labelErrosImg").show();
            return;
        }
        if(resp.estado=='sucesso'){
          $('#photoProfile').css("background-image","url(/img/comerciantes/"+resp.foto+")");
          $('#loadingImg').hide();
          $('#botoesImg').show();
        }else if(resp.estado=='erro'){
          $("#spanErroImg").html(resp.erro);
          $("#labelErrosImg").show();
        }else{
          $("#spanErroImg").html(resposta);
          $("#labelErrosImg").show();
        }
      });
    });

    function apagarFoto(){
      var id = {{ $dados['id'] }};
      $.ajax({
        type: "POST",
        url: '{{ route('sellersDeletePhotoFormB') }}',
        data: { id,id },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        if(resposta=='sucesso'){
          $('#photoProfile').css('background-image','url({{ asset('/backoffice/img/icons/user-default-1.svg') }})');
        }else{
          $("#spanErroImg").html(resposta);
          $("#labelErrosImg").show();
        }
      });
    }

    $('#sellersInfoAccountFormB').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta) {
        if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
        else{
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }
      });
    });

    $('#sellersInfoPersonalFormB').on('submit',function(e) {
      $("#labelSucesso2").hide();
      $("#labelErros2").hide();
      $('#loading2').show();
      $('#botoes2').hide();
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
        //$('#myModalSave').modal('show');
        //console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){            
            if(resposta){
              $("#spanErro2").html(resposta);
              $("#labelErros2").show();
            }
            $('#loading2').hide();
            $('#botoes2').show();
            return;
        }
        if(resp.estado=='sucesso'){
          //$('#id').val(resp.id);
          limparFicheiros();
          if(resp.ficheiro){
            $('#ficheiro_antigo').val(resp.ficheiro);
            $('#ficheiro').html('<a href="/doc/companies/'+resp.ficheiro+'" target="_blank" class="a-dotted-white" download>'+resp.ficheiro+'</a>');
          }
          $('#loading2').hide();
          $('#botoes2').show();
          $("#labelSucesso2").show();
        }else if(resp.estado=='erro'){
          $("#spanErro2").html(resp.mensagem);
          $("#labelErros2").show();
          $('#loading2').hide();
          $('#botoes2').show();
        }
      });
    });
    $('#sellersCompanyFormB').on('submit',function(e) {
      $("#labelSucesso3").hide();
      $("#labelErros3").hide();
      $("#loading3").show();
      $("#botoes3").hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta){
        //alert(resposta);
        if(resposta=='sucesso'){
          $("#loading3").hide();
          $("#botoes3").show();
          $("#labelSucesso3").show();
        }
      });
    });
  </script>
@stop