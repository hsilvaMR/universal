@extends('backoffice/layouts/default')

@section('content')
@if($funcao=='addComuni')
<?php $arrayCrumbs = [ 'Comunicacao' => route('mainPageComun'), 'Novo Item' => route('comunAdd') ]; ?>@else
<?php $arrayCrumbs = [ 'Comunicacao' => route('mainPageComun'), 'Editar Item' => route('comunEdit',['id'=>$obj->id]) ]; ?>@endif
@include('backoffice/includes/crumbs')

<div class="page-titulo">
    @if($funcao=='addComuni')Novo Item @else Editar Item @endif
</div>

{{--  form  --}}
<form id="@if($funcao=='addComuni'){{'addComunication'}}@else {{'editComunication'}} @endif" method="POST"
    enctype="multipart/form-data"
    action="@if($funcao=='addComuni'){{ route('comunAdd_DB') }}  @else {{ route('comunEdit_DB') }} @endif">
    {{-- <form id="addComunication" method="POST" enctype="multipart/form-data" action="{{ route('comunAdd_DB') }}">
    --}}
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
        {{--  Nome --}}
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.comuniFrName') }}</label>
            <input class="ip" type="text" name="nome" value="@if(isset($obj->nome)){{ $obj->nome }}@endif">
        </div>
        {{--  Descricao --}}
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.comuniFrDesc') }}</label>
            <input class="ip" type="text" name="descricao"
                value="@if(isset($obj->descricao)){{ $obj->descricao }}@endif">
        </div>
    </div>
    <div class="row">
        {{--  tipo --}}
        <div class="col-lg-6">
            <label class="lb">{{ trans('backoffice.comuniFrType') }}</label>
            <select class="select2" name="tipo">
                <option value="" selected disabled></option>
                <option value="Rotulo" @if(isset($obj->tipo)&& $obj->tipo=='Rotulo') selected
                    @endif>{{ trans('backoffice.comuniFrTypeR') }}</option>
                <option value="Image" @if(isset($obj->tipo)&& $obj->tipo=='Image') selected @endif
                    >{{ trans('backoffice.comuniFrTypeImg') }}</option>
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
                    <label class="a-dotted-white" name="uploads_xs" id="uploads_xs">&nbsp; @if(isset($obj->file) &&
                        $obj->file)
                        {{ $obj->file}} @endif</label>
                    <input type="hidden" name="fileName"
                        value="@if(isset($obj->file) && $obj->file) {{ $obj->file}} @endif">
                    @endif
                </div>
                <label for="selecao-arquivo_xs" class="lb-40 bt-azul float-right">
                    <i class="fas fa-upload"></i>
                </label>
                <input id="selecao-arquivo_xs" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads_xs');"
                    accept="image/*">
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_xs');"><i
                    class="fas fa-trash-alt"></i></label>
        </div>
    </div>

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
                <a href="{{ route('mainPageComun') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i>
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
    $(document).ready(function(){
    $('#fd_cor').css('background',$('#sel_cor').val());
  });
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){
        
        $('#'+id).html(nome);
        //alert(" file ok ")
    }
    else{
        $('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');
         alert(" file empty")
    }
  }
  function limparFicheiros(id) {
    $('#selecao-arquivo'+id).val('');
    $('#uploads'+id).html('&nbsp;');
    $('#img_antiga'+id).val('');
    $('#imagem'+id).html('<label class="a-dotted-white" id="uploads'+id+'">&nbsp;</label>');
  }
  
  // form add ajax 
  $('#addComunication').on('submit',function(e) {
    
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

        resp=$.parseJSON(data);
          
        if(resp.success=='success'){
            
            //console.log( resposta )
         // $('#addComunication')[0].reset();
            $("#addComunication").trigger("reset");
              $("#uploads_xs").empty();
          $('#myModalSave').modal('show');
       
        }
        else {
            
        alert(resp.error);
        console.log(resp.success)
        }
       }
      })
      
    });

 // form  update  ajax 
    $('#editComunication').on('submit',function(e) {
    
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

      resp=$.parseJSON(data);
        
      if(resp.success=='success'){
          
          //console.log( resposta )
       // $('#addComunication')[0].reset();
         $("#addComunication").trigger("reset");
         $("#uploads_xs").empty();
        $('#myModalSave').modal('show');
     
      }
      else {
          
      alert(resp.error);
      console.log(resp.success)
      }
     }
    })
    
  });
   
</script>
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tm.tinymce.js') }}"></script>
@stop