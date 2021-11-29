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
          <h3>{{ trans('seller.Company_Data') }}</h3>
        </div>
        <div class="mod-area">
          
          <form id="form-company-new" enctype="multipart/form-data" action="{{ route('saveCompanyDataPost') }}" name="form" method="post">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-12">
                <div id="avatarCompany" class="data-img-user margin-bottom40" @if($company->logotipo) style="background-image:url(/img/empresas/{!! $company->logotipo !!});" @else style="background-image:url('/img/empresas/company.svg');" @endif></div>    

                @if($company->estado != 'aprovado')
                  <div class="data-img-button">
                    <label for="selecao-arquivo" class="data-upload" style="margin-bottom:0px;"><i class="fas fa-cloud-upload-alt"></i> <span>{{ trans('seller.Upload_logo') }}</span></label>
                    <input id="selecao-arquivo" class="display-none" type="file" accept="image/*" name="logo" accept="image/*">

                    <label class="data-remove" style="margin-bottom:0px;" onclick="deleteAvatar();">
                      <i class="far fa-trash-alt"></i> <span>{{ trans('seller.Remove') }}</span>
                    </label>
                  </div>
                @endif
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <label>{{ trans('seller.Name') }}</label>
                <input class="ip data-ip" type="text" name="name" @if($company) value="{{ $company->nome }}" @endif disabled>
              </div>

              <div class="col-md-6">
                <label>{{ trans('seller.Email_general') }}</label>
                <input class="ip data-ip" type="email" name="email" @if($company->email_alteracao) value="{{ $company->email_alteracao }}" @else value="{{ $company->email }}" @endif disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>{{ trans('seller.CAE') }}</label>
                <input class="ip data-ip" type="text" name="cae" @if($company) value="{{ $company->cae }}" @endif disabled>
              </div>

              <div class="col-md-4">
                <label>{{ trans('seller.VAT') }}</label>
                <input class="ip data-ip" type="text" name="nif" @if($company->nif != 0) value="{{ $company->nif }}" @endif disabled>
              </div>

              <div class="col-md-4">
                <label>{{ trans('seller.Phone_call') }}</label>
                <input class="ip data-ip" type="text" name="telefone" @if($company->telefone != 0) value="{{ $company->telefone}}" @endif disabled>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>{{ trans('seller.Number_Points_Sale_txt') }}</label>
                <input class="ip data-ip" type="text" name="n_vendas" @if($company) value="{{ $company->pontos_venda }}" @endif disabled>
              </div>

              <div class="col-md-4">
                <label>{{ trans('seller.Last_Year_Sales_Volume') }}</label>
                <input class="ip data-ip" type="text" name="v_vendas" @if($company) value="{{ $company->volume_venda }}" @endif disabled>
              </div>

              <div class="col-md-4">
                <label>{{ trans('seller.Last_Year_EBITDA') }}</label>
                <input class="ip data-ip" type="text" name="ebitda" @if($company) value="{{ $company->ebitda }}" @endif disabled>
              </div>
            </div>

            <label>{{ trans('seller.Permanent_Company_Certificate') }}</label>
            <div class="div-file">
              <label style="width:calc(100% - 5px);" class="ip data-ip resp_file" id="certidao">
                <a class="tx-navy" href="doc/companies/{{ $company->certidao }}" download>{{ $company->certidao }}</a>
              </label>
            </div>

            <span>
              <input id="certidao_old" type="hidden" name="certidao_old" @if($company->certidao)value="{{ $company->certidao }}"@endif>
              
              @if($company->estado == 'aprovado')
                <label class="download_file bt-40 bg-navy float-right line-height40">
                  <a class="tx-white"  href="doc/companies/{{ $company->certidao }}" download><i class="fas fa-cloud-download-alt"></i></a>
                </label> 
              @else
                <span id="upload_file">
                  <label for="certidao_new" class="bt-40 bg-navy float-right line-height40">
                  <i class="fas fa-cloud-upload-alt"></i> </label>
                  <input id="certidao_new" type="file" name="certidao" onchange="lerFicheiros(this,'certidao');">
                </span>
              @endif
            </span>

            <label>{{ trans('seller.Payment_Term') }}</label>
            <br>
            <div class="select-wrapper">
              <select name="prazo_pagamento" disabled>
                <option @if($company->prazo_pag == '30') selected @endif value="30">30 {{ trans('seller.days') }}</option>
                <option @if($company->prazo_pag == '60') selected @endif value="60">60 {{ trans('seller.days') }}</option>
              </select>
            </div>
           

            <label>{{ trans('seller.IES_txt') }}</label>
            @if($company->estado == 'aprovado')
              @foreach($arrayIES as $array)
                <div class="div-file">
                  <label class="margin-right10 float-left line-height40">@if($array['ano'] != 0){{ $array['ano'] }}@endif</label>
                  <label class="div-file ip data-ip resp_file" id="ies_{{ $array['ano'] }}"><a class="tx-navy" href="doc/companies/{{ $array['ies'] }}" download>{{ $array['ies'] }}</a></label>
                </div>

                <span> 
                  <label class="download_file bt-40 bg-navy float-right line-height40">
                    <a class="tx-white"  href="doc/companies/{{ $array['ies'] }}" download><i class="fas fa-cloud-download-alt"></i></a>
                  </label> 
                </span>
              @endforeach
            @else
              @foreach($arrayNew as $array)
                @if($array['valor'] == 1)
                  <div class="div-file">
                    <label class="margin-right10 float-left line-height40">@if($array['ano'] != 0){{ $array['ano'] }}@endif</label>
                    <label class="div-file ip data-ip resp_file" id="ies_{{ $array['ano'] }}"><a class="tx-navy" href="doc/companies/{{ $array['ies'] }}" download>{{ $array['ies'] }}</a></label>
                  </div>

                  <span id="upload_file">
                    <label for="ies_1_{{ $array['ano'] }}" class="bt-40 bg-navy float-right line-height40">
                    <i class="fas fa-cloud-upload-alt"></i> </label>
                    <input id="ies_1_{{ $array['ano'] }}" type="file" name="ies_{{ $array['ano'] }}" onchange="lerFicheiros(this,'ies_{{ $array['ano'] }}');" disabled>
                  </span>
                @else
                  <div class="div-file">
                    <label class="margin-right10 float-left line-height40">@if($array['ano'] != 0){{ $array['ano'] }}@endif</label>
                    <label class="div-file ip data-ip resp_file" id="ies_{{ $array['ano'] }}"><a class="tx-navy" href="doc/companies/" download></a></label>
                  </div>

                  <span id="upload_file">
                    <label for="ies_1_{{ $array['ano'] }}" class="bt-40 bg-navy float-right line-height40">
                    <i class="fas fa-cloud-upload-alt"></i> </label>
                    <input id="ies_1_{{ $array['ano'] }}" type="file" name="ies_{{ $array['ano'] }}" onchange="lerFicheiros(this,'ies_{{ $array['ano'] }}');" disabled>
                  </span>
                @endif
              @endforeach
            @endif

            <label>{{ trans('seller.Invoice_Type') }}</label>
            <br>
            <div class="select-wrapper">
              <select name="tipo_fatura" disabled>
                <option @if($company->tipo_fatura == 'unificada') selected @endif value="unificada">{{ trans('seller.Unified_Invoice_txt') }}</option>
                <option @if($company->tipo_fatura == 'separada') selected @endif value="separada">{{ trans('seller.Separate_Invoice_txt') }}</option>
              </select>
            </div>

            <label>{{ trans('seller.Additional_information') }}</label>
            <textarea class="tx" name="obs" disabled>@if($company) {{ $company->obs }} @endif</textarea>

            <input id="tipo" type="hidden" name="tipo">
            @if($company->estado != 'aprovado')
              <div class="tx-right margin-top10">
                <button class="bt-transparent tx-navy margin-right20" type="button" onclick="saveForm('guardar');"><i class="fas fa-check"></i> {{ trans('seller.Save') }}</button>
                @if($company->estado == 'em_aprovacao')
                  <button id="bt-amarelo" class="bt-amarelo" type="button"><i class="fas fa-check"></i> Em aprovação</button>
                @else
                  <button class="bt-blue" type="button" onclick="saveForm('submeter');"><i class="fas fa-check"></i> {{ trans('seller.Save') }} e submeter para aprovação</button>
                @endif
              </div>
            @endif

            <label id="labelSucesso" class="av-100 alert-success display-none" role="alert">
              <span id="spanSucesso">{{ trans('seller.savedSuccessfully') }}</span>
              <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
            </label>
            <label id="labelErros" class="av-100 alert-danger display-none" role="alert">
              <span id="spanErro"></span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
            </label>
          </form>
        </div>                  
      </div>
    </div>
  </div>

  <!--MODAL AVISO PREENCHIMENTO LOGO -->
  <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ trans('seller.Data_Confirmation_tit') }}</h3>
          <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body mod-area">
          <label>{!! trans('seller.Data_Confirmation_txt') !!}</label>
        </div> 
      </div>
    </div>
  </div>
@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>

  if (('{!! $company->estado !!}' == 'ativo') || ('{!! $company->estado !!}' == 'aprovado')) {
    $('.data-ip').css('color','#999999');
    $('select').css('color','#999999');
  }

  if (('{!! $company->estado !!}' != 'aprovado')) {
    $('input').removeAttr("disabled");
    $('select').removeAttr("disabled");
    $('textarea').removeAttr("disabled");
  }
  
</script>

<script>
  $('#form-company-new').on('submit',function(e) {
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
        return;
      }
      if(resp.estado == 'sucesso'){

        if ((resp.logo != null) && (resp.logo != '')) {
          $('#avatarCompany').css("background-image","url(/img/empresas/"+resp.logo+")");
          $('#logo_submenu').attr('src', '/img/empresas/'+resp.logo);
        }
        else{
          $('#avatarCompany').css('background-image','url(/img/empresas/company.svg)');
          $('#logo_submenu').attr('src', '/img/empresas/company.svg');

          //Show modal information para adicionar o logotipo da empresa
          if(resp.tipo == 'submeter'){
            $("#modalConfirmation").modal();
          }
        }

        for (var i = 0; i < resp.ies.length; i++) {
          var ano = resp.ies[i].ano;
          var ies = resp.ies[i].ies;
            
          $('#ies_'+ano).html('');
          $('#ies_'+ano).html('<a class="tx-navy" href="doc/companies/'+ies+'" download>'+ies+'</a>');
        }

        $('#certidao_old').val(resp.certidao);
        $('#labelSucesso').show();
        $("#labelErros").hide();
        $("#company_name").html(resp.nome_empresa);
      }
    });
  });
</script>

<script>
  function saveForm(tipo){
    $('#tipo').val(tipo);
    $('#form-company-new').submit();
  }
</script>

<script>
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('profile.selectedFiles') }}');}
  };
</script>

<script>
  function deleteAvatar(){
    $.ajax({
      type: "POST",
      url: '{{ route('deleteCompanyAvatarPost') }}',
      data: {tipo:'company'},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta == 'sucesso'){
        $('#avatarCompany').css('background-image','url({{ asset('/img/empresas/company.svg') }})');
        $('#logo_submenu').attr('src', '/img/empresas/company.svg');
        $('#selecao-arquivo').val('');
      }
    });
  }
</script>
@stop