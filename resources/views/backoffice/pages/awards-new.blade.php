@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allAwards') => route('awardsAllPageB'), trans('backoffice.newAward') => route('awardsNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allAwards') => route('awardsAllPageB'), trans('backoffice.editAward') => route('awardsEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newAward') }}@else{{ trans('backoffice.editAward') }}@endif</div>

  <form id="awardsFormB" method="POST" enctype="multipart/form-data" action="{{ route('awardsFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (PT)</label>
        <input class="ip" type="text" name="nome_pt" value="@if(isset($obj->nome_pt)){{ $obj->nome_pt }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (PT)</label>
        <textarea class="tx" name="descricao_pt" rows="3">@if(isset($obj->descricao_pt)){{ $obj->descricao_pt }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (EN)</label>
        <input class="ip" type="text" name="nome_en" value="@if(isset($obj->nome_en)){{ $obj->nome_en }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (EN)</label>
        <textarea class="tx" name="descricao_en" rows="3">@if(isset($obj->descricao_en)){{ $obj->descricao_en }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (ES)</label>
        <input class="ip" type="text" name="nome_es" value="@if(isset($obj->nome_es)){{ $obj->nome_es }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (ES)</label>
        <textarea class="tx" name="descricao_es" rows="3">@if(isset($obj->descricao_es)){{ $obj->descricao_es }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (FR)</label>
        <input class="ip" type="text" name="nome_fr" value="@if(isset($obj->nome_fr)){{ $obj->nome_fr }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (FR)</label>
        <textarea class="tx" name="descricao_fr" rows="3">@if(isset($obj->descricao_fr)){{ $obj->descricao_fr }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.PointsUser') }}</label>
        <input class="ip" type="text" name="valor_cliente" value="@if(isset($obj->valor_cliente)){{ $obj->valor_cliente }}@endif">
      </div>
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.PointsCompany') }}</label>
        <input class="ip" type="text" name="valor_empresa" value="@if(isset($obj->valor_empresa)){{ $obj->valor_empresa }}@endif">
        
      </div>
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.Stock') }}</label>
        <input class="ip" type="text" name="stock" value="@if(isset($obj->stock)){{ $obj->stock }}@endif">
      </div>
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.expirationDate') }}</label>
        <input class="ip" type="text" id="validade" name="data_validade" maxlength="10" value="@if(isset($obj->data_validade)){{ date('Y-m-d',$obj->data_validade) }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Image') }}</label>
        <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($obj->img)){{ $obj->img }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="imagem">
              @if(isset($obj->img) && $obj->img)<img src="{{ $obj->img }}" class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
            </div>
            <label for="selecao-arquivo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');" accept="image/*">
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('');"><i class="fas fa-trash-alt"></i></label>
        </div>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.PremiumAvailable') }}</label>
        <select class="select2" name="tipo">
          <option value="cliente" @if(isset($obj->tipo) && ($obj->tipo=='cliente')) selected @endif>{{ trans('backoffice.User') }}</option>
          <option value="empresa" @if(isset($obj->tipo) && ($obj->tipo=='empresa')) selected @endif>{{ trans('backoffice.Company') }}</option>
          <option value="ambos" @if(isset($obj->tipo) && ($obj->tipo=='ambos')) selected @endif>{{ trans('backoffice.Both') }}</option>
        </select>
      </div>
    </div>

    <label class="lb">{{ trans('backoffice.Variants') }}</label>
    @foreach($variante as $val)
      
      <input class="ip" type="text" name="cor" value="{{ $val->variante_pt }}" disabled>

      <div class="row">
        <div class="col-md-3">
          <div id="list_variant{{ $val->id }}_PT" class="margin-bottom20">
            @if(isset($variante_premio))
            @foreach($variante_premio as $premio)
              @if($premio->id_variante == $val->id)
                @if($premio->valor_pt) 
                  <div id="variante_pt_{{ $premio->id }}">
                    <label class="lb margin-right10">(PT)</label>
                    <div class="div-90">
                      <input class="ip" type="text" value="{{ $premio->valor_pt }}" disabled>
                    </div>
                  </div>
                @endif
              @endif
            @endforeach
            @endif
          </div>
        </div>
        <div class="col-md-3">
          <div id="list_variant{{ $val->id }}_EN" class="margin-bottom20">
            @if(isset($variante_premio))
            @foreach($variante_premio as $premio)
              @if($premio->id_variante == $val->id)  
                @if($premio->valor_en)
                <div id="variante_en_{{ $premio->id }}">
                  <label class="lb margin-right10">(EN)</label>
                  <div class="div-90">
                    <input class="ip" type="text" value="{{ $premio->valor_en }}" disabled>
                  </div>
                </div>
                @endif
              @endif
            @endforeach 
            @endif
          </div>
        </div>
        <div class="col-md-3">
          <div id="list_variant{{ $val->id }}_ES" class="margin-bottom20">
            @if(isset($variante_premio))
            @foreach($variante_premio as $premio)
              @if($premio->id_variante == $val->id)  
                @if($premio->valor_es)
                <div id="variante_es_{{ $premio->id }}">
                  <label class="lb margin-right10">(ES)</label>
                  <div class="div-90">
                    <input class="ip" type="text" value="{{ $premio->valor_es }}" disabled>
                  </div>
                </div>
                @endif
              @endif
            @endforeach
            @endif
          </div>
        </div>
        <div class="col-md-3">
          <div id="list_variant{{ $val->id }}_FR" class="margin-bottom20">
            @if(isset($variante_premio))
            @foreach($variante_premio as $premio)
              @if($premio->id_variante == $val->id)  
                @if($premio->valor_fr)
                  <div id="variante_fr_{{ $premio->id }}">
                    <label class="lb margin-right10">(FR)</label>
                    <div class="div-90">
                      <input class="ip" type="text" value="{{ $premio->valor_fr }}" disabled>
                    </div>
                    <label class="lb-40 bt-azul float-right" onclick="deleteVariant({{ $premio->id }});"><i class="fas fa-trash-alt"></i></label>
                  </div>
                @endif
              @endif
            @endforeach
            @endif
          </div>
        </div>
      </div>

      <label class="lb margin-right10">(PT)</label>
      <div class="div-90">
        <input id="input_variant{{ $val->id }}_PT" class="ip" type="text" name="variante_pt" placeholder="Introduza a variante" value="">
      </div>
      
      <br>
      <label class="lb margin-right10">(EN)</label>
      <div class="div-90">
        <input id="input_variant{{ $val->id }}_EN" class="ip" type="text" name="variante_en" placeholder="Introduza a variante" value="">
      </div>
      
      <br>
      <label class="lb margin-right10">(ES)</label>
      <div class="div-90">
        <input id="input_variant{{ $val->id }}_ES" class="ip" type="text" name="variante_es" placeholder="Introduza a variante" value="">
      </div>
      
      <br>
      <label class="lb margin-right10">(FR)</label>
      <div class="div-90">
        <input id="input_variant{{ $val->id }}_FR" class="ip" type="text" name="variante_fr" placeholder="Introduza a variante" value="">
      </div>
      <label class="lb-40 bt-azul float-right" onclick="addVariant({{ $val->id }},@if(isset( $obj->id)){{ $obj->id }}@else 0 @endif);"><i class="fas fa-plus"></i></label>

      <div class="margin-top20">
        <label id="labelErrosVariant{{ $val->id }}" class="av-100 av-vermelho display-none"><span id="spanErroVariant{{ $val->id }}"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </div>
        
    @endforeach

    <label class="lb">{{ trans('backoffice.Additional') }}</label>
    <div class="clearfix height-10"></div>
    <input type="checkbox" name="online" id="checkOn" value="1" @if(isset($obj->online) && ($obj->online)) checked @endif>
    <label for="checkOn"><span></span>{{ trans('backoffice.Online') }}</label>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('awardsAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('awardsAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

<script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>

<script>
  function addVariant(id,id_premio){
    var variante_pt=$('#input_variant'+id+'_PT').val();
    var variante_es=$('#input_variant'+id+'_ES').val();
    var variante_en=$('#input_variant'+id+'_EN').val();
    var variante_fr=$('#input_variant'+id+'_FR').val();

    if ((!variante_pt) || (!variante_es) || (!variante_en) || (!variante_fr)) {
      $('#labelErrosVariant'+id).css('display','block');
      $('#spanErroVariant'+id).html('{!! trans('backoffice.EnterVariantsLanguages_txt') !!}');
    }
    else{
      if(variante_pt){
        var html_pt='<div id="variante_pt_'+id_premio+'"><label class="lb margin-right10">(PT)</label><div class="div-90"><input name="variante_pt'+id+'[]" class="ip" type="text" value="'+variante_pt+'" readonly="readonly"></div></div>';
      }
      if(variante_es){
        var html_es='<div id="variante_es_'+id_premio+'"><label class="lb margin-right10">(ES)</label><div class="div-90"><input name="variante_es'+id+'[]" class="ip" type="text" value="'+variante_es+'" readonly="readonly"></div></div>';
      }
      if(variante_en){
        var html_en='<div id="variante_en_'+id_premio+'"><label class="lb margin-right10">(EN)</label><div class="div-90"><input name="variante_en'+id+'[]" class="ip" type="text" value="'+variante_en+'" readonly="readonly"></div></div>';
      }
      if(variante_fr){
        var html_fr='<div id="variante_fr_'+id_premio+'"><label class="lb margin-right10">(FR)</label><div class="div-90"><input name="variante_fr'+id+'[]" class="ip" type="text" value="'+variante_fr+'" readonly="readonly"></div><label id="label_'+id+'" class="lb-40 bt-azul float-right" onclick="deleteVariant('+id_premio+');"><i class="fas fa-trash-alt"></i></label></div>';
      }

      if (id_premio != 0) {
        $.ajax({
          type: "POST",
          url: '{{ route('awardsAddVariantB') }}',
          data: { id:id,id_premio:id_premio,variante_pt:variante_pt,variante_en:variante_en,variante_es:variante_es,variante_fr:variante_fr },
          headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
        })
        .done(function(resposta) {
          console.log(resposta);
            $('#list_variant'+id+'_PT').append(html_pt);
            $('#list_variant'+id+'_ES').append(html_es);
            $('#list_variant'+id+'_EN').append(html_en);
            $('#list_variant'+id+'_FR').append(html_fr);

            $('#input_variant'+id+'_FR').val('');  
            $('#input_variant'+id+'_EN').val('');  
            $('#input_variant'+id+'_ES').val('');  
            $('#input_variant'+id+'_PT').val('');  

            $('#label_'+id).attr('onclick','deleteVariant('+resposta+');');
            $('#variante_pt_'+id_premio).attr('id','variante_pt_'+resposta);
            $('#variante_en_'+id_premio).attr('id','variante_en_'+resposta);
            $('#variante_es_'+id_premio).attr('id','variante_es_'+resposta);
            $('#variante_fr_'+id_premio).attr('id','variante_fr_'+resposta);
        });
      }
      else{
        $('#list_variant'+id+'_PT').append(html_pt);
        $('#list_variant'+id+'_ES').append(html_es);
        $('#list_variant'+id+'_EN').append(html_en);
        $('#list_variant'+id+'_FR').append(html_fr);

        $('#input_variant'+id+'_FR').val('');  
        $('#input_variant'+id+'_EN').val('');  
        $('#input_variant'+id+'_ES').val('');  
        $('#input_variant'+id+'_PT').val('');
      }
    }
  }
</script>

<script>
  function deleteVariant(id){

    $.ajax({
      type: "POST",
      url: '{{ route('awardsDeleteVariantB') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      if(resposta=='sucesso'){
        $('#variante_pt_'+id).remove();
        $('#variante_en_'+id).remove();
        $('#variante_es_'+id).remove();
        $('#variante_fr_'+id).remove();
      }
    });
  }
</script>

<script type="text/javascript">
  $('#validade').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });  
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#fd_cor').css('background',$('#sel_cor').val());
  });
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
  }
  function limparFicheiros(id) {
    $('#selecao-arquivo'+id).val('');
    $('#uploads'+id).html('&nbsp;');
    $('#img_antiga'+id).val('');
    $('#imagem'+id).html('<label class="a-dotted-white" id="uploads'+id+'">&nbsp;</label>');
  }
  $('#awardsFormB').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      //var editorContent = tinyMCE.get('message').getContent();
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
        console.log(resposta);
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
          limparFicheiros('');
          if(resp.imagem){
            $('#img_antiga').val(resp.imagem);
            $('#imagem').html('<img src="'+resp.imagem+'" class="height-40 margin-top10">');
          }
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
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tm.tinymce.js') }}"></script>
@stop