<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Validator;
use File;
use DB;

class PagesController extends Controller
{
   
	public function dashboard()
	{
        $title = 'Admin';

        $user_count = User::where('role','F')->count();
        return view('admin.page.dashboard',compact('title','user_count'));
	}

}
