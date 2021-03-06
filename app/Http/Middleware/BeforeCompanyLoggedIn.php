<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class BeforeCompanyLoggedIn{
    public function handle($request, Closure $next){
        //dd(Session::get('LoggedIn'));
       
        if(!Session::has('CompanyLoggedIn'))
        {
            return $next($request);
        }
        else
        {
        	
        	Session::flash('warning','Invalid request');
        	return redirect()->route('company.dashboard');
        }

        
    }
}
