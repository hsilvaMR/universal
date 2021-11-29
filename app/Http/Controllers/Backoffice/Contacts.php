<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Contacts extends Controller
{
  private $dados=[];
  /*private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = json_decode(Cookie::get('admin_cookie'))->lingua;
          return $next($request);
      });
  }*/

  /**************/
  /*  CONTACTS  */
  /**************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.contactsTitulo');
    $this->dados['separador']="webContacts";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('contactos')
                  ->orderBy('id','DESC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $array[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'email' => $valor->email,
        'mensagem' => $valor->mensagem,
        'data' => $valor->data ? date('Y-m-d',$valor->data) : ''
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/contacts-all', $this->dados);
  }
}