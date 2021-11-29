@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'),$process->referencia => route('processesPageB',$process->id_certificacao), $acti->referencia => route('activitiesPageB',$acti->id_processo), trans('backoffice.allTasks') => route('tasksPageB',$id_acti), trans('backoffice.newTask') => route('tasksNewPageB',$id_acti) ]; ?>@else
  <?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'),$process->referencia => route('processesPageB',$process->id_certificacao), $acti->referencia => route('activitiesPageB',$acti->id_processo), trans('backoffice.allTasks') => route('tasksPageB',$id_acti), trans('backoffice.editTask') => route('tasksEditPageB',['id_acti'=>$id_acti,'id'=>$dados->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newTask') }}@else{{ trans('backoffice.editTask') }}@endif</div>

  <form id="form" method="POST" enctype="multipart/form-data" action="{{ route('tasksFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_atividade" value="@if(isset($id_acti)){{ $id_acti }}@endif">
    <input type="hidden" id="id" name="id" value="@if(isset($dados->id)){{ $dados->id }}@endif">
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Task') }}</label>
        <input class="ip" type="text" name="tarefa" value="@if(isset($dados->tarefa)){{ $dados->tarefa }}@endif">
      </div>
    </div>


    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Responsible') }}</label>
        <div id="responsavel">
          @if(isset($responsavel))
            @foreach ($responsavel as $resp)
              <div>
                <div class="div-50">
                  <input type="hidden" name="resp[]" value="@if($resp->id_identificacao) {{$resp->id_identificacao}} @else {{$resp->nome}} @endif">
                  <input class="ip" type="text" name="resp{{$resp->id}}" value="@if($resp->id_identificacao) {{$resp->sigla.' - '.$resp->descricao}} @else {{$resp->nome}} @endif" disabled>
                </div>
                <button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="div-50"><input id="nomeResp" class="ip" type="text" name="nomeResp" value="" placeholder="{!! trans('backoffice.Name') !!}"></div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarIdentificacao('responsavel','nomeResp');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
      <div class="col-lg-6">
        <div class="div-50">          
          <select class="select2" id="siglaResp" name="siglaResp">
            <option value="" selected>&nbsp;</option>
            @foreach($identificacoes as $value)
              <option value="{{ $value->id }}">{{ $value->sigla.' - '.$value->descricao }}</option>
            @endforeach
          </select>
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarIdentificacao('responsavel','siglaResp');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Send') }}</label>
        <div id="enviar">
          @if(isset($enviar))
            @foreach ($enviar as $env)
              <div>
                <div class="div-50">
                  <input type="hidden" name="env[]" value="@if($env->id_identificacao) {{$env->id_identificacao}} @else {{$env->nome}} @endif">
                  <input class="ip" type="text" name="env{{$env->id}}" value="@if($env->id_identificacao) {{$env->sigla.' - '.$env->descricao}} @else {{$env->nome}} @endif" disabled>
                </div>
                <button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="div-50"><input id="nomeEnv" class="ip" type="text" name="nomeEnv" value="" placeholder="{!! trans('backoffice.Name') !!}"></div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarIdentificacao('enviar','nomeEnv');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
      <div class="col-lg-6">
        <div class="div-50">          
          <select class="select2" id="siglaEnv" name="siglaEnv">
            <option value="" selected>&nbsp;</option>
            @foreach($identificacoes as $value)
              <option value="{{ $value->id }}">{{ $value->sigla.' - '.$value->descricao }}</option>
            @endforeach
          </select>
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarIdentificacao('enviar','siglaEnv');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>



    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Input') }}</label>
        <div id="entrada">
          @if(isset($entrada))
            @foreach ($entrada as $ent)
              <div>
                <div class="div-50">
                  <input type="hidden" name="entTipo[]" value="@if($ent->id_documento) doc @elseif($ent->url) url @else txt @endif">
                  <input type="hidden" name="entTexto[]" value="{{$ent->nome}}">
                  <input type="hidden" name="entDocumento[]" value="{{$ent->id_documento}}">
                  <input type="hidden" name="entUrl[]" value="{{$ent->url}}">
                  <input class="ip" type="text" name="ent{{$ent->id}}" value="@if($ent->id_documento) {{$ent->nome.' '.$ent->referencia.' ('.str_replace("/backoffice/gestao_documental/doc/", "", $ent->ficheiro).')'}} @elseif($ent->url) {{$ent->nome.' ('.$ent->url.')'}} @else {{$ent->nome}} @endif" disabled>
                </div>
                <button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="div-50">
          <div class="row">
            <div class="col-lg-4">
              <input id="docTxtEnt" class="ip" type="text" name="docTxtEnt" value="" placeholder="{!! trans('backoffice.Text') !!}">
            </div>
            <div class="col-lg-8">
              <select class="select2" id="docEnt" name="docEnt">
                <option value="" selected>&nbsp;</option>
                @foreach($documentos as $value)
                  <option value="{{ $value->id }}">{{$value->referencia.' ('.str_replace("/backoffice/gestao_documental/doc/", "", $value->ficheiro).')'}}</option>
                @endforeach
              </select>
            </div>
          </div>  
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarDocumento('entrada','docTxtEnt','docEnt');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
      <div class="col-lg-6">
        <div class="div-50">
          <div class="row">
            <div class="col-lg-4">
              <input id="urlTxtEnt" class="ip" type="text" name="urlTxtEnt" value="" placeholder="{!! trans('backoffice.Text') !!}">
            </div>
            <div class="col-lg-8">
              <input id="urlEnt" class="ip" type="text" name="urlEnt" value="" placeholder="{!! trans('backoffice.Link') !!}">
            </div>
          </div>  
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarDocumento('entrada','urlTxtEnt','urlEnt');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Output') }}</label>
        <div id="saida">
          @if(isset($saida))
            @foreach ($saida as $sai)
              <div>
                <div class="div-50">
                  <input type="hidden" name="saiTipo[]" value="@if($sai->id_documento) doc @elseif($sai->url) url @else txt @endif">
                  <input type="hidden" name="saiTexto[]" value="{{$sai->nome}}">
                  <input type="hidden" name="saiDocumento[]" value="{{$sai->id_documento}}">
                  <input type="hidden" name="saiUrl[]" value="{{$sai->url}}">
                  <input class="ip" type="text" name="sai{{$sai->id}}" value="@if($sai->id_documento) {{$sai->nome.' '.$sai->referencia.' ('.str_replace("/backoffice/gestao_documental/doc/", "", $sai->ficheiro).')'}} @elseif($sai->url) {{$sai->nome.' ('.$sai->url.')'}} @else {{$sai->nome}} @endif" disabled>
                </div>
                <button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="div-50">
          <div class="row">
            <div class="col-lg-4">
              <input id="docTxtSai" class="ip" type="text" name="docTxtSai" value="" placeholder="{!! trans('backoffice.Text') !!}">
            </div>
            <div class="col-lg-8">
              <select class="select2" id="docSai" name="docSai">
                <option value="" selected>&nbsp;</option>
                @foreach($documentos as $value)
                  <option value="{{ $value->id }}">{{$value->referencia.' ('.str_replace("/backoffice/gestao_documental/doc/", "", $value->ficheiro).')'}}</option>
                @endforeach
              </select>
            </div>
          </div>  
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarDocumento('saida','docTxtSai','docSai');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
      <div class="col-lg-6">
        <div class="div-50">
          <div class="row">
            <div class="col-lg-4">
              <input id="urlTxtSai" class="ip" type="text" name="urlTxtSai" value="" placeholder="{!! trans('backoffice.Text') !!}">
            </div>
            <div class="col-lg-8">
              <input id="urlSai" class="ip" type="text" name="urlSai" value="" placeholder="{!! trans('backoffice.Link') !!}">
            </div>
          </div>  
        </div>
        <button class="bt-40 bt-azul float-right" type="button" onclick="adicionarDocumento('saida','urlTxtSai','urlSai');"><i class="fas fa-plus" aria-hidden="true"></i></button>
      </div>
    </div>




    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="online" id="checkOn" value="1" @if(isset($dados->online) && ($dados->online)) checked @endif>
        <label for="checkOn"><span></span>{{ trans('backoffice.Online') }}</label>
      </div>
    </div>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('tasksPageB',$id_acti) }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>    
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('tasksPageB',$id_acti) }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

<script type="text/javascript">
function adicionarIdentificacao(id_tipo,id){
  var valor = $('#'+id).val();
  if(valor){
    if(id_tipo=='responsavel'){
      if(id=='nomeResp'){ var texto=valor; }else{ var texto=$('#'+id).find(":selected").text(); }
      $('#'+id_tipo).append('<div><div class="div-50"><input type="hidden" name="resp[]" value="'+valor+'"><input class="ip" type="text" name="res" value="'+texto+'" disabled></div><button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button></div>');
    }else{
      if(id=='nomeEnv'){ var texto=valor; }else{ var texto=$('#'+id).find(":selected").text(); }
      $('#'+id_tipo).append('<div><div class="div-50"><input type="hidden" name="env[]" value="'+valor+'"><input class="ip" type="text" name="en" value="'+texto+'" disabled></div><button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button></div>');
    }
    $('#'+id).val('');
    $('#'+id).trigger('change');
  }
}
function adicionarDocumento(id_tipo,id_nome,id){
  var nome = $('#'+id_nome).val();
  var valor = $('#'+id).val();
  if(nome || valor){
    if(id_tipo=='entrada'){
      var tipo='txt';
      var texto=nome;
      if(id=='urlEnt' && valor){
        tipo='url';
        if(!nome){ nome='Link'; }
        texto=nome+' ('+valor+')';
      }
      if(id=='docEnt' && valor){
        tipo='doc';
        texto=nome+' '+$('#'+id).find(":selected").text();
      }
      $('#'+id_tipo).append('<div><div class="div-50"><input type="hidden" name="entTipo[]" value="'+tipo+'"><input type="hidden" name="entTexto[]" value="'+nome+'"><input type="hidden" name="entDocumento[]" value="'+valor+'"><input type="hidden" name="entUrl[]" value="'+valor+'"><input class="ip" type="text" name="en" value="'+texto+'" disabled></div><button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button></div>');
    }else{
      var tipo='txt';
      var texto=nome;
      if(id=='urlSai' && valor){
        tipo='url';
        if(!nome){ nome='Link'; }
        texto=nome+' ('+valor+')';
      }
      if(id=='docSai' && valor){
        tipo='doc';
        texto=nome+' '+$('#'+id).find(":selected").text();
      }
      $('#'+id_tipo).append('<div><div class="div-50"><input type="hidden" name="saiTipo[]" value="'+tipo+'"><input type="hidden" name="saiTexto[]" value="'+nome+'"><input type="hidden" name="saiDocumento[]" value="'+valor+'"><input type="hidden" name="saiUrl[]" value="'+valor+'"><input class="ip" type="text" name="en" value="'+texto+'" disabled></div><button class="bt-40 bt-azul float-right" type="button" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i></button></div>');
    }
    $('#'+id_nome).val('');
    $('#'+id).val('');
    $('#'+id).trigger('change');
  }
}

$('#form').on('submit',function(e) {
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
    .done(function(resposta){
      //console.log(resposta);
      try{ resp=$.parseJSON(resposta); }
      catch (e){            
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
          return;
      }
      if(resp.estado=='sucesso'){
        //$('#myModalSave').modal('show');
        $('#id').val(resp.id);
        $('#loading').hide();
        $('#botoes').show();
        $("#labelSucesso").show();
      }else if(resposta){
        $("#spanErro").html(resposta);
        $("#labelErros").show();
        $('#loading').hide();
        $('#botoes').show();
      }
    });
});
</script>
@stop