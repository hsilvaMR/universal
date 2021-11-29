<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;
use Hash;
use Cookie;

class AdminAccount extends Controller
{
	private $dados=[];
	/*private $id_agent;
    public function __construct()
    {
        //$this->lang=Session::get('locale');
        $this->middleware(function($request, $next){
            $id_admin = Cookie::get('admin_cookie')['id'];
            return $next($request);
        });
    }+/

	/*****************
	*  USER ACCOUNT  *
	*****************/
	public function index(){
		$this->dados['headTitulo']=trans('backoffice.accountTitulo');
		$this->dados['separador']="userAccount";
        $this->dados['funcao']="";

        $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
		$this->dados['user'] = \DB::table('admin')->where('id',$id_admin)->first();
		return view('backoffice/pages/admin-account', $this->dados);
	}

	public function accountAvatarForm(Request $request)
    {
        $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    	$user = \DB::table('admin')->where('id',$id_admin)->first();
    	$ficheiro=$request->file('ficheiro');

    	$novoNome='';
    	if(count($ficheiro)){
    		$antigoNome=$user->avatar;
    		$cache = str_random(3);
    		$extensao = strtolower($ficheiro->getClientOriginalExtension());
    		$novoNome = 'admin'.$id_admin.'-'.$cache.'.'.$extensao;

    		$pasta = base_path('public_html/backoffice/img/admin/');
    		$width = 300; $height = 300;

    		$uploadImage = New uploadImage;
			$uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
			\DB::table('admin')->where('id',$id_admin)->update([ 'avatar'=>$novoNome ]);
        }

        $userDados=['id'=> $id_admin,
                    'nome'=> $user->nome,
                    'token'=> $user->token,
                    'avatar'=> $novoNome,
                    'tipo'=> $user->tipo,
                    'lingua'=> $user->lingua
        ];
        $userDados=json_encode($userDados);
        Cookie::queue(Cookie::make('admin_cookie', $userDados, 43200));

        $resposta = [
        		'estado' => 'sucesso',
        		'avatar' => $novoNome,
        		'erro' => ''
    	];
    	return json_encode($resposta,true);
    }

    public function accountAvatarApagar()
    {
        $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    	$user = \DB::table('admin')->where('id',$id_admin)->first();

    	if($user->avatar && file_exists(base_path().'/public_html/backoffice/img/admin/'.$user->avatar)){ \File::delete('../public_html/backoffice/img/admin/'.$user->avatar); }
        \DB::table('admin')->where('id',$id_admin)->update([ 'avatar'=>'' ]);

        $userDados=['id'=> $id_admin,
                    'nome'=> $user->nome,
                    'token'=> $user->token,
                    'avatar'=> '',
                    'tipo'=> $user->tipo,
                    'lingua'=> $user->lingua
        ];
        $userDados=json_encode($userDados);
        Cookie::queue(Cookie::make('admin_cookie', $userDados, 43200));

        return 'sucesso';
    }

	public function accountDataForm(Request $request)
    {
    	$id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    	$user = \DB::table('admin')->where('id',$id_admin)->first();

        $nome=trim($request->nome);
        //$lingua=trim($request->lingua);
        $lingua='pt';
        $pass=$request->pass;
        $password=$request->password;

    	\DB::table('admin')
    		->where('id',$id_admin)
        	->update(['nome'=>$nome,
					  'lingua'=>$lingua
		]);

        $userDados=['id'=> $id_admin,
                    'nome'=> $nome,
                    'token'=> $user->token,
                    'avatar'=> $user->avatar,
                    'tipo'=> $user->tipo,
                    'lingua'=> $lingua
        ];
        $userDados=json_encode($userDados);
        Cookie::queue(Cookie::make('admin_cookie', $userDados, 43200));

        $reload='';
        if($user->lingua != $lingua){$reload='sim';}

        $erro='';
        if(($pass || $password) && !Hash::check($pass, $user->password)){ $erro=trans('backoffice.incorrectPassword'); }
        if(($pass || $password) && strlen($password)<6){ $erro=trans('backoffice.least6characteres'); }
        if(Hash::check($pass, $user->password) && strlen($password)>=6){
        	$password = Hash::make($password);
        	\DB::table('admin')->where('id',$id_admin)->update(['password'=>$password ]);
        }

        $resposta = [
        		'estado' => 'sucesso',
        		'nome' => $nome,
        		'reload' => $reload,
        		'erro' => $erro
    	];
    	return json_encode($resposta,true);
    }
}