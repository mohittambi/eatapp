@extends('layouts.admin.admin')

@section('content')
<style>
   #edit-farmer label{margin-bottom:10px!important;}.avatar-view{height:100px;border:1px solid #ccc;float:right;padding: 0px;}input[type="file"]{float:left;margin-top:17px;display:inline-block;width:60%;}
</style>

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
                {!! Form::model($user, ['method' => 'post', 'route' => ['admin.'.$model.'.update', $user->id], 'class'=>'form-horizontal validate', 'enctype'=>'multipart/form-data', 'id'=>'edit-farmer']) !!}
                {{ csrf_field() }}
                @include('message')

        				
                  <div class="row">
                    <div class="col-md-6">
                      <label class="control-label" for="first_name">First Name<span class="required">*</span>
                      </label>
                      <div class="">
                        {!! Form::text('first_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'First Name','required'=>true,'id'=>'first_name']) !!}                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label" for="last_name">Last Name<span class="required">*</span>
                      </label>
                      <div class="">
                        {!! Form::text('last_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Last Name','required'=>true,'id'=>'last_name']) !!}                      
                      </div>
                    </div>
                  </div>
               

                <div class="row">
                    <div class="col-md-6">
                      <label class="control-label" for="company_name">Company Name<span class="required">*</span>
                      </label>
                      <div class=""><?php //print_r($user->farmerDetails); ?>
                        {!! Form::text('company_name', $user->farmerDetails->company_name, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Company Name','required'=>true,'id'=>'company_name']) !!}                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="">
                        <label class="control-label">Address <span class="required">*</span></label>
                      </div>
                      <div class="">
                        {!! Form::text('address', $user->locations->address, ['maxlength'=>'255','placeholder' => 'Address', 'required'=>true, 'class'=>'form-control', 'id'=>'autocomplete']) !!} 
                        {!! Form::hidden('latitude', $user->locations->latitude, ['class'=>'form-control', 'id'=>'latitude']) !!}
                        {!! Form::hidden('longitude', $user->locations->longitude, ['class'=>'form-control col-md-7 col-xs-12', 'id'=>'longitude']) !!}
                      </div>
                    </div>
                </div>

              	<div class="row">
                    <div class="col-md-6">
                    	<label class="control-label" for="email">Email<span class="required">*</span>
                    	</label>
                    	<div class="">
                      	{!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true, 'disabled']) !!}                      
                    	</div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label" for="phone_number">Phone Number<span class="required">*</span>
                      </label>
                      <div class="">
                          <select name="phonecode" class="form-control" style="width: 30%;display: inline-block;float: left;">
                            <?php foreach($countryData as $key => $value){
                                    if(isset($user->phonecode) && !empty($user->phonecode)){$selectedCountry = $user->phonecode;}else{$selectedCountry='39';} ?>
                            <option value="{{$key}}" <?= $key == $selectedCountry ? ' selected="selected"' : '';?>> {{$value .' (+'.$key.')'}}</option>
                            <?php } ?>
                          </select>
                          {!! Form::text('phone_number', null, ['class'=>'form-control','placeholder'=>'Phone Number','required'=>true,'id'=>'phone_number','style'=>'width: 70%;display: inline-block;float: right;']) !!}
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                      <label class="control-label">VAT Number <span class="required">*</span></label>
                      <div class="">
                        {!! Form::text('vat_number', $user->farmerDetails->vat_number , ['maxlength'=>'50','placeholder' => 'VAT Number', 'class'=>'form-control','id'=>'vat_number']) !!}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label">CF</label>
                      <div class="">
                        {!! Form::text('cf', $user->farmerDetails->cf, ['maxlength'=>'50','placeholder' => 'CF', 'class'=>'form-control','id'=>'cf']) !!}
                      </div>
                    </div>
                </div>
          
              	<div class="row">
                    <div class="col-md-6">
                      <label class="control-label" for="category">Category</label>
                  	  <div class="">
                      	{!! Form::select('categories', $categoryList, $selectedCatList, ['class'=>'form-control col-md-7 col-xs-12','multiple'=>'multiple','name'=>'categories[]','id'=>'category']) !!}
                  	  </div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label" for="profile_pic" style="float: left;width: 100%;text-align: left;">Profile Picture</label>
                        {!! Form::file('profile_pic', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Farmer Image','required'=>true, 'id'=>'profile_pic']) !!}                      
                  
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view col-md-3" src="{{ $user->image?asset('public/uploads/farmers/thumb/'.$user->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Location Name <small class="text-lowercase smallt">(if applicable)</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      {!! Form::text('location_name', $user->locations->location_name, ['maxlength'=>'255','placeholder' => 'Type Location Name', 'class'=>'form-control col-md-7 col-xs-12']) !!}
                    </div>
                </div> -->

                <div class="row">
                  <div class="col-md-12">
                     <label class="control-label" for="description">Description</label>
                      {!! Form::textarea('description', $user->farmerDetails->description, ['class'=>'form-control', 'placeholder'=>'Description','id'=>'description']) !!}      
                 </div>
             </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <label class="control-label" for="description">Description (Italian)</label>
                    <?php echo \App\Lib\LanguageConvertor\Translator::languageFields("description","",["table"=>"users","pk"=>$user->farmerDetails->user_id]); ?>
                  </div>
                </div>
                
          
                  <div class="row">
                    <div class="col-md-6">
                      <label class="control-label" for="contract_start_date">Contract Start Date
                      </label>
                      <div class="">
                        {!! Form::date('contract_start_date', $user->farmerDetails->contract_start_date, ['class'=>'form-control','placeholder'=>'Contract Start Date','id'=>'contract_start_date']) !!}                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label" for="contract_end_date">Contract End Date
                      </label>
                      <div class="">
                        {!! Form::date('contract_end_date', $user->farmerDetails->contract_end_date, ['class'=>'form-control','placeholder'=>'Contract Start Date','id'=>'contract_end_date']) !!}                      
                      </div>
                    </div>
                  </div>

                <div class="row">
                    <div class="col-md-6">
                      <label class="control-label" for="status">Service Type
                      </label>
                      <div class="">
                        {!! Form::select('service_type', array('1' => 'Standard', '2' => 'Silver', '3' => 'Gold'), $user->farmerDetails->service_type, ['class'=>'form-control','placeholder'=>'Status', 'id'=>'status']) !!}                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="control-label" for="status">Status<span class="required">*</span>
                      </label>
                      <div class="">
                        {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), $user->status, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Status','required'=>true, 'id'=>'status']) !!}                      
                      </div>
                    </div>
                </div>

                

                <!-- <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banner_image">Banner Image
                  </label>
                   <div class="col-md-4 col-sm-4 col-xs-12">
                    {!! Form::file('banner_image', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Banner Image','required'=>true, 'id'=>'banner_image']) !!}                      
                  </div>
                  <div class="profile_img">
                    <div id="">
                      <img class="img-responsive avatar-view" src="{{ $user->farmerDetails->banner_image?asset('public/uploads/banners/'.$user->farmerDetails->banner_image.''):'' }}" alt="Banner Image" title="Change Banner Image">
                    </div>
                  </div>
                </div> -->


                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
                   
                    <button type="submit" class="btn btn-success">Submit</button>
                  </div>
                </div>

              </form>

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
