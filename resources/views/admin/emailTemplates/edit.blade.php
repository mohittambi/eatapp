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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::text('title', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Title','required'=>true]) !!}                      
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_mail">From Email<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::text('from_email', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'From Email','required'=>true]) !!}                      
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">Subject<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::text('subject', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Subject','required'=>true]) !!}                      
                </div>
              </div>
              <div cl
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description<span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  {!! Form::textarea('description', null, ['class'=>'form-control col-md-7 col-xs-12','placeholder'=>'Description','required'=>true]) !!}                      
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
