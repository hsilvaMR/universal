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
          <h3>{{ trans('seller.Orders') }}</h3>
        </div>
        <div class="mod-area">
          <div class="float-left">
            <a href="{{ route('ordersV2') }}"><button class="bt bt-blue"><i class="fas fa-angle-left"></i> {{ trans('seller.Back') }}</button></a>
          </div>

          <table class="orders-details-desc">
            <tr>
              <td class="orders-line-right">
                <span class="margin-right5 tx-gray">{{ trans('seller.Date') }}:</span> 
                <span class="tx-jet"><i class="fas fa-calendar-alt"></i> {{ date('Y-m-d',$encomenda->data) }}</span>
              </td>
              <td class="orders-line-right">
                <span class="margin-right5 tx-gray">{{ trans('seller.User') }}:</span> 
                <span class="tx-jet"><i class="far fa-user"></i> {{ $encomenda->nome }}</span>
              </td>
              <td class="orders-details-status">
                <span class="margin-right5 tx-gray">{{ trans('seller.Status') }}:</span> 
                @if($encomenda->estado_enc == 'em_processamento') 
                  <i class="fas fa-circle tx-amarelo-claro margin-right5"></i> 
                  <span class="tx-jet">{{ trans('seller.processing') }}</span>
                @elseif($encomenda->estado_enc == 'registada') 
                  <i class="fas fa-circle tx-blue margin-right5"></i> 
                  <span class="tx-jet">{{ trans('seller.resgisted') }}</span>
                @elseif($encomenda->estado_enc == 'concluida') 
                  <i class="fas fa-circle tx-lightgreen margin-right5"></i> 
                  <span class="tx-jet">{{ trans('seller.completed') }}</span>
                @else 
                  <i class="fas fa-circle tx-red margin-right5"></i> 
                  <span class="tx-jet">{{ trans('seller.overdue_invoice') }}</span> 
                @endif
              </td>
            </tr>
          </table>
          
          <div class="modulo-table">
            <div class="modulo-scroll orders-details">
              <table class="modulo-body" id="sortable">
                <thead>
                  <tr>
                    <th>{{ trans('seller.Adres') }}</th>
                    <th>{{ trans('seller.Order') }}</th>
                    <th>{{ trans('seller.Expedition') }}</th>
                    <th>{{ trans('seller.Invoice') }}</th>
                    <th>{{ trans('seller.Proof') }}</th>
                    <th>{{ trans('seller.Receipt') }}</th>
                    <th>{{ trans('seller.Return_Note') }}</th>
                  </tr>
                </thead>
                <tbody id="order_details" class="orders-line-bottom">
                  @foreach($encomenda_armazem as $val)
                    <tr id="adress_{{ $val->id_line }}">
                      <td>{{ $val->nome_personalizado }}</td>
                      <td class="tx-underline">
                        <a class="tx-navy" @if(file_exists(base_path('public_html/doc/orders/'.$val->doc_encomenda))) href="/doc/orders/{{ $val->doc_encomenda }}" @else href="{{ route('ordersAdressPdfV2',[$encomenda->id_enc,$val->id_morada,$company->id]) }}" @endif download>
                          <i class="far fa-file-pdf"></i> {{ $val->doc_encomenda }}
                        </a>
                      </td>
                      <td>
                        @if($val->doc_guia)
                          <span class="tx-navy tx-underline"> <i class="far fa-file-pdf"></i> {{ $val->doc_guia }}</span>
                          <br>
                          <span class="font12">{{ date('Y-m-d',$val->data_guia) }}</span>
                        @else
                          @if($val->estado_armaz == 'em_processamento')
                            <i class="fas fa-circle tx-orange-esc margin-right5"></i> {{ trans('seller.processing') }} 
                          @elseif($val->estado_armaz == 'registada') 
                            <i class="fas fa-circle tx-blue margin-right5"></i> {{ trans('seller.resgisted') }} 
                          @elseif($val->estado_armaz == 'concluida') 
                            <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.completed') }}
                          @elseif($val->estado_armaz == 'expedida')
                            <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.spent') }}
                          @elseif($val->estado_armaz == 'expedida_parcialmente')
                            <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.partially_dispatched') }}
                          @elseif($val->estado_armaz == 'backoffice')
                            <i class="fas fa-circle tx-amarelo-claro margin-right5"></i> {{ trans('seller.processing') }}
                          @else 
                            <i class="fas fa-circle tx-red margin-right5"></i> {{ trans('seller.overdue_invoice') }}
                          @endif
                        @endif
                      </td>
                      <td>
                        @if($val->doc_fatura)
                          <a href="/doc/orders/{{ $val->doc_fatura }}" download><span class="tx-navy tx-underline"> <i class="far fa-file-pdf"></i> {{ $val->doc_fatura }}</span></a>
                          <br>
                          <span class="font12">{{ date('Y-m-d',$val->data_fatura) }}</span>
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        @if($val->doc_comprovativo)
                          <div id="line_{{ $val->id_line }}">
                            @if($val->estado_armaz != 'concluida')<i class="fas fa-times tx-red margin-right5 cursor-pointer" onclick="deleteDoc({{ $val->id_line }},'parcial');"></i>@endif 
                            <a id="payment{{ $val->id_line }}" class="tx-navy" href="/doc/orders/{{ $val->doc_comprovativo }}" download>
                              <span class="tx-underline"><i class="far fa-file-pdf"></i> {{ $val->doc_comprovativo }}</span>
                            </a>
                            <br>
                            <span id="date{{ $val->id_line }}" class="font12">{{ date('Y-m-d',$val->data_comprovativo) }}</span>
                          </div>

                          <form class="display-none" id="form_comprovativo{{ $val->id_line }}" action="{{ route('addComprovativoPost') }}" name="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $val->id_line }}">
                            <label for="doc_{{ $val->id_line }}" class="tx-navy cursor-pointer">
                              <i class="fas fa-cloud-upload-alt"></i> {{ trans('seller.upload') }}
                            </label>
                            <input id="doc_{{ $val->id_line }}" type="file" name="comprovativo{{ $val->id_line }}" onchange="$(this).submit();">
                          </form>
                        @else
                          <div id="line_{{ $val->id_line }}" class="display-none">
                            <i class="fas fa-times tx-red margin-right5 cursor-pointer" onclick="deleteDoc({{ $val->id_line }},'parcial');"></i> 
                            <a id="payment{{ $val->id_line }}" class="tx-navy tx-underline" href="/doc/orders/{{ $val->doc_comprovativo }}" download>
                              <span class="tx-underline"><i class="far fa-file-pdf"></i> {{ $val->doc_comprovativo }}</span>
                            </a>
                            <br>
                            <span id="date{{ $val->id_line }}" class="font12">{{ date('Y-m-d',$val->data_comprovativo) }}</span>
                          </div>
                          <form id="form_comprovativo{{ $val->id_line }}" action="{{ route('addComprovativoPost') }}" name="form" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $val->id_line }}">
                            <label for="doc_{{ $val->id_line }}" class="tx-navy cursor-pointer">
                              <i class="fas fa-cloud-upload-alt"></i> {{ trans('seller.upload') }}
                            </label>
                            <input id="doc_{{ $val->id_line }}" type="file" name="comprovativo{{ $val->id_line }}" onchange="$(this).submit();">
                          </form>
                        @endif
                      </td>
                      <td>
                        @if($val->doc_recibo)
                          <a href="/doc/orders/{{ $val->doc_recibo }}" download><span class="tx-navy tx-underline"> <i class="far fa-file-pdf"></i> {{ $val->doc_recibo }}</span></a>
                          <br>
                          <span class="font12">{{ date('Y-m-d',$val->data_recibo) }}</span>
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        @if($val->doc_proforma)
                          <a href="/doc/orders/{{ $val->doc_proforma }}" download><span class="tx-navy tx-underline"> <i class="far fa-file-pdf"></i> {{ $val->doc_proforma }}</span></a>
                          <br>
                          <span class="font12">{{ date('Y-m-d',$val->data_proforma) }}</span>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  @endforeach                                       
                </tbody>
                <tr>
                  <td></td>
                  <td class="tx-navy tx-underline">
                    <a @if(file_exists(base_path('public_html/doc/orders/'.$encomenda->documento))) href="/doc/orders/{{ $encomenda->documento }}" @else href="{{ route('ordersPdfV2',[$encomenda->id_enc,$company->id]) }}" @endif class="tx-navy" download><i class="far fa-file-pdf"></i> {{ $val->doc_total }}</a>
                  </td>
                  <td></td>
                  <td>
                    @if($encomenda->fatura)
                      <a class="tx-navy" href="/doc/orders/{{ $encomenda->fatura }}" download><i class="far fa-file-pdf"></i> {{ $encomenda->fatura }}</a>
                      <br>
                      <span class="font12">{{ date('Y-m-d',$encomenda->data_fatura) }}</span>
                    @else - @endif
                  </td>
                  <td>
                    @if($encomenda->comprovativo)
                      <div id="line{{ $encomenda->id_enc }}">
                        @if($val->estado_armaz != 'concluida')<i class="fas fa-times tx-red margin-right5 cursor-pointer" onclick="deleteDoc({{ $encomenda->id_enc }},'total');"></i>@endif 
                        <a id="payment_{{ $encomenda->id_enc }}" class="tx-navy" href="/doc/orders/{{ $encomenda->comprovativo }}" download>
                          <span class="tx-underline"><i class="far fa-file-pdf"></i> {{ $encomenda->comprovativo }}</span>
                        </a>
                        <br>
                        <span id="date_{{ $encomenda->id_enc }}" class="font12">{{ date('Y-m-d',$encomenda->data_comprovativo) }}</span>
                      </div>

                      <form id="comprovativo_total" class="display-none" action="{{ route('addComprovativoPost') }}" name="form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_encomenda" value="{{ $encomenda->id_enc }}">
                        <label for="doc_{{ $encomenda->id_enc }}" class="tx-navy cursor-pointer">
                          <i class="fas fa-cloud-upload-alt"></i> {{ trans('seller.upload') }}
                        </label>
                        <input id="doc_{{ $encomenda->id_enc }}" type="file" name="comprovativo_total" onchange="$(this).submit();">
                      </form>
                    @else
                      <div id="line{{ $encomenda->id_enc }}" class="display-none">
                        <i class="fas fa-times tx-red margin-right5 cursor-pointer" onclick="deleteDoc({{ $encomenda->id_enc }},'total');"></i> 
                        <a id="payment_{{ $encomenda->id_enc }}" class="tx-navy tx-underline" href="/doc/orders/{{ $encomenda->comprovativo }}" download>
                          <span class="tx-underline"><i class="far fa-file-pdf"></i> {{ $encomenda->comprovativo }}</span>
                        </a>
                        <br>
                        <span id="date_{{ $encomenda->id_enc }}" class="font12">{{ date('Y-m-d',$encomenda->data_comprovativo) }}</span>
                      </div>
                      <form id="comprovativo_total" action="{{ route('addComprovativoPost') }}" name="form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_encomenda" value="{{ $encomenda->id_enc }}">
                        <label for="doc_{{ $encomenda->id_enc }}" class="tx-navy cursor-pointer">
                          <i class="fas fa-cloud-upload-alt"></i> {{ trans('seller.upload') }}
                        </label>
                        <input id="doc_{{ $encomenda->id_enc }}" type="file" name="comprovativo_total" onchange="$(this).submit();">
                      </form>
                    @endif
                  </td>
                  <td>
                    @if($encomenda->recibo)
                      <a class="tx-navy" href="/doc/orders/{{ $encomenda->recibo }}" download><i class="far fa-file-pdf"></i> {{ $encomenda->recibo }}</a>
                      <br>
                      <span class="font12">{{ date('Y-m-d',$encomenda->data_recibo) }}</span>
                    @else - @endif
                  </td>
                  <td></td>
                </tr>
              </table>
            </div>
          </div>
          <div class="tx-right margin-top30">
            <a href="{{ route('ordersDetailsAllV2', $encomenda->id_enc) }}">
              <button class="bt bt-blue"><i class="far fa-eye"></i> {{ trans('seller.Details') }}</button>
            </a>
          </div>
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
  @foreach($encomenda_armazem as $val)
    $('#form_comprovativo'+{!! $val->id_line !!}).on('submit',function(e) {
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
          
          return;
        }
        if(resp.estado == 'sucesso'){
          $('#line_'+resp.id_enc).show();
          $('#payment'+resp.id_enc).attr('href','/doc/orders/'+resp.doc);
          $('#payment'+resp.id_enc).html('<i class="far fa-file-pdf"></i> '+ '<span class="tx-underline">'+resp.doc+'</span>');
          $('#date'+resp.id_enc).html(resp.date);
          $('#form_comprovativo'+resp.id_enc).hide();
        }
      });
    });
  @endforeach
</script>

<script>
  $('#comprovativo_total').on('submit',function(e) {
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
          
          return;
      }
      if(resp.estado == 'sucesso'){
        $('#line'+resp.id_enc).show();
        $('#payment_'+resp.id_enc).attr('href','/doc/orders/'+resp.doc);
        $('#payment_'+resp.id_enc).html('<i class="far fa-file-pdf"></i> '+ '<span class="tx-underline">'+resp.doc+'</span>');
        $('#date_'+resp.id_enc).html(resp.date);
        $('#comprovativo_total').hide();
      }
    });
  });
</script>

<script>
  function deleteDoc(id,tipo){

    

    $.ajax({
      type: "POST",
      url: '{{ route('deleteComprovativoPost') }}',
      data: {id:id,tipo:tipo},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) { 
      console.log(resposta);
      if(resposta=='sucesso'){
        if (tipo == 'parcial') {
          $('#form_comprovativo'+id).show();
          $('#line_'+id).hide();
        }
        else{
          $('#comprovativo_total').show();
          $('#line'+id).hide();
        }
      }
    });
  }
</script>
@stop