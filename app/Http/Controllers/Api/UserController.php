<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use App\Model\UserDevice;
use App\Model\SocialAccount;

use JWTAuthException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DB;

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
              //  'gender' => 'required|in:M,F',
                'phone_number' => 'required|numeric|digits_between:7,15|unique:users',
                'country_id' => 'required',


                //'phone_number' => 'required|numeric|digits_between:7,15|unique:users,phone_number,NULL,id,country_id,' . $request->country_id,
               // 'dob' => 'required|date',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|max:50|min:8',
               // 'zipcode' => 'required|max:999999|integer',
                'profile_pic' =>  'mimes:jpeg,jpg,png,gif',
              
                'device_type'=>'required',
                'device_id'=>'required'
        ];

        if($request->role == 'C')
        {
            $validatorRules['gender'] = 'required|in:M,F';
            $validatorRules['dob'] = 'required|date';
            $validatorRules['zipcode'] = 'required|max:999999|integer';
        }

        if($request->role == 'B')
        {
            $validatorRules['business_name'] = 'required';
            $validatorRules['business_address'] = 'required';
        }

       


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

   

    public function signup(Request $request)
    {

        $validatorRules = [
                'role' => 'required|in:B,C',
                'full_name' => 'required|max:255',
               // 'gender' => 'required|in:M,F',
                'phone_number' => 'required|numeric|digits_between:7,15|unique:users',
                'country_id' => 'required',
               // 'dob' => 'required|date',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|max:50|min:8',
               // 'zipcode' => 'required|max:999999|integer',
                'profile_pic' =>  'mimes:jpeg,jpg,png,gif',
                'device_type'=>'required',
                'device_id'=>'required'
        ];


        if($request->role == 'C')
        {
            $validatorRules['gender'] = 'required|in:M,F';
            $validatorRules['dob'] = 'required|date';
            $validatorRules['zipcode'] = 'required|max:999999|integer';
        }

        if($request->role == 'B')
        {
            $validatorRules['business_name'] = 'required';
            $validatorRules['business_address'] = 'required';
        }
        $validator = Validator::make($request->all(),$validatorRules);
        
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = new User();
           
            $row->full_name= $request->full_name;
           
            $row->phone_number=$request->phone_number;
            $row->country_id=$request->country_id;
            
            $row->email=$request->email;
            $row->password = bcrypt($request->password);
            
            $row->status = 1;
            $row->role = $request->role?$request->role:'C';

            if($request->role == 'C')
            {
                $row->gender=$request->gender?$request->gender:'M';
                $dob = strtotime($request->dob);
                $row->dob=date('Y-m-d', $dob);
                $row->zipcode=$request->zipcode;
            }
              if($request->role == 'B')
            {
               
                $row->business_name=$request->business_name;
                 $row->business_address=$request->business_address;
            }





            if($request->file('profile_pic'))
            {
                $file = $request->file('profile_pic');
                $image = uploadwithresize($file,'users');
                $row->image= $image;
            }
            $row->save();
            $this->manageDeviceIdAndToken($row->id,$request->device_id,$request->device_type,'add');
            if(isset($request->social_id) && $request->social_id != "" && isset($request->social_type) && $request->social_type != "")
            {
                $this->manageSocialAccounts($row->id,$request->social_id,$request->social_type);
            }
            $user = $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Registration successful.','data'=>$user]);
        }
    }


    public function login(Request $request)
    {

    	Log::debug($request->all());
        $validator = Validator::make($request->all(), [
                        'role' => 'required|in:B,C',
                        'username' => 'required',
                        'password' => 'required',
                        'device_type' => 'required',
                        'device_id' => 'required',
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $username = $request->username;
            $password = bcrypt($request->password);
            $row = User::where(function($query) use ($username){
                        $query->where('email',$username)
                        ->orWhere('phone_number',$username);
                    })
                   
                    ->first();

            if ($row && Hash::check($request->password, $row->password)) 
            {
                if($row->status == '1')
                {
                    if($request->role == 'B')
                    {
                        $is_have_business_details = $this->userHaveBusinessDetails($row);
                        if($is_have_business_details == true)
                        {
                            $row->role = 'B';
                            $row->save();  
                        }
                    }

                    if($request->role == 'C')
                    {
                        $is_have_customer_details = $this->userHaveCustomerDetails($row);
                        if($is_have_customer_details == true)
                        {
                            $row->role = 'C';
                            $row->save();  
                        }
                    }


                    $user =  $this->getuserdetailfromObjectArray($row);

                    $this->manageDeviceIdAndToken($row->id,$request->device_id,$request->device_type,'add');               
                    return response()->json(['status'=>true,'message'=>'Login successful.','data'=>$user]);
                }
                else
                {
                    return response()->json(['status'=>false,'message'=>'Your account is deactivated.']);
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
                'role' => 'required|in:B,C',
                'user_id' => 'required|isValidUser:'.$request->user_id,
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'full_name' => 'required|max:255',
                'profile_pic' =>  'mimes:jpeg,jpg,png,gif',
        ];


         if($request->role == 'C')
        {
            $validatorRules['zipcode'] = 'required|max:999999|integer';
        }

        if($request->role == 'B')
        {
            $validatorRules['business_name'] = 'required|max:255';
            $validatorRules['business_address'] = 'required|max:255';
            $validatorRules['cover_photo'] = 'mimes:jpeg,jpg,png,gif';
        }


        $validator = Validator::make($request->all(), $validatorRules);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row =  User::where('id',$request->user_id)->first();
            $previous_row = $row;
            $row->full_name= $request->full_name;
            $row->email=$request->email;   

            if($request->role == 'C')
            {
                $row->zipcode=$request->zipcode;
            }

            if($request->role == 'B')
            {
                $row->business_name=$request->business_name;
                $row->business_address=$request->business_address;
            }
                 
           
            if($request->file('cover_photo'))
            {
                $file = $request->file('cover_photo');
                $cover_photo = uploadwithresize($file,'users');
               
                if($previous_row->cover_photo)
                {
                    unlinkfile('users',$previous_row->cover_photo);
                }
                $row->cover_photo= $cover_photo;
               
            }

            if($request->file('profile_pic'))
            {
                $file = $request->file('profile_pic');
                $image = uploadwithresize($file,'users');
               
                if($previous_row->image)
                {
                    unlinkfile('users',$previous_row->image);
                }
                $row->image= $image;
               
            }
            $row->save();
            $user = $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Profile updated successfully.','data'=>$user]);
        }
    }


    public function socialUserCheck(Request $request)
    {

        $validator = Validator::make($request->all(), [
                        'role' => 'required|in:B,C',
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
            $social_row = SocialAccount::where('social_type',$request->social_type)->where('social_id',$request->social_id);
            if(isset($request->email) && $request->email !="")
            {
                $email = $request->email;
                $social_row = $social_row->whereHas('getAssociateUserWithSocial',function ($query) use($email) {  
                        $query->where('email', $email)
                        ->where('role', 'U')

                        ;
                    });
            }
            $social_row = $social_row->first();    
            if($social_row)
            {
                $row = $social_row->getAssociateUserWithSocial;
                $user =  $this->getuserdetailfromObjectArray($row);
                return response()->json(['status'=>true,'message'=>'Social user detail.','data'=>$user]);
            }
            else
            {

                 if(isset($request->email) && $request->email !="")
                 {
                    $user = User::where('email',$request->email)->first();
                    if($user)
                    {
                        $this->manageSocialAccounts($user->id,$request->social_id,$request->social_type);

                        if($request->role == 'B')
                        {
                            $is_have_business_details = $this->userHaveBusinessDetails($user);
                            if($is_have_business_details == true)
                            {
                                $user->role = 'B';
                                $user->save();  
                            }
                        }

                        if($request->role == 'C')
                        {
                             $is_have_customer_details = $this->userHaveCustomerDetails($user);
                            if($is_have_customer_details == true)
                            {
                                $user->role = 'C';
                                $user->save();  
                            }
                        }


                        $user =  $this->getuserdetailfromObjectArray($user);
                        return response()->json(['status'=>true,'message'=>'Social user detail','data'=>$user]);
                    }
                    else
                    {
                        return response()->json(['status'=>false,'message'=>'No user found.']);
                    }

                 }
                else
                {
                    return response()->json(['status'=>false,'message'=>'No user found.']);
                }

               
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
                $password_reset_token = strtotime(date('Y-m-d H:i:s')).rand(99,999);
                $password_reset_token =  bcrypt($password_reset_token);
                $row->password_reset_token = $password_reset_token;
                $row->save();
                $data=  array(
                    "email"=>$row->email,
                    "token"=>$password_reset_token,
                    'reset_password_url'=>route('front.forgot.password',['token'=>$password_reset_token])
                    );
                mailSend($data);
                return response()->json(['status'=>true,'message'=>'Reset password link sent to your email address.']);
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
            $user = (object)array(
            'user_id'=>(int)$row->id,
          
            'full_name'=>$row->full_name,
            'country_id'=>(int)$row->country_id,
            'country_name'=>$row->getRelatedCountry->name,
            'phonecode'=>$row->getRelatedCountry->phonecode,
            'email'=>$row->email,
            'phone_number'=>$row->phone_number,
            'profile_pic'=>$row->image?$this->uploadsfolder.'/users/'.$row->image:asset('images/user.png'),
            'profile_pic_thumb'=>$row->image?$this->uploadsfolder.'/users/thumb/'.$row->image:asset('images/user.png'),
            'status'=>$row->status,
            'role'=>'C',
            'zipcode'=>$row->zipcode?$row->zipcode:'',
            'created_at'=> strtotime($row->created_at),
            'gender'=>$row->gender=='M'?'Male':'Female',
            'dob'=> date('d-m-Y',strtotime($row->dob)),


           
            );
        }

         if($row->role == 'B')
        {
            $user = (object)array(
            'user_id'=>(int)$row->id,
           
            'full_name'=>$row->full_name,
            'country_id'=>(int)$row->country_id,
            'country_name'=>$row->getRelatedCountry->name,
            'phonecode'=>$row->getRelatedCountry->phonecode,
            'email'=>$row->email,
            'phone_number'=>$row->phone_number,
            'profile_pic'=>$row->image?$this->uploadsfolder.'/users/'.$row->image:asset('images/user.png'),
            'profile_pic_thumb'=>$row->image?$this->uploadsfolder.'/users/thumb/'.$row->image:asset('images/user.png'),
            'status'=>$row->status,
            'role'=>'B',
            'business_name'=>$row->business_name,
             'business_address'=>$row->business_address,

              'about_my_company'=>$row->about_my_company?$row->about_my_company:'',
               'business_license'=>$row->business_license?$row->business_license:'',

             
             

              'cover_photo'=>$row->cover_photo?$this->uploadsfolder.'/users/'.$row->cover_photo:'',
            'cover_photo_thumb'=>$row->cover_photo?$this->uploadsfolder.'/users/thumb/'.$row->cover_photo:'',


            'created_at'=> strtotime($row->created_at)
            );
        }

        $user->is_have_customer_details = $this->userHaveCustomerDetails($row);
        $user->is_have_business_details = $this->userHaveBusinessDetails($row);
        return $user;
    }

    public function userHaveCustomerDetails($row)
    {
        if($row->gender && $row->gender != '' && $row->zipcode && $row->zipcode != '' && $row->dob && $row->dob != '')
        {
            return true;
        }
        else
        {
             return false;
        }
    }

    public function userHaveBusinessDetails($row)
    {
        if($row->business_name && $row->business_name != '' && $row->business_address && $row->business_address != '' )
        {
            return true;
        }
        else
        {
             return false;
        }
    }

    public function addBusinessDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id,
                        'business_address'=>'required',
                        'business_name'=>'required'  
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::whereId($request->user_id)->first();
            $row->business_address = $request->business_address;
            $row->business_name = $request->business_name;
            $row->role = 'B';
            $row->save();
            $user =  $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Profile updated successfully.','data'=>$user]);
        }
    }

     public function addBusinessExtraDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id,
                        'key'=>'required|in:about_my_company,business_license',
                        'detail'=>'required'  
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::whereId($request->user_id)->first();
            if($request->key == 'about_my_company')
            {
                $row->about_my_company = $request->detail;
            }

            if($request->key == 'business_license')
            {
                $row->business_license = $request->detail;
            }

            $row->save();
            $user =  $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Successfully updated.','data'=>$user]);
        }
    }

    public function addCustomerDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id,
                        'gender'=>'required|in:M,F',
                        'zipcode'=>'required|max:999999|integer', 
                        'dob'=>'required|date' 
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::whereId($request->user_id)->first();
            $row->gender=$request->gender?$request->gender:'M';
            $dob = strtotime($request->dob);
            $row->dob=date('Y-m-d', $dob);
            $row->zipcode=$request->zipcode;
            $row->role = 'C';
            $row->save();
            $user =  $this->getuserdetailfromObjectArray($row);
            return response()->json(['status'=>true,'message'=>'Profile updated successfully.','data'=>$user]);
        }
    }


     public function switchRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'user_id' => 'required|isValidUser:'.$request->user_id,
                        'role'=>'required|in:B,C'
                        
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::whereId($request->user_id)->first();

            if($request->role=='C')
            {
                $is_have_customer_details = $this->userHaveCustomerDetails($row);
                if($is_have_customer_details == true)
                {
                    $row->role = 'C';
                    $row->save(); 
                    $user =  $this->getuserdetailfromObjectArray($row);
                    return response()->json(['status'=>true,'message'=>'Switched successfully.','data'=>$user]);
                }
                else
                {
                     return response()->json(['status'=>false,'message'=>'Do not have customer details.']);
                }
            }
            else if($request->role=='B')
            {
                $is_have_business_details = $this->userHaveBusinessDetails($row);
                if($is_have_business_details == true)
                {
                    $row->role = 'B';
                    $row->save(); 
                    $user =  $this->getuserdetailfromObjectArray($row);
                    return response()->json(['status'=>true,'message'=>'Switched successfully.','data'=>$user]);
                }
                else
                {
                     return response()->json(['status'=>false,'message'=>'Do not have business details.']);
                }
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Invalid access.']);
            }
        }
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
            $row = User::whereId($request->user_id)->first();
            if (Hash::check($request->currentPassword, $row->password)) 
            {
                $row->password = bcrypt($request->newPassword);
                $row->save();
                return response()->json(['status'=>true,'message'=>'Password changed successfully.']);
            }
            else
            {
                return response()->json(['status'=>false,'message'=>'Old password is not correct.']);
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

    public function updatePasswordByPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'phone_number' => 'required',
                        'password'=>'required||min:8'
                        
                    ]);
        if ($validator->fails()) 
        {
            $error = $this->validationHandle($validator->messages()); 
            return response()->json(['status'=>false,'message'=>$error]);
        }
        else
        {
            $row = User::where('phone_number',$request->phone_number)->first();
            if($row)
            {
                $row->password = bcrypt($request->password);
                $row->save();
                return response()->json(['status'=>true,'message'=>'Password updated successfully.']);
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