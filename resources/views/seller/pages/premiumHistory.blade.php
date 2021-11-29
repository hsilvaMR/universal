@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3"> @include('seller/includes/menu') </div>
      <div class="col-lg-9">
        <div class="mod-tit">
          <h3>{{ trans('seller.Premium_History') }}</h3>
        </div>
        
        <div class="mod-area">
          <div class="modulo-scroll font16">

            <table class="modulo-body" id="sortable">
              <thead>
                <tr>
                  <th>{{ trans('seller.Request_Date') }}</th>
                  <th>{{ trans('seller.Article') }}</th>
                  <th>{{ trans('seller.Send_Date') }}</th>
                  <th>{{ trans('seller.Status') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="modulo-body">
                @foreach($premios as $val)
                  <tr>
                    <td>{{ date('Y-m-d',$val->data_pedido) }}</td>
                    <td>
                      <div class="float-left">
                        <span class="premium-img"><img height="25" src="{{ $val->img }}"></span> 
                      </div>
                      <span>{{ $val->nome_pt }}</span> <br> 
                      <span class="font14">{{ $val->descricao_pt }}</span>
                    </td>
                    <td>@if($val->data_envio){{ date('Y-m-d',$val->data_envio) }}@endif</td>
                    <td>
                      @if($val->estado_pedido == 'processamento') 
                        <i class="fas fa-circle tx-amarelo-claro margin-right5"></i> {{ trans('seller.processing') }} 
                      @else 
                        <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.delivered') }} 
                      @endif
                    </td>
                    <td class="tx-red">
                      @if(date('Y-m-d',strtotime(date('Y-m-d',$val->data_pedido). ' + 7 days')) < date('Y-m-d',strtotime(date('Y-m-d'))) && ($val->estado != 'enviado')) 
                        <i class="fas fa-info-circle cursor-pointer" data-toggle="modal" data-target="#modalErro_{{ $val->id }}"></i> 
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table> 
          </div>
          @foreach($premios as $value)
            <!-- Modal -->
            <div class="modal fade" id="modalErro_{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close premio-icon-modal" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body tx-center">
                    <h3>{{ trans('seller.premium_not_arrived_txt') }}</h3>
                    <p>{{ trans('seller.Ask_about_premium_txt') }}</p>
                    <button id="ask_info" type="submit" class="bt bt-blue margin-top30" onclick="askInformation({{ $value->id }},{{ $value->data_pedido }});">
                      <i class="fas fa-check"></i> {{ trans('seller.Ask_for_information') }}
                    </button>

                    <label id="labelSucessoInfo" class="av-100 alert-success display-none" role="alert">
                      <span id="spanSucessoInfo"></span>
                      <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
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
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']], 
    });
    $('#sortable_filter').html('<a href="{{ route('changePointsV2') }}"><button class="bt bt-blue" style="margin-bottom:30px;"><i class="fas fa-exchange-alt"></i> {{ trans('seller.Change_Points') }}</button></a>');
    $('.paginate_button i').addClass('fas');
    $('.sorting').css('background-image','url()');
    $('.sorting_asc').css('background-image','url()');

    if({!! $count_premios !!} == 0){
      $('#sortable_paginate').hide();
    }
  });
</script>

<script>
  function askInformation(id_pedido,data){

    $.ajax({
      type: "POST",
      url: '{{ route('askSendPremiumPost') }}',
      data: {id_pedido:id_pedido,data:data},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      resp = $.parseJSON(resposta);
      console.log(resp);
      if(resp.estado == 'sucesso'){
        $('#ask_info').hide();
        $('#labelSucessoInfo').show();
        $('#spanSucessoInfo').append(resp.mensagem);
      }
    });
  }
</script>



@stop