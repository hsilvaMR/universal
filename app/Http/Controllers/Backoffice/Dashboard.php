<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Dashboard extends Controller
{
  private $dados=[];
  /*private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = Cookie::get('admin_cookie')['lingua'];
          return $next($request);
      });
  }*/

  /**************
  *  DASHBOARD  *
  **************/	
  public function index(){
  	$this->dados['headTitulo']=trans('backoffice.dashboardTitulo');
  	$this->dados['separador']="dashboard";
    $this->dados['funcao']="";

    //$lang = Cookie::get('admin_cookie')['lingua'];
    //$lang = json_decode(Cookie::get('admin_cookie'))->lingua;
  	// \DB::select(\DB::raw("     "))
    //$id_admin = json_decode(Cookie::get('admin_cookie'))->id;

    $this->dados['num1'] = \DB::table('utilizadores')->select('id')->count();
    $this->dados['num2'] = \DB::table('empresas')->select('id')->count();
    $this->dados['num3'] = \DB::table('comerciantes')->select('id')->count();
    $this->dados['num4'] = \DB::table('encomenda')->select('id')->where('estado','<>','inicio')->count();


    $this->dados['lista1'] = \DB::table('encomenda')
                                ->select('data','referencia AS nome','total')
                                ->where('estado','<>','inicio')
                                ->orderBy('id','DESC')
                                ->limit(10)
                                ->get();

    $this->dados['lista2'] = \DB::table('empresas')
                                ->select('data','nome','email')
                                ->orderBy('id','DESC')
                                ->limit(10)
                                ->get();

    $this->dados['lista3'] = \DB::table('comerciantes')
                                ->select('comerciantes.data_registo AS data','comerciantes.nome','empresas.nome AS empresa')
                                ->leftJoin('empresas','empresas.id','comerciantes.id_empresa')
                                ->orderBy('comerciantes.id','DESC')
                                ->limit(10)
                                ->get();

    $this->dados['lista4'] = \DB::table('utilizadores')
                                ->select('data','nome','apelido','email')
                                ->orderBy('id','DESC')
                                ->limit(10)
                                ->get();

  	return view('backoffice/pages/dashboard', $this->dados);
  }

  public function settings(){
    $this->dados['headTitulo']=trans('backoffice.settingsTitulo');
    $this->dados['separador']="setSettings";
    $this->dados['funcao']="all";

    $query=\DB::table('configuracoes')->get();
    $array=[];
    foreach ($query as $value){
      switch($value->tag){
        case 'ponto_em_euros': $valor=$value->valor.' €'; break;
        case 'premio_notificacao_dias': $valor=$value->valor.' '.trans('backoffice.set_days'); break;
        case 'euros_em_pontos_empresa': $valor=$value->valor.' €'; break;
        case 'margem_lucro': $valor=$value->valor.' %'; break;
        default: $valor=$value->valor; break;
      }

      $array[]=[
        'id'=>$value->id,
        'tag'=>$value->tag,
        'descricao'=>trans('backoffice.SET_'.$value->tag),
        'valor'=>$valor
      ];
    }

    $this->dados['array']=$array;
    return view('backoffice/pages/settings-all', $this->dados);
  }

  public function settingsEdit($id){
    $this->dados['headTitulo']=trans('backoffice.settingsTitulo');
    $this->dados['separador']="setSettings";
    $this->dados['funcao']="edit";

    $this->dados['obj']=\DB::table('configuracoes')->where('id',$id)->first();
    return view('backoffice/pages/settings-new', $this->dados);
  }

  public function settingsForm(Request $request){
    $id=trim($request->id);
    $valor=trim($request->valor);

    \DB::table('configuracoes')->where('id',$id)->update([ 'valor'=>$valor ]);
    return 'sucesso';
  }
}