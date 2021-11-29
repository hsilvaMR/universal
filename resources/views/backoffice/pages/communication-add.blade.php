@extends('backoffice/layouts/default')

@section('content')
@if($funcao=='addComuni')
<?php $arrayCrumbs = [ 'Comunicacao' => route('mainPageComun'), 'Novo Item' => route('comunAdd') ]; ?>@else
<?php $arrayCrumbs = [ 'Códigos Promocionais' => route('cookingPageB'), 'Editar Item' => route('editCodecookingPageB',['id'=>$codigo->id]) ]; ?>@endif
@include('backoffice/includes/crumbs')

<div class="page-titulo">
    @if($funcao=='addComuni')Novo Item @else Editar Código @endif
</div>

{{--  form  --}}
<form id="addComunication" method="POST" enctype="multipart/form-data" action="{{ route('comunAdd_DB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($codigo->id)){{ $codigo->id }}@endif">
    <div class="row">
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.Code') }}</label>
            <input class="ip" type="text" name="codigo" value="@if(isset($codigo->codigo)){{ $codigo->codigo }}@endif">
        </div>
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.Description') }}</label>
            <input class="ip" type="text" name="descricao"
                value="@if(isset($codigo->descricao)){{ $codigo->descricao }}@endif">
        </div>
    </div>
    <div class="row">
        {{--  tipo --}}
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.comuniFrType') }}</label>
            <select class="select2" name="type">
                <option value="" selected disabled></option>
                <option value="disponivel" @if(isset($obj->estado) && $obj->estado=='disponivel') selected
                    @endif>{{ trans('backoffice.Available') }}</option>
                <option value="indisponivel" @if(isset($obj->estado) && $obj->estado=='indisponivel') selected
                    @endif>{{ trans('backoffice.Unavailable') }}</option>
                <option value="codigo" @if(isset($obj->estado) && $obj->estado=='codigo') selected
                    @endif>{{ trans('backoffice.Code') }}</option>
            </select>
        </div>
        {{--  Ficheiro --}}
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.comuniFrFile') }}</label>
            <div class="div-50">
                <div class="div-50" id="imagem_xs">
                    @if(isset($obj->img_xs) && $obj->img_xs)
                    <img src="{{ $obj->img_xs }}" class="height-40 margin-top10">
                    @else
                    <label class="a-dotted-white" id="uploads_xs">&nbsp;</label>
                    @endif
                </div>
                {{-- icone upload --}}
                <label for="selecao-arquivo_xs" class="lb-40 bt-azul float-right">
                    <i class="fas fa-upload"></i>
                </label>

                <input id="selecao-arquivo_xs" type="file" name="ficheiro_xs"
                    onchange="lerFicheiros(this,'uploads_xs');" accept="image/*">

            </div>
            {{-- icone delete img  --}}
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_xs');"><i class="fas fa-trash-alt"></i>
            </label>
        </div>
    </div>
    {{--  BTN  --}}
    <div class="clearfix height-20"></div>
    <div id="botoes">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i>
            {{ trans('backoffice.Save') }}</button>
        <label class="width-10 height-40 float-right"></label>
        <a href="{{ route('adminAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i>
            {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none">
        <span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span>
        <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
    </label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times"
            onclick="$(this).parent().hide();"></i></label>
</form>

{{-- modal  --}}
<div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4>
            </div>
            <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
            <div class="modal-footer">
                <a href="{{ route('cookingPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i>
                    {{ trans('backoffice.Back') }}</a>&nbsp;
                <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i>
                    {{ trans('backoffice.Ok') }}</a>
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

<script type="text/javascript">
    function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){
        $('#'+id).html(nome);
    }
    else{
        $('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');
    }
  }

//   add data ajax 
$('#addComunication').on('submit',function(e) {
     /* $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();*/
      //var editorContent = tinyMCE.get('message').getContent();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false,
        error: function (xhr,status, error) {
            //alert(ajaxContext.responseText)
          console.log( xhr.status)
          console.log( xhr.statusText )
          console.log( xhr.readyState )
          console.log( xhr.responseText )
           
       },
       success: function(data) {
          
        if(resposta=='success'){

        alert(resposta);
        console.log( resposta )
        }
        else {
        alert(resposta);
        console.log( resposta )
}
       }
      })
     /* .done(function(resposta){
        //console.log(resposta);
        /*try{ resp=$.parseJSON(resposta); }
        catch (e){            
            if(resposta){ $("#spanErro").html(resposta); }
            else{ $("#spanErro").html('ERROR'); }
            $("#labelErros").show();
            $('#loading').hide();
            $('#botoes').show();
            return;
            if(resp.estado=='sucess'){
        }

        if(resposta=='success'){

            alert(resposta);
            console.log( resposta )
        }
        else {
            alert(resposta);
            console.log( resposta )
        }
      });*/
    });
</script>

@stop