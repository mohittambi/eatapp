<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\User;
use App\Model\Farmer;
use App\Model\FarmerCategory;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;
use App\Model\ContactForm as ContactForm;

class FrontController extends Controller
{
    public function __construct()
    {
        
    }

    public function home(){

        $title= 'EATAPP | Home'; 
        $breadcrumb = ['EatApp'=>''];
		$facebook = 'facebook.com';
		$twitter = 'facebook.com';
		$instagram = 'facebook.com';
        if($user = Auth::user())
        {
            $id = Auth::user()->id;
            $user = User::find($id);
        }
        return view('front.home',compact('title','row','breadcrumb','user','facebook','instagram','twitter'));
    }

    public function signup()
    {
    	$title= 'EATAPP | SignUp'; 
        $breadcrumb = ['EatApp'=>''];
        $countryList = array_column($this->getCountryList(), 'name','id');
        return view('front.login.signup',compact('title','row','breadcrumb','countryList'));
    }

    public function signin()
    {
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
                    $user = User::where('email',$email)->where('role','F')->first();


                    if($user && Hash::check($request->password, $user->password))
                    {
                        // Session::put('AdminLoggedIn', ['user_id'=>$user->id,'userData'=> $user]);
                        // Session::save();

                        if($user->verified){
                            Auth::login($user);
                            return redirect()->route('front.home');
                        }
                        else{
                            Session::flash('danger','You are not a verified user please chek your mailbox.');
                            return redirect()->back()->withInput();
                        }
                    }
                    else
                    {
                        Session::flash('danger','Invalid email or password.');
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
                    return redirect('/#getInTouch');
				//	 return redirect()->back();

                }
            } 
        catch (\Exception $e) 
        {
            $msg = $e->getMessage();
            Session::flash('danger',$msg);
            return redirect()->back()->withInput();
        }

    }
    public function profile()
    {
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->with(['farmerDetails','farmer_categories'])->first();
        
        $title= 'EATAPP | My Profile';
        $breadcrumb = ['EatApp'=>'','My Profile'=>''];
        $countryList = array_column($this->getCountryList(), 'name','id');
        $categoryList = array_column($this->getCategoryList(), 'name','id');

        foreach ($userDetails->farmer_categories as $key => $value) {
            $selectedCatList[] = $value->category_id;
        }

        return view('front.page.profile',compact('title','userDetails','breadcrumb','countryList','categoryList','selectedCatList'));
    }

    public function updateprofile(Request $request)
    {

        
        $user_slug = Auth::user()->slug;
        $user = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();
        $farmer = Farmer::where('farmers.user_id','=',$user->id)->first();
        
        //dd($farmer);
        try
        {
            $validatorRules = [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                
            ];
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {

                $previous_row = $user;
                $full_name=$request->first_name.' '.$request->last_name;
                $user->first_name=$request->first_name;
                $user->last_name=$request->last_name;
                $user->full_name=$full_name;
                $user->email=$request->email;
                $user->country_id=$request->country_id;
                //$user->status=$request->status;

                if($request->file('profile_pic'))
                {
                    $file = $request->file('profile_pic');
                    $image = uploadwithresize($file,'farmers');

                    if($previous_row->image)
                    {
                        unlinkfile('farmers',$previous_row->image);
                    }

                    $user->image= $image;
                }
                
                $user->save();

                if($request->file('banner_image'))
                {
                    $file = $request->file('banner_image');
                    $image = uploadwithresize($file,'banners');

                    if($previous_row->image)
                    {
                        unlinkfile('banners',$previous_row->image);
                    }

                    $farmer->banner_image= $image;
                }

                $farmer->user_id = $user->id;
                $farmer->description=$request->description;
                $farmer->categories=$request->category;
                
                $farmer->save();

                FarmerCategory::where('user_id', $user->id)->delete();

                if(isset($request->categories)){
                    foreach ($request->categories as $key => $category_id) {
                        $farmer_category = new FarmerCategory();
                        $farmer_category->user_id = $user->id;
                        $farmer_category->category_id = $category_id;
                        $farmer_category->save();
                    }

                }
                Session::flash('success', 'Farmer updated successfully.');
                return redirect()->back()->withInput();
            }
        }

        catch(\Exception $e)
        {
           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        }
    }

}
