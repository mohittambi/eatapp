<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Model\EmailTemplate;
use App\Model\User;
use DB;
use Session;
use File;

class EmailTemplateController extends Controller
{
	protected $model;
    protected $title;

    public function __construct()
    {
      $this->model = 'emailTemplates';
      $this->title = 'Email Template';
    }

	public function index()
    {

        $row = EmailTemplate::where('status','=', '1')->get();
        $title= 'All Templates'; 
        $breadcum = ['Templates'=>''];
       
        return view('admin.'.$this->model.'.index',compact('title','row','breadcum'));

    }

    public function create()
    {
        $model=$this->model;
        $title= 'Add Email Template'; 
        $breadcum = ['EmailTemplate'=>''];
       
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
        $emailTemplate = new EmailTemplate;
        try
        {
            $validatorRules = [
                'title' 		=> 'required|max:255',
                'from_email' 	=> 'required|email|max:255',
                'subject' 		=> 'required|max:255',
                'description' 	=> 'required',
            ];
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                
                $emailTemplate->title 		= $request->title;
                $emailTemplate->from_email 	= $request->from_email;
                $emailTemplate->subject 	= $request->subject;
                $emailTemplate->description = $request->description;
                
                $emailTemplate->save();

                Session::flash('success', 'Email Template added successfully.');
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

    public function edit($id)
    {

        try {

            $user = EmailTemplate::where('id','=',$id)->first();
            if($user)
            {
                
                $title 		= 'Edit Email Template'; 
                $breadcum 	= ['Email Templates'=>route('admin.emailTemplates.index'),'Edit Email Template'=>''];
                $model 		= $this->model;

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

    public function update(Request $request, $id)
    {

        $emailTemplate = EmailTemplate::where('id','=',$id)->first();
        try
        {
            $validatorRules = [
                'title' 		=> 'required|max:255',
                'from_email' 	=> 'required|email|max:255',
                'subject' 		=> 'required|max:255',
                'description' 	=> 'required',
            ];
            $validator = Validator::make($request->all(),$validatorRules);
            if ($validator->fails()) 
            {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            else
            {
                
                $emailTemplate->title 		= $request->title;
                $emailTemplate->from_email 	= $request->from_email;
                $emailTemplate->subject 	= $request->subject;
                $emailTemplate->description = $request->description;
                
                $emailTemplate->save();

                Session::flash('success', 'Email Template updated successfully.');
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

    public function EmailTemplateListWithDatatable(Request $request)
    {
        $columns = array( 
                0 =>'id', 
                1 =>'title',
                2 =>'from_email',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
            );


        $totalData = EmailTemplate::where('status','=','1')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = EmailTemplate::where('status', '=','1')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = EmailTemplate::where('status','=','1')
                            ->where(function($query) use ($search){
                        		$query->where('id','LIKE',"%{$search}%")
                            		->orWhere('title','LIKE',"%{$search}%")
                            		->orWhere('subject','LIKE',"%{$search}%")
                            		->orWhere('from_email','LIKE',"%{$search}%");
                        	})
		                    ->offset($start)
		                    ->limit($limit)
		                    ->orderBy($order,$dir)
		                    ->get();
            $totalFiltered = EmailTemplate::where('status','=','1')
                                    ->where(function($query) use ($search){
                                		$query->where('id','LIKE',"%{$search}%")
		                            		->orWhere('title','LIKE',"%{$search}%")
		                            		->orWhere('subject','LIKE',"%{$search}%")
		                            		->orWhere('from_email','LIKE',"%{$search}%");
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
                $nestedData['title'] =  $list->title;
                $nestedData['from_email'] =  $list->from_email;
                $nestedData['action'] =  getButtons([
                                //['key'=>'view','link'=>route('admin.farmers.view',$list->id)],
                                ['key'=>'edit','link'=>route('admin.emailTemplates.edit',$list->id)],
                                //['key'=>'delete','link'=>route('admin.farmers.delete',$list->id)],
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

    public function EmailTemplateStatusUpdate(Request $request)
    {
        $id = $request->id;
        $row  = EmailTemplate::whereId($id)->first();
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

  //   public function sendEmail(Request $request)
  //   {
  //   	dd($request->all());
		// //$user_id
		// //$template_id
  //   }
}