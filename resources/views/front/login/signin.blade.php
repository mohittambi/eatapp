@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signin" class="main_section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                <div class="inner_main sign_in clearfix">
                    <div class="col-sm-6 left_sect">
                        <div class="tagline">
                            <h3>Good Heading Here</h3>
                            <p>Good Subheading Here. Good Subheading Here</p>
                        </div>
                    </div>
                    <div class="col-sm-6 right_sect p-lr25">
                        <h4 class="sub_head2 m-top25 m-bottom0 red">Sign In</h4>
                        <p class="m-bottom30">Hello! Let’s get started</p>
                        {!! Form::model(null, ['method' => 'POST','route' => ['front.post.signin'],'class'=>'validate','autocomplete'=>'off','id'=>'signin-form']) !!}
                        {{ csrf_field() }}
                        @include('message')
                            <div class="form-group">
                                <label>Email Address</label>
                                {!! Form::email('email', null, ['class'=>'form-control','placeholder'=>'Email','autocomplete'=>'off','required'=>true]) !!}
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                {!! Form::password('password',  ['class'=>'form-control','placeholder'=>'Password','required'=>true]) !!}
                            </div>
                            <p><a href="forgot-password" class="forget_pass red">Forgot Password?</a></p>
                            <div class="submit m-top30">
                                <input type="submit"  value="Sign In" class="btn btn-red submitbtn">
                            </div>
                            <p class="m-bottom30">Don’t have an account? <a href="signup" class="red"> Sign Up</a></p>
                            <!-- <p class="m-bottom30"><a href="forgot-password" class="red">Forgot Password?</a></p> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--form section end now-->
@endsection