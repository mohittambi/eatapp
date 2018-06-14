@extends('layouts.admin.admin')

@section('content')
<style>
textarea.front-content.valid {width: 100%;height: auto;}
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
          <div class="x_content">
            <div class="x_content">
              <div class="col-md-12 col-sm-12 col-xs-12 profile_left">
              {!! Form::model($user, ['method' => 'post', 'route' => ['admin.'.$model.'.update', $user->id], 'class'=>'form-horizontal validate', 'enctype'=>'multipart/form-data', 'id'=>'banner-add']) !!}
              {{ csrf_field() }}
              @include('message')

        				<div class="form-group">
        					<label class="control-label" for="title">Title<span class="required">*</span>
        					</label>
        					<div class="">
        					  {!! Form::text('title', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Title','required'=>true]) !!}                      
        					</div>
        				</div>

                <div class="form-group row">
                  <?php echo \App\Lib\LanguageConvertor\Translator::languageFields("title","",["table"=>"front_pages","pk"=>$user->id]); ?>
                </div>

              	<div class="form-group">
                	<label class="control-label " for="content">Content</label>
                	<div class="">
                  		{!! Form::textarea('content', $user->content, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Content','id'=>'editor']) !!}
                	</div>
              	</div>
                
              	<div class="form-group row">
                	<?php echo \App\Lib\LanguageConvertor\Translator::languageFields("content", "", ["table"=>"front_pages", "pk"=>$user->id, "tag"=>'textarea', "class"=>'front-content', "id"=>'editor-it']); ?>
              	</div>

                <div class="form-group">
                  <label class="control-label " for="status">Status<span class="required">*</span>
                  </label>
                   <div class="">
                    {!! Form::select('status', array('1' => 'Active', '0' => 'InActive'), $user->status, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Status','required'=>true, 'id'=>'status']) !!}                      
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
