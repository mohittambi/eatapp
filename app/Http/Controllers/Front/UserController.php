<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;   
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function __construct()
    {
        
    } 

    public function changePassword(Request $request)
    {
        
        if ($request->has('token'))
        {

            $token = $request->input('token');
            $row =  User::where('password_reset_token',$token)->first();
          
           if($row)
           {
            return view('front.users.changepassword',compact('row','token'));
           }
           else
           {    
                return view('front.users.invalidtoken');
           }
        }
        else
        {
            return view('front.users.invalidtoken');
        }
    }

    public function updateChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'password' => 'required|min:8',
                        'confirm_password' => 'required|same:password',
                    ]);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator->errors());
        }
        else
        {
            if ($request->has('token'))
            {
                $token = $request->input('token');
                $row =  User::where('password_reset_token',$token)->first();
                   if($row)
                   {
                        $row->password = bcrypt($request->password);
                        $row->password_reset_token = null;
                        $row->save();
                        return view('front.users.password_updated');
                   }
                   else
                   {    
                        return view('front.users.invalidtoken');
                   }
            }
            else
            {
                return view('front.users.invalidtoken');
            }
        }
    }


  
}
