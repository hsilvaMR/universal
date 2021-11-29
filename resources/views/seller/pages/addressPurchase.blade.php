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
          <h3>{{ trans('seller.Purchase_Addresses') }}</h3>
        </div>

        <div class="mod-area">
          <div class="modulo-table">
              <div class="modulo-scroll">
                <table class="modulo-body" id="sortable" >
                  <thead>
                    <tr>
                      <th class="display-none">ID</th>
                      <th class="table-padding-left">{{ trans('seller.Name') }}</th>
                      <th>{{ trans('seller.Adress') }}</th>
                      <th>{{ trans('seller.Contacts') }}</th>
                      <th>{{ trans('seller.Responsible') }}</th>
                      <th>{{ trans('seller.Status') }}</th>
                      <th class="background-none"></th>
                    </tr>
                  </thead>
                  <tbody id="new_adress">
                    @foreach($morada_armazem as $val)

                      <div id="morada_{{ $val['id'] }}">
                        <input id="{{ $val['id'] }}_id_morada" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['id'] }}">
                        <input id="{{ $val['id'] }}_nome_personalizado" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['nome_personalizado'] }}">
                        <input id="{{ $val['id'] }}_estado" class="{{ $val['id'] }}_update up_select" type="hidden" name="" value="{{ $val['estado'] }}">
                        <input id="{{ $val['id'] }}_morada" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['morada'] }}">
                        <input id="{{ $val['id'] }}_morada_opc" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['morada_opc'] }}">
                        <input id="{{ $val['id'] }}_codigo_postal" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['codigo_postal'] }}">
                        <input id="{{ $val['id'] }}_cidade" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['cidade'] }}">
                        <input id="{{ $val['id'] }}_pais" class="{{ $val['id'] }}_update up_select" type="hidden" name="" value="{{ $val['pais'] }}">
                        <input id="{{ $val['id'] }}_contacto_empresa" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['telefone'] }}">
                        <input id="{{ $val['id'] }}_fax_empresa" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['fax'] }}">
                        <input id="{{ $val['id'] }}_nome_gerente" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['nome_gerente'] }}">
                        <input id="{{ $val['id'] }}_cargo_gerente" class="{{ $val['id'] }}_update up_select" type="hidden" name="" value="{{ $val['cargo_gerente'] }}">
                        <input id="{{ $val['id'] }}_email_gerente" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['email_gerente'] }}">
                        <input id="{{ $val['id'] }}_telefone_gerente" class="{{ $val['id'] }}_update" type="hidden" name="" value="{{ $val['telefone_gerente'] }}">
                      </div>

                      <tr id="tr_{{ $val['id'] }}">
                        <td class="display-none">{{ $val['id'] }}</td>
                        <td>
                          @if($val['delete'] == 0)<i class="fas fa-times table-icon-delete" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete"></i>@endif
                          {{ $val['nome_personalizado'] }}
                        </td>
                        <td>
                          <span>{{ $val['morada'] }}</span><br> 
                          @if($val['morada_opc'])<span>{{ $val['morada_opc'] }}</span><br>@endif
                          <span>{{ $val['codigo_postal'] }} {{ $val['cidade'] }}</span><br>
                          <span>{{ $val['pais'] }}</span>
                        </td>
                        <td>
                          <span class="tx-bold">{{ trans('seller.telephone') }}:</span> @if(empty($val['telefone'])) - @else {{ $val['telefone'] }} @endif <br> 
                          <span class="tx-bold">{{ trans('seller.fax') }}:</span> @if(empty($val['fax'])) - @else {{ $val['fax'] }} @endif
                        </td>
                        <td>
                          @if($val['nome_gerente']) {{ $val['nome_gerente'] }}<br> @endif 
                          @if($val['cargo_gerente']) {{ $val['cargo_gerente'] }}<br> @endif
                          @if($val['email_gerente'])<span class="tx-bold">{{ trans('seller.email') }}:</span> {{ $val['email_gerente'] }}<br>@endif 
                          @if($val['telefone_gerente'])<span class="tx-bold">{{ trans('seller.telephone') }}:</span> {{ $val['telefone_gerente'] }}@endif
                        </td>
                        <td id="estado_{{ $val['id'] }}">
                          @if($val['estado'] == 'ativo')
                            <i class="fas fa-circle dashboard-invoice-completed"></i> {{ trans('seller.active') }} <br> 
                            <span class="tx-navy cursor-pointer" onclick="changeStatus({{ $val['id'] }},'ativo');">
                              <i class="fas fa-eye-slash"></i> {{ trans('seller.Deactivate') }}
                            </span>
                          @elseif($val['estado'] == 'inativo') 
                            <i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.inactive') }} <br> 
                            <span class="tx-navy cursor-pointer" onclick="changeStatus({{ $val['id'] }},'inativo');">
                              <i class="fas fa-eye"></i> {{ trans('seller.Enable') }}
                            </span> 
                          @endif
                        </td>
                        <td class="dashboard-table-details cursor-pointer" onclick="edit({{ $val['id'] }});">
                          <i class="fas fa-pencil-alt"></i> {{ trans('seller.Edit') }}
                        </td>
                      </tr>
                    @endforeach                   
                  </tbody>
                </table>
              </div>
            </div>
        </div> 

        <form id="form-add" enctype="multipart/form-data" action="{{ route('addAdressPost') }}" name="form" method="post">
          {{ csrf_field() }}

          <input id="id_morada" type="hidden" name="id_morada" value="">
          <input type="hidden" name="tipo" value="morada_armazem">

          <div id="add_adress" class="display-none">
            <div class="mod-tit mod-border-top">
              <h3>{{ trans('seller.Add_Adress') }}</h3>
            </div>

            <div class="mod-area">

              <div class="row">
                <div class="col-md-6">
                    <label>{{ trans('seller.Name_to_show') }}</label>
                    <input id="nome_personalizado" class="ip bg-gray-dark" type="text" name="nome_personalizado" value="">
                </div>

                <div class="col-md-6">
                  <label>{{ trans('seller.Status') }}</label>
                  <div class="select-wrapper">
                    <select id="estado" name="estado_armazem" class="bg-gray-dark">
                      <option value="ativo">{{ trans('seller.Active') }}</option>
                      <option value="inativo">{{ trans('seller.Inactive') }}</option>
                    </select>
                  </div>
                </div>
              </div>

              <label>{{ trans('seller.Adress') }}</label>
              <input id="morada" class="ip bg-gray-dark margin-bottom10" type="text" name="morada" value="">
              <input id="morada_opc" class="ip bg-gray-dark" type="text" name="morada_opc" placeholder="opcional" value="">

              <div class="row">
                <div class="col-md-3">
                  <label>{{ trans('seller.Postal_Code') }}</label>
                  <input id="codigo_postal" class="ip bg-gray-dark" type="text" name="codigo_postal" value="">
                </div>

                <div class="col-md-4">
                  <label>{{ trans('seller.City') }}</label>
                  <input id="cidade" class="ip bg-gray-dark" type="text" name="cidade" value="">
                </div>

                <div class="col-md-5">
                  <label>{{ trans('seller.Country') }}</label>
                  <div class="select-wrapper">
                    <select id="pais" name="pais" class="bg-gray-dark">
                      <option selected disabled>{{ trans('seller.Country') }}</option>
                      @foreach ($paises as $pais)
                        <option value="{{ $pais->nome }}">{{ $pais->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.Phone_call') }}</label>
                  <input id="contacto_empresa" class="ip bg-gray-dark" type="text" name="contacto_empresa" value="">
                </div>
                <div class="col-md-6">
                  <label>{{ trans('seller.Fax') }}</label>
                  <input id="fax_empresa" class="ip bg-gray-dark" type="text" name="fax_empresa" placeholder="opcional" value="">
                </div>
              </div>
            </div>

            <div class="mod-tit mod-border-top">
              <h3>{{ trans('seller.Responsible') }}</h3>
            </div>

            <div class="mod-area">  
              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.Name') }}</label>
                  <input id="nome_gerente" class="ip bg-gray-dark" type="text" name="nome_gerente" value="">
                </div>

                <div class="col-md-6">
                  <label>{{ trans('seller.Office') }}</label>
                  <input id="cargo_gerente" class="ip bg-gray-dark" type="text" name="cargo_gerente" value="">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.E-mail') }}</label>
                  <input id="email_gerente" class="ip bg-gray-dark" type="email" name="email_gerente" value="">
                </div>

                <div class="col-md-6">
                  <label>{{ trans('seller.Phone_call') }}</label>
                  <input id="telefone_gerente" class="ip bg-gray-dark" type="text" name="telefone_gerente" value="">
                </div>
              </div>
              <div class="tx-right margin-top10">
                <span class="bt margin-right10 tx-gray" onclick="$('#add_adress').hide();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</span>
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
          </div> 
        </form>

        <!-- Modal Delete-->
        <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <input type="hidden" name="id_modal" id="id_modal">
              <div class="modal-header">
                <h3>{{ trans('seller.Remove_Adress') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <label>{{ trans('seller.Remove_Adress_txt') }}</label>
                <div>
                  <button class="bt-transparent tx-gray" data-dismiss="modal"><i class="fas fa-times"></i>{{ trans('seller.Cancel') }}</button>
                  <button class="bt-blue" data-dismiss="modal" onclick="deleteAdress();"><i class="fas fa-check"></i>{{ trans('seller.Remove') }}</button>
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
  $(document).ready(function () { 
    var $campo = $("#codigo_postal");
    $campo.mask('0000-000', {reverse: true});
  });
</script>

<script>
  function addAdress(){
    document.getElementById('form-add').reset();
    $('#add_adress').show();
    $('#id_morada').val('');
  }
  
</script>

<script>

  $('#form-add').on('submit',function(e) {
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
      if(resp.estado == 'sucesso'){
        //console.log(resposta);

        var existe_tr = $('#tr_'+resp.id_morada).html();

        
        if(existe_tr){
          $('#tr_'+resp.id_morada).html('');
          $('#tr_'+resp.id_morada).append(resp.conteudo_edit);
          $('#morada_'+resp.id_morada).append(resp.conteudo);
        }
        else{
          $('#new_adress').append(resp.conteudo_add);
          $('#new_adress').append(resp.conteudo);
        }
        
        //$('#id_morada').val();
        //$('.odd').hide();
        $('#labelSucesso').show();
        $("#labelErros").hide();

        $("html, body").animate({ scrollTop: $('#tr_'+resp.id_morada).offset().top }, 800);
        $('#add_adress').hide();
        document.getElementById("form-add").reset();
        $('.dataTables_empty').hide();
      }
      $("#labelSucesso").hide();
      $("#labelErros").hide();
    });
  });
</script>

<script>
  function edit(id){
    $('#add_adress').show();
    $('html,body').animate({scrollTop: $("#add_adress").offset().top},'slow');
    $('#id_morada').val(id);

    $('#labelSucesso').hide();
    $("#labelErros").hide();

    
    if (id) {
      console.log(id);
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
  }
</script>

<script>
  function changeStatus(id,estado){
    $.ajax({
      type: "POST",
      url: '{{ route('changeStatusAdress') }}',
      data: {id:id,estado:estado},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      try{ resp=$.parseJSON(resposta); }
      catch (e){
        if(resposta){ $("#spanErro").html(resposta); }
        else{ $("#spanErro").html('ERROR'); }
        return;
      }
      if(resp.estado == 'sucesso'){
        console.log(resposta);

        $('#'+resp.id_morada+'_estado').val(resp.conteudo);
        $('#estado_'+resp.id_morada).html('');
        $('#estado_'+resp.id_morada).append(resp.conteudo_tr);
        $('#id_morada').val(resp.id_morada);
        $('#add_adress').hide();      
      }
    });
  }
</script>

<script>
  function deleteAdress(){
    document.getElementById("form-add").reset();
    $('#add_adress').hide();
    var id = $('#id_modal').val();

    $.ajax({
      type: "POST",
      url: '{{ route('deleteAdressPost') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      
      try{ resp=$.parseJSON(resposta); }
      catch (e){
        if(resposta){ $("#spanErro").html(resposta); }
        else{ $("#spanErro").html('ERROR'); }
        return;
      }
      if(resp.estado == 'sucesso'){
        $('#tr_'+resp.id_morada).remove();
        $('#id_morada').val('');
        document.getElementById("form-add").reset();

        if (resp.count_m == 1) {
          $('#new_adress').append('<tr><td style="background-color:#fbfbfb;" valign="top" colspan="6" class="dataTables_empty">Sem dados disponíveis na tabela</td><tr>');
        }
      }
    });
  }
</script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 6 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [[ 0, "asc" ]],
    });

    //$('#sortable_length').hide(); 
    //$('#sortable_filter').hide();
    $('#sortable_filter input').attr('placeholder', ' {{ trans('seller.Search') }}');
    //$('#sortable_filter input').attr('placeholder', 'Pesquisar');
    $('#sortable_length').html('<button class="bt-blue" style="margin-bottom:30px;" onclick="addAdress();"><i class="fas fa-plus"></i> {{ trans('seller.Add_Adress') }}</button>');
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();

  });
</script>
@stop