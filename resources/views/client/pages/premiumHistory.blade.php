@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  @include('client/includes/headerSubMenu')

  <div class="container">
    <div class="row">
      <div class="col-lg-3">@include('client/includes/menu')</div>
      <div class="col-lg-9">
        <div class="bg-white">
          <div class="data-border-form">
            <label>{{ trans('site_v2.Premium_History') }}</label>
          </div>
            <div class="history-premium">

              <table class="table table-striped table-datos overflowX">
                <thead>
                  <tr class="table-thead">
                    <th scope="col">{{ trans('site_v2.Request_Date') }}</th>
                    <th scope="col">{{ trans('site_v2.Article') }}</th>
                    <th scope="col">{{ trans('site_v2.Send_date') }}</th>
                    <th scope="col">{{ trans('site_v2.State') }}</th>
                    <!--<th scope="col"></th>-->
                  </tr>
                </thead>
                <tbody>
                  @foreach($primeiros_dez as $value)
                    <tr id="hideAll_{{ $value->id }}_{{ $value->id_pedido }}" class="table-height-tr">
                      <td scope="row">{{ date('Y-m-d',$value->data_pedido) }}</td>
                      <td scope="row">
                        <div class="history-premium-div-img"><img class="history-premium-img" src="{{ $value->img }}"></div><span class="history-premium-span">{{ $value->name }}</span>
                      </td>
                      <td>@if(isset($value->data_entrega)) {{ date('Y-m-d',$value->data_entrega) }} @else - @endif</td>
                      <td scope="row">
                        @if($value->estado == 'processamento')<i class="fas fa-circle tx-navy margin-right10"></i> {{ trans('site_v2.processing') }}
                        @elseif($value->estado == 'enviado')<i class="fas fa-circle tx-amarelo-claro margin-right10"></i> {{ trans('site_v2.in_dispatch') }}
                        @elseif($value->estado == 'concluido') <i class="fas fa-circle tx-lightgreen margin-right10"></i> {{ trans('site_v2.sent') }} @endif
                      </td>
                      @if( date('Y-m-d',strtotime(date('Y-m-d',$value->data_pedido). ' + '.$notificacao_dias->valor.'days')) < date('Y-m-d',strtotime(date('Y-m-d'))) && ($value->estado != 'concluido'))
                        <td scope="row">
                          <i class="fas fa-info-circle tx-coral cursor-pointer" data-toggle="modal" data-target="#modalErro_{{ $value->id }}"></i>
                        </td>
                      @endif
                    </tr>
                  @endforeach

                  @foreach($premios_utilizador as $value)
                    <tr id="showAll_{{ $value->id }}_{{ $value->id_pedido }}" class="table-height-tr display-none">
                      <td scope="row">{{ date('Y-m-d',$value->data_pedido) }}</td>
                      <td scope="row">
                        <div class="history-premium-div-img"><img class="history-premium-img" src="{{ $value->img }}"></div><span class="history-premium-span">{{ $value->name }}</span>
                      </td>
                      <td>@if(isset($value->data_entrega)) {{ date('Y-m-d',$value->data_entrega) }} @else - @endif</td>
                      <td scope="row">
                        @if($value->estado == 'processamento')<i class="fas fa-circle tx-navy margin-right10"></i> {{ trans('site_v2.processing') }}
                        @elseif($value->estado == 'enviado')<i class="fas fa-circle tx-amarelo-claro margin-right10"></i> {{ trans('site_v2.in_dispatch') }}
                        @elseif($value->estado == 'concluido') <i class="fas fa-circle tx-lightgreen margin-right10"></i> {{ trans('site_v2.sent') }} @endif
                      </td>
                      @if( date('Y-m-d',strtotime(date('Y-m-d',$value->data_pedido). ' + '.$notificacao_dias->valor.'days')) < date('Y-m-d',strtotime(date('Y-m-d'))) && ($value->estado != 'concluido'))
                        <td scope="row">
                          <i class="fas fa-info-circle tx-coral cursor-pointer" data-toggle="modal" data-target="#modalErro_{{ $value->id }}"></i>
                        </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
              
              @if(count($premios_utilizador) > 10)
                <div class="table-view">
                  <a id="search_plus" onclick="openRotulosInvalidos();"><span class="tx-navy">{{ trans('site_v2.view_more') }} <i class="fas fa-angle-down"></i></span></a>
                  <a id="search_minus" class="display-none" onclick="closeRotulosInvalidos();"><span class="tx-navy">{{ trans('site_v2.see_less') }} <i class="fas fa-angle-up"></i></span></a>
                </div>
              @endif

              @foreach($premios_utilizador as $value)
                <!-- Modal -->
                <div class="modal fade" id="modalErro_{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close premio-icon-modal" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body tx-center">
                        <h3 style="margin-bottom:20px;">{{ trans('site_v2.premium_not_arrived_txt') }}</h3>
                        <p>{{ trans('site_v2.Ask_about_premium_txt') }}</p>
                        <button id="ask_info" type="submit" class="bt bt-blue margin-top30" onclick="askInformation({{ $value->id_pedido }},{{ $value->id_premio }},{{ $value->data_pedido }},{{ $value->id }});"><i class="fas fa-check"></i> {{ trans('site_v2.Ask_for_information') }}</button>

                        <label id="labelSucessoInfo" class="av-100 alert-success display-none" role="alert"><span id="spanSucessoInfo">{{ trans('site_v2.Answer_contact_txt') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="bg-gradient-blue">
  <div class="premium-banner" style="background: url('/site_v2/img/site/code-hero-back.png')no-repeat center;">
    <div class="container">
      <h1>{{ trans('site_v2.Insufficient_points') }}</h1>
      <button class="bt-gray tx-transform">{{ trans('site_v2.BUY_POINTS') }}</button>
    </div>
  </div>
</div>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>

  <script>
    function openRotulosInvalidos(){

      @foreach($premios_utilizador as $value)
        $('#showAll_'+{!! $value->id !!}+'_'+{!! $value->id_pedido !!}).show();
        $('#hideAll_'+{!! $value->id !!}+'_'+{!! $value->id_pedido !!}).hide();
      @endforeach

      
      $('#search_plus').hide();
      $('#search_minus').show();
    };

    function closeRotulosInvalidos(){

      @foreach($premios_utilizador as $value)
        $('#hideAll_'+{!! $value->id !!}+'_'+{!! $value->id_pedido !!}).show();
        $('#showAll_'+{!! $value->id !!}+'_'+{!! $value->id_pedido !!}).hide();
      @endforeach
        
      $('#search_plus').show();
      $('#search_minus').hide();
    };
  </script>

  <script>
    function askInformation(id_pedido,id_premio,data,id){

      $.ajax({
        type: "POST",
        url: '{{ route('askInfoPostV2') }}',
        data: {id_pedido:id_pedido, id_premio:id_premio, data:data},
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        $('#modalErro_'+id).modal('hide');
        $('#labelSucesso').show();
        $('#spanSucesso').html('{{ trans('site_v2.Answer_contact_txt') }}');
      });
    }
  </script>
@stop