@extends('layouts.emails.email')

@section('content')	
		
<h3 style="letter-spacing:0.3px; color:#000000;margin-bottom:30px; margin-top:75px;">Email Confirmation</h3>
	<p style="padding:0 30px;line-height:1.5">Thank you for your registration.</p>
    <p style="padding:0 30px;line-height:1.5">Please click on below link for email verification.</p>
   
  <a href="{!! $data['registration_url'] !!}{!! $data['token'] !!}" style="background-color:#557d87; color:#FFFFFF; padding:10px 25px; display:inline-block; text-decoration:none;margin-top:15px; margin-bottom:25px; margin-top:20px; border-radius:3px;">Click here</a>

@endsection