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
                    <br />

                    @include('message')
                    {!! Form::model($row, ['method' => 'PATCH','route' => ['admin.update.profile', $row->slug],'class'=>'form-horizontal validate','enctype'=>'multipart/form-data','id'=>'demo-form2']) !!}
                    {{ csrf_field() }}

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Full Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
                          {!! Form::text('full_name', null, ['maxlength'=>'255','placeholder' => 'Name', 'required'=>true, 'class'=>'form-control col-md-7 col-xs-12']) !!}                        
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
                          {!! Form::email('email', null, ['maxlength'=>'255','placeholder' => 'Email', 'required'=>true, 'class'=>'form-control col-md-7 col-xs-12']) !!}                        
                        </div>
                      </div>


                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Image
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
                           <input type = "file" name = "image_update" accept="image/*">
                           <br>
                            <img src = "{{ getLoggedUserInfo()->image?asset('uploads/users/thumb/'.getLoggedUserInfo()->image.''):asset('images/user.png') }}" height = "100px;" width = "100px;">                      
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

      


@endsection