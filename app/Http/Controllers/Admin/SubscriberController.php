<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Model\EmailTemplate;
use App\Model\ContactForm;
use App\Model\User;
use DB;
use Session;
use File;
use Mail;

class SubscriberController extends Controller
{
	protected $model;
    protected $title;

    public function __construct()
    {
      $this->model = 'subscribers';
      $this->title = 'Subscribers';
    }

	public function index()
    {

        $row = ContactForm::where('status','=', '1')->get();
        $title= 'All Subscribers'; 
        $breadcum = ['Subscribers'=>''];
        return view('admin.'.$this->model.'.index',compact('title','row','breadcum'));

    }



    public function SubscribersListWithDatatable(Request $request)
    {
        $email_template_sulg = 'welcome-mail';
        $columns = array( 
                0 =>'id', 
                1 =>'name',
                2 =>'email',
                3 =>'created_at',
                4=> 'status',
                5=> 'action',
                6=> 'sendEmail', 
            );


        $totalData = ContactForm::where('status','=','1')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            $posts = ContactForm::where('status', '=','1')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        else
        {
            $search = $request->input('search.value'); 
            $posts = ContactForm::where('status','=','1')
                            ->where(function($query) use ($search){
                        		$query->where('id','LIKE',"%{$search}%")
                            		->orWhere('name','LIKE',"%{$search}%")
                            		->orWhere('email','LIKE',"%{$search}%");
                        	})
		                    ->offset($start)
		                    ->limit($limit)
		                    ->orderBy($order,$dir)
		                    ->get();
            $totalFiltered = ContactForm::where('status','=','1')
                                    ->where(function($query) use ($search){
                                		$query->where('id','LIKE',"%{$search}%")
                                		->orWhere('name','LIKE',"%{$search}%")
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
                $nestedData['name'] =  $list->name;
                $nestedData['email'] =  $list->email;
                $nestedData['sendEmail'] =  sendEmail($list->id,$email_template_sulg);
                $nestedData['action'] =  getButtons([
                                //['key'=>'view','link'=>route('admin.farmers.view',$list->id)],
                                //['key'=>'edit','link'=>route('admin.emailTemplates.edit',$list->id)],
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

    public function SubscribersStatusUpdate(Request $request)
    {
        $id = $request->id;
        $row  = ContactForm::whereId($id)->first();
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

    public function sendEmail(Request $request,$user_id,$email_template_sulg)
    {

    	// dd($email_template_sulg); 

        try{

            $user_details = ContactForm::whereId($user_id)->get()->toArray();
            $emailTemplate = EmailTemplate::whereSlug($email_template_sulg)->get()->toArray();

            $data = array_merge($user_details[0], $emailTemplate[0]);
                $search = array('{{name}}', '{{email}}');
                $replace = array($data['name'], $data['email']);
                $body = str_replace($search, $replace, $data['description']);

            Mail::raw([], function($message) use($data,$body){
                $message->from($data['from_email']);
                $message->subject($data['subject']);
                $message->to($data['email']);
                $message->setBody($body, 'text/plain');
            });

            Session::flash('success', 'Welcome message sent to '. $data['name'] .'.');
            return redirect()->back();

        }
        catch (Exception $e)
        {
            dd($e);
            return false;            
        }
    	
    }


}