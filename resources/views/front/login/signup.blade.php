@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
            <h2 class="animated">Sign Up</h2>
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
                                {!! Form::model(null, ['method' => 'POST','route' => ['front.post.signup'],'class'=>'form-horizontal validate','enctype'=>'multipart/form-data','id'=>'signup-form']) !!}
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

                                <div class="form-group clearfix">
                                    <div class="col-sm-6 p-left0 mob_pad0 mob_bottm10">
                                        <label>Company Name <span class="required">*</span></label>
                                        {!! Form::text('company_name', null, ['class'=>'form-control','placeholder'=>'Company Name','required'=>true]) !!}
                                    </div>
                                    <div class="col-sm-6 p-right0 mob_pad0">
                                        <label>Address <span class="required">*</span></label>
                                        {!! Form::text('address', null, ['maxlength'=>'255','placeholder' => 'Address', 'required'=>true, 'class'=>'form-control', 'id'=>'autocomplete']) !!} 
                                        {!! Form::hidden('latitude', null, ['class'=>'form-control', 'id'=>'latitude']) !!}
                                        {!! Form::hidden('longitude', null, ['class'=>'form-control', 'id'=>'longitude']) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6 p-left0 mob_pad0 mob_bottm10">
                                    <label>Email Address <span class="required">*</span></label>
                                        {!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true]) !!}
                                    </div>
                                    <div class="col-sm-6 p-right0 mob_pad0">
                                        <label>Phone Number <span class="required">*</span></label>
                                        <div class="input-group clearfix">
                                            <div class="input-group-addon">
                                                <select name="phonecode" class="form-control">
                                                    <?php foreach($countryData as $key => $value){ ?>
                                                    <option value="{{$key}}" <?= $key == '39' ? ' selected="selected"' : '';?>> {{$value .' (+'.$key.')'}}</option>
                                                    <?php } ?>
                                                </select>        
                                            </div>
                                                {!! Form::text('phone_number', null, ['required'=>true,'class'=>'form-control','placeholder'=>'Phone Number']) !!}
                                        </div>
                                    </div>

                                </div>
                                <!-- <div class="form-group">
                                    <label>Location Name <small class="text-lowercase smallt">(if applicable)</small></label>
                                    {!! Form::text('location_name', null, ['maxlength'=>'255','placeholder' => 'Type Location Name', 'class'=>'form-control']) !!} 
                                </div> -->
                              
                                    

                                    <div class="form-group">
                                        <div class="col-sm-6 p-left0 mob_pad0 mob_bottm10">
                                            <label>Password <span class="required">*</span></label>
                                            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password','required'=>true]) !!}
                                        </div>
                                        <div class="col-sm-6 p-right0 mob_pad0">
                                            <label>Confirm Password <span class="required">*</span></label>
                                            {!! Form::password('confirm_password', ['class'=>'form-control', 'placeholder'=>'Confirm Password', 'required'=>true]) !!}
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label>Description</label>
                                        {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Description']) !!}
                                    </div> -->

                                    <div class="submit text-center">
                                        <input type="submit"  value="Sign Up" class="btn btn-red submitbtn">
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