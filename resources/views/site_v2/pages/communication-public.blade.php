@extends('site_v2/layouts/default')

@section('content')

<div class="container" id="tbl_container" style="margin-top:140px; width:100%;">
  <div class="mod-table" style="width:100%;">
    <table class="table table-striped" style="margin-top:12px;" id="sortable">
      <thead class="table-dark mt-5">
        <tr>
          <th scope="col">#</th>
          <th scope="col">{{ trans('backoffice.communiFile') }}</th>
          <th scope="col">{{ trans('backoffice.communNome') }}</th>
          <th scope="col">{{ trans('backoffice.comunDesc') }}</th>
          <th scope="col">{{ trans('backoffice.communUpdate') }}</th>
          <th scope="col">{{ trans('backoffice.communTipo') }}</th>
          <th class="text-center" scope="col">{{ trans('backoffice.commuAction') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($array as $val)
        <tr id="linha_{{ $val['id'] }}">

          <td>{!! $val['id'] !!}</td>
          <td>{!! $val['ficheiro'] !!}</td>
          <td>{!! $val['nome'] !!}</td>
          <td>{!! $val['descr'] !!}</td>
          <td>{!! $val['update'] !!}</td>
          <td>{!! $val['tipo'] !!}</td>
          <td class="text-center">
            {{-- download   --}}
            <a class="table-opcao" href="{{ route('downloadPage',['id'=>$val['id']]) }}">
              <span class="table-opcao">
                <i class="fas fa-file-download"></i>&nbsp;{{trans('backoffice.commuDownload')}}
              </span>
            </a>&ensp;
            {{-- share link  --}}
            <input type="hidden" id="id_copy-{{$val['id']}}" value="{{ route('downloadPage',['id'=>$val['id']]) }}">
            <span class="table-opcao" id="btnShareLink" onclick="copyLink_v2({{$val['id']}})" style="cursor:pointer;">
              <i class="fas fa-clone"></i>&nbsp;{{trans('backoffice.commuShare')}}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script>
  $('.header-span').css('color','#333');
		$('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
		$('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
		$('.header-submenu a').css('color','#333');
		$('.header').css('background-color','#fff');
		$('.header-xs').css('background-color','#fff');
		//$('.tbl_container').addClass('mt-5');

	 function copyLink_v1(id){
      
     /* Get the text field */
   var url=$('#id_copy-'+id).select();
   //texto = url.val();
   //texto.select();
   document.execCommand("copy")
   
  }
  
    function copyLink_v2(id){
        
       var url = document.getElementById("id_copy-"+id);
       copyToClipboard(url.value);
       alert("link copiado com sucesso")
      // $.notific8('{{ trans('backoffice.comuniNotifCopyLink') }}');
    }
    
    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
   }
   
    //<!-- PAGINAR -->
    $(document).ready(function(){
        $('#sortable').dataTable({
            aoColumnDefs: [{
				"bSortable": false,
				"aTargets": [1,6]
			}]
			
        })
   });
   
</script>

@stop