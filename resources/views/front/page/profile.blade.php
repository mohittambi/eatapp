@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
			<h2 class="animated">Edit Profile</h2>
            <hr class="border-line">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="inner_main clearfix">					
                    <div class="col-sm-12 right_sect p-lr25 p-top30 m-bottom20">
                      <!--  <ul class="nav nav-tabs signup_tab">
                            <li class="active"><a data-toggle="tab" href="#home">customer</a></li>
                            <li><a data-toggle="tab" href="#menu1">Owner</a></li>
                        </ul>-->                       
                        <div class="tab-content">
                            <div id="home" class="tab-pane1">
                                <!--<h1 class="browen text-center"> Privacy Policy</h1>-->
                                {!! Form::model($userDetails, ['method' => 'POST','route' => ['front.post.profile'],'class'=>'form-horizontal validate','enctype'=>'multipart/form-data','id'=>'profile-form']) !!}
                                {{ csrf_field() }}
                                @include('message')
                                    <div class="form-group clearfix">
                                        <div class="col-sm-6 p-left0 mob_pad0 mob_bottm10">
                                            <label>First Name <span class="required">*</span></label>
                                            {!! Form::text('first_name', null, ['class'=>'form-control','placeholder'=>'First Name','required'=>true]) !!}
                                        </div>
                                        <div class="col-sm-6 p-right0 mob_pad0">
                                            <label>Last Name <span class="required">*</span></label>
                                            {!! Form::text('last_name', null, ['class'=>'form-control','placeholder'=>'Last Name','required'=>true]) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address <span class="required">*</span></label>
                                        {!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true, 'disabled']) !!}
                                    </div>
                                    <!-- <div class="form-group">
                                        <label>Location Name <small class="text-lowercase smallt">(if applicable)</small></label>
                                        {!! Form::text('location_name', $userDetails->locations->location_name, ['maxlength'=>'255','placeholder' => 'Type Location Name', 'class'=>'form-control']) !!} 
                                    </div> -->
                                    <div class="form-group">
                                        <label>Address <span class="required">*</span></label>
                                        {!! Form::text('address', $userDetails->locations->address, ['maxlength'=>'255','placeholder' => 'Address', 'required'=>true, 'class'=>'form-control', 'id'=>'autocomplete']) !!} 
                                        {!! Form::hidden('latitude', $userDetails->locations->latitude, ['class'=>'form-control', 'id'=>'latitude']) !!}
                                        {!! Form::hidden('longitude', $userDetails->locations->longitude, ['class'=>'form-control', 'id'=>'longitude']) !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        <label>Phone Number</label>
                                        <div class="input-group clearfix">
                                            <div class="input-group-addon">
                                                {!! Form::select('country_id', $countryList, null, ['class'=>'form-control','placeholder'=>'Country Name']) !!}        
                                            </div>
                                                {!! Form::text('phone_number', null, ['class'=>'form-control','placeholder'=>'Phone Number']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        {!! Form::textarea('description', $userDetails->farmerDetails->description, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Category</label>
                                        {!! Form::select('category',$categoryList, $selectedCatList,['class'=>'form-control', 'multiple'=>'multiple','name'=>'categories[]']) !!}
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        {!! Form::file('profile_pic', null, ['class'=>'form-control', 'placeholder'=>'Profile Picture', 'id'=>'profile_pic']) !!}
                                        <div class="profile_img">
                                            <div id="crop-avatar">
                                            <!-- Current Avatar -->
                                            <img class="img-responsive avatar-view" src="{{ $userDetails->image?asset('public/uploads/farmers/thumb/'.$userDetails->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label>Banner Image</label>
                                        {!! Form::file('banner_image', null, ['class'=>'form-control', 'placeholder'=>'Banner Image']) !!}
                                        <div class="profile_img">
                                            <div id="crop-avatar"> -->
                                            <!-- Current Banner -->
                                            <!-- <img class="img-responsive avatar-view" src="{{ $userDetails->farmerDetails->banner_image?asset('public/uploads/banners/thumb/'.$userDetails->farmerDetails->banner_image.''):asset('images/user.png') }}" alt="Banner Image" title="Change the Banner">
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label>Farmer Code </label>
                                        {!! Form::text('farmer_code', $userDetails->farmerDetails->farmer_code,['class'=>'form-control', 'placeholder'=>'Farmer Code', 'disabled']) !!}
                                    </div>
                                    <div class="submit text-center">
                                        <input type="submit"  value="Update Profile" class="btn btn-red submitbtn">
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