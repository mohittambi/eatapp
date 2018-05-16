<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class AdminLoggedIn{
    public function handle($request, Closure $next){
        //dd(Session::get('LoggedIn'));
       
        if(Session::has('AdminLoggedIn'))
        {

            return $next($request);
        }
        else
        {
        	return redirect()->route('admin.login');
        }

        
    }
}
