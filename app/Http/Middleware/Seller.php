<?php
namespace App\Http\Middleware;

use Closure;
use Cookie;

class Seller {
  
  public function handle($request, Closure $next){
    //return $next($request);

    //date_default_timezone_set('Europe/Lisbon');
    if(Cookie::get('cookie_comerc_id') && Cookie::get('cookie_comerc_token')){
      
      //$id_comerciante = Cookie::get('cookie_comerc_id');
      //$token_comerciante = Cookie::get('cookie_comerc_token');

      $comerciante = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->where('token',Cookie::get('cookie_comerc_token'))->first();

      if($comerciante) {
        
        $empresa = \DB::table('empresas')->where('id',$comerciante->id_empresa)->first();
        \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->update(['ultimo_acesso' => \Carbon\Carbon::now()->timestamp]);

        if ($comerciante->aprovacao == 'em_aprovacao'){
          return redirect()->route('pendingPageV2');
        }
                
        if (($empresa->estado == 'aprovado') || ($empresa->estado == 'ativo')) {
          
          if ($comerciante->aprovacao != '' ) {
            switch ($comerciante->aprovacao and $comerciante->estado) {
              case ($comerciante->aprovacao == 'em_aprovacao'):
                return redirect()->route('pendingPageV2');
                break;

              case ($comerciante->aprovacao == 'reprovado'):
                switch ($comerciante->estado) {
                  case ('ativo'):
                    $array=['personalDataV2','companyDataV2','saveCompanyDataPost','deleteCompanyAvatarPost','savePersonalDataPost','supportV2','newTicketV2','msgTicketV2','newTicketPostV2'];
                    if(in_array($request->route()->getName(), $array)){ return $next($request); }
                    else{ return redirect()->route('personalDataV2'); }
                    break;

                  case ('pendente'):
                    return redirect()->route('pendingPageV2');
                    break;
                } 
              break;
              
              case ($comerciante->aprovacao == 'aprovado' and $comerciante->estado == 'pendente'):
                return redirect()->route('pendingPageV2');
                break;

              default:
                return $next($request);
                break;
            }
          }
          else{
            switch ($comerciante->estado) {
              case ($comerciante->estado == 'pendente'):
                return redirect()->route('pendingPageV2');
                break;

              default:
                return $next($request);
                break;
            }
          }
        }
        elseif(($empresa->estado == 'pendente') || ($empresa->estado == 'em_aprovacao') || ($empresa->estado == 'reprovado')){

          switch ($comerciante->estado) {
            case ('ativo'):
              $array=['personalDataV2','companyDataV2','saveCompanyDataPost','deleteCompanyAvatarPost','savePersonalDataPost','supportV2','newTicketV2','msgTicketV2','newTicketPostV2'];
              if(in_array($request->route()->getName(), $array)){ return $next($request); }
              else{ return redirect()->route('companyDataV2'); }
              break;

            case ('pendente'):
              return redirect()->route('pendingPageV2');
              break;

            default:
              return $next($request);
              break;
          } 
        }
        elseif ($empresa->estado == 'suspenso') {
          return redirect()->route('pageSuspended');
        }
      }
      else{
        return redirect()->route('loginPageV2');
        Cookie::queue(Cookie::forget('cookie_comerc_id'));
        Cookie::queue(Cookie::forget('cookie_comerc_token'));
        Cookie::queue(Cookie::forget('cookie_comerc_name'));
        Cookie::queue(Cookie::forget('cookie_comerc_photo'));
        Cookie::queue(Cookie::forget('cookie_comerc_id_empresa'));
        Cookie::queue(Cookie::forget('cookie_company_photo'));
        Cookie::queue(Cookie::forget('cookie_company_name'));
        Cookie::queue(Cookie::forget('cookie_company_status'));
        Cookie::queue(Cookie::forget('cookie_comerc_type'));
        Cookie::queue(Cookie::forget('cookie_notification_ative'));
      }

    }else{return redirect()->route('homePageV2');}


    //$user=\DB::table('utilizadores')->select('id','estado','tipo')->where('id',$id_user)->where('token',$token_user)->where('tipo',$tipo_user)->first();

    //if(empty($user)){ return redirect()->route('homePage');}

    //\DB::table('utilizadores')->where('id',$id_user)->update([ 'ultimo_acesso'=>\Carbon\Carbon::now()->timestamp ]); //ou strtotime(date('Y-m-d H:i:s'))
    //app()->setLocale(Cookie::get('user_cookie')['lingua']);
  }
}