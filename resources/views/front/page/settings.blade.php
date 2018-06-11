@extends('layouts.front.front')
@section('content')
<style>
.errorsContainer{color:red;font-size: 13px;}img.img-responsive.avatar-view {width: 200px;margin: 0px auto 10px auto;}
</style>
<!--form section start now-->
<style>.grouping{margin:20px 0px;}.tabling .col-md-3{margin-bottom: 10px; }.fc-left h2{background-color: transparent!important;margin-top: 10px!important;}.grouping label{font-weight: bold!important;}.input-check{margin: 20px auto;}.input-check input{position: relative;top: 4px;left: 4px;}</style>
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
			<h2 class="animated">Settings Panel</h2>
            <hr class="border-line">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="inner_main clearfix">
                    <div class="col-sm-12 right_sect p-lr25 p-top30 m-bottom20 tabs">
                        <ul class="nav nav-tabs" id="settings">
                            <li class="active"><a data-toggle="tab" href="#home">Non Availibility</a></li>
                            <li><a data-toggle="tab" href="#ah">Available Hours</a></li>
                            <li><a data-toggle="tab" href="#amenities">Amenities</a></li>
                            <li><a data-toggle="tab" href="#banner-manager">Banner Manager</a></li>
                        </ul>
                        @include('message')
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <div class="form-group">
                        
                                    <div id='calendar'></div>

                                    <span class="error">NOTE: Please click on date to set unavailability for the corresponding date.</span>
                                </div>
                            </div>
                            <div id="ah" class="tab-pane fade">
                                {!! Form::model($data, ['method' => 'POST','route' => ['front.update.settings'],'class'=>'form-horizontal','id'=>'settings-form']) !!}
                                {{ csrf_field() }}
                                
                                    <div class="grouping">
                                        <div class="form-group ">
                                            <div class="col-sm-3 mob_pad0 mob_bottm10">
                                                <label>Day Name</label>
                                            </div>
                                            <div class="col-sm-3  mob_pad0">
                                                <label>Opening Time</label>
                                            </div>
                                            <div class="col-sm-3  mob_pad0">
                                                <label>Closing Time</label>
                                            </div>
                                            <div class="col-sm-3  mob_pad0">
                                                <label>Max Visitors</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabling">
                                        @foreach ($data as $day => $value)
                                            <div class="row">
                                                <div class="col-md-3"> {!! Form::label($value['day_name'], $value['day_name']) !!}
                                                    {!! Form::hidden($day.'[0]', $value['day_name']) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::text($day.'[1]', $value['opening_time'], ['class'=>'form-control clockpicker opening'.$day]) !!}
                                                    <span class="errorsContainer errors{{$day}}"></span>
                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::text($day.'[2]', $value['closing_time'], ['class'=>'form-control clockpicker closing'.$day]) !!}
                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::text($day.'[3]', $value['visitors'], ['class'=>'form-control']) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="submit text-center">
                                            <!-- <input type="submit"  value="Update Settings" class="btn btn-red submitbtn AHR"> -->
                                            <a href="javascript:void(0);" class="btn btn-red submitbtn AHR">Update Settings</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="amenities" class="tab-pane fade">
                                <div class="form-group">
                                    <div class="tabling">
                                      
                                        {!! Form::model(null, ['method' => 'POST','route' => ['front.amenity.settings'],'class'=>'form-horizontal','id'=>'amenity-form']) !!}
                                        {{ csrf_field() }}
                                            <div class="row input-check">
                                                @foreach ($all_amenities as $amenity)
                                                    <div class="col-md-4">
                                                        <!-- {!! Form::label($amenity->name, $amenity->name) !!} -->
                                                        <label for="{{$amenity->name}}">{{$amenity->name}}
                                                        {!! Form::checkbox($amenity->id, $amenity->name, in_array($amenity->id,$user_selected_amenity)) !!}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="submit text-center">
                                                <input type="submit"  value="Update Amenities" class="btn btn-red submitbtn">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="banner-manager" class="tab-pane fade">
                                <!-- <label class="control-label col-md-3 col-sm-3 col-xs-12" for="farmer_banner_image">Banner Image
                                </label>
 -->
                                {!! Form::model(null, ['method' => 'POST','route' => ['front.banner.settings'],'class'=>'form-horizontal','id'=>'amenity-form','files'=>true]) !!}
                                {{ csrf_field() }}


                                    @for ($i = 0; $i < 4; $i++)
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <?php 
                                            if($i==0){
                                                $validationsImage = ((count($farmers_banners)>0))?false:true;
                                            ?>
                                                {!! Form::file('farmer_banner_image[]',  ['class'=>'form-control','placeholder'=>'Banner Image','required'=>$validationsImage, 'id'=>'farmer_banner_image[]']) !!}                      
                                                <?php }else{ ?>
                                                {!! Form::file('farmer_banner_image[]',  ['class'=>'form-control','placeholder'=>'Banner Image', 'id'=>'farmer_banner_image[]']) !!}
                                                <?php }  ?>
                                        </div>
                                        <div class="profile_img col-md-4 col-sm-4 col-xs-12">
                                            <div id=""><!-- <pre><?php //print_r($farmers_banners);?></pre> -->
                                                <?php if(count($farmers_images)>0){ ?>
                                                <input type="hidden" name="ids[]" value="{{@$farmers_images[$i]['id']}}">
                                                <?php  } ?>

                                                <?php if(isset($farmers_banners[$i]) && !empty($farmers_banners[$i])){ ?>
                                                <img class="img-responsive avatar-view" src="{{ @$farmers_banners[$i]?asset('public/uploads/farmers-banners/'.@$farmers_banners[$i].''):'' }}" alt="Banner Image" title="Change Banner Image">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            {!! Form::text('farmer_banner_text[]', @$farmers_banners_desc[$i], ['class'=>'form-control','placeholder'=>'Banner Text', 'id'=>'farmer_banner_text[]']) !!}                      
                                        </div>
                                    </div>
                                    @endfor
                                    <div class="submit text-center">
                                        <input type="submit"  value="Update Banners" class="btn btn-red submitbtn">
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
<!-- <script src="{{asset('assets/front/js/settings-calender.js')}}"></script> -->
