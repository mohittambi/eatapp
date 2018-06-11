<?php
namespace App\Http\Middleware;

use Closure;
use Auth;

class FarmerLoggedIn{
    public function handle($request, Closure $next){
       
        if( {{Auth::user()->role }} == 'F')
        {

            return $next($request);
        }
        else
        {
        	return redirect()->route('front.login.signin');
        }

        
    }
}
