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
use App\Model\Country;
use App\Model\ContactForm as ContactForm;
use App\Model\FarmerNonAvailibility as FarmerNA;
use App\Model\FarmerWorkingHour as FarmerWH;
use App\Model\Amenity;
use App\Model\FarmerAmenity;
use App\Model\FarmerBanner;
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
        // $settings = Setting::where('status','1')->get();
		// foreach($settings as $setting => $value){
		// 	$this->settingValue[$value->slug] = $value->description;
		// }
    }


    public function home(){

        $title= 'EATAPP | Home'; 
        $breadcrumb = ['EatApp'=>''];
		//$settingValue = $this->settingValue;

        if($user = Auth::user())
        {
            $id = Auth::user()->id;
            $user = User::find($id);
        }
        return view('front.home',compact('title','row','breadcrumb','user'));
    }

    public function signup()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
    	$title= 'EATAPP | SignUp'; 
        $breadcrumb = ['EatApp'=>''];
        //$countryList = array_column($this->getCountryList(), 'name','id');
        $countryData = Country::orderBy('sortname','asc')->pluck('sortname','phonecode');
		//$settingValue = $this->settingValue;
		
        return view('front.login.signup',compact('title','row','breadcrumb','countryData'));
    }

    public function signin()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
    	$title= 'EATAPP | SignIn';
        $breadcrumb = ['EatApp'=>''];
		//$settingValue = $this->settingValue;
		
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
		//$settingValue = $this->settingValue;
		
        return view('front.login.forgotPassword',compact('title','breadcrumb'));
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
		//$settingValue = $this->settingValue;
        $countryData = Country::orderBy('sortname','asc')->pluck('sortname','phonecode');
        //$countryList = array_column($this->getCountryList(), 'sortname','phonecode');
        $categoryList = array_column($this->getCategoryList(), 'name','id');
		
		if($userDetails->farmer_categories->toArray()){
			foreach ($userDetails->farmer_categories as $key => $value) {
				$selectedCatList[] = $value->category_id;
			}
		}
		else {
			$selectedCatList[] ='';
		}

        return view('front.page.profile',compact('title','userDetails','breadcrumb','categoryList','selectedCatList','countryData'));
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
                'company_name'  => 'required',
                'vat_number'    => 'required',
                'cf'            => 'max:16',
                'latitude'      => 'required',
                'description'   => 'required',
                'phone_number'  => 'digits_between:7,15',

            ];
            $messages = [
                'latitude.required'    => 'Invalid address.',
            ];
            $validator = Validator::make($request->all(),$validatorRules,$messages);
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
                $user->phonecode=$request->phonecode;
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
                $farmer->company_name=$request->company_name;
                $farmer->vat_number=$request->vat_number;
                $farmer->cf=$request->cf?$request->cf:'';
                
                
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
        //$settingValue = $this->settingValue;
        
        $all_na = FarmerNA::where('user_id',$userDetails->id)->select('start_date as start','title')->get()->toJson();
        $days = ['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday','sunday'=>'Sunday'];

        if(FarmerWH::where('user_id',$userDetails->id)->exists())
        {
                $row = FarmerWH::where('user_id',$userDetails->id)->get();
                for($i=0;$i<$row->count();$i++){
                    //dd($row[$i]->day_name);
                    $keys = array_keys($days);
                    $data[$i]['day_name']     = $days[$keys[$i]];
                    $data[$i]['opening_time'] = date('H:i', strtotime($row[$i]->opening_time));
                    $data[$i]['closing_time'] = date('H:i', strtotime($row[$i]->closing_time));
                    $data[$i]['visitors']     = $row[$i]->visitors;
                }
        }else{
            $i=0;
            foreach($days as $day){
                    $data[$i]['day_name']     = $day;
                    $data[$i]['opening_time'] = '10:00';
                    $data[$i]['closing_time'] = '18:00';
                    $data[$i]['visitors']     = '1';
                    $i++;
                }
        }

        $all_amenities = Amenity::where('status','1')->get();
        $user_selected_amenity = FarmerAmenity::select('amenity_id')->where('user_id',$userDetails->id)->pluck('amenity_id')->toArray();
        $farmers_images = FarmerBanner::select('id')->where('user_id',$userDetails->id)->get()->toArray();
        $farmers_banners = FarmerBanner::select('name','description')->where('user_id',$userDetails->id)->pluck('name')->toArray();
        $farmers_banners_desc = FarmerBanner::select('description')->where('user_id',$userDetails->id)->pluck('description')->toArray();

        return view('front.page.settings',compact('title','data','breadcrumb','all_na','days','all_amenities','user_selected_amenity','userDetails','farmers_banners','farmers_banners_desc','farmers_images'));
    }

    public function updateNonAvailibilityDays(Request $request)
    {
        try{
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

        catch(\Exception $e)
        {
           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        }
    }

    public function updateSettings(Request $request)
    {
        unset($request['_token']);
        
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();

        if(FarmerWH::where('user_id',$userDetails->id)->exists())
        {
            foreach ($request->all() as $value) {
                $row = FarmerWH::where('user_id',$userDetails->id)->where('day_name',$value[0])->first();
                $row->user_id      = $userDetails->id;
                $row->day_name     = $value[0];
                $row->opening_time = $value[1];
                $row->closing_time = $value[2];
                $row->visitors     = $value[3];
                $row->save();
            }
        }
        else{
            foreach ($request->all() as $value) {
                $row = new FarmerWH();
                $row->user_id      = $userDetails->id;
                $row->day_name     = $value[0];
                $row->opening_time = $value[1];
                $row->closing_time = $value[2];
                $row->visitors     = $value[3];
                $row->save();
            }
        }
        Session::flash('success', 'Farmer settings updated successfully.');
        return redirect()->back();
    }

    public function amenitySettings(Request $request)
    {
        unset($request['_token']);
        $selected_amenity = $request->all();
        
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();

        if(FarmerAmenity::where('user_id',$userDetails->id)->exists())
        {
            FarmerAmenity::where('user_id', $userDetails->id)->delete();
        }
            foreach ($selected_amenity as $key => $value) {
                $row           = new FarmerAmenity();
                $row->user_id  = $userDetails->id;
                $row->amenity_id = $key;
                $row->save();
            }
        Session::flash('success', 'Farmer amenities updated successfully.');
        return redirect()->back();
        

    }

    public function bannerSettings(Request $request)
    {
        unset($request['_token']);
        $farmer_images_detail = $request->all();
        
        $user_slug = Auth::user()->slug;
        $userDetails = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->first();
        
        $userId = $userDetails->id;
            for ($i=0;$i<4;$i++) {
                @$image='';
                if($request->file('farmer_banner_image.'.$i))
                {
                    $file = $request->file('farmer_banner_image.'.$i);
                    @$image = uploadwithresize($file,'farmers-banners');
                    
                    // if($previous_row->image)
                    // {
                    //     unlinkfile('farmers-banners',$previous_row->image);
                    // }

                    FarmerBanner::where('id',$request['ids'][$i])->update(['name'  =>  @$image]);                     
                }
                //$row->description = $farmer_images_detail['farmer_banner_text'][$i];

                if(isset($request['ids'])){

                    FarmerBanner::where('id',$request['ids'][$i])
                                            ->update(
                                               ['user_id'       =>  @$userId,
                                                'description'   =>  $farmer_images_detail['farmer_banner_text'][$i]
                                                ]
                                        ); 

                }else{
                    FarmerBanner::Create(
                                           ['user_id'       =>  @$userId,
                                            'name'          =>  @$image, 
                                            'description'   =>  $farmer_images_detail['farmer_banner_text'][$i]
                                            ]
                                        ); 

                }
                //$row->save(); 
                
            }
            
        Session::flash('success', 'Farmer banners updated successfully.');
        return redirect()->back();

        
    }


    public function changePassword()
    {
        $title= 'EATAPP | Change Password';
        $breadcrumb = ['EatApp'=>'','Change Password'=>''];
        //$settingValue = $this->settingValue;
        $user_slug  = Auth::user()->slug;
        $user = User::where('slug',$user_slug)->where('role','F')->where('verified','1')->where('status','1')->first();

        return view('front.page.changePassword',compact('title','user','breadcrumb'));
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
