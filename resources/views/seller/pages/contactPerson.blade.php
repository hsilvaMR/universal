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
          <h3>{{ trans('seller.Contact_Person') }}</h3>
        </div>

        <div class="mod-area">

          @foreach($person_contact as $valor)
            <div id="empresa_{{ $valor->id }}">
              <input id="{{ $valor->id }}_id" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->id }}">
              <input id="{{ $valor->id }}_nome" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->nome }}">
              <input id="{{ $valor->id }}_cargo" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->cargo }}">
              <input id="{{ $valor->id }}_email" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->email }}">
              <input id="{{ $valor->id }}_contacto" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->contacto }}">
              <input id="{{ $valor->id }}_obs" class="{{ $valor->id }}_update" type="hidden" name="" value="{{ $valor->obs }}">
            </div>
          @endforeach

          <div class="modulo-table">
            <div class="modulo-scroll">
              <table class="modulo-body" id="sortable" >
                <thead>
                  <tr>
                    <th class="display-none">ID</th>
                    <th class="table-padding-user">{{ trans('seller.Name') }}</th>
                    <th>{{ trans('seller.Office') }}</th>
                    <th>{{ trans('seller.Email') }}</th>
                    <th>{{ trans('seller.Contact') }}</th>
                    <th>{{ trans('seller.Additional_information') }}</th>
                    <th class="background-none"></th>
                  </tr>
                </thead>
                <tbody id="linha_tbody">
                  @foreach($person_contact as $val)
                    <tr id="linha_{{ $val->id}}">
                      <td class="display-none">{{ $val->id}}</td>
                      <td class="line-height50"> 
                        
                          <i class="fas fa-times user-delete margin-right20" onclick="$('#id_modal').val({{ $val->id }});" data-toggle="modal" data-target="#myModalDelete"></i> 
                        
                        {{ $val->nome }}
                      </td>
                      <td>{{ $val->cargo }}</td>
                      <td>{{ $val->email }}</td>
                      <td>{{ $val->contacto }}</td>
                      <td>{{ $val->obs }}</td>
                      
                      <td><span class="dashboard-table-details" onclick="editSeller({{ $val->id }});"><i class="fas fa-pencil-alt"></i> {{ trans('seller.Edit') }}</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div id="add_user" class="display-none">
          <div class="mod-tit mod-border-top">
            <h3>{{ trans('seller.AddPersonContact') }}</h3>
          </div>

          <form id="form-addUser" enctype="multipart/form-data" action="{{ route('saveContactDataPost') }}" name="form" method="post">
            {{ csrf_field() }}
            <div class="mod-area">
              
              <input id="id" type="hidden" name="id" value="">
              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.Name') }}</label>
                  <input id="nome" class="ip data-ip bg-isabelline" type="text" name="nome" value="">
                </div>
                <div class="col-md-6">
                  <label>{{ trans('seller.Office') }}</label>
                  <input id="cargo" class="ip data-ip bg-isabelline" type="text" name="cargo" value="">
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

              <label>{{ trans('seller.Additional_information') }}</label>
              <textarea id="obs" class="tx" name="info_adicional" placeholder="{{ trans('seller.optional') }}"></textarea>

              <div class="tx-right">
                <span class="bt margin-right10 tx-gray" onclick="closeDiv();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</span>
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
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 6 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [[ 0, "asc" ]],
    });

    //$('#sortable_length').hide(); 
    $('#sortable_filter input').attr('placeholder', ' {{ trans('seller.Search') }}');

    $('#sortable_length').html('<button class="bt-blue" onclick="addUser();"><i class="fas fa-plus"></i> {{ trans('seller.AddPersonContact') }}</button>');
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();

  });
</script>


<script>
  function addUser(){
    $('#add_user').show();
    $('#id').val('');
    $('#nome').val('');
    $('#cargo').val('');
    $('#email').val('');
    $('#contacto').val('');
    $('#obs').val('');
  }
</script>

<script>
  $('#form-addUser').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();
    $('#botoes').hide();
    var id_form = $('#id').val();
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
        
        $('#labelSucesso').show();
        $("#labelErros").hide();
        $('#add_user').hide();
        document.getElementById("form-addUser").reset();
        $('#labelSucesso').hide();
        //$('#linha_'+resp.id).remove();
        if (id_form) {
          $('#linha_'+resp.id).html(resp.conteudo_tr);
          $('#empresa_'+resp.id).html(resp.conteudo);
        }else{
          $('#linha_tbody').append('<tr id="linha_'+resp.id+'">'+resp.conteudo_tr+'</tr>');
          $('.mod-area').append('<div id="empresa_'+resp.id+'">'+resp.conteudo+'</div>');
        }
        
        $('#header-notification-number').html(resp.count_notificacoes);
        $('.dataTables_empty').hide();
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
    var tipo = 'person_contact';

    $.ajax({
      type: "POST",
      url: '{{ route('deleteSellerPost') }}',
      data: { id:id,tipo:tipo },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      if(resposta.estado == 'sucesso'){
        $('#linha_'+id).hide();
        $('#add_user').hide();

        if (resposta.n_users == 1) {
          $('#linha_tbody').append('<td style="background-color:#fbfbfb;" valign="top" colspan="7" class="dataTables_empty">Sem dados disponíveis na tabela</td>');
        }
      }
    });
  }


  function editSeller(id){
    console.log(id);
    $('#add_user').show();

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
@stop