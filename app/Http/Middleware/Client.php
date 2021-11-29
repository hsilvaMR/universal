<?php
namespace App\Http\Middleware;

use Closure;
use Cookie;

/* CheckAccount */
class Client {

  public function handle($request, Closure $next){

    
    if(Cookie::get('cookie_user_id') && Cookie::get('cookie_user_token')){ 
      $id_user=Cookie::get('cookie_user_id');
      $token_user=Cookie::get('cookie_user_token');

      $user = \DB::table('utilizadores')->where('id',$id_user)->where('token',$token_user)->first();
      
      if($user) {

        \DB::table('utilizadores')->where('id',$id_user)->update(['ultimo_acesso' => \Carbon\Carbon::now()->timestamp]);

        switch ($user->estado) {
          case 'pendente':
            return redirect()->route('pendingPageV2');
            //return $next($request);
            break;
          
          case 'ativo':
            return $next($request);
            break;
        }
      }
      else{return redirect()->route('loginPageV2');}
    }
    else{ return redirect()->route('loginPageV2');}    
  }

}