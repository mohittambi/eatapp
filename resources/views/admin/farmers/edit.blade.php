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
              {!! Form::model($user, ['method' => 'post', 'route' => ['admin.'.$model.'.update', $user->id], 'class'=>'form-horizontal validate', 'enctype'=>'multipart/form-data', 'id'=>'banner-add']) !!}
              {{ csrf_field() }}
              @include('message')

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">First Name<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::text('first_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'First Name','required'=>true]) !!}                      
                </div>
              </div>

              <?/*<div class="form-group row">
                <?php echo \App\Lib\LanguageConvertor\Translator::languageFields("first_name","",["table"=>"users","pk"=>$user->id]); ?>
              </div>
              */?>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Last Name<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::text('last_name', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Last Name','required'=>true]) !!}                      
                </div>
              </div>

              <?/*<div class="form-group row">
                <?php //echo \App\Lib\LanguageConvertor\Translator::languageFields("last_name","",["table"=>"users","pk"=>$user->id]); ?>
              </div>
              */?>
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
                  {!! Form::textarea('description', $user->farmerDetails->description, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Description']) !!}                      
                </div>
              </div>
              
              <div class="form-group row">
                <?php echo \App\Lib\LanguageConvertor\Translator::languageFields("description","",["table"=>"users","pk"=>$user->farmerDetails->user_id]); ?>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  
                  {!! Form::select('categories', $categoryList, $selectedCatList, ['class'=>'form-control col-md-7 col-xs-12','multiple'=>'multiple','placeholder'=>'Category','name'=>'categories[]','id'=>'category']) !!}
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status<span class="required">*</span>
                </label>
                 <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), $user->status, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Status','required'=>true, 'id'=>'status']) !!}                      
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="profile_pic">Profile Picture
                </label>
                 <div class="col-md-4 col-sm-4 col-xs-12">
                  {!! Form::file('profile_pic', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Farmer Image','required'=>true, 'id'=>'profile_pic']) !!}                      
                </div>
                <div class="profile_img">
                  <div id="crop-avatar">
                    <!-- Current avatar -->
                    <img class="img-responsive avatar-view" src="{{ $user->image?asset('public/uploads/users/thumb/'.$user->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banner_image">Banner Image
                </label>
                 <div class="col-md-4 col-sm-4 col-xs-12">
                  {!! Form::file('banner_image', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Banner Image','required'=>true, 'id'=>'banner_image']) !!}                      
                </div>
                <div class="profile_img">
                  <div id="">
                    <!-- Current avatar -->
                    <img class="img-responsive avatar-view" src="{{ $user->farmerDetails->banner_image?asset('public/uploads/users/'.$user->farmerDetails->banner_image.''):'' }}" alt="Banner Image" title="Change Banner Image">
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
