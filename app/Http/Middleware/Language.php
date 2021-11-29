<?php
namespace App\Http\Middleware;

use Closure;
//use Session;
use Cookie;

class Language {

    public static function handle($request, Closure $next)
    {
    	
		//if(!Session::has('locale')){ Session::put('locale', config('app.locale')); }
		//app()->setLocale(Session::get('locale'));

		//if(!isset(Cookie::get('locale'))){ Cookie::queue(Cookie::make('locale', config('app.locale'), 43200)); }
		//app()->setLocale(Cookie::get('locale'));

		$lang = Cookie::get('locale') ? Cookie::get('locale') : config('app.locale');
		app()->setLocale($lang);
    	
		return $next($request);
   }
}