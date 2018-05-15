@extends('layouts.emails.email')

@section('content')

<h3 style="letter-spacing:0.3px; color:#000000;margin-bottom:30px; margin-top:75px;">Contact Form Submitted Successfully</h3>
    <p style="padding:0 30px;line-height:1.5">Your details are as:</p>
    
	<tr>
 		<td>{!! $data['name'] !!}</td>
 		<td>{!! $data['email'] !!}</td>
 		<td>{!! $data['phone_number'] !!}</td>
 		<td>{!! $data['comment'] !!}</td>
	</tr>
@endsection