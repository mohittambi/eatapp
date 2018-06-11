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
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
                          <img class="img-responsive avatar-view" src="{{ $row->image?asset('public/uploads/users/thumb/'.$row->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
                      <h3>{!! ucfirst($row->name) !!}</h3>

                      <ul class="list-unstyled user_data">
                      
                        <li> <b>Description:</b> {!! $row->description !!}
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