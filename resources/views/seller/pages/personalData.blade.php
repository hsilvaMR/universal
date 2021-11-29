@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3">
          @include('seller/includes/menu')
      </div>
      <div class="col-lg-9">

        <div class="mod-tit">
          <h3>{{ trans('seller.Personal_Data') }}</h3>
        </div>

        <form id="form-personal" enctype="multipart/form-data" action="{{ route('savePersonalDataPost') }}" name="form" method="post">
          {{ csrf_field() }}

          <input type="hidden" name="id" value="{{ $seller->id }}">
          <div class="mod-area">

            <div id="avatarAccount" class="data-img-user" @if($seller->foto) style="background-image:url(/img/comerciantes/{!! $seller->foto !!});" @else style="background-image:url('/img/comerciantes/default.svg');" @endif ></div>
                             
            <div class="data-img-button">
              <label for="selecao-arquivo" class="data-upload" style="margin-bottom:0px;">
                <i class="fas fa-cloud-upload-alt"></i> 
                <span>{{ trans('seller.Upload_photo') }}</span>
              </label>
              <input id="selecao-arquivo" class="display-none" type="file" accept="image/*" name="foto" onchange="$(this).submit();" accept="image/*" disabled>
              <label class="data-remove" data-toggle="modal" data-target="#myModalDeletePhoto" style="margin-bottom:0px;">
                <i class="far fa-trash-alt"></i> <span>{{ trans('seller.Remove') }}</span>
              </label>
            </div>
             
            <div class="row">
              <div class="col-md-12">
                <label>{{ trans('seller.Name') }}</label>
                <input class="ip data-ip" type="text" name="nome" value="{{ $seller->nome }}" disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label>{{ trans('seller.E-mail') }}</label>
                <input class="ip data-ip" type="email" name="email"  value="{{ $seller->email }}" disabled>
              </div>

              <div class="col-md-6">
                <label>{{ trans('seller.Phone_call') }}</label>
                <input class="ip data-ip" type="text" name="contacto" placeholder="{{ trans('seller.optional') }}"  @if($seller->telefone !=0) value="{{ $seller->telefone }}" @endif disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label>{{ trans('seller.New_Password') }}</label>
                <div class="div-35 text-center margin-right10">
                  <input id="showPass" class="data-ip ip" type="password" name="password" disabled>
                </div>
              
                <a onclick="hidePass('pass');"><i id="eye-pass" class="far fa-eye tx-navy display-none"></i></a>
                <a onclick="showPass('pass');" id="eye-slash-pass"><i class="fas fa-eye-slash tx-navy"></i></a>
              </div>

              <div class="col-md-6">
                <label>{{ trans('seller.Repeat_password') }}</label>
                <div class="div-35 text-center margin-right10">
                  <input id="showPassRepite" class="data-ip ip" type="password" name="repite_password" disabled>
                </div>
              
                <a onclick="hidePass('repite');"><i id="eye-repite" class="far fa-eye tx-navy display-none"></i></a>
                <a onclick="showPass('repite');" id="eye-slash-repite"><i class="fas fa-eye-slash tx-navy"></i></a>
              </div>
            </div>

            @if(($seller->tipo == 'admin') || ($seller->tipo == 'gestor'))
              <label>{{ trans('seller.Permissions') }}</label>
              <div class="select-wrapper">
                <select id="tipo" name="permissoes" disabled onclick="showPermissions();">
                  <option @if($seller->tipo == 'admin') selected @endif value="admin">{{ trans('seller.Admin') }}</option>
                  <option @if($seller->tipo == 'gestor') selected @endif value="gestor">{{ trans('seller.Manager') }}</option>
                  @if($n_admin > 1)
                    <option @if($seller->tipo == 'comerciante') selected @endif value="comerciante">{{ trans('seller.Seller') }}</option>
                  @endif
                </select>
              </div>
            @endif

            <div id="file_div" @if($seller->tipo == 'gestor') class="display-none" @endif>
              <label>{{ trans('seller.Validation_Document') }}</label>
              
              <input type="hidden" name="file_old" value="{{ $seller->ficheiro }}">
              <div id="file_validation">
                <div class="div-50 float-left margin-bottom30">
                  <label class="ip data-ip" id="uploads">@if($seller->ficheiro)<a class="tx-navy" href="doc/companies/{{ $seller->ficheiro }}" download>{{ $seller->ficheiro }}</a>@endif</label>
                </div>

                <span id="file"> 
                  @if($seller->ficheiro)

                    <label id="download_file" class="bt-40 bg-navy float-right line-height40">
                      <a class="tx-white" href="doc/companies/{{ $seller->ficheiro }}" download><i class="fas fa-cloud-download-alt"></i></a>
                    </label> 
                  @else 
                    <span id="upload_file">
                      <label for="arquivo" class="bt-40 bg-navy float-right line-height40">
                      <i class="fas fa-cloud-upload-alt"></i> </label>
                      <input id="arquivo" type="file" name="ficheiro_validacao" onchange="lerFicheiros(this,'uploads');" disabled>
                    </span>
                  @endif

                  <span id="upload_file2" class="display-none">
                    <label for="arquivo2" class="bt-40 bg-navy float-right line-height40">
                    <i class="fas fa-cloud-upload-alt"></i> </label>
                    <input id="arquivo2" type="file" name="ficheiro_validacao" onchange="lerFicheiros(this,'uploads');">
                  </span>
                </span>
              </div>
            </div>

            @if($seller->aprovacao != 'reprovado')
              <div id="bt_unlock" class="tx-right margin-top10">
                <label class="bt-gray" data-toggle="modal" @if($seller->estado != 'aprovado') data-target="#modalUnlock" @else data-target='#modalAprovation' @endif><i class="fas fa-lock-open"></i> {{ trans('seller.Unlock_to_Edit') }}</label>
              </div>
            @endif

            <div class="@if($seller->aprovacao == 'reprovado') @else display-none @endif tx-right margin-top10" id="bt_save">
              @if($seller->aprovacao != 'reprovado')
                <label class="bt-transparent tx-gray margin-right10 margin-bottom0" onclick="lock();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</label>
              @endif
              <button class="bt-blue"><i class="fas fa-check"></i> {{ trans('seller.Save') }}</button>
            </div>

            
            <label id="labelSucesso" class="av-100 alert-success display-none" role="alert"><span id="spanSucesso">{{ trans('seller.savedSuccessfully') }}</span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i></label>
            <label id="labelErros" class="av-100 alert-danger display-none" role="alert"><span id="spanErro"></span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i></label>
          </div>  

          <div class="modal fade" id="modalUnlock" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3>{{ trans('seller.Unlock_to_Edit') }}</h3>
                  <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body mod-area">
                  <label>{{ trans('seller.Changes_validated_txt') }} <span class="tx-bold">{{ trans('seller.Continue_editing_txt') }}</span> </label>
                  <button class="bt-transparent tx-gray" data-dismiss="modal"><i class="fas fa-times"></i>{{ trans('seller.Cancel') }}</button>
                  <button class="bt-blue" onclick="unlock();" data-dismiss="modal"><i class="fas fa-check"></i>{{ trans('seller.Continue') }}</button>
                </div> 
              </div>
            </div>
          </div>
        </form>

        <!--Modal Changes-->
        <div class="modal fade" id="modalAprovation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3>{{ trans('seller.Data_Approval_tit') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <label>{!! trans('seller.Data_Approval_txt') !!}</label>
              </div> 
            </div>
          </div>
        </div>

        <!--Modal Delete PHOTO-->
        <div class="modal fade" id="myModalDeletePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <input type="hidden" name="id_modal_photo" id="id_modal_photo">
              <div class="modal-header">
                <h3>{{ trans('seller.Photo_remove') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <label>{{ trans('seller.Photo_remove_txt') }}</label><br>
                <button class="bt-transparent tx-gray" data-dismiss="modal"><i class="fas fa-times"></i>{{ trans('seller.Cancel') }}</button>
                <button class="bt-blue" type="button" onclick="deletePhoto();"><i class="fas fa-check"></i>{{ trans('seller.Remove') }}</button>

                <p id="labelSucessoPhoto" class="av-100 alert-success display-none" role="alert"><span id="spanSucesso">{{ trans('seller.deleteSuccessfully') }}</span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i></p>
              </div> 
            </div>
          </div>
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
  function showPermissions(){
    var select = document.getElementById("tipo");
    var selectValue = select.options[select.selectedIndex].value;

    if (selectValue == 'gestor') { $('#file_div').hide(); }
    else{ $('#file_div').show(); }
  }
</script>

<script>

  function showPass(valor){
    if(valor == 'repite'){ 
      document.getElementById("showPassRepite").type = 'text';
      $("#eye-slash-repite").hide();
      $("#eye-repite").show();
    }
    else if(valor == 'pass'){ 
      document.getElementById("showPass").type = 'text';
      $("#eye-slash-pass").hide();
      $("#eye-pass").show();
    }
  }

  function hidePass(valor){
    if(valor == 'repite'){ 
      document.getElementById("showPassRepite").type = 'password';
      $("#eye-slash-repite").show();
      $("#eye-repite").hide();
    }
    else if(valor == 'pass'){ 
      document.getElementById("showPass").type = 'password';
      $("#eye-slash-pass").show();
      $("#eye-pass").hide();
    }
  }

  $('#selecao-arquivo').removeAttr("disabled")
  $("#labelSucesso").hide();

  if('{!!$seller->aprovacao!!}' == 'reprovado'){

    $('#upload_file2').show();
    $('#upload_file').hide();
    $('#download_file').hide();
    $('input').removeAttr("disabled");
    $('select').removeAttr("disabled");
  }

  function unlock(){
    $("#labelSucesso").hide();
    $('#bt_unlock').hide();
    $('#bt_save').show();
    $('#upload_file2').show();
    $('#upload_file').hide();
    $('#download_file').hide();
    //$('input').css('disabled','false');
    $('input').removeAttr("disabled");

    $('select').removeAttr("disabled");
  }

  function lock(){
    $("#labelSucesso").hide();
    $('#bt_unlock').show();
    $('#bt_save').hide();
    //$('#upload_file').show();
    $('#download_file').show();
    $('#upload_file2').hide();

    $('input').attr('disabled', true); 

    $('select').attr('disabled', true);
    $("#labelSucesso").hide();
    $("#labelErros").hide();
  }

  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('profile.selectedFiles') }}');}
  };

  if ('{!! $seller->estado !!}' == 'aprovado') {
    $('.data-ip').css('color','#999999');
  }
</script>


<script>
  $('#form-personal').on('submit',function(e) {
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
      
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          $("#labelErros").show();
          //$('#botoes').show();
          return;
      }
      if (resp.estado == 'foto') {
       
        $('#avatarAccount').css("background-image","url(img/comerciantes/"+resp.foto+")");
        $('#selecao-arquivo').val('');
        $('#labelSucesso').hide();
        
      }
      if(resp.estado == 'sucesso'){
        console.log(resposta);
        if (resp.foto) {
          $('#avatarAccount').css("background-image","url(/img/comerciantes/"+resp.foto+")");
          $('#img-header').attr('src', '/img/comerciantes/'+resp.foto);
          $('#labelSucesso').hide();
          
        }
        else{
          $('#avatarAccount').css('background-image','url(/img/comerciantes/default.svg)');
          $('#img-header').attr('src', '/img/comerciantes/default.svg');
        }

        if (resp.alterado == 'sim') {
          $("#spanSucesso").html(resp.mensagem);
        }

        $('#uploads').html('<a class="tx-navy" href="doc/companies/'+resp.doc+'" download>'+resp.doc+'</a>');

        $('#labelSucesso').show();
        $("#labelErros").hide();

        if(resp.aprovacao == 'em_aprovacao'){
          setTimeout(function() {window.location.href = '{{ route('pendingPageV2') }}';}, 1000);
        }
      }else if(resposta){
       
        //$('#botoes').show();
      }
    });
  });
</script>

<script>
  function deletePhoto(){
    $.ajax({
      type: "POST",
      url: '{{ route('deleteCompanyAvatarPost') }}',
      data: {tipo:'seller'},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
     console.log(resposta);
      if(resposta == 'sucesso'){
        $('#labelSucesso').hide();
        $("#labelErros").hide();
        $('#avatarAccount').css('background-image','url({{ asset('/img/comerciantes/default.svg') }})');
        $('#img-header').attr('src', '/img/comerciantes/default.svg');
        $('#selecao-arquivo').val('');
        $('#labelSucessoPhoto').show();
      }
    });
  }
</script>

@stop