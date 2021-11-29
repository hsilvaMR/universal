<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Hash;
use Validator;
use Mail;
use Cookie;

class Login extends Controller
{
	private $dados=[];
	
	public function index(){
		$this->dados['headTitulo']=trans('backoffice.loginTitulo');
		$this->dados['separador']="login";
        $this->dados['funcao']="";
		return view('backoffice/pages/login', $this->dados);
	}

	public function loginForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required' ]);
        $niceNames = array(
            'email' => '<b>'.trans('backoffice.email').'</b>',
            'password' => '<b>'.trans('backoffice.password').'</b>' );
        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) {
            $erros = '';
            foreach ($validator->errors()->all() as  $value){ $erros .= $value.'<br>'; }
            if($request->ajax()){ return $erros; }
            else{ return redirect()->route('loginPageB')->withErrors($erros)->withInput(); }
        }

        $email=trim($request->email);
        //$password = Hash::make($request->password);
        //\DB::table('admin')->where('email','tmendes@mredis.com')->update(['password'=>Hash::make('1310mredis') ]);

        $user = \DB::table('admin')->where('email', $email)->first();
        if(empty($user)){ return 'email'; }
        if(!Hash::check($request->password, $user->password)){ return 'password'; }
        if($user->estado=='pendente'){ return 'pendente'; }
        if($user->estado=='bloqueado'){ return 'bloqueado'; }
        if($user->estado=='eliminado'){ return 'eliminado'; }

        $userDados=['id'=> $user->id,
                    'nome'=> $user->nome,
                    'token'=> $user->token,
                    'avatar'=> $user->avatar,
                    'tipo'=> $user->tipo,
                    'lingua'=> $user->lingua
        ];
        $userDados=json_encode($userDados);
        Cookie::queue(Cookie::make('admin_cookie', $userDados, 43200));

        //cookie certificacoes
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

        //cookie permissões
        $permissions = \DB::table('admin_permissao')->where('id_admin',$user->id)->get();

        $permissionsDados = [];
        foreach ($permissions as $value) {
            if ($value->estado == 1) {
                $permissionsDados[] = [
                    'tipo'=> $value->tipo
                ];
            }
        }

        $permissionsDados=json_encode($permissionsDados);
        Cookie::queue(Cookie::make('permissions_cookie', $permissionsDados, 43200));
        return 'sucesso';
    }

    public function restoreForm(Request $request)
    {
        $email=trim($request->email);

        $user = \DB::table('admin')->where('email',$email)->first();
        if(isset($user->id))
        {
            $token = str_random(12);
            \DB::table('admin_pas')
               ->insert(['email'=>$email,
                         'token' => $token,
                         'data'=>strtotime(date('Y-m-d H:i:s'))
            ]);

            app()->setLocale($user->lingua);
            $dados = [ 'token'=>$token ];
            Mail::send('backoffice.emails.recuperar-password', $dados, function($message) use ($request){
                $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
                $message->subject(trans('backoffice.subjectRestore'));
                $message->to($request->email);
                $message->replyTo($request->email);
            });
            return 'sucesso';
        }
        return trans('backoffice.NonExistentEmail');
    }

    public function restorePasswordPage($token)
    {
        $linhaToken = \DB::table('admin_pas')->where('token',$token)->first();

        if(isset($linhaToken->id)){
            $this->dados['headTitulo']=trans('backoffice.restoreTitulo');
            $this->dados['headDescricao']=trans('backoffice.siteDescricao');
            $this->dados['separador']="restore";
            $this->dados['funcao']="";

            $this->dados['token']=$token;
            return view('backoffice/pages/restore-password', $this->dados);
        }
        return redirect()->route('loginPageB');
    }

    public function restorePasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [ 'password' => 'required|min:6' ]);
        $niceNames = array( 'password' => '<b>'.trans('backoffice.password').'</b>' );
        $validator->setAttributeNames($niceNames);

        if ($validator->fails()){
            $erros = '';
            foreach ($validator->errors()->all() as  $value){ $erros .= $value.'<br>'; }
            if($request->ajax()){ return $erros; }
            else{ return redirect()->route('emailRestorePageB')->withErrors($erros)->withInput(); }
        }

        $token=trim($request->token);
        $password = Hash::make($request->password);
        //$password = bcrypt($request->password);
        
        $linhaToken = \DB::table('admin_pas')->where('token',$token)->first();

        if(isset($linhaToken->id)){

            $linhaAdmin = \DB::table('admin')->where('email',$linhaToken->email)->first();

            $estado = ($linhaAdmin->estado=='bloqueado') ? 'bloqueado' : 'ativo'; 

            \DB::table('admin')
                ->where('email',$linhaToken->email)
                ->update(['password'=>$password,
                          'estado'=>$estado
            ]);

            \DB::table('admin_pas')->where('email',$linhaToken->email)->delete();
            return '';
        }
        return trans('backoffice.invalidRequest');
    }

    public function logout(){
        Cookie::queue(Cookie::forget('admin_cookie'));
        //return redirect()->route('loginPageB');
        return redirect('/admin');
    }

    public function version($token){
        return '1';
    }
}