@extends('layouts.emails.email')

@section('content')
<style>
	table{
		
	}
table tr td{
	
}
</style>
<h3 style="letter-spacing:0.3px; color:#000000;margin-bottom:30px; margin-top:75px;">Contact Form Information</h3>
   
	<table align="center" bgcolor="#FFFFFF" cellpadding="5" cellspacing="5" height="150" width=100%;" >
		<tbody style="padding:10px;">
			<tr><td style="border:1px solid #ccc; padding-left: 10px;">Name : </td><td height="25" style="border:1px solid #ccc;padding-left: 10px;">{!! $data['name'] !!}</td></tr>
			<tr><td style="border:1px solid #ccc; padding-left: 10px;">Email : </td><td height="25" style="border:1px solid #ccc; padding-left: 10px;">{!! $data['email'] !!}</td></tr>
			<tr><td style="border:1px solid #ccc; padding-left: 10px;">Phone Number : </td><td height="25" style="border:1px solid #ccc;padding-left: 10px;">{!! $data['phone_number'] !!}</td></tr>
			<tr><td style="border:1px solid #ccc; padding-left: 10px;">Comment : </td><td height="25" style="border:1px solid #ccc;padding-left: 10px;"><p style="white-space: normal;    word-break: break-all;">{!! $data['comment'] !!}</p></td></tr>
		</tbody>
	</table>
@endsection