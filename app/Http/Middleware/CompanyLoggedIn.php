<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class CompanyLoggedIn{
    public function handle($request, Closure $next){
        if(Session::has('CompanyLoggedIn'))
        {
            return $next($request);
        }
        else
        {
        	return redirect()->route('front.login');
        }
    }
}
