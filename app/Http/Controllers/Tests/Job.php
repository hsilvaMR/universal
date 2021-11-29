<?php namespace App\Http\Controllers\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Job extends Controller{

    public function index(){
    	Cookie::queue(Cookie::make('job', 'sim', 43200));
    	return redirect()->back();
    }
}

