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
          <h3>{{ trans('seller.Order') }} {{ $encomenda->id }}</h3>
        </div>
        <div class="mod-area">

          <a href="{{ route('ordersDetailsV2', $encomenda->id) }}">
            <button class="bt-blue"><i class="fas fa-angle-left"></i> {{ trans('seller.Back') }}</button>
          </a>

          <table class="orders-details-desc">
            <tr>
              <td class="orders-line-right">
                <span class="tx-gray margin-right5">{{ trans('seller.Date') }}:</span>
                <span class="tx-jet"><i class="fas fa-calendar-alt"></i> {{ date('Y-m-d', $encomenda->data) }}</span>
              </td>
              <td class="orders-line-right">
                <span class="tx-gray margin-right5">{{ trans('seller.User') }}:</span>
                <span class="tx-jet"><i class="far fa-user"></i> {{ $comerciante->nome }}</span>
              </td>
              <td class="orders-details-status">
                <span class="tx-gray margin-right5">{{ trans('seller.Status') }}:</span>
                <span class="tx-jet">
                  @if($encomenda->estado == 'em_processamento') <i class="fas fa-circle tx-amarelo-claro margin-right5"></i> {{ trans('seller.processing') }} 
                  @elseif($encomenda->estado == 'registada') <i class="fas fa-circle tx-azul-claro margin-right5"></i> {{ trans('seller.resgisted') }} 
                  @elseif($encomenda->estado == 'concluida') <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.completed') }} 
                  @else <i class="fas fa-circle tx-red margin-right5"></i> {{ trans('seller.overdue_invoice') }} @endif
                </span>
              </td>
            </tr>
          </table>

          
            <div class="modulo-table">
              <div class="modulo-scroll">
                <table class="modulo-body" id="sortable_summary">
                  <thead>
                    <tr>
                      <th>{{ trans('seller.Article') }}</th>
                      <th class="tx-right">{{ trans('seller.Amount') }}</th>
                      <th class="tx-right">{{ trans('seller.Value') }}</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                    @php $valor_total_t = 0; $qtd_final = 0;@endphp
                    @foreach($morada_armazem as $value)
                      @php $valor_total_p = 0; $qtd_total_line = 0;@endphp
                                          
                        @foreach($array_morada as $morada)
                          @if($morada['valor'] == 1)
                            @if($morada['id_morada'] == $value->id)
                              <tr class="bg-white modulo-body">
                                <td class="tx-bold">{{ $value->morada }}</td>
                                <td></td>
                                <td></td>
                              </tr>
                            @endif
                          @endif
                        @endforeach
                      
                      @foreach($enc_linha as $enc)
                        @if($enc['id_morada'] == $value->id)
                          @php
                            $valor_line = $enc['quantidade'] * ($enc['qtd_caixa']* $enc['valor']);
                            $valor_dec = number_format((float)round( $valor_line ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
                            $valor_total_t = number_format((float)round( $valor_total_t + $valor_dec ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
                            $valor_total_p = number_format((float)round( $valor_total_p + $valor_dec ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
                            $qtd_total_line = $qtd_total_line + $enc['quantidade'];
                            $qtd_final = $qtd_final + $enc['quantidade'];

                            $valor_iva = round($valor_total_t * 0.06, 2); 
                            $valor_com_iva = round($valor_total_t + $valor_iva, 2);
                          @endphp
                          
                          <tr class="enc_prod">
                            <td>{{ $enc['produto'] }}</td>
                            <td class="tx-right">{{ $enc['quantidade'] }}</td>
                            <td class="tx-right">{{ $valor_dec }} €</td>
                          </tr>
                        @endif
                      @endforeach

                      @if($qtd_total_line > 0)
                        <tr class="bg-white modulo-body">
                          <td></td>
                          <td class="tx-right">{{ trans('seller.Total_partial') }}<span class="tx-bold margin-left20">{{ $qtd_total_line }}</span></td>
                          <td class="tx-right">{{ trans('seller.Total_partial') }}<span class="tx-bold margin-left20">{{ $valor_total_p }} €</span> </td>
                        </tr>

                        <tr>
                          <td></td>
                          <td></td>
                          <td class="tx-right">{{ trans('seller.Carrying') }} <span class="tx-bold margin-left20">{{ $valor_total_t }} €</span></td>
                        </tr>
                      @endif
                    @endforeach
                    
                    <tr class="bg-white modulo-body orders-line-top">
                      <td></td>
                      <td class="tx-right">
                        <i class="fas fa-info-circle orders-details-info" data-toggle="modal" data-target="#modalDelete"></i>{{ trans('seller.Total_units') }} 
                        <span class="tx-bold margin-left20"> {{ $qtd_final }}</span>
                      </td>
                      <td class="tx-right">{{ trans('seller.Amount_Total') }} <span class="tx-bold margin-left20"> {{ $valor_total_t }} € </span></td>
                    </tr>

                    <tr class="bg-white border-bottom-line" >
                      <td></td>
                      <td></td>
                      <td class="tx-right">{{ trans('seller.IVA') }} (6%)<span class="tx-bold margin-left20"> {{ $valor_iva }} € </span></td>
                    </tr>

                    <tr class="bg-white">
                      <td></td>
                      <td></td>
                      <td class="tx-right">{{ trans('seller.Total') }}<span class="tx-bold margin-left20">{{ $valor_com_iva}} € </span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          <div class="tx-right margin-top30">
            <a href="{{ route('ordersDetailsV2',$encomenda->id) }}">
              <button class="bt bt-blue"><i class="fas fa-angle-left"></i> {{ trans('seller.Back') }}</button>
            </a>
          </div>
        </div>  

        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="margin-bottom0">{{ trans('seller.Total_Units') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <table class="modulo-body" id="sortable">
                  <thead>
                    <tr>
                      <th class="tx-left">{{ trans('seller.Article') }}</th>
                      <th class="tx-right">{{ trans('seller.Amount') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($enc_linha as $enc)
                      <tr class="tx-left">
                        <td>{{ $enc['produto'] }}</td>
                        <td class="dashboard-col-add-2">{{ $enc['quantidade'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

                <div class="tx-center margin-top30">
                  <button class="bt-blue" data-dismiss="modal"><i class="fas fa-times margin-right5"></i>{{ trans('seller.Close') }}</button>
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
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
@stop