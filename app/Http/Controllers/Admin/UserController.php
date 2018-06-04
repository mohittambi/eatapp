<?php

namespace App\Http\Controllers\Admin;
use App\Model\User;
use App\Model\Customer;
use Session;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;   
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
class UserController extends Controller
{

    protected $model;
    protected $title;
    protected $pmodule;
    public function __construct()
    {
        $this->model = 'users';
        $this->title = 'Users';
        $this->pmodule = 'users';
    }

    public function login()
    {
        $title= 'Login'; 
        return view('admin.users.login',compact('title'));
    }

    public function makelogin(Request $request)
    {
        
        try {
                $validator = Validator::make($request->all(), [
                            'username' => 'required',
                            'password' => 'required',
                ]);
                if ($validator->fails()) 
                {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                else 
                {
                   $username = $request->username;
                   $password = bcrypt($request->password);
                   $user = User::where('email',$username)->where('role','A')->first();
                  
                    if($user && Hash::check($request->password, $user->password))
                   {
                        Session::put('AdminLoggedIn', ['user_id'=>$user->id,'userData'=> $user]);
                        Session::save();
                        return redirect()->route('admin.dashboard');
                   }
                   else
                   {
                        Session::flash('danger','Invalid username or password.');
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

    public function logout()
    {
        Session::forget('AdminLoggedIn');
        return redirect()->route('admin.login');
    }

    public function userGetChangePassword()
    {
        $user_id = Session::get('AdminLoggedIn')['user_id'];
        $row =  User::whereId($user_id)->first();
        $title = 'Change Password';
        $breadcum = ['Change Password'=>''];
       return view('admin.users.change_profile_password',compact('title','breadcum','row')); 
    }

    public function userPostChangePassword(Request $request)
    {
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator->errors());
        }
        else
        {
            $user_id =  Session::get('AdminLoggedIn')['user_id'];
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
                Session::flash('warning', 'Current password is incorrect.');
                return redirect()->back();
            }
        }
    }

     public function myProfile()
    {
        $user_id =  Session::get('AdminLoggedIn')['user_id'];
        $row = User::whereId($user_id)->first();
        $title= 'My Profile'; 
        $breadcum = ['My Profile'=>''];
       
        return view('admin.users.myProfile',compact('title','row','breadcum'));

    }

    public function updateMyProfile(Request $request, $slug)
    {
        $row =  User::whereSlug($slug)->first();
        try
        {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|max:255',
                'image_update' =>  'mimes:jpeg,jpg,png,gif',
                'email' => 'required|email|unique:users,email,' . $row->id,
                ]);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                 $previous_row = $row;
                $row->full_name= $request->full_name;
                $row->email=$request->email;
                 if($request->file('image_update'))
                {
                    $file = $request->file('image_update');
                    $image = uploadwithresize($file,'users');
                   
                    if($previous_row->image)
                    {
                        unlinkfile('users',$previous_row->image);
                    }

                    $row->image= $image;
                   
                }
                $row->save();
                Session::flash('success', 'Profile updated successfully.');
                return redirect()->route('admin.profile');
            }  
        }
        catch(\Exception $e)
        {

           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        }
    }

    public function index()
    {
        $title = 'Customers';
        $model = $this->model;
       // $lists = User::where('role','U')->get();

        $breadcum = [$title=>route($this->model.'.index'),'Listing'=>''];
        return view('admin.users.index',compact('title','model','breadcum')); 
    }

     public function businessIndex()
    {
        $title = 'Business';
        $model = 'business';
       // $lists = User::where('role','U')->get();

        $breadcum = [$title=>route($model.'.index'),'Listing'=>''];
        return view('admin.users.business.index',compact('title','model','breadcum')); 
    }

     public function businessListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'full_name',
                2 =>'email',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
            );

       

        $totalData = User::where('role','B')->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = User::where('role','B')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = User::where('role','B')
                        ->where(function($query) use ($search){
                        $query->where('id','LIKE',"%{$search}%")
                        
                         ->orWhere('full_name','LIKE',"%{$search}%")
                          ->orWhere('email','LIKE',"%{$search}%");
                        })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            $totalFiltered = User::where('role','B')
                                
                               ->where(function($query) use ($search){
                                $query->where('id','LIKE',"%{$search}%")
                               
                                 ->orWhere('full_name','LIKE',"%{$search}%")
                                  ->orWhere('email','LIKE',"%{$search}%");
                                })
                                ->count();
            
        }


         $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $list)
            {
                
                $nestedData['id'] = $list->id;
                $nestedData['created_at'] = date('d-m-Y H:i A',strtotime($list->created_at));
               
                $nestedData['status'] = getStatus($list->status,$list->id);
                $nestedData['email'] =  $list->email;
                $nestedData['full_name'] =  ucfirst($list->full_name);
                $nestedData['action'] =  getButtons([
                                ['key'=>'view','link'=>route('admin.business.view',$list->slug)],
                                ['key'=>'edit','link'=>route('admin.business.edit',$list->slug)],
                                ['key'=>'delete','link'=>route('admin.users.delete',$list->slug)],
                            ]);
                $data[] = $nestedData; 
            }
        }

      $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );
        echo json_encode($json_data); 
    }

    

    public function userListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'full_name',
                2 =>'email',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
                6=>'farmer_code',
            );

       

        $totalData = User::where('role','C')->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = User::where('role','C')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = User::where('role','C')
                        ->where(function($query) use ($search){
                        $query->where('id','LIKE',"%{$search}%")
                        
                         ->orWhere('full_name','LIKE',"%{$search}%")
                          ->orWhere('email','LIKE',"%{$search}%");
                        })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            $totalFiltered = User::where('role','C')
                                
                               ->where(function($query) use ($search){
                                $query->where('id','LIKE',"%{$search}%")
                               
                                 ->orWhere('full_name','LIKE',"%{$search}%")
                                  ->orWhere('email','LIKE',"%{$search}%");
                                })
                                ->count();
            
        }


         $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $list)
            {
                
                $nestedData['id'] = $list->id;
                $nestedData['created_at'] = date('d-m-Y H:i A',strtotime($list->created_at));
               
                $nestedData['status'] = getStatus($list->status,$list->id);
                $nestedData['email'] =  $list->email;
                $customer = Customer::where('user_id',$list->id)->first();
                $nestedData['farmer_code'] =  $customer->farmer_code;
                $nestedData['full_name'] =  ucfirst($list->full_name);
                $nestedData['action'] =  getButtons([
                                ['key'=>'view','link'=>route('admin.users.view',$list->slug)],
                                ['key'=>'edit','link'=>route('admin.users.edit',$list->slug)],
                                ['key'=>'delete','link'=>route('admin.users.delete',$list->slug)],
                            ]);
                $data[] = $nestedData; 
            }
        }

      $json_data = array(
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $data   
                );
        echo json_encode($json_data); 
    }

    public function userStatusUpdate(Request $request)
    {
        $user_id = $request->user_id;
        $row  = User::whereId($user_id)->first();
        $row->status =  $row->status=='1'?'0':'1';
        $row->save();
        $html = '';
        switch ($row->status) {
          case '1':
               $html =  '<a data-toggle="tooltip"  class="btn btn-success btn-xs" title="Active" onClick="changeStatus('.$user_id.')" >Active</a>';
              break;
               case '0':
               $html =  '<a data-toggle="tooltip"  class="btn btn-danger btn-xs" title="Inactive" onClick="changeStatus('.$user_id.')" >InActive</a>';
              break;
          
          default:
            
              break;
      }
      return $html;


    }


    public function userView($slug)
    {
        $title = 'Customers';
        $model = $this->model;
        $row = User::where('slug',$slug)->where('role','C')->first();
        if($row)
        {
            $breadcum = [$title=>route($this->model.'.index'),$row->full_name=>''];
            return view('admin.users.userView',compact('title','model','breadcum','row')); 
        }
        else
        {
            Session::flash('warning', 'Invalid request');
           return redirect()->back();
        }   
    }


    public function userEdit($slug)
    {

        try
        {
            $title = 'Customers';
            $model = $this->model;
            $row = User::where('slug',$slug)->where('role','C')->with('customerDetails')->first();

            if($row)
            {
                $countryList = array_column($this->getCountryList(), 'name','id');
                $breadcum = [$title=>route($this->model.'.index'),'Edit'=>'',$row->full_name=>''];
                return view('admin.users.userEdit',compact('title','model','breadcum','row','countryList')); 
            }
            else
            {
                Session::flash('warning', 'Invalid request');
                return redirect()->back();
            }  
        }
        catch(\Exception $e)
        {
           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        } 
    }


    public function userUpdate(Request $request, $slug)
    {
        $row =  User::whereSlug($slug)->first();
        try
        {
            $validatorRules = [
                'full_name' => 'required|max:255',
                'gender' => 'required|in:M,F',
                'phone_number' => 'required|numeric|digits_between:7,15',
                //'country_id' => 'required',
                'dob' => 'required|date',
                //'email' => 'required|email|max:255|unique:users,email,' . $row->id,
                // 'zipcode' => 'required|max:999999|integer',  
            ];
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                $previous_row = $row;
                $dob = strtotime($request->dob);
                $row->full_name= $request->full_name;
                $row->gender=$request->gender;
                $row->phonecode=$request->phonecode;
                $row->phone_number=$request->phone_number;
                $row->country_id=$request->country_id?$request->country_id:'0';
                $row->dob=date('Y-m-d', $dob);
                //$row->email=$request->email;
                //$row->zipcode=$request->zipcode;
                $row->status=$request->status; 
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
                $customer = Customer::where('user_id',$row->id)->first();
                $customer->address       = $request->address;
                $customer->address_lat   = $request->latitude;
                $customer->address_lang  = $request->longitude;
                    
                $customer->save();
                Session::flash('success', 'Record updated successfully.');
                return redirect()->route($this->model.'.index');  
               
            }
        }
        catch(\Exception $e)
        {
            $msg = $e->getMessage();
            Session::flash('warning', $msg);
            return redirect()->back()->withInput();
        }

    }

    

    public function userDelete($slug)
    {
        try {

            $user = User::where('slug', '=', $slug)->delete();
            
            Session::flash('success', 'Record deleted successfully.');
            return redirect()->back();

        } catch (\Exception $e) {

            $msg = $e->getMessage();
            Session::flash('warning', $msg);
            return redirect()->back()->withInput();

        }
    }
 
}
