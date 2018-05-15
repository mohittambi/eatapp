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
                    {!! Form::open(['method' => 'post', 'route' => ['admin.'.$model.'.store'], 'class'=>'form-horizontal validate', 'enctype'=>'multipart/form-data', 'id'=>'banner-add']) !!}
                    {{ csrf_field() }}
                     @include('message')
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">First Name<span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('first_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'First Name','required'=>true]) !!}
                        </div>
                      </div>

                      <!-- <div class="form-group row">
                        <?php //echo \App\Lib\LanguageConvertor\Translator::languageFields("first_name","",["table"=>"users","pk"=>0]); ?>
                      </div> -->

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Last Name<span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::text('last_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Last Name','required'=>true]) !!}                      
                        </div>
                      </div>

                      <!-- <div class="form-group row">
                        <?php //echo \App\Lib\LanguageConvertor\Translator::languageFields("last_name","",["table"=>"users","pk"=>0]); ?>
                      </div> -->

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::email('email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true]) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::textarea('description', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Description']) !!}                      
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <?php echo \App\Lib\LanguageConvertor\Translator::languageFields("description","",["table"=>"farmers","pk"=>0]); ?>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::select('category', $categoryList, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Category']) !!}                      
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status<span class="required">*</span>
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), '1', ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Status','required'=>true, 'id'=>'status']) !!}                      
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cat_img">Profile Image
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::file('cat_img', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Farmer Image','required'=>true, 'id'=>'cat_img']) !!}
                        </div>
                        <div class="profile_img">
                          <div id="crop-avatar">
                            <!-- Current avatar -->
                            <?php /*<!-- <img class="img-responsive avatar-view" src="{{ $row->image?asset('public/uploads/users/thumb/'.$row->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar"> -->*/?>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banner_img">Banner Image
                        </label>
                         <div class="col-md-9 col-sm-9 col-xs-12">
                          {!! Form::file('banner_img', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Farmer Image','required'=>true, 'id'=>'banner_img']) !!}
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
