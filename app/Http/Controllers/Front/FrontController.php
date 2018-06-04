<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Model\User;
use App\Model\Farmer;
use App\Model\FarmerCategory;
use App\Model\Setting;
use App\Model\Location;
use App\Model\ContactForm as ContactForm;
use App\Model\FarmerNonAvailibility as FarmerNA;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;

class FrontController extends Controller
{
    public function __construct()
    {
        $settings = Setting::where('status','1')->get();
		foreach($settings as $setting => $value){
			$this->settingValue[$value->slug] = $value->description;
		}
    }

    public function home(){

        $title= 'EATAPP | Home'; 
        $breadcrumb = ['EatApp'=>''];
		$settingValue = $this->settingValue;

        if($user = Auth::user())
        {
            $id = Auth::user()->id;
            $user = User::find($id);
        }
        return view('front.home',compact('title','row','breadcrumb','user','settingValue'));
    }

    public function signup()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
    	$title= 'EATAPP | SignUp'; 
        $breadcrumb = ['EatApp'=>''];
        $countryList = array_column($this->getCountryList(), 'name','id');
		$settingValue = $this->settingValue;
		
        return view('front.login.signup',compact('title','row','breadcrumb','countryList','settingValue'));
    }

    public function signin()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
    	$title= 'EATAPP | SignIn';
        $breadcrumb = ['EatApp'=>''];
		$settingValue = $this->settingValue;
		
        return view('front.login.signin',compact('title','row','breadcrumb','settingValue'));
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
                            if($user->status){
                                Auth::login($user);
                                return redirect()->route('front.home');
                            }
                            else{
                                Session::flash('danger','You account is currently inactive.');
                                return redirect()->back()->withInput();
                            }
                        }
                        else{
                            Session::flash('danger','You are not a verified user please check your mailbox.');
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

        if (Auth::check()) {
            return redirect('/home');
        }

        $title= 'EATAPP | ForgotPassword';
        $breadcrumb = ['EatApp'=>''];
		$settingValue = $this->settingValue;
		
        return view('front.login.forgotPassword',compact('title','breadcrumb','settingValue'));
    }

    public function contactForm(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                            'email'         => 'required|email',
                            'name'          => 'required|max:255',
                            'phone_number'  => 'required|numeric|digits_between:7,15',
                            'comment'       => 'required',
                ]);
                if ($validator->fails()) 
                {
                    return redirect('/#getInTouch')->withInput()->withErrors($validator->errors());
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
                    $data['adminEmail'] = User::where('role','A')->where('status','1')->first()->email;
                    $data['mail_type'] = 'contactForm';
                    mailSend($data);
                    
                    Session::flash('success','Your contact details have been successfully saved.');
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
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->with(['farmerDetails','farmer_categories','locations'])->first();
        
        $title= 'EATAPP | My Profile';
        $breadcrumb = ['EatApp'=>'','My Profile'=>''];
		$settingValue = $this->settingValue;
        $countryList = array_column($this->getCountryList(), 'name','id');
        $categoryList = array_column($this->getCategoryList(), 'name','id');
		
		if($userDetails->farmer_categories->toArray()){
			foreach ($userDetails->farmer_categories as $key => $value) {
				$selectedCatList[] = $value->category_id;
			}
		}
		else {
			$selectedCatList[] ='';
		}


        return view('front.page.profile',compact('title','userDetails','breadcrumb','countryList','categoryList','selectedCatList','settingValue'));
    }

    public function updateprofile(Request $request)
    {

        
        $user_slug = Auth::user()->slug;
        $user = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();
        $farmer = Farmer::where('farmers.user_id',$user->id)->first();
        $location = Location::where('user_id',$user->id)->first();

        
        try
        {
            $validatorRules = [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                //'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'address'       => 'required|max:255',
                'location_name' => 'max:255',
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
                //$user->email=$request->email;
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
                //$farmer->categories=$request->category;
                
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

                
                $location->address       = $request->address;
                $location->latitude      = $request->latitude;
                $location->longitude     = $request->longitude;
                $location->location_name = $request->location_name?$request->location_name:null;
                $location->save();

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


    public function editSettings()
    {
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();
        
        $title= 'EATAPP | My Settings';
        $breadcrumb = ['EatApp'=>'','My Settings'=>''];
        $settingValue = $this->settingValue;
        $all_na = FarmerNA::where('user_id',$userDetails->id)->select('start_date as start')->get()->toJson();
        
        return view('front.page.settings',compact('title','userDetails','breadcrumb','settingValue','all_na'));
    }

    public function updateNonAvailibilityDays(Request $request)
    {
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();
        $date_exist = FarmerNA::where('user_id',$userDetails->id)->where('start_date',$request->date)->exists();
        // dd($date_exist);
        if($request->type == "add"){
            if($date_exist){
                return true;
            }
            else
            {
                $farmer_na = new FarmerNA();
                $farmer_na->user_id = $userDetails->id;
                $farmer_na->start_date = $request->date;
                $farmer_na->save();
            }
        }
        else if ($request->type == "remove") {

            $farmer_na = FarmerNA::where('user_id', $userDetails->id)->where('start_date',$request->date)->delete();
        }

        return ($farmer_na);
    }

    public function updateSettings(Request $request)
    {
        $user = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();


        Session::flash('success', 'Farmer updated successfully.');
        return redirect()->back()->withInput();
    }


    public function changePassword()
    {
        $title= 'EATAPP | Change Password';
        $breadcrumb = ['EatApp'=>'','Change Password'=>''];
        $settingValue = $this->settingValue;
        $user_slug  = Auth::user()->slug;
        $user = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->where('status','1')->first();

        return view('front.page.changePassword',compact('title','user','breadcrumb','settingValue'));
    }

    public function postchangePassword(Request $request)
    {
        try
        {
            $rules = array(
                'old_password' => 'required',
                'new_password' => 'required|max:20|min:8',
                'confirm_password' => 'required|same:new_password',
            );
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator->errors());
            }
            else
            {
                $user_id =  Auth::user()->id;
                $user =  User::whereId($user_id)->first();
                if (Hash::check(Input::get('old_password'), $user->password))
                {
                    $user->password = Hash::make(Input::get('new_password'));
                    $user->save();
                    Session::flash('success', 'Password updated successfully.');
                    return redirect()->back();
                } 
                else
                {
                    Session::flash('danger', 'Current password is incorrect.');
                    return redirect()->back();
                }
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
