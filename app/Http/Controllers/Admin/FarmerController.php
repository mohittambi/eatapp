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
        $row = User::where('status','=', '1')->where('role','=','F')->get();
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

            $full_name          = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->full_name    = $full_name;
            $user->email        = $request->email;
            //$user->password     = $request->password;
            $user->status       = 0;
            $user->role         = 'F';


            if($request->file('cat_img'))
            {
                $file = $request->file('cat_img');
                $image = uploadwithresize($file,'users');
                $user->image= $image;
            }

            $user->save();

            if($request->file('banner_img'))
            {
                $file = $request->file('banner_img');
                $image = uploadwithresize($file,'users');
                $farmer->banner_image = $image;
            }

            $farmer->user_id       = $user->id;
            $farmer->description   = $request->description;
            $farmer->categories    = $request->category;

            $farmer->save();

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
        $row = User::where('id',$id)->where('verified','=','1')->with(['farmerDetails'])->first();

        if($row)
        {
            $breadcum = [$title=>route('admin.'.$this->model.'.index'),$row->full_name=>''];
            return view('admin.'.$this->model.'.view',compact('title','model','breadcum','row')); 
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

            $user = User::where('users.id','=',$id)->with(['farmerDetails','farmer_categories'])->first();
            

            foreach ($user->farmer_categories as $key => $value) {
                $selectedCatList[] = $value->category_id;
            }
            if(!isset($selectedCatList) && empty($selectedCatList)){
                $selectedCatList = '';
            }
            //$farmerDetails = Farmer::where('farmers.user_id','=', $user->id)->first();
            //$farmerDetails = User::find($id)->farmerDetails;
            //dd($user->toArray());
            if($user)
            {
                
                $title= 'Edit Farmer'; 
                $breadcum = ['Farmers'=>route('admin.farmers.index'),'Edit Farmer'=>''];
                $model=$this->model;
                $categoryList = array_column($this->getCategoryList(), 'name','id');

                return view('admin.'.$this->model.'.edit',compact('title','user','categoryList','breadcum','model','categoryList','selectedCatList'));
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

        $user = User::where('users.id','=',$id)->first();
        $farmer =  Farmer::where('farmers.user_id','=',$id)->first();

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
