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
                   
                   

                    @include('message')
                    {!! Form::model($row, ['method' => 'PATCH','route' => [$model.'.update', $row->slug],'class'=>'form-horizontal validate','enctype'=>'multipart/form-data','id'=>'demo-form2']) !!}
                    {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Key <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
                           
                           {!! Form::text('name', null, [
                                                                    'maxlength'=>'191',
                                                                    'class'=>'form-control col-md-7 col-xs-12',
                                                                    'placeholder'=>'Key','required'=>true,'readonly'=>true]) !!}                      
                        </div>
                      </div>
                             <div class="box-body">
                <div class="form-group">
                    <label for="first-name" class="control-label col-md-3 col-sm-3 col-xs-12"> @if($row->parameter_type=='file')
                                    Upload Document:
                                   @else
                                    Value:
                                   @endif</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    @if($row->parameter_type=='number')
                                       {!! Form::number('description', null,['maxlength'=>'500','class'=>'form-control  col-md-7 col-xs-12','placeholder'=>'Value','required'=>true,'min'=>0]) !!}

                                       @elseif($row->parameter_type=='email')
                                       {!! Form::email('description', null,['maxlength'=>'500','class'=>'form-control  col-md-7 col-xs-12','placeholder'=>'Value','required'=>true,'min'=>0]) !!}

                                       @elseif($row->parameter_type=='url')
                                       {!! Form::url('description', null,['maxlength'=>'500','class'=>'form-control  col-md-7 col-xs-12','placeholder'=>'Value','required'=>true]) !!}

                                        @elseif($row->parameter_type=='boolean')
                                       {!! Form::select('description', ['1'=>'Yes','0'=>'No'],null,['class'=>'form-control  col-md-7 col-xs-12','required'=>true]) !!}

                                    

                                       @elseif($row->parameter_type=='file')
                                        <input type = "file" class="form-control" name = "description" >
                                    @else
                                       {!! Form::text('description', null,['maxlength'=>'500','class'=>'form-control','title'=>'Please enter value','placeholder'=>'Value','required'=>true]) !!}
                                    @endif
                        
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

      


@endsection