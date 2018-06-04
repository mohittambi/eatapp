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
                    <div class="col-md-5 col-sm-5 col-xs-12 profile_left">
                      
                      <h3>{!! ucfirst($row->name) !!}</h3>

                      <ul class="list-unstyled user_data">
                        <li> <b>Email:</b> {!! $row->email !!}
                        </li>

                        <li> <b>Comment:</b>
                        <div class="comment" style="word-wrap: break-word;">{!! $row->comment !!}</div>
                        </li>

                        <li> <b>Created At:</b> {!! date('d-m-Y H:i A',strtotime($row->created_at)) !!}
                        </li>

                         <li> <b>Status:</b> {!! $row->status==1?'<span class="btn btn-success btn-xs">Active</span>':'<span class="btn btn-danger btn-xs">Inactive</span>' !!}
                        </li>
                      </ul>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      


@endsection