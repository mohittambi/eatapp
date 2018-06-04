<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use JWTAuthException;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Model\User;
use App\Model\Customer;
use App\Model\UserDevice;
use App\Model\SocialAccount;
use App\Model\Farmer;

class UserController extends Controller
{   

    private $uploadsfolder;

    public function __construct()
    { 
        $this->uploadsfolder = asset('uploads/');    
    }


    public function getApiKey(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return response()->json(compact('token'));
    }


    public function verifyForSignupAndGetOtp(Request $request)
    {

        $validatorRules = [
                'role' => 'required|in:B,C',
                'full_name' => 'required|max:255',
                //'gender' => 'required|in:M,F',
                'phone_number' => 'required|numeric|digits_between:7,15|unique:users',
                //'country_id' => 'required',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|max:20|min:8',
                'profile_pic' =>  'mimes:jpeg,jpg,png,gif',              
                'device_type'=>'required',
                'device_id'=>'required'
        ];
   

        $validator = Validator::make($request->all(),$validatorRules);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            return response()->json(['status'=>true,'message'=>'User can register']);
        }
    }


    public function generateRandomString($length = 20)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = 'UC';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $anyOldCode = User::where('customer_code',$randomString)->first();
        if($anyOldCode){
            generateRandomString($length);
        }
        return $randomString;
    }


    public function signup(Request $request)
    {

        $validatorRules = [
                'first_name'    => 'required|max:255',
                'last_name'     => 'required|max:255',
                'email'         => 'required|email|max:255|unique:users',
                'country_code'  => 'required|numeric|digits_between:0,5',
                'phone_number'  => 'required|numeric|digits_between:7,15',
                'address'       => 'required',
                'address_lat'   => 'required',
                'address_lang'  => 'required',
                //'country'       => 'required',
                'country'       => 'max:10',
                'gender'        => 'required|in:M,F',
                'dob'           => 'required|date',
                'password'      => 'required|max:20|min:8',
               // 'zipcode'     => 'required|max:999999|integer',
               // 'profile_pic' => 'mimes:jpeg,jpg,png,gif',
                'farmer_code'   => 'max:6|isValidFarmerCode:'.$request->farmer_code,
                'device_type'   => 'required',
                'device_id'     => 'required'
        ];

        $validator = Validator::make($request->all(),$validatorRules);
        
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $user = new User();
            $customer = new Customer();
            $social = new SocialAccount();

            $dob = strtotime($request->dob);
            $full_name              = $request->first_name.' '.$request->last_name;

            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->full_name        = $full_name;
            $user->email            = $request->email;
            $user->country_id       = $request->country?$request->country:null;
            $user->phonecode        = $request->country_code;
            $user->phone_number     = $request->phone_number;
            $user->gender           = $request->gender;
            $user->dob              = date('Y-m-d', $dob);
            $user->password         = bcrypt($request->password);
            $user->role             = 'C';

            if(isset($request->social_id) && !empty($request->social_id) ){
                $user->verified     = '1';
            } else{
                $user->verified     = '0';
            }

            $user->status           = '1';
            $user->save();

            $customer->user_id      = $user->id;
            $customer->farmer_code  = $request->farmer_code;
            $customer->address      = $request->address;
            $customer->address_lat  = $request->address_lat;
            $customer->address_lang = $request->address_lang;

            $customer->save();
            $message['response']    = 'Registration successful.';
            if(isset($request->social_id) && !empty($request->social_id) ){
                $social->user_id        = $user->id;
                $social->social_id      = $request->social_id;
                $social->social_type    = $request->social_type;
                $social->save();
            }
            else{
                $data['user_id']        = $user->id;
                $data['email']          = $user->email;
                $data['mail_type']      = 'app_signup';
                $mailResponse['isSent'] = mailSend($data);
                $message['response']    = 'Registration mail sent please verify.';
            }

            //$userDetails            = array_merge($user->toArray(), $customer->toArray(), $mailResponse);

            $this->manageDeviceIdAndToken($user->id,$request->device_id,$request->device_type,'add');
            if(isset($request->social_id) && $request->social_id != "" && isset($request->social_type) && $request->social_type != "")
            {
                $this->manageSocialAccounts($user->id,$request->social_id,$request->social_type);
            }
            $user = $this->getuserdetailfromObjectArray($user);
            return response()->json(['status'=>true,'message'=>$message['response'],'data'=>$user]);
        }
    }


    public function login(Request $request)
    {

    	Log::debug($request->all());
        $validator = Validator::make($request->all(), [
                        'email'       => 'required|email',
                        'password'    => 'required|max:20|min:8',
                        'device_type' => 'required',
                        'device_id'   => 'required',
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $email = $request->email;
            $password = bcrypt($request->password);
            $row = User::where(function($query) use ($email){
                        $query->where('email',$email);
                    })
                   
                    ->first();

            if ($row && Hash::check($request->password, $row->password) && $row->role == 'C') 
            {
                if($row->verified == '1')
                {
                    if($row->status == '1' ){
                        $user =  $this->getuserdetailfromObjectArray($row);

                        $this->manageDeviceIdAndToken($row->id,$request->device_id,$request->device_type,'add');               
                        return response()->json(['status'=>true,'message'=>'Login successful.','data'=>$user]);
                    }
                    else
                    {
                        return response()->json(['status'=>false,'message'=>'Your account is inactive.']);
                    }
                }
                else
                {
                    return response()->json(['status'=>false,'message'=>'Your account is not verified.']);
                }
                

            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Invalid credentials.']);
            }
            
        }
    }


    public function updateProfile(Request $request)
    {

        $validatorRules = [
                'user_id'       => 'required|isValidUser:'.$request->user_id,
                'first_name'    => 'required|max:255',
                'last_name'     => 'required|max:255',
                //'email'         => 'required|email|unique:users,email,' . $request->user_id,
                'country_code'  => 'required|numeric|digits_between:0,5',
                'phone_number'  => 'required|numeric|digits_between:7,15',
                'address'       => 'required',
                'address_lat'   => 'required',
                'address_lang'  => 'required',
                //'country'       => 'required',
                'gender'        => 'required|in:M,F',
                'dob'           => 'required|date',
                'profile_pic'   => 'mimes:jpeg,jpg,png,gif',
                // 'device_type'   => 'required',
                // 'device_id'     => 'required'
        ];


        //  if($request->role == 'C')
        // {
        //     $validatorRules['zipcode'] = 'required|max:999999|integer';
        // }

        // if($request->role == 'B')
        // {
        //     $validatorRules['business_name'] = 'required|max:255';
        //     $validatorRules['business_address'] = 'required|max:255';
        //     $validatorRules['cover_photo'] = 'mimes:jpeg,jpg,png,gif';
        // }


        $validator = Validator::make($request->all(), $validatorRules);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row =  User::where('id',$request->user_id)->first();
            $previous_row       = $row;
            $row->first_name    = $request->first_name;
            $row->last_name     = $request->last_name;
            //$row->email         = $request->email;
            $row->phonecode     = $request->country_code;
            $row->phone_number  = $request->phone_number;
            //$row->country_id    = $request->country;
            $row->dob           = date('Y-m-d',strtotime($request->dob));
            //$row->image         = $request->profile_pic;
            $row->gender        = $request->gender;
           
            // if($request->file('cover_photo'))
            // {
            //     $file = $request->file('cover_photo');
            //     $cover_photo = uploadwithresize($file,'users');
               
            //     if($previous_row->cover_photo)
            //     {
            //         unlinkfile('users',$previous_row->cover_photo);
            //     }
            //     $row->cover_photo= $cover_photo;
               
            // }


            if($request->file('profile_pic') && $request->file('profile_pic')!= null)
            {
                $file = $request->file('profile_pic');
                $image = uploadwithresize($file,'users');
                //dd($image);
                if($previous_row->image)
                {
                    unlinkfile('users',$previous_row->image);
                }
                $row->image = $image;
               
            }
            //dd($row);
            $row->save();
            //dd($row);

            if(Customer::where('user_id','=',$row->id)->exists()){
                $customer = Customer::where('user_id','=',$row->id)->first();
                //$customer = new Customer();
            }else{
                $customer = new Customer();
            }
            

            $customer->user_id      = $row->id;
            $customer->address      = $request->address;
            $customer->address_lat  = $request->address_lat;
            $customer->address_lang = $request->address_lang;

            $customer->save();

            //$this->manageDeviceIdAndToken($row->id,$request->device_id,$request->device_type,'add');
            if(isset($request->social_id) && $request->social_id != "" && isset($request->social_type) && $request->social_type != "")
            {
                $this->manageSocialAccounts($row->id,$request->social_id,$request->social_type);
            }

            $user = $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Profile updated successfully.','data'=>$user]);
        }
    }


    public function socialUserCheck(Request $request)
    {

        $validator = Validator::make($request->all(), [
                        'social_type' => 'required',
                        'social_id' => 'required',
                        'device_type'=>'required',
                        'device_id'=>'required',
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            //$social_row = SocialAccount::where('social_type',$request->social_type)->where('social_id',$request->social_id);
            $email = $request->email;
            $socialId = $request->social_id;

            if(isset($email) && !empty($email))
            {
            /* $email = $request->email;
                $social_row = $social_row->whereHas('getAssociateUserWithSocial',function ($query) use($email) {  
                        $query->where('email', $email)
                        ->where('role', 'C');
                    });*/

                $social_row =  User::orWhereHas('social_accounts', function ($query) use ($socialId)
                                {
                                    $query->where('social_id',$socialId);
                                })
                                ->orWhere('email', $email)->where('role', 'C');


            }else{
                $social_row =  User::orWhereHas('social_accounts', function ($query) use ($socialId)
                                {
                                    $query->where('social_id',$socialId);
                                })->where('role', 'C');
            }
            $social_row = $social_row->first();    
            
            if($social_row)
            {
                //$row = $social_row->getAssociateUserWithSocial;
                $user =  $this->getuserdetailfromObjectArray($social_row);
                return response()->json(['status'=>true,'message'=>'Social user detail.','data'=>$user]);
            }else{
                return response()->json(['status'=>false,'message'=>'No user found.']);
            }
            
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'email' => 'required|email',
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::where('role','C')
                    ->where('email', $request->email)
                    ->first();
            if($row)
            {
                if($row->verified == '1'){
                    $password_reset_token = strtotime(date('Y-m-d H:i:s')).rand(99,999);
                    $password_reset_token =  bcrypt($password_reset_token);
                    $row->password_reset_token = $password_reset_token;
                    $row->save();
                    $data=  array(
                        "email"=>$row->email,
                        "token"=>$password_reset_token,
                        'reset_password_url'=>route('front.forgot.password',['token'=>$password_reset_token]),
                        'mail_type' => 'forgot_password'
                        );
                    mailSend($data);
                    return response()->json(['status'=>true,'message'=>'Reset password link sent to your email address.']);
                }
                else
                {
                    return response()->json(['status'=>true,'message'=>'Your email is not verified please check your inbox.']);
                }
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Invalid email.']);
            }

        }
    }

    public function manageSocialAccounts($user_id,$social_id,$social_type)
    {
        SocialAccount::updateOrCreate(
        ['social_id'=>$social_id,'social_type'=>$social_type],['user_id' => $user_id]
        ); 
    }

    public function manageDeviceIdAndToken($user_id,$device_id,$device_type,$methodName)
    {

        if($methodName =='add')
        {
            UserDevice::updateOrCreate(
                ['user_id' => $user_id,'device_id'=>$device_id,'device_type'=>$device_type]
                ); 
        }
        if($methodName=='delete')
        {
            UserDevice::where('user_id',$user_id)
            ->where('device_id',$device_id)
            ->where('device_type',$device_type)
            ->delete();
        }
    }


    public function getuserdetailfromObjectArray($row)
    {

        $user = [];
        if($row->role == 'C')
        {
            //echo $row->id;  die;
            $customer = Customer::where('user_id',$row->id)->get();
            $user = (object)array(
            'user_id'           => (int)$row->id,
            'first_name'        => $row->first_name,
            'last_name'         => $row->last_name,
            'country_id'        => $row->country_id?(int)$row->country_id:'',
            //'country_name'      => $row->getRelatedCountry->name,
            //'phonecode'         => $row->getRelatedCountry->phonecode,
            'email'             => $row->email,
            'phone_number'      => $row->phone_number,
            'profile_pic'       => $row->image?$this->uploadsfolder.'/users/'.$row->image:asset('images/user.png'),
            'profile_pic_thumb' => $row->image?$this->uploadsfolder.'/users/thumb/'.$row->image:asset('images/user.png'),
            'verified'          => (int)$row->verified,
            'status'            => (int)$row->status,
            'gender'            => $row->gender=='M'?'M':'F',
            'dob'               => date('d-m-Y',strtotime($row->dob)),
            'created_at'        => strtotime($row->created_at), 
            );

            // if($row->apiType == 'get-user-profile'){
                $user->country_code = $row->phonecode;
                $user->address = $row->customerDetails->address;
                $user->address_lat = $row->customerDetails->address_lat;
                $user->address_lang = $row->customerDetails->address_lang;
                $user->farmer_code = $row->customerDetails->farmer_code?$row->customerDetails->farmer_code:''; 
            //}
        }
        else
        {
            if($row->role == 'F'){
                $user['message'] = 'Invalid username or password.';
            }
        }
        return $user;
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id,
                        'currentPassword'=>'required',
                        'newPassword'=>'required|min:8'  
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::whereId($request->user_id)->where('verified','1')->where('role','C')->first();
            if (Hash::check($request->currentPassword, $row->password)) 
            {
                $row->password = bcrypt($request->newPassword);
                $row->save();
                return response()->json(['status'=>true,'message'=>'Password changed successfully.']);
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Current password is not correct.']);
            }
        }
    }


    public function getUserProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id
                        
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::where('id',$request->user_id)
                    ->first();
            if($row)
            {
                $row->apiType = 'get-user-profile';
                $user =  $this->getuserdetailfromObjectArray($row);
                
                    return response()->json(['status'=>true,'message'=>'User detail.','data'=>$user]);
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Invalid user.']);
            }

        }
    }


    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required',
                        'device_type'=>'required',
                        'device_id'=>'required'
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $this->manageDeviceIdAndToken($request->user_id,$request->device_id,$request->device_type,'delete');
            return response()->json(['status'=>true,'message'=>'Logged out successfully.']);

        }
    }


    public function checkuser(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'email' => 'email',
                        
                        
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
           
            $row = User::where('role','C') ;
            if($request->phone_number && $request->phone_number !="")
            {
                $phone_number = $request->phone_number;
                $row = $row->where('phone_number',$phone_number);
            }

            if($request->email && $request->email !="")
            {
                $email = $request->email;
                $row = $row ->where('email', $email);
            }      
            $row = $row->first();

            if ($row) 
            {
                
                $user =  $this->getuserdetailfromObjectArray($row);             
                return response()->json(['status'=>true,'message'=>'User detail.','data'=>$user]);
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Invalid user.']);
            }
            
        }
    }


    public function getCountries()
    {
         return response()->json(['status'=>true,'message'=>'Listing.','data'=>$this->getCountryList()]);
    }


}  