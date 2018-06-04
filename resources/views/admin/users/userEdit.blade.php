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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Farmer Code
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          <?php $row->customerDetails->farmer_code?$row->customerDetails->farmer_code:''?>
                          {!! Form::text('farmer_code', $row->customerDetails->farmer_code, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Farmer Code','required'=>true, 'disabled']) !!}                      
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true, 'disabled']) !!}                      
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Address<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('address', $row->customerDetails->address, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Address','required'=>true, 'id'=>'autocomplete']) !!}
                          {!! Form::hidden('latitude', $row->customerDetails->address_lat, ['class'=>'form-control', 'id'=>'latitude']) !!}
                          {!! Form::hidden('longitude', $row->customerDetails->address_lang, ['class'=>'form-control', 'id'=>'longitude']) !!}                    
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
                          {!! Form::select('country_id',$countryList, null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Country','id'=>'country_id']) !!}                   
                        </div>
                      </div>

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

<script>

$("#autocomplete").change(function(){
    $("#latitude").val('');
    $("#longitude").val('');
});
function initialize() 
{
    var input = document.getElementById('autocomplete');
    var options = {};            
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            $("#latitude").val(lat);
            $("#longitude").val(lng); 
        });
}
             
google.maps.event.addDomListener(window, 'load', initialize);

 
</script>

@include('googlemapjs')



@endsection