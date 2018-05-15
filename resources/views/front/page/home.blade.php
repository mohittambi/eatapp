@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<div class="container">
    <div class="row">
        <div class="jumbotron">
            <center><h1>EATAPP | HOME PAGE</h1></center>
            <?php if(isset($user->first_name) && !empty($user->first_name)){?>
            Hello, <?php print_r($user->first_name);}?>
            <pre><?php //print_r($user);?></pre>

        </div>
    </div>
</div>
<!--form section end now-->
@endsection
