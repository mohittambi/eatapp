<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Session;
use File;
use Exception;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Model\User;
use App\Model\Farmer;
use App\Model\Location;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function generateRandomString($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = 'EA';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $anyOldCode = Farmer::where('farmers.farmer_code',$randomString)->first();
        if($anyOldCode){
            generateRandomString($length);
        }
        return $randomString;
    }

    public function makeLoginFromSignup(Request $request)
    {

        try
        {
            $validatorRules = [
                'first_name'        => 'required|max:255',
                'last_name'         => 'required|max:255',
                'email'             => 'required|max:255|unique:users,email,' . $request->user_id,
                'company_name'      => 'required',
                'password'          => 'required|max:20|min:8',
                'confirm_password'  => 'required|same:password',
				'phone_number'      => 'digits_between:7,15',
                'address'           => 'required|max:255',
                'location_name'     => 'max:255',
                'phonecode'         => 'required',
                'latitude'          => 'required',
            ];
            $messages = [
                'latitude.required'    => 'Invalid address.',
            ];
            $validator = Validator::make($request->all(),$validatorRules,$messages);
            if ($validator->fails()) 
            {
                //dd($validator->errors());
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                $user = new User();
                $farmer = new Farmer();

                $full_name = $request->first_name.' '.$request->last_name;

                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->full_name = $full_name;
                $user->email = $request->email;
                $user->password = Hash::make(Input::get('password'));
                $user->country_id = $request->country_id;
                $user->phonecode = $request->phonecode;
                $user->phone_number = $request->phone_number;
                $user->role = 'F';
                $user->status = 0;
                $user->verified = 1;

                $user->save();

                $farmer->user_id = $user->id;
                //$farmer->categories = $request->categories;
                $farmer->description = $request->description;
                $farmer->company_name = $request->company_name;
                $farmer->farmer_code = $this->generateRandomString(4);

                $farmer->save();

                $location = new Location();

                $location->user_id       = $user->id;
                $location->address       = $request->address;
                $location->latitude      = $request->latitude;
                $location->longitude     = $request->longitude;
                $location->location_name = $request->location_name?$request->location_name:null;
                $location->save();
                
                $data['user_id'] = $user->id;
                $data['email'] = $request->email;
                $data['mail_type'] = 'front_signup';
                mailSend($data);

                return redirect()->back()->withInput();
                //Auth::login($user);


                // switch ($user->role) {
                // case 'F':
                //     return redirect()->route('front.home');

                // case 'A':
                //     return redirect()->route('admin.dashboard');

                // default:
                //     return redirect()->route('front.login.signup');
                // }
            }
        }
        catch(Exception $e)
        {
            $msg = $e->getMessage();
            Session::flash('danger',$msg);
            return redirect()->back()->withInput();
        }


        

    }
}
