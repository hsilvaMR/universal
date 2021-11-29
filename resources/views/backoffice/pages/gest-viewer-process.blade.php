@extends('backoffice/layouts/default')

@section('content')
	<?php $arrayCrumbs = [ $headTitulo => route('certificationsIdPageB',$process->id_certificacao), $process->ref_processo.' - '.$process->nome_processo => route('certificationsProcessPageB',$process->id_processo)]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ $process->ref_processo }} - {{ $process->nome_processo }}</div>

  @if($process->tipo == 'diagrama')

    <div class="process_diagram">
      <h4 class="margin-bottom20 tx-azul">{{ trans('backoffice.ProcessMatrix') }}</h4>
      <img src="{{ $process->ficheiro }}">
    </div>
  @endif
  
	<div class="tabs">
    @if(isset($first_activit))
  		<div id="TAB{{ $first_activit->id }}" class="tab tab-active" onClick="mudarTab({{ $first_activit->id }});">
      	<span class="visible-xs">{{ $first_activit->id }}</span>
      	<span class="hidden-xs">{{ $first_activit->referencia }}</span>
      </div>
    @endif

    @if(isset($activits))
  		@foreach($activits as $value)
  	    <div id="TAB{{ $value->id }}" class="tab" onClick="mudarTab({{ $value->id }});">
  	    	<span class="visible-xs">{{ $value->id }}</span>
  	    	<span class="hidden-xs">{{ $value->referencia }}</span>
  	    </div>
      @endforeach
    @endif
	</div>

  @if(isset($first_activit))
    <div id="INF{{ $first_activit->id }}">
      <div class="modulo-table">
        <div class="page-titulo-table">{{ $first_activit->referencia }} - {{ $first_activit->nome }}</div>
        <div class="modulo-scroll">
          <table class="modulo-body">
            <thead>
              <tr>
                <th class="display-none"></th>
                <th>{{ trans('backoffice.Task') }}</th>
                <th>Resp</th>
                <th>Env</th>
                <th>{{ trans('backoffice.Input') }}</th>
                <th>{{ trans('backoffice.Output') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php $count=1; ?>
              @foreach($first_tarefa as $value)
                <tr id="linha_{{ $value->id }}">
                  <td class="display-none"></td>
                  <td>{{ $count }} - {{ $value->tarefa }}</td>
                  <td>
                    @foreach($responsavel as $resp)
                      @if($value->id == $resp['id_tarefa'])
                        <p>{{ $resp['nome'] }}</p>
                        <p>{{ $resp['sigla'] }}</p>
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach($env as $val)
                      @if($value->id == $val['id_tarefa'])
                        <p>{{ $val['nome'] }}</p>
                        <p>{{ $val['sigla'] }}</p>
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach($entrada as $val)
                      @if($value->id == $val['id_tarefa'])
                        @if($val['url'] != '')
                          <a href="{{ $val['url'] }}" target="_blank"><p>{{ $val['nome'] }}</p></a>
                        @elseif($val['doc'] != '')
                          <a href="{{ $val['doc'] }}" target="_blank" download><p>{{ $val['nome'] }}</p></a>
                        @else
                          <p>{{ $val['nome'] }}</p>
                        @endif
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @foreach($saida as $val)
                      @if($value->id == $val['id_tarefa'])
                        @if($val['url'] != '')
                          <a href="{{ $val['url'] }}" target="_blank"><p>{{ $val['nome'] }}</p></a>
                        @elseif($val['doc'] != '')
                          <a href="{{ $val['doc'] }}" target="_blank" download><p>{{ $val['nome'] }}</p></a>
                        @else
                          <p>{{ $val['nome'] }}</p>
                        @endif
                      @endif
                    @endforeach
                    <?php $count++; ?>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  @if(isset($activits))
    @foreach($activits as $value)
      <div id="INF{{ $value->id }}" class="display-none"> 
        <div class="modulo-table">
          <div class="page-titulo-table">{{ $value->referencia }} - {{ $value->nome }}</div>
          <div class="modulo-scroll">
            <table class="modulo-body">
              <thead>
                <tr>
                  <th class="display-none"></th>
                  <th>{{ trans('backoffice.Task') }}</th>
                  <th>Resp</th>
                  <th>Env</th>
                  <th>{{ trans('backoffice.Input') }}</th>
                  <th>{{ trans('backoffice.Output') }}</th>
                </tr>
              </thead>
              <tbody>
                <?php $count=1; ?>
                @foreach($tarefas_all as $val)
                  @if($val['id_atividade'] == $value->id)
                    <tr id="linha_{{ $val['id'] }}">
                      <td class="display-none"></td>
                      <td>{{ $count }} - {{ $val['tarefa'] }}</td>
                      <td>
                        @foreach($responsavel_all as $resp)
                          @if($val['id'] == $resp['id_tarefa'])
                            <p>{{ $resp['nome'] }}</p>
                            <p>{{ $resp['sigla'] }}</p>
                          @endif
                        @endforeach
                      </td>
                      <td>
                        @foreach($env_all as $env)
                          @if($val['id'] == $env['id_tarefa'])
                            <p>{{ $env['nome'] }}</p>
                            <p>{{ $env['sigla'] }}</p>
                          @endif
                        @endforeach
                      </td>
                      <td>
                        @foreach($entrada_all as $entrada)
                          @if($val['id'] == $entrada['id_tarefa'])
                            @if($entrada['url'] != '')
                              <a href="{{ $entrada['url'] }}" target="_blank"><p>{{ $entrada['nome'] }}</p></a>
                            @elseif($entrada['doc'] != '')
                              <a href="{{ $entrada['doc'] }}" target="_blank" download><p>{{ $entrada['nome'] }}</p></a>
                            @else
                              <p>{{ $entrada['nome'] }}</p>
                            @endif
                          @endif
                        @endforeach
                      </td>
                      <td>
                        @foreach($saida_all as $saida)
                          @if($val['id'] == $saida['id_tarefa'])
                            @if($saida['url'] != '')
                              <a href="{{ $saida['url'] }}" target="_blank"><p>{{ $saida['nome'] }}</p></a>
                            @elseif($saida['doc'] != '')
                              <a href="{{ $saida['doc'] }}" target="_blank" download><p>{{ $saida['nome'] }}</p></a>
                            @else
                              <p>{{ $saida['nome'] }}</p>
                            @endif
                          @endif
                        @endforeach
                      </td>
                    </tr>
                    <?php $count++; ?>
                  @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endforeach
  @endif

@stop

@section('css')
@stop

@section('javascript')
<script type="text/javascript">
  function mudarTab(numero) {
    var tabs = $('.tabs').find('.tab').length;
    for (var i=tabs; i>0; i--) {
      if(i==numero){
        $("#TAB"+i).addClass("tab-active");
        $("#INF"+i).css("display","block");
      }
      else{
        $("#TAB"+i).removeClass("tab-active");
        $("#INF"+i).css("display","none");
      }
    }
  }

</script>
@stop