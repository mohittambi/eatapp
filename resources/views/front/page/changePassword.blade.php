@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
			<h2 class="animated">Change Password</h2>
            <hr class="border-line">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="inner_main clearfix">					
                    <div class="col-sm-12 right_sect p-lr25 p-top30 m-bottom20">
                        <div class="tab-content">
                            <div id="home" class="tab-pane1">
                                {!! Form::model(null, ['method' => 'POST','route' => ['front.post.changePassword'],'class'=>'form-horizontal validate','id'=>'change-password']) !!}
                                {{ csrf_field() }}
                                @include('message')
                                    <div class="form-group">
                                        <label>Current Password <span class="required">*</span></label>
                                        {!! Form::password('old_password', ['class'=>'form-control','placeholder'=>'Current Password','required'=>true]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>New Password <span class="required">*</span></label>
                                        {!! Form::password('new_password', ['class'=>'form-control','placeholder'=>'New Password','required'=>true]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password <span class="required">*</span></label>
                                        {!! Form::password('confirm_password', ['class'=>'form-control','placeholder'=>'Confirm Password','required'=>true]) !!}
                                    </div>
                                    <div class="submit text-center">
                                        <input type="submit"  value="Change Password" class="btn btn-red submitbtn">
                                    </div>
                                </form>
                                        
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--form section end now-->
@endsection