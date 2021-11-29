<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class _TableManager extends Controller
{
  /*private $dados=[];
  private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = Cookie::get('admin_cookie')['lingua'];
          return $next($request);
      });
  }*/
  
  public function updateOnOff(Request $request)
  {
    $tabela=trim($request->tabela);
    $referencia= trim($request->referencia) ? trim($request->referencia) : 'id';
    $id=trim($request->id);
    $campo= trim($request->campo) ? trim($request->campo) : 'online';
    
    $query = \DB::table($tabela)->select($campo)->where($referencia,$id)->first();
    if(!empty($query)){
      $aux = ($query->$campo) ? 0 : 1 ;
      \DB::table($tabela)->where($referencia,$id)->update([$campo=>$aux]);
      
      if (($tabela == 'gest_certificacoes')) {
        //update cookie certificacoes
        $certifications = \DB::table('gest_certificacoes')->where('online','1')->get();

        $certificationsDados = [];
        foreach ($certifications as $value) {
          $certificationsDados[] = [
            'id'=> $value->id,
            'nome'=> $value->nome
          ];
        }
        
        $certificationsDados=json_encode($certificationsDados);
        Cookie::queue(Cookie::make('certifications_cookie', $certificationsDados, 43200));
      }

      return 'sucesso';
    }
    return 'erro';
  }

  public function deleteLine(Request $request)
  {
    $tabela=trim($request->tabela);
    $referencia= trim($request->referencia) ? trim($request->referencia) : 'id';
    $id=trim($request->id);
    if(\DB::table($tabela)->where($referencia,$id)->delete()){ 

      if (($tabela == 'gest_certificacoes')) {
        //update cookie certificacoes
        $certifications = \DB::table('gest_certificacoes')->where('online','1')->get();

        $certificationsDados = [];
        foreach ($certifications as $value) {
          $certificationsDados[] = [
            'id'=> $value->id,
            'nome'=> $value->nome
          ];
        }
        
        $certificationsDados=json_encode($certificationsDados);
        Cookie::queue(Cookie::make('certifications_cookie', $certificationsDados, 43200));
      }
      
      return 'sucesso'; 
    }
    return 'erro';
  }

  public function replaceDelete(Request $request)
  {
    $tabela_sub=trim($request->tabela_sub);
    $tabela_del=trim($request->tabela_del);
    $campo_sub=trim($request->campo_sub);
    $campo_del=trim($request->campo_del);
    $id_antigo=trim($request->id_antigo);
    $id_novo=trim($request->id_novo);

    \DB::table($tabela_sub)->where($campo_sub,$id_antigo)->update([$campo_sub=>$id_novo ]);
    
    if(\DB::table($tabela_del)->where($campo_del,$id_antigo)->delete()){ return 'sucesso'; }
    return 'erro';
  }


  public function orderTable(Request $request)
  {
    $tabela = trim($request->tabela);
    $referencia = trim($request->referencia) ? trim($request->referencia) : 'id';
    $campo = trim($request->campo) ? trim($request->campo) : 'ordem';
    $array = $request->linha;
    $arrayOrder = trim($request->ordem);


    $todos = 0;
    if($arrayOrder){
      $ordem = explode(",", $arrayOrder);
      $num_ordem = count($ordem);
      $num_query = \DB::table($tabela)->count();
      if($num_ordem == $num_query){ $todos = 1; }
    }

    if($arrayOrder && !$todos){
      
      $i = 0;
      foreach ($array as $value){
        \DB::table($tabela)->where($referencia,$value)->update([$campo=>$ordem[$i]]); 
        $i++; 
      }
    }else{
      
      $count = 1;
      foreach ($array as $value){
        \DB::table($tabela)->where($referencia,$value)->update([$campo=>$count]); 
        $count ++; 
      }
      
    }

    return 'sucesso';
  }
  
  /*
  function deleteLine(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineTM') }}',
      data: { tabela:'blog', id:'id' },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_'+id).slideUp();
      }
    });
  }
  */

}