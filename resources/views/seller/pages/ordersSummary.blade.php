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
          <h3>{{ trans('seller.Order_Summary') }}</h3>
        </div>
        <div class="mod-area">

          <a href="{{ route('ordersNewV2') }}">
            <button class="bt-blue"><i class="fas fa-angle-left"></i> {{ trans('seller.Back') }}</button>
          </a>
          <div class="dashboard-col-add">
                
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
                    @php $valor_total_t = 0;$qtd_final = 0; @endphp
                    @php $valor_total_p = 0;$qtd_total_line = 0;@endphp
                                          
                    @foreach($enc_linha as $enc)
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
                      <tr class="bg-white modulo-body">
                          <td class="tx-bold">{{ $enc['morada'] }}</td>
                          <td></td>
                          <td></td>
                        </tr>
                      <tr class="enc_prod modulo-body">
                        <td>{{ $enc['produto'] }}</td>
                        <td class="tx-right">{{ $enc['quantidade'] }}</td>
                        <td class="tx-right">{{ $valor_dec }} €</td>
                      </tr>
                    @endforeach

                    @if($qtd_total_line > 0)
                      <tr class="bg-white modulo-body">
                        <td></td>
                        <td class="tx-right">{{ trans('seller.Total_partial') }}<span class="tx-bold margin-left20">{{ $qtd_total_line }}</span></td>
                        <td class="tx-right">{{ trans('seller.Total_partial') }}<span class="tx-bold margin-left20">{{ $valor_total_p }} €</span> </td>
                      </tr>

                      <!--<tr>
                        <td></td>
                        <td></td>
                        <td class="tx-right">{{ trans('seller.Carrying') }} <span class="tx-bold margin-left20">{{ $valor_total_t }} €</span></td>
                      </tr>-->
                    @endif
          
                    
                    <tr class="bg-white orders-line-top">
                      <td></td>
                      <td class="tx-right">
                        <i class="fas fa-info-circle orders-details-info" data-toggle="modal" data-target="#modalDelete"></i>
                        {{ trans('seller.Total_units') }}
                        <span class="tx-bold margin-left20"> {{ $qtd_final }}</span>
                      </td>
                      <td class="tx-right">{{ trans('seller.Amount_Total') }} <span class="tx-bold margin-left20"> {{ $valor_total_t }} € </span></td>
                    </tr>

                    <tr class="bg-white modulo-body">
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
          </div>

          <div class="tx-right margin-top30">
            <button onclick="next({!! $encomenda->id !!});" class="bt bg-green"><i class="fas fa-check"></i> {{ trans('seller.Confirm') }}</button>
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

<script>
  function next(id){
   
    $.ajax({
      type: "POST",
      url: '{{ route('nextOrderPost') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){}
          else{}
          return;
      }
      if (resp.estado == 'sucesso') {
        
        window.location.href = '{{ route('ordersPdfV2',[$encomenda->id,$company->id]) }}';
        setTimeout(function() {window.location.href = '{{ route('ordersSucessV2',$encomenda->id) }}';}, 1000);
      }
    });
  }

</script>
@stop