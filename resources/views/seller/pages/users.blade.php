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
          <h3>{{ trans('seller.Users') }}</h3>
        </div>

        <div class="mod-area">

          @if($company->estado != 'aprovado')
            <label class="av-100 alert-danger" role="alert">
              <span id="spanErro">{{ trans('seller.Account_approval_not_possible_users_txt') }}</span>
              <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
            </label>
          @else

            @foreach($array_comerc as $valor)
              <div id="empresa_{{ $valor['id'] }}">
              
                <input id="{{ $valor['id'] }}_nome" class="{{ $valor['id'] }}_update" type="hidden" name="" value="{{ $valor['nome'] }}">
                <input id="{{ $valor['id'] }}_email" class="{{ $valor['id'] }}_update" type="hidden" name="" value="{{ $valor['email'] }}">
                <input id="{{ $valor['id'] }}_contacto" class="{{ $valor['id'] }}_update" type="hidden" name="" @if($valor['telefone'] != 0) value="{{ $valor['telefone'] }}" @endif>
                <input id="{{ $valor['id'] }}_tipo" class="{{ $valor['id'] }}_update up_select" type="hidden" name="" value="{{ $valor['tipo'] }}">
                <input id="{{ $valor['id'] }}_ficheiro_v" class="{{ $valor['id'] }}_update" type="hidden" name="" value="{{ $valor['ficheiro'] }}">
                <input id="{{ $valor['id'] }}_foto" class="{{ $valor['id'] }}_update" type="hidden" name="" value="{{ $valor['foto'] }}">

                @foreach($valor['id_morada'] as $val)
                  <input id="{{ $valor['id'] }}_armazem{{ $val['id'] }}" class="{{ $valor['id'] }}_update up_check" type="hidden" name="" value="{{ $val['id'] }}">
                @endforeach

              </div>
            @endforeach

            <div class="modulo-table">
              <div class="modulo-scroll">
                <table class="modulo-body" id="sortable" >
                  <thead>
                    <tr>
                      <th class="display-none">ID</th>
                      <th class="table-padding-user">{{ trans('seller.Name') }}</th>
                      <th>{{ trans('seller.Email') }}</th>
                      <th>{{ trans('seller.Telephone') }}</th>
                      <th>{{ trans('seller.File') }}</th>
                      <th>{{ trans('seller.Type') }}</th>
                      <th>{{ trans('seller.Status') }}</th>
                      <th class="background-none"></th>
                    </tr>
                  </thead>
                  <tbody id="linha_tbody">
                    @foreach($empresa_comerc as $val)
                      <tr id="linha_{{ $val->id}}">
                        <td class="display-none">{{ $val->id}}</td>
                        <td class="line-height50">
                          @if(Cookie::get('cookie_comerc_type') == 'gestor')
                            @if($val->id != Cookie::get('cookie_comerc_id') && ($val->tipo != 'admin')) 
                              <i class="fas fa-times user-delete" onclick="$('#id_modal').val({!! $val->id !!});" data-toggle="modal" data-target="#myModalDelete"></i> 
                            @endif
                          @else
                            @if($val->id != Cookie::get('cookie_comerc_id')) 
                              <i class="fas fa-times user-delete" onclick="$('#id_modal').val({!! $val->id !!});" data-toggle="modal" data-target="#myModalDelete"></i> 
                            @endif
                          @endif
                          <img id="img_{{ $val->id}}" class="user-img 
                          @if($val->id != Cookie::get('cookie_comerc_id') && (Cookie::get('cookie_comerc_type') == 'gestor') && ($val->tipo != 'admin')) margin-left20
                          @elseif($val->id != Cookie::get('cookie_comerc_id') && Cookie::get('cookie_comerc_type') == 'admin') margin-left20
                          @else margin-left30 
                          @endif" @if($val->foto) src="img/comerciantes/{{ $val->foto }}" @else src="img/comerciantes/default.svg" @endif> <span>{{ $val->nome }}</span> @if($val->id == Cookie::get('cookie_comerc_id'))<span>({{ trans('seller.I') }})</span>@endif
                        </td>
                        <td>{{ $val->email }}</td>
                        <td>@if($val->telefone != 0){{ $val->telefone }}@endif</td>
                        <td><a class="tx-navy" href="doc/companies/{{ $val->ficheiro }}" download>{{ $val->ficheiro }}</a></td>
                        <td>
                          @if($val->tipo == 'admin') {{ trans('seller.Admin') }} 
                          @elseif($val->tipo == 'gestor') {{ trans('seller.Manager') }} 
                          @else {{ trans('seller.Seller') }} 
                          @endif
                        </td>
                        <td>
                          @if($val->aprovacao == 'em_aprovacao')<i class="fas fa-circle dashboard-invoice-resgisted"></i> {{ trans('seller.on_approval') }} 
                          @elseif(($val->estado == 'pendente') && ($val->aprovacao == ''))<i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.pending') }} 
                          @elseif(($val->aprovacao == 'aprovado') || (($val->estado == 'ativo') && ($val->aprovacao == '')))<i class="fas fa-circle dashboard-invoice-completed"></i> {{ trans('seller.active') }} 
                          @else <i class="fas fa-circle dashboard-invoice-reproved"></i> {{ trans('seller.disapproved') }} 
                          @endif
                        </td>
                        <td>
                          <span class="dashboard-table-details" onclick="editSeller({{ $val->id }},'{{ $val->foto }}','{{ $val->ficheiro }}','{{ $val->tipo }}');"><i class="fas fa-pencil-alt"></i> {{ trans('seller.Edit') }}</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div id="add_user" class="display-none">
            <div class="mod-tit mod-border-top">
              <h3>{{ trans('seller.Add_User') }}</h3>
            </div>

            <form id="form-addUser" enctype="multipart/form-data" action="{{ route('addUserPost') }}" name="form" method="post">
              {{ csrf_field() }}
              <div class="mod-area">
                
                <img id="avatarAccount" class="data-img-user" src="{{ asset('img/comerciantes/default.svg') }}" >
                <input id="img_value" type="hidden" name="img_value" value="">        
                <input id="id_comerciante" type="hidden" name="id_seller">        
                <input id="foto" type="hidden" name="foto" value="">
                <input id="ficheiro_v" type="hidden" name="ficheiro_v" value="">
                <input id="empresa_admin" type="hidden" value="{{ $empresa_admin }}">

                <div class="data-img-button">
                  <label for="selecao-arquivo" class="data-upload" style="margin-bottom:0px;"><i class="fas fa-cloud-upload-alt"></i> <span>{{ trans('seller.Upload_photo') }}</span></label>
                  <input id="selecao-arquivo" class="display-none" type="file" accept="image/*" name="foto">
                  <label class="data-remove" onclick="deletePhoto();" style="margin-bottom:0px;"><i class="far fa-trash-alt"></i> <span>{{ trans('seller.Remove') }}</span></label>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <label>{{ trans('seller.Name') }}</label>
                    <input id="nome" class="ip data-ip bg-isabelline" type="text" name="nome" value="">
                  </div>

                  <div class="col-md-6">
                    <label>{{ trans('seller.Type') }} </label> <i class="fas fa-info-circle tx-coral cursor-pointer" data-toggle="modal" data-target="#myModalInfo"></i>
                    <div class="select-wrapper">
                      <select id="tipo" name="permissao_user" onclick="showPermissions();">
                        <option value="admin">{{ trans('seller.Admin') }}</option>
                        <option value="gestor">{{ trans('seller.Manager') }}</option>
                        <option id="comerciante" value="comerciante">{{ trans('seller.Seller') }}</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <label>{{ trans('seller.E-mail') }}</label>
                    <input id="email" class="ip data-ip bg-isabelline" type="email" name="email">
                  </div>

                  <div class="col-md-6">
                    <label>{{ trans('seller.Phone_call') }}</label>
                    <input id="contacto" class="ip data-ip bg-isabelline" type="text" name="contacto" placeholder="{{ trans('seller.optional') }}">
                  </div>
                </div>

                <div id="ficheiro">
                  <label>{{ trans('seller.Validation_Document') }}</label>
                  <div class="div-50 float-left margin-bottom30">
                    <label class="ip data-ip tx-underline" id="uploads"></label>
                  </div>
                 
                  <span id="upload_file">
                    <label for="arquivo" class="bt-40 bg-navy float-right line-height40">
                    <i class="fas fa-cloud-upload-alt"></i> </label>
                    <input id="arquivo" type="file" name="doc_validate" onchange="lerFicheiros(this,'uploads');">
                  </span>
                </div>

                @if((count($moradas_armazem) > 0))
                  <div id="moradas_armazem">
                    <label>{{ trans('seller.Purchase_Addresses') }}</label>
                    <br>

                    @foreach($moradas_armazem as $val)
                      <input id="armazem{{ $val->id }}" type="checkbox" name="id_morada[]" value="{{ $val->id }}">
                      <label class="margin-right10" for="armazem{{ $val->id }}"><span></span> {{ $val->nome_personalizado}}</label>
                    @endforeach
                  </div>
                @endif


                <div class="tx-right">
                  <label class="bt margin-right10 tx-gray" onclick="closeDiv();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</label>
                  <button class="bt-blue"><i class="fas fa-check"></i> {{ trans('seller.Save') }}</button>
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
        @endif

        <!-- Modal Delete-->
        <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <input type="hidden" name="id_modal" id="id_modal">
              <div class="modal-header">
                <h3>{{ trans('seller.Remove_User') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <label>{{ trans('seller.Remove_User_txt') }}</label>
                <div>
                  <button class="bt-transparent tx-gray" data-dismiss="modal"><i class="fas fa-times"></i>{{ trans('seller.Cancel') }}</button>
                  <button class="bt-blue" data-dismiss="modal" onclick="removeSeller();"><i class="fas fa-check"></i>{{ trans('seller.Remove') }}</button>
                </div>
              </div> 
            </div>
          </div>
        </div>

        <!-- Modal Information Permissions-->
        <div class="modal fade" id="myModalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('seller.Information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="text-align:left;">
                <li>{!! trans('seller.AdminInfo') !!}</li>
                <li>{!! trans('seller.GestorInfo') !!}</li>
                <li>{!! trans('seller.ComercianteInfo') !!}</li>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>  
  </div>  
@stop

@section('css')
<!-- PAGINAR -->
<link href="{{ asset('/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<!-- ORDENAR -->
<script src="{{ asset('/vendor/sortable/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 7 ], "ordering": false }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [[ 1, "desc" ]],
    });

    //$('#sortable_length').hide(); 
    $('#sortable_filter input').attr('placeholder', ' {{ trans('seller.Search') }}');
    $('#sortable_length').html('<button class="bt-blue" onclick="addUser();"><i class="fas fa-plus"></i> {{ trans('seller.Add_User') }}</button>');
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();
  });


</script>

<script>
  function showPermissions(){
    var select = document.getElementById("tipo");
    var selectValue = select.options[select.selectedIndex].value;

    if (selectValue == 'gestor') {
      $('#ficheiro').hide();
      $('#moradas_armazem').hide();
    }
    else{
      $('#ficheiro').show();
      $('#moradas_armazem').show();
    }
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
  function addUser(){
    $('#add_user').show();
    $('#avatarAccount').attr("src","/img/comerciantes/default.svg");
    $('#nome').val('');
    $('#email').val('');
    $('#contacto').val('');
    $('#uploads').html('');
    $('#id_modal_photo').val('');
    $('#id_comerciante').val('');
    $('#img_value').val('');
    $("#tipo option:selected").prop("selected", false);
    $("#moradas_armazem").show();
    $("#ficheiro").show();

    @foreach($moradas_armazem as $value)
      $('#armazem'+{!! $value->id !!}).attr('checked',false);
    @endforeach

    //$('#comerciante').attr('disabled',false);
    $('#comerciante').show();
  }
</script>

<script>
  $('#form-addUser').on('submit',function(e) {
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
        return;
      }
      if(resp.estado == 'foto') {
        
        $('#avatarAccount').attr("src","/img/comerciantes/"+resp.foto);
        $('#selecao-arquivo').val('');
        $('#img_value').val(resp.foto);
        $('#img_'+resp.id_seller).attr('src','/img/comerciantes/'+resp.foto);
      }
      if(resp.estado == 'sucesso'){

        $('#avatarAccount').attr("src","/img/comerciantes/"+resp.foto);
        $('#selecao-arquivo').val('');
        $('#img_value').val(resp.foto);
        $('#img_'+resp.id_seller).attr('src','/img/comerciantes/'+resp.foto);
        if (resp.foto_cookie) {
          $('#img-header').attr('src','/img/comerciantes/'+resp.foto_cookie);
        }else{
          $('#img-header').attr('src','/img/comerciantes/default.svg');
        }
        
   
        if (resp.tipo == 'add') {
          $('#sortable').append(resp.conteudo_add);
        }
        else{
          $('#linha_'+resp.id_seller).html(resp.conteudo_edit);
          $('#avatarAccount').attr("src","/img/comerciantes/"+resp.foto);          
        }

        $('.mod-area').append(resp.conteudo2);
        $('#empresa_'+resp.id_seller).append(resp.conteudo2);

        $('#labelSucesso').show();
        $("#labelErros").hide();
        $('#add_user').hide();
        document.getElementById("form-addUser").reset();
        $('#avatarAccount').attr('src','/img/comerciantes/default.svg');
        
        $('#labelSucesso').hide();

        $("#empresa_admin").val(resp.num_empresa_admin);

        if (resp.id_seller == {{ Cookie::get('cookie_comerc_id') }} && resp.aprovacao =='em_aprovacao') {
          setTimeout(function() {window.location.href = '{{ route('pendingPageV2') }}';}, 1000);
        }
        
      }
    });
  });
  
</script>

<script>
  function closeDiv(){
    $('#add_user').hide();
    $("html, body").animate({ scrollTop: $('#sortable').offset().top }, 800);
  }
 
  function removeSeller(){
    var id = $('#id_modal').val();

    $('#add_user').hide();

    $.ajax({
      type: "POST",
      url: '{{ route('deleteSellerPost') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      if(resposta.estado == 'sucesso'){
        $('#linha_'+id).hide();
        $("#empresa_admin").val(resposta.num_empresa_admin);
      }
    });
  }


  function editSeller(id,foto,file,tipo){
    $("#labelSucesso").hide();
    $("#labelErros").hide();
    var empresa_admin = $("#empresa_admin").val();

    $('#add_user').show();
    var foto_edit = $('#foto').val();
    var file_edit = $('#ficheiro_v').val();

    if (tipo == 'gestor') {
      $('#ficheiro').hide();
      $('#moradas_armazem').hide();
    }
    else{
      $('#ficheiro').show();
      $('#moradas_armazem').show();
    }

    if(empresa_admin == 1){
      if(tipo != 'comerciante'){
        //$('#comerciante').attr('disabled',true);
        $('#comerciante').hide();
      }
      
    }
    

    @foreach($moradas_armazem as $value)
      $('#armazem'+{{ $value->id }}).attr('checked',false);
    @endforeach

    if (foto) { $('#avatarAccount').attr('src','/img/comerciantes/'+foto); $('#img_value').val(foto);}
    else if(foto_edit){ $('#avatarAccount').attr('src','/img/comerciantes/'+foto_edit); $('#img_value').val(foto_edit);}
    else{ $('#avatarAccount').attr('src','/img/comerciantes/default.svg'); }


    $('#id_comerciante').val(id);
    $('#ficheiro_v').val(file);

    $('#uploads').html('');

    if (file) { $('#uploads').append('<a class="tx-navy" href="/doc/companies/'+file+'" download>' +file+'</a>'); }
    else if(file_edit){ $('#uploads').append('<a class="tx-navy" href="/doc/companies/'+file+'" download>'+file_edit+'</a>');}
    

    $('.'+id+'_update').each(function(){
      if($(this).hasClass('up_check')){
        var valor=$(this).val();
        var destino=$(this).attr('id');
        destino = destino.replace(id+'_','');
        if(!valor){
          $('#'+destino).attr('checked',false);
        }else{ $('#'+destino).attr('checked',true); }
      }
      if($(this).hasClass('up_select')){
        var valor=$(this).val();
        var destino=$(this).attr('id');
        destino = destino.replace(id+'_','');
        $('#'+destino).val(valor).trigger('change');
      }else{
        var valor=$(this).val();
        var destino=$(this).attr('id');
        destino = destino.replace(id+'_','');
        $('#'+destino).val(valor);
        }
    });
  }

</script>

<script>
  
  function deletePhoto(){
    var id = $('#id_comerciante').val();
    console.log(id);
    $.ajax({
      type: "POST",
      url: '{{ route('photoDeletePost') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      if(resposta.estado == 'sucesso'){
        $('#avatarAccount').attr('src','/img/comerciantes/default.svg');
        $('#img-header').attr('src','/img/comerciantes/default.svg');
        $('#img_value').val('');
      }

      $('#labelSucessoPhoto').show();
    });
  }
</script>


@stop