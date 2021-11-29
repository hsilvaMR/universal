@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3"> @include('seller/includes/menu') </div>
      <div class="col-lg-9">
  
        <div class="mod-tit"><h3>Conversação</h3></div>

        <div id="mensagem" class="div-msg">
          @foreach($tickets_msg as $msg)
            <div class="row" style="padding:0px 20px;">
              <div class="div-msg-img">
                @if($msg->autor == 'cliente')
                  @if($msg->foto)<img src="\img\comerciantes\{{ $msg->foto }}">@else<img src="\img\comerciantes\default.svg">@endif
                @elseif($msg->autor == 'universal')
                  @if($msg->foto)<img src="\img\site\logotipo.svg">@else<img src="\img\empresas\default.svg">@endif
                @endif
              </div>
              <div class="div-msg-conteudo">
                <div class="div-msg-mensagem">
                  <label class="div-msg-mensagem-label">{{ $msg->mensagem }}</label>
                  <label class="font12 float-right">{{ date('Y-m-d H:m:s',$msg->data_msg) }}</label>
                </div>

                @if($msg->ficheiros != '[]')
                  <label class="margin-top10">{{ trans('seller.Attachments') }}</label>
                  <div class="div-msg-files">
                    @foreach($arrayFicheiros as $array)
                      @if($array['id_msg'] == $msg->id_msg)
                        <a href="/doc/support/{{ $array['ficheiro']->ficheiro }}" download>{{ $array['ficheiro']->ficheiro }}</a><br>
                      @endif
                    @endforeach
                  </div>
                @endif
              </div>
            </div>

            <div class="div-msg-line"></div>
          @endforeach
        </div>

        <div @if($ticket->estado == 'fechado') class="display-none" @endif>
          <div class="mod-tit"><h3>{{ trans('seller.New_Message') }}</h3></div>
          <form id="form-respTickect" enctype="multipart/form-data" action="{{ route('newTicketPostV2') }}" name="form" method="post">
            {{ csrf_field() }}
            <div class="mod-area">
              <input type="hidden" name="id_ticket" value="{{ $id_ticket }}">

              <label>{{ trans('seller.Message') }}</label>
              <textarea class="tx" name="mensagem" maxlength="1000"></textarea>

              <label>{{ trans('seller.Attachments') }}</label>
              <div id="ficheiro">
                <div class="div-50 float-left margin-bottom30">
                  <label class="ip data-ip tx-underline" id="uploads"></label>
                </div>
               
                <span id="upload_file">
                  <label for="arquivo" class="bt-40 bg-navy float-right line-height40">
                  <i class="fas fa-cloud-upload-alt"></i> </label>
                  <input id="arquivo" type="file" name="ficheiros[]" onchange="lerFicheiros(this,'uploads');" multiple>
                </span>
              </div>

              <div class="tx-right">
                <a href="{{ route('supportV2') }}"><label class="bt margin-right10 tx-gray"><i class="fas fa-times"></i> {{ trans('seller.Cancel_Ticket') }}</label></a>
                <button class="bt-blue"><i class="fas fa-check"></i> {{ trans('seller.Send_Ticket') }}</button>
              </div>

              <label id="labelSucesso" class="av-100 alert-success display-none" role="alert">
                <span id="spanSucesso">{{ trans('seller.savedSuccessfully') }}</span>
                <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
              <label id="labelErros" class="av-100 alert-danger display-none" role="alert">
                <span id="spanErro"></span>
                <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
            </div>
          </form>
        </div>
      </div>
    </div>  
  </div>  
@stop

@section('css')
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script>
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('profile.selectedFiles') }}');}
  };
</script>

<script>
  $('#form-respTickect').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();
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
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          $("#labelErros").show();
          //$('#botoes').show();
          return;
      }
  
      if(resp.estado == 'sucesso'){
        $('#mensagem').append(resp.resposta_html);
        document.getElementById("form-respTickect").reset();
        $('#uploads').html('');
      }
    });
  });
</script>
@stop