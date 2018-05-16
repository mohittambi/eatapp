<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\User;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Model\ContactForm as ContactForm;

class FrontController extends Controller
{
    public function __construct()
    {
        
    }

    public function home(){

        $title= 'EATAPP | Home'; 
        $breadcrumb = ['EatApp'=>''];
        if($user = Auth::user())
        {
            $id = Auth::user()->id;
            $user = User::find($id);
        }
        return view('front.home',compact('title','row','breadcrumb','user'));
    }

    public function signup(){
    	$title= 'EATAPP | SignUp'; 
        $breadcrumb = ['EatApp'=>''];
        $countryList = array_column($this->getCountryList(), 'name','id');
        return view('front.login.signup',compact('title','row','breadcrumb','countryList'));
    }

    public function signin(){
    	$title= 'EATAPP | SignIn';
        $breadcrumb = ['EatApp'=>''];
       
        return view('front.login.signin',compact('title','row','breadcrumb'));
    }

    public function makelogin(Request $request)
    {
        
        try {
                $validator = Validator::make($request->all(), [
                            'email' => 'required',
                            'password' => 'required',
                ]);
                if ($validator->fails()) 
                {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                else 
                {
                    $email = $request->email;
                    $password = bcrypt($request->password);
                    $user = User::where('email',$email)->where('role','C')->where('verified','1')->first();
					
                    if($user && Hash::check($request->password, $user->password))
                    {
                        // Session::put('AdminLoggedIn', ['user_id'=>$user->id,'userData'=> $user]);
                        // Session::save();
                        Auth::login($user);
                        return redirect()->route('front.home');
                    }
                    else
                    {
                        Session::flash('danger','Invalid email or password');
                        return redirect()->back()->withInput();
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

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function forgotPassword(){
        $title= 'EATAPP | ForgotPassword';
        $breadcrumb = ['EatApp'=>''];

        return view('front.login.forgotPassword',compact('title','breadcrumb'));
    }

    public function contactForm(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                            'email'         => 'required',
                            'name'          => 'required',
                            'phone_number'  => 'required',
                            'comment'       => 'required',
                ]);
                if ($validator->fails()) 
                {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                else 
                {
                    $contactForm                = new ContactForm;
                    $contactForm->name          = $request->name;
                    $contactForm->email         = $request->email;
                    $contactForm->phone_number  = $request->phone_number;
                    $contactForm->comment       = $request->comment;
                    
                    $contactForm->save();

                    $data['name'] = $contactForm->name;
                    $data['email'] = $contactForm->email;
                    $data['phone_number'] = $contactForm->phone_number;
                    $data['comment'] = $contactForm->comment;
                    $data['mail_type'] = 'contactForm';
                    mailSend($data);
                    
                    Session::flash('success','Your contact form details have been successfully sent.');
                    return redirect()->back();

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
