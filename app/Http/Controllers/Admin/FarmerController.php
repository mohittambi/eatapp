<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use DB;
use App\Model\User;
use App\Model\Farmer as Farmer;
use App\Model\Category as Category;
use App\Model\FarmerCategory;
use App\Model\Country;
use App\Model\Location;
use App\Model\FarmerNonAvailibility as FarmerNA;
use App\Model\FarmerWorkingHour as FarmerWH;
use App\Model\FarmerAmenity;
use App\Model\Amenity;
use App\Model\FarmerBanner;
use Session;
use File;

class FarmerController extends Controller
{

    protected $model;
    protected $title;

    public function __construct()
    {
      $this->model = 'farmers';
      $this->title = 'Farmer';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user_id =  Session::get('AdminLoggedIn')['user_id'];
        $row = User::where('verified','=', '1')->where('role','=','F')->get();
        $title= 'All Farmer'; 
        $breadcum = ['Farmers'=>''];
       
        return view('admin.'.$this->model.'.index',compact('title','row','breadcum'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model=$this->model;
        $title= 'Add Farmer'; 
        $breadcum = ['Farmer'=>''];
        $categoryList = array_column($this->getCategoryList(), 'name','id');
       
        return view('admin.'.$this->model.'.add',compact('title','model','breadcum','categoryList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        try
        {
        
            $user = new User;
            $farmer = new Farmer;
            $farmerCategory = new FarmerCategory;

            $full_name          = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->full_name    = $full_name;
            $user->email        = $request->email;
            //$user->password     = $request->password;
            $user->status       = 0;
            $user->verified     = '1';
            $user->role         = 'F';


            if($request->file('cat_img'))
            {
                $file = $request->file('cat_img');
                $image = uploadwithresize($file,'farmers');
                $user->image= $image;
            }

            $user->save();

            if($request->file('banner_img'))
            {
                $file = $request->file('banner_img');
                $image = uploadwithresize($file,'banners');
                $farmer->banner_image = $image;
            }

            $farmer->user_id       = $user->id;
            $farmer->description   = $request->description;

            $farmer->save();

            

            if(isset($request->categories)){
                foreach ($request->categories as $key => $category_id) {
                    $farmerCategory->user_id        = $user->id;
                    $farmerCategory->category_id    = $category_id;
                    $farmerCategory->save();
                }

            }

            Session::flash('success', 'Farmer added successfully.');
            return redirect()->route('admin.'.$this->model.'.index');

        }

        catch(\Exception $e)
        {
           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = $this->title.' Profile';
        $model = $this->model;
        
        $row = User::where('id',$id)->where('verified','1')->with(['farmerDetails','farmer_categories','locations'])->first();
        foreach($row->farmer_categories as $categories){
            $catlist[] = Category::where('categories.id',$categories->category_id)->first()->name;
        }
        if(isset($catlist) && !empty($catlist))
        {$catListName = implode(', ', $catlist);}
        else{$catListName='';}
        $all_service_types = array('1' => 'Standard', '2' => 'Silver', '3' => 'Gold', null=>'');

        $all_na = FarmerNA::where('user_id',$row->id)->select('start_date as start')->get()->toArray();

        $days = ['monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday','sunday'=>'Sunday'];

        if(FarmerWH::where('user_id',$row->id)->exists())
        {
                $farmer_wh = FarmerWH::where('user_id',$row->id)->get();
                for($i=0;$i<$farmer_wh->count();$i++){
                    //dd($farmer_wh[$i]->day_name);
                    $keys = array_keys($days);
                    $data[$i]['day_name']     = $days[$keys[$i]];
                    $data[$i]['opening_time'] = date('H:i', strtotime($farmer_wh[$i]->opening_time));
                    $data[$i]['closing_time'] = date('H:i', strtotime($farmer_wh[$i]->closing_time));
                    $data[$i]['visitors']     = $farmer_wh[$i]->visitors;
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
        
        $user_selected_amenity = FarmerAmenity::select('amenity_id')->where('user_id',$row->id)->pluck('amenity_id')->toArray();
        foreach ($user_selected_amenity as $value) {
            $amenities[] = Amenity::where('status','1')->where('amenities.id',$value)->get();
        }

        $farmers_banners = FarmerBanner::select('name','description')->where('user_id',$row->id)->get()->toArray();

        if($row)
        {
            $breadcum = [$title=>route('admin.'.$this->model.'.index')];
            return view('admin.'.$this->model.'.view',compact('title','model','breadcum','row','catListName','all_service_types','data','all_na','user_selected_amenity','amenities','farmers_banners')); 
        }
        else
        {
            Session::flash('warning', 'Invalid request');
           return redirect()->back();
        }  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {

            $user = User::where('users.id',$id)->with(['farmerDetails','farmer_categories','locations'])->first();
            $countryData = Country::orderBy('sortname','asc')->pluck('sortname','phonecode');

            foreach ($user->farmer_categories as $key => $value) {
                $selectedCatList[] = $value->category_id;
            }
            if(!isset($selectedCatList) && empty($selectedCatList)){
                $selectedCatList = '';
            }
            //$farmerDetails = Farmer::where('farmers.user_id','=', $user->id)->first();
            //$farmerDetails = User::find($id)->farmerDetails;
            
            $all_service_types = array('1' => 'Standard', '2' => 'Silver', '3' => 'Gold');

            if($user)
            {
                
                $title= 'Edit Farmer'; 
                $breadcum = ['Farmers'=>route('admin.farmers.index'),'Edit Farmer'=>''];
                $model=$this->model;
                $categoryList = array_column($this->getCategoryList(), 'name','id');

                return view('admin.'.$this->model.'.edit',compact('title','user','categoryList','breadcum','model','categoryList','selectedCatList','countryData','all_service_types'));
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::where('id',$id)->first();
        $farmer =  Farmer::where('farmers.user_id','=',$id)->first();
        $location = Location::where('user_id',$user->id)->first();

        try
        {
            $validatorRules = [
                'first_name'    => 'required|max:255',
                'last_name'     => 'required|max:255',
                //'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'address'       => 'required|max:255',
                'vat_number'    => 'required|max:50',
                'cf'            => 'max:16',
                'location_name' => 'max:255',
                'phonecode'     => 'required',
                'latitude'      => 'required'
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
                // if(preg_match('/^[A-Z]{6}+[0-9]{2}+^[A-Z]{1}+[0-9]{2}+^[A-Z]{1}+[0-9]{3}+^[A-Z]{1}/', $request->cf)) {
                //     echo 'valid';

                // }
                // else{
                //     echo "invalid";
                // }
                //dd($request->all());
                $previous_row = $user;
                $full_name=$request->first_name.' '.$request->last_name;
                $user->first_name=$request->first_name;
                $user->last_name=$request->last_name;
                $user->full_name=$full_name;
                //$user->email=$request->email;
                $user->phonecode=$request->phonecode;
                $user->status=$request->status;

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
                // print_r($request->phonecode);
                // dd($user);
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

                $farmer->user_id             = $user->id;
                $farmer->description         = $request->description;
                $farmer->company_name        = $request->company_name?$request->company_name:null;
                $farmer->vat_number          = $request->vat_number;
                $farmer->cf                  = $request->cf?$request->cf:null;
                $farmer->contract_start_date = $request->contract_start_date?$request->contract_start_date:null;
                $farmer->contract_end_date   = $request->contract_end_date?$request->contract_end_date:null;
                $farmer->service_type        = $request->service_type?$request->service_type:null;

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
                return redirect()->route('admin.'.$this->model.'.index');
            }
        }

        catch(\Exception $e)
        {
           $msg = $e->getMessage();
           Session::flash('warning', $msg);
           return redirect()->back()->withInput();
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $user = User::where('id', '=', $id)->delete();
            
            Session::flash('alert-success', 'Record deleted successfully.');
            return redirect()->route('admin.'.$this->model.'.index');

        } catch (\Exception $e) {

            $msg = $e->getMessage();
            Session::flash('warning', $msg);
            return redirect()->back()->withInput();

        }
    }


    public function FarmerListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'full_name',
                2 =>'email',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
            );


        $totalData = User::where('verified','=','1')->where('role','=','F')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = User::where('verified', '=','1')
                        ->where('role','=','F')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = User::where('verified','=','1')
                            ->where('role','=','F')
                            ->where(function($query) use ($search){
                        $query->where('id','LIKE',"%{$search}%")
                            ->orWhere('first_name','LIKE',"%{$search}%")
                            ->orWhere('last_name','LIKE',"%{$search}%")
                            ->orWhere('email','LIKE',"%{$search}%");
                        })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            $totalFiltered = User::where('verified','=','1')
                                    ->where('role','=','F')
                                    ->where(function($query) use ($search){
                                $query->where('id','LIKE',"%{$search}%")
                                    ->orWhere('first_name','LIKE',"%{$search}%")
                                    ->orWhere('last_name','LIKE',"%{$search}%")
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
                                ['key'=>'view','link'=>route('admin.farmers.view',$list->id)],
                                ['key'=>'edit','link'=>route('admin.farmers.edit',$list->id)],
                                ['key'=>'delete','link'=>route('admin.farmers.delete',$list->id)],
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

    public function FarmerStatusUpdate(Request $request)
    {
        $id = $request->id;
        $row  = User::whereId($id)->first();
        $row->status =  $row->status=='1'?'0':'1';
        $row->save();
        $html = '';
        switch ($row->status) {
          case '1':
            $html =  '<a data-toggle="tooltip"  class="btn btn-success btn-xs" title="Active" onClick="changeStatus('.$id.')" >Active</a>';
            break;
          case '0':
            $html =  '<a data-toggle="tooltip"  class="btn btn-danger btn-xs" title="Inactive" onClick="changeStatus('.$id.')" >InActive</a>';
            break;
          
          default:
            break;
      }
      return $html;


    }
}
