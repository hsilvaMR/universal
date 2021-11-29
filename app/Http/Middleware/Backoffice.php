<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;

/* CheckAccount */
class Backoffice {

  public static function handle($request, Closure $next)
  {
    date_default_timezone_set('Europe/Lisbon');

    if(Cookie::get('admin_cookie') !== null){
      $admin_cookie=json_decode(Cookie::get('admin_cookie'));
      $id_user=$admin_cookie->id;
      $token_user=$admin_cookie->token;
      $lingua_user=$admin_cookie->token;
    }else{
      return redirect()->route('loginPageB');
    }

    $user=\DB::table('admin')->select('id','estado')->where('id',$id_user)->where('token',$token_user)->first();
    if(empty($user)){ return redirect()->route('loginPageB'); }
    \DB::table('admin')->where('id',$id_user)->update([ 'ultimo_acesso'=>\Carbon\Carbon::now()->timestamp ]); //ou strtotime(date('Y-m-d H:i:s'))

    app()->setLocale($lingua_user);
    /*
    $notificacoes = \DB::table('ag_notificacoes')->where('id_agente', $user['id'])->where('visto', 0)->count();
    if($notificacoes > 9){ $notificacoes = '+9'; }
    Cookie::queue(Cookie::make('notificacoes', $notificacoes, 43200));
    */
    return $next($request);
  }

  /*self::setUserUltimoAcesso($user->id);
  static function setUserUltimoAcesso($id)
  {
    \DB::table('admin')
    ->where('id',$id)
    ->update([ 'ultimo_acesso' => \Carbon\Carbon::now()->timestamp ]);
  }*/
}