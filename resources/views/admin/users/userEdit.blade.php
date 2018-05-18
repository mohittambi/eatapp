@extends('layouts.admin.admin')

@section('content')
   <div class="right_col" role="main">
          <div class="">
           @include('admin.includes.breadcum')
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>{!! $title !!}</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="x_content">
                      <div class="col-md-9 col-sm-9 col-xs-12 profile_left">
                     




                   
                    {!! Form::model($row, ['method' => 'patch','route' => ['admin.'.$model.'.update', $row->slug],'class'=>'form-horizontal validate','enctype'=>'multipart/form-data','id'=>'customer-edit']) !!}
                    {{ csrf_field() }}
                     @include('message')
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="full-name">Full Name<span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('full_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Full Name','required'=>true]) !!}                      
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true]) !!}                      
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gender">Gender<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          


                           {!! Form::select('gender', array('M' => 'Male', 'F' => 'Female'),null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Gender','required'=>true, 'id'=>'gender']) !!}                     
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">DOB<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::date('dob', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'DOB','required'=>true]) !!}                      
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Country<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                         

                           {!! Form::select('country_id',$countryList, null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Country','required'=>true, 'id'=>'country_id']) !!}                   
                        </div>
                      </div>


                      <!-- <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zipcode">Zip Code<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('zipcode', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Zip Code','required'=>true, 'id'=>'zipcode']) !!}                      
                        </div>
                      </div> -->

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_number">Phone Number<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('phone_number', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Phone Number','required'=>true, 'id'=>'phone_number']) !!}                      
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), $row->status, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Status','required'=>true, 'id'=>'status']) !!}                      
                        </div>
                      </div>

                    

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_pic">Profile Picture
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::file('profile_pic', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Profile Pic','required'=>true, 'id'=>'profile_pic']) !!}                      
                        </div>
                        <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view" src="{{ $row->image?asset('public/uploads/users/thumb/'.$row->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
                      </div>


                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>

                    </div>
                  </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      


@endsection