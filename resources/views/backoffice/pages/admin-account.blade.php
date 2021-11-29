@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.Account') => route('adminAccountPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
    {{ trans('backoffice.Account') }}
  </div>
  
  <form id="userAccAvaForm" method="POST" enctype="multipart/form-data" action="{{ route('adminAccAvaFormB') }}">
    {{ csrf_field() }}
    <div class="row text-center">
      <div class="col-lg-12">
        <div id="avatarAccount" class="conta-avatar" @if($user->avatar) style="background-image:url('/backoffice/img/admin/{{ $user->avatar }}');" @endif></div>
        <div class="clearfix"></div>
        <div id="botoesAvatar">
          <div class="conta-col-esq">
            <label for="selecao-arquivo" class="lb-40 bt-azul"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" accept="image/*" name="ficheiro" onchange="$(this).submit();">
          </div>
          <div class="conta-col-dir">
            <button class="bt-40 bt-azul" type="button" data-toggle="modal" data-target="#myModalDelete"><i class="fas fa-trash-alt"></i></button>
          </div>
        </div>
        <div id="loadingAvatar" class="loading-center"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
        <div class="clearfix height-10"></div>
        <label id="labelErrosAvatar" class="av-100 av-vermelho display-none"><span id="spanErroAvatar"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </div>
    </div>    
  </form>
  
  <form id="userAccDatForm" method="POST" enctype="multipart/form-data" action="{{ route('adminAccDatFormB') }}">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }}</label>
        <input class="ip" type="text" name="nome" maxlength="20" value="@if(isset($user->nome)){{ $user->nome }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Email') }}</label>
        <input class="ip" type="text" name="email" value="@if(isset($user->email)){{ $user->email }}@endif" disabled>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Language') }}</label>
        <select class="select2" name="lingua" disabled>
          <option value="en" @if($user->lingua=='en') selected @endif>English</option>
          <option value="pt" @if($user->lingua=='pt') selected @endif>Português</option>
        </select>
      </div>
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.oldPassword') }}</label>
        <input class="ip" type="password" id="pass" name="pass" minlength="6" value="">
      </div>
      <div class="col-lg-3">
        <label class="lb">{{ trans('backoffice.newPassword') }}</label>
        <input class="ip" type="password" id="password" name="password" minlength="6" value="">
      </div>
    </div>
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
@stop

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>
  <script type="text/javascript">
    $('.select2').select2();
  </script>
  <script type="text/javascript">
    $('#userAccAvaForm').on('submit',function(e) {
      $("#labelErrosAvatar").hide();
      $('#loadingAvatar').show();
      $('#botoesAvatar').hide();
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
        resp = $.parseJSON(resposta);
        if(resp.estado=='sucesso'){
          $('#avatarNav').css("background-image","url(/backoffice/img/admin/"+resp.avatar+")");
          $('#avatarAccount').css("background-image","url(/backoffice/img/admin/"+resp.avatar+")");
          $('#loadingAvatar').hide();
          $('#botoesAvatar').show();
        }else if(resp.estado=='erro'){
          $("#spanErroAvatar").html(resp.erro);
          $("#labelErrosAvatar").show();
        }else{
          $("#spanErroAvatar").html(resposta);
          $("#labelErrosAvatar").show();
        }
      });
    });

    function apagarAvatar(){
      $.ajax({
        type: "POST",
        url: '{{ route('adminAccAvaApagarB') }}',
        data: { },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        if(resposta=='sucesso'){
          $('#avatarNav').css('background-image','url({{ asset('/backoffice/img/admin/default.svg') }})');
          $('#avatarAccount').css('background-image','url({{ asset('/backoffice/img/admin/default.svg') }})');
        }else{
          $("#spanErroAvatar").html(resposta);
          $("#labelErrosAvatar").show();
        }
      });
    }

    $('#userAccDatForm').on('submit',function(e) {
      var form = $('#userAccDatForm');
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
            $("#nameNav").html(resp.nome);
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
@stop