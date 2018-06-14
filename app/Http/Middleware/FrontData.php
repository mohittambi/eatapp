<?php
namespace App\Http\Middleware;

use Closure;
use Session;
use App\Model\Setting;

class FrontData{
    public function handle($request, Closure $next){
       
        if(1)
        {
            return $next($request);
        }
        else
        {
        	
        	Session::flash('warning','Invalid request');
        	return redirect()->route('admin.dashboard');
        }

        
    }
}
