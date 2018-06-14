<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use DB;
use App\Model\User;
use App\Model\Banner as Banner;
use Session;
use File;

class BannerController extends Controller
{

    protected $model;
    protected $title;

    public function __construct()
    {
      $this->model = 'banner';
      $this->title = 'banner';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$user_id =  Session::get('AdminLoggedIn')['user_id'];
        $row = Banner::where('status','=', '1')->get();
        $title= 'Banners'; 
        $breadcum = ['Banners'=>route('admin.banner.index')];
       
        return view('admin.banner.index',compact('title','row','breadcum'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $model=$this->model;
        $title= 'Add Banner'; 
        $breadcum = ['Banners'=>route('admin.banner.index'), 'Add Banner'=>''];
       
        return view('admin.banner.add',compact('title','model','breadcum'));

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
        
            $row = new Banner;

            $row->name=$request->name;
            $row->description=$request->description;
            $row->status=$request->status;
            if($request->file('cat_img'))
            {
                $file = $request->file('cat_img');
                $image = uploadwithresize($file,'banners');

                // if($previous_row->image)
                // {
                //     unlinkfile('banners',$previous_row->image);
                // }

                $row->image= $image;
            }

            $row->save();
            Session::flash('success', 'Banner added successfully.');
            return redirect()->route('admin.banner.index');

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
        $title = 'banner';
        $model = $this->model;
        $row = Banner::where($this->model.'.id',$id)->where('status','=','1')->first();
        if($row)
        {
            $breadcum = [$title=>route('admin.'.$this->model.'.index'),$row->full_name=>''];
            return view('admin.banner.view',compact('title','model','breadcum','row')); 
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

            $row = Banner::where('banners.id','=', $id)->first();
            // dd($row->toArray());
            if($row)
            {
                $title= 'Edit Banner'; 
                $breadcum = ['Banner'=>''];
                $model=$this->model;
                return view('admin.banner.edit',compact('title','row','breadcum','model'));
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

        $row =  Banner::where('banners.id','=',$id)->first();
        // print_r($row);die();
        //print_r($request->all());die();
        try
        {
            $validatorRules = [
                'name' => 'required|max:255',
                //'email' => 'required|email|max:255|unique:users,email,' . $row->id,
                
            ];
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                
                $previous_row = $row;
                $row->name=$request->name;
                $row->description=$request->description;
                $row->status=$request->status;

                if($request->file('banner_img'))
                {
                    $file = $request->file('banner_img');
                    $image = uploadwithresize($file,'banners');

                    if($previous_row->image)
                    {
                        unlinkfile('banners',$previous_row->image);
                    }

                    $row->image= $image;
                }
                
                $row->save();
                Session::flash('success', 'Banner added successfully.');
                return redirect()->route('admin.banner.index');
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

            $user = Banner::where('banners.id', '=', $id)->delete();
            
            Session::flash('alert-success', 'Record deleted successfully.');
            return redirect()->route('admin.'.$this->model.'.index');

        } catch (\Exception $e) {

            $msg = $e->getMessage();
            Session::flash('warning', $msg);
            return redirect()->back()->withInput();

        }
    }


    public function BannerListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'name',
                2 =>'description',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
            );


        $totalData = Banner::where('isVerified','=','1')->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = Banner::where('isVerified', '=','1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = Banner::where('isVerified','=','1')
                        ->where(function($query) use ($search){
                        $query->where('banners.id','LIKE',"%{$search}%")
                        ->orWhere('name','LIKE',"%{$search}%")
                        ->orWhere('description','LIKE',"%{$search}%");
                        })
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
            $totalFiltered = Banner::where('isVerified','=','1')
                                ->where(function($query) use ($search){
                                $query->where('banners.id','LIKE',"%{$search}%")
                                ->orWhere('name','LIKE',"%{$search}%")
                                ->orWhere('description','LIKE',"%{$search}%");
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
                $nestedData['description'] =  $list->description;
                $nestedData['name'] =  ucfirst($list->name);
                $nestedData['action'] =  getButtons([
                                //['key'=>'view','link'=>route('admin.banner.view',$list->id)],
                                ['key'=>'edit','link'=>route('admin.banner.edit',$list->id)],
                                ['key'=>'delete','link'=>route('admin.banner.delete',$list->id)],
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

    public function BannerStatusUpdate(Request $request)
    {
        $id = $request->id;
        $row  = Banner::where('banners.id','=',$id)->first();
        $row->status =  $row->status=='1'?'0':'1';
        $row->save();
        $html = '';
        switch ($row->status) {
          case '1':
            $html =  '<a data-toggle="tooltip"  class="btn btn-success btn-xs" title="Active" onClick="changeStatus('.$id.')" >Active</a>';
            break;
          case '0':
            $html =  '<a data-toggle="tooltip"  class="btn btn-danger btn-xs" title="InActive" onClick="changeStatus('.$id.')" >InActive</a>';
            break;
          
          default:
            break;
      }
      return $html;


    }
}
