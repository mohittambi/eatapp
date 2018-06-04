<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Model\User;
use Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\PasswordBroker;
// use Illuminate\Auth\Passwords;
// use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
// use App\Http\Controllers\Auth;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgot(Request $request)
    {
        
        try{
            $data = $request->all();
            $this->validateEmail($request);
            $validator = Validator::make($request->all(), ['email' => 'required']);
            if ($validator->fails()) {
                $response['verified'] = "false";
                $response['message'] = $validator->messages();
                $new_arr = $validator->messages()->toArray();
                $value = reset($new_arr);
                $response['message'] = $value[0];
                return $response;
            } else {
                $userdata = ['email' => $data['email']];
                $user = User::where('email', $data['email'])->where('verified','1')->where('role','F')->first();
                //dd($user);
                if (!$user) {
                    $response['verified'] = "false";
                    $response['message'] = "Email does not exist.";
                    //return $response;
                    Session::flash('danger', 'Email does not exist.');
                    return redirect()->back()->withInput();
                } else { 
                    $user = User::find($user->id);
                    $password_broker = app(PasswordBroker::class);
                    $token = $password_broker->createToken($user);
                    $url = url('password/reset').'/'.$token;
                    $data['mail_type'] = 'forgot_password';
                    $data['reset_password_url'] = $url;
                    mailSend($data);
                    // dd($token);
                    //$this->notify(new ResetPasswordNotification($token));
                    // $response1 = $this->broker()->sendResetLink(
                    //         $request->only('email')
                    // );
                    // $response1 == Password::RESET_LINK_SENT ? $this->sendResetLinkResponse($response1) : $this->sendResetLinkFailedResponse($request, $response1);
                    // $response['status'] = "true";
                    // $response['message'] = "Change Password link successfully sent on your email.";

                    Session::flash('success', 'Change Password link successfully sent on your email.');
                    return redirect()->back()->withInput();
                    //return view('front.home');
                    //return $response;
                }
            }
        } 
        catch (\Exception $e) 
        {
            $msg = $e->getMessage();
            Session::flash('danger',$msg);
            return redirect()->back()->withInput();
        }
    }

}
