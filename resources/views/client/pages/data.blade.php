@extends('client/layouts/default-menu')
@section('content')

<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  @include('client/includes/headerSubMenu')
  
  <div class="container">
    <div class="row">
        <div class="col-lg-3">
            @include('client/includes/menu')
        </div>
      <div class="col-lg-9">
        <div class="bg-white">
          <div class="data-border-form"><label>{{ trans('site_v2.Personal_Data') }}</label></div>

          
          <div class="data-form">
            <form id="uploadPhoto" method="POST" enctype="multipart/form-data" action="{{ route('uploadPhotoPostV2') }}">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-sm-2">
                  <img id="avatarAccount" class="data-img" @if($utilizadores->foto) src="/img/clientes/{{ $utilizadores->foto }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
                </div>
                <div class="col-sm-10">
                  <div class="data-opc">
                    <div class="data-div-upload">
                      <label for="selecao-arquivo" class="data-upload"><i class="fas fa-cloud-upload-alt"></i> <span>{{ trans('site_v2.Upload_photo') }}</span></label>
                      <input id="selecao-arquivo" class="display-none" type="file" accept="image/*" name="ficheiro" onchange="lerFicheiros(this,'avatarAccount');" accept="image/*">
                      <label class="data-remove" data-toggle="modal" data-target="#myModalDelete"><i class="far fa-trash-alt"></i> <span>{{ trans('site_v2.Remove') }}</span></label>
                    </div>
                  </div>
                </div>
                
                <div style="padding:0px 15px;width:100%;">
                  <label id="labelSucessoPhoto" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucessoPhoto">{{ trans('site_v2.Send_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                  <label id="labelErrosPhoto" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroPhoto"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                </div>
                
              </div>
            </form>

            <form id="updateData" method="POST" enctype="multipart/form-data" action="{{ route('updateDataPostV2') }}">
              {{ csrf_field() }}

              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('site_v2.Name') }}</label>
                  <input class="ip data-ip" type="text" name="name" @if($utilizadores->nome) value="{{ $utilizadores->nome }}" @else value="" @endif>

                  <label>{{ trans('site_v2.E-mail') }}</label>
                  <input class="ip data-ip" type="email" name="email" @if($utilizadores->email) value="{{ $utilizadores->email }}" @else value="" @endif>

                  <label>{{ trans('site_v2.Password_New') }}</label>
                  <input class="ip data-ip" type="password" name="password">
                </div>

                <div class="col-md-6">
                  <label>{{ trans('site_v2.Nickname') }}</label>
                  <input class="ip data-ip" type="text" name="apelido" @if($utilizadores->apelido) value="{{ $utilizadores->apelido }}" @else value="" @endif>

                  <label>{{ trans('site_v2.Phone_call') }}</label>
                  <input class="ip data-ip" type="text" name="contacto" placeholder="opcional" @if($utilizadores->telefone) value="{{ $utilizadores->telefone }}" @else value="" @endif>

                  <label>{{ trans('site_v2.Repeat_password') }}</label>
                  <input class="ip data-ip" type="password" name="repite_password">
                </div>
              </div>

              <input type="checkbox" id="termos_cond" name="termos_cond" @if($utilizadores->newsletter == 1) value="1" checked @endif>
              <label for="termos_cond" class="data-terms">
                <span class="data-ip"></span>{{ trans('site_v2.Universal_newsletter_txt') }}
              </label>

              <div class="height20"></div>
              
              <label style="line-height:40px;" class="bt bt-gray-clear" data-toggle="modal" data-target="#myModalDeleteAccount"><i class="fas fa-user-slash"></i> {{ trans('site_v2.Delete_Account') }}</label>
              <button class="bt-blue float-right"><i class="fas fa-check"></i> {{ trans('site_v2.Save') }}</button>

              <div class="height20"></div>
              <label id="labelSucessoData" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucessoData">{{ trans('seller.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
              <label id="labelErrosData" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroData"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
              <div class="height20"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body tx-center">
          <label class="tx-jet font16" id="exampleModalLongTitle">{!! trans('site_v2.DeletePhoto') !!}</label>
          <button type="button" class="bt bt-white tx-jet tx-coral" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> {{ trans('site_v2.Cancel') }}</button>
          <button type="button" class="bt bt-blue" data-dismiss="modal" onclick="apagarFoto();"><i class="fas fa-check"></i> {{ trans('site_v2.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModalDeleteAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{!! trans('seller.Delete_Account') !!}</h3>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body tx-center">
          <label class="tx-jet font16" id="exampleModalLongTitle">{!! trans('site_v2.DeleteAccount') !!}</label>
          <br>
          <div class="div-100"><input id="password_delete" class="ip" type="text" name="password_delete" placeholder="Introduza a sua password"></div>
          <br>
          <button type="button" class="bt bt-white tx-jet" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> {{ trans('site_v2.Cancel') }}</button>
          <button type="button" class="bt bt-blue" onclick="apagarConta();"><i class="fas fa-check"></i> {{ trans('site_v2.Delete') }}</button>
          <label id="labelSucessoDelete" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucessoData">{!! trans('seller.Delete_Account_txt') !!}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
          <label id="labelErrosDelete" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroDataDelete"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
              <div class="height20"></div>
        </div>
        
      </div>
    </div>
  </div>
</section>
@include('site_v2/includes/premium-banner')
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('#myModalDeleteAccount').on('show.bs.modal', function (event) {
      $('#labelErrosDelete').hide();
      $('#labelSucessoDelete').hide();
    });
  </script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');

  </script>

  <script>
    $(document).ready(function () { 
      var $campo = $("#code-premios");
      $campo.mask('AAA AAA', {reverse: false});
    });
  </script>

  <script>
    $("#termos_cond").on('change', function() {
      if ($(this).is(':checked')) { $(this).attr('value', '1'); } 
      else { $(this).attr('value', '0'); }
    });
  </script>

  <script>
    function lerFicheiros(input,id) {
      var quantidade = input.files.length;
      var nome = input.value;


      if(quantidade==1){$('#'+id).html(nome);}
      else{$('#'+id).html(quantidade+' {{ trans('backoffice_v2.selectedFiles') }}');}

      $('#uploadPhoto').submit();
    }
  </script>

  <script>
    function apagarFoto(){
      $.ajax({
        type: "POST",
        url: '{{ route('deletePhotoPostV2') }}',
        data: {},
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        $('#avatarAccount').attr("src","{{ asset('img/clientes/default.svg') }}");
        $('#img-menu').attr("src","{{ asset('img/clientes/default.svg') }}");
        $('#img-header').attr("src","{{ asset('img/clientes/default.svg') }}");
      });
    }
  </script>

  <script>
    function apagarConta(){
      $('#labelErrosDelete').hide();
      $('#labelSucessoDelete').hide();
      var password_delete = $('#password_delete').val();
      $.ajax({
        type: "POST",
        url: '{{ route('deleteAccountPostV2') }}',
        data: {password_delete:password_delete},
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) { 
        console.log(resposta); 

        if (resposta == 'sucesso') {
          $('#labelSucessoDelete').show();
          window.location="{{ route('loginPageV2') }}";
        }
        else{
          $('#labelErrosDelete').show();
          $("#spanErroDataDelete").html(resposta);
        }
      });
    }
  </script>

  <script>
    $('#updateData').on('submit',function(e) {
      $("#labelSucessoData").hide();
      $("#labelErrosData").hide();
      $("#labelSucessoPhoto").hide();
      $("#labelErrosPhoto").hide();
      $('#loadingData').show();

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
            if(resposta){ $("#spanErroData").html(resposta); }
            else{ $("#spanErroData").html('ERROR'); }
            $("#labelErrosData").show();
            $('#loadingData').hide();

            return;
        }
        if(resp.estado=='sucesso'){

          $("#name_user").html(resp.nome_user);
          $("#cookie_user_name").html(resp.nome_user);
          $("#cookie_user_name_xs").html(resp.nome_user);

          $("#labelSucessoData").show();

          if (resp.email=='alterado') {
            $("#spanSucessoData").html(resp.mensagem);
          }
          
        }
        else{
          $("#spanErroData").html(resposta);
          $("#labelErrosData").show();
        }
        $('#loadingData').hide();
      });
    });
  </script>

  <script>
    
    $('#uploadPhoto').on('submit',function(e) {
      $("#labelSucessoPhoto").hide();
      $("#labelErrosPhoto").hide();
      $("#labelSucessoData").hide();
      $("#labelErrosData").hide();
      $('#loadingData').show();

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
            if(resposta){ $("#spanErroPhoto").html(resposta); }
            else{ $("#spanErroPhoto").html('ERROR'); }
            $("#labelErrosPhoto").show();
            $('#loadingData').hide();

            return;
        }
        if(resp.estado=='sucesso'){

          if (resp.foto) {
            $("#avatarAccount").attr("src","/img/clientes/"+resp.foto);
            $("#img-menu").attr("src","/img/clientes/"+resp.foto);
            $("#img-menu-xs").attr("src","/img/clientes/"+resp.foto);
            $("#img-header").attr("src","/img/clientes/"+resp.foto);
          }
          else{
            $("#avatarAccount").attr("src","/img/clientes/default.svg");
            $("#img-menu").attr("src","/img/clientes/default.svg");
            $("#img-menu-xs").attr("src","/img/clientes/default.svg");
            $("#img-header").attr("src","/img/clientes/default.svg");
          }

          $("#labelSucessoPhoto").show(); 
        }
        else{
          $("#spanErroPhoto").html(resposta);
          $("#labelErrosPhoto").show();
        }
        $('#loadingData').hide();

      });
    });
  </script>
@stop