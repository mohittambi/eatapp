<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use DB;
use App\Model\User;
use App\Model\Farmer;
use App\Model\Category;
use App\Model\FrontPageCategory;
use App\Model\Location;
use App\Model\FrontPage;
use Session;
use File;

class FrontPagesController extends Controller
{

    protected $model;
    protected $title;

    public function __construct()
    {
      $this->model = 'frontPages';
      $this->title = 'FrontPage';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user_id =  Session::get('AdminLoggedIn')['user_id'];
        $row = FrontPage::where('status', '1')->get();
        $title= 'All FrontPage'; 
        $breadcum = ['FrontPages'=>''];
       
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
        $title= 'Add FrontPage'; 
        $breadcum = ['FrontPage'=>''];
       
        return view('admin.'.$this->model.'.add',compact('title','model','breadcum'));

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
            $validatorRules = [
                'title'    => 'required|max:255|unique:front_pages,title',
            ];
            //dd($validatorRules);
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
             
              $frontPage = new FrontPage;
          
              $frontPage->title   = $request->title;
              $frontPage->content = $request->content;
              $frontPage->status  = $request->status;
              $frontPage->slug = str_slug($request->title);

              $frontPage->save();

              //dd($frontPage);

              Session::flash('success', 'FrontPage added successfully.');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = $this->title.' Profile';
        $model = $this->model;
        
        $row = FrontPage::where('front_pages.id',$id)->where('status','1')->first();
        
        
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

            $user = FrontPage::where('front_pages.id',$id)->first();
            
            if($user)
            {
                
                $title= 'Edit FrontPage'; 
                $breadcum = ['FrontPages'=>route('admin.frontPages.index'),'Edit FrontPage'=>''];
                $model=$this->model;
                $categoryList = array_column($this->getCategoryList(), 'name','id');

                return view('admin.'.$this->model.'.edit',compact('title','user','breadcum','model'));
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
        $frontPage =  FrontPage::where('front_pages.id',$id)->first();

        try
        {
            $validatorRules = [
                'title'    => 'required|max:255|unique:front_pages,title,'.$frontPage->id,
            ];
            //dd($validatorRules);
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                $previous_row           = $frontPage;
                $frontPage->title       = $request->title;
                $frontPage->content     = $request->content;
                $frontPage->slug = str_slug($request->title);

                $frontPage->save();

                Session::flash('success', 'FrontPage updated successfully.');
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

            $user = FrontPage::where('front_pages.id', $id)->delete();
            
            Session::flash('alert-success', 'Record deleted successfully.');
            return redirect()->route('admin.'.$this->model.'.index');

        } catch (\Exception $e) {

            $msg = $e->getMessage();
            Session::flash('warning', $msg);
            return redirect()->back()->withInput();

        }
    }


    public function FrontPageListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'title',
                2 =>'content',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
            );


        $totalData = FrontPage::where('status','1')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = FrontPage::where('status','1')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = FrontPage::where('status','1')
                            ->where(function($query) use ($search){
                        $query->where('id','LIKE',"%{$search}%")
                            ->orWhere('title','LIKE',"%{$search}%")
                            ->orWhere('content','LIKE',"%{$search}%");
                        })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
            $totalFiltered = FrontPage::where('status','1')
                                    ->where(function($query) use ($search){
                                $query->where('id','LIKE',"%{$search}%")
                                    ->orWhere('title','LIKE',"%{$search}%")
                                    ->orWhere('content','LIKE',"%{$search}%");
                                })
                                ->count();
            
        }


        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $list)
            {
              if (isset($list->content) && !empty($list->content)) {
                if(strlen(strip_tags($list->content))>200){
                  $content = substr(strip_tags($list->content), 0, 200).'...';
                }else{$content = strip_tags($list->content);}
              }else{$content = '';}

                $nestedData['id'] = $list->id;
                $nestedData['created_at'] = date('d-m-Y H:i A',strtotime($list->created_at));
                $nestedData['status']  = getStatus($list->status,$list->id);
                $nestedData['title']   = $list->title;
                $nestedData['content'] = $content;
                $nestedData['action']  = getButtons([
                                ['key'=>'view','link'=>route('admin.frontPages.view',$list->id)],
                                ['key'=>'edit','link'=>route('admin.frontPages.edit',$list->id)],
                                ['key'=>'delete','link'=>route('admin.frontPages.delete',$list->id)],
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

    public function FrontPageStatusUpdate(Request $request)
    {
        $id = $request->id;
        $row  = FrontPage::whereId($id)->first();
        $row->status = $row->status=='1'?'0':'1';
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
