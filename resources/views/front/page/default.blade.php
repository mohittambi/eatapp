@extends('layouts.admin.admin')

@section('content')
   <div class="right_col" role="main">

          <div class="">
           @include('admin.includes.breadcum')
            <div class="row top_tiles">
             <a href = "{!! route('users.index')!!}"> <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-users"></i></div>
                  <div class="count">{!! $user_count !!}</div>
                  <h3>Total Users</h3>
                 
                </div>
              </div>
              </a>

              <!--div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-comments-o"></i></div>
                  <div class="count">179</div>
                  <h3>New Sign ups</h3>
                  <p>Lorem ipsum psdea itgum rixt.</p>
                </div>
              </div-->
            
            </div>

          </div>
        </div>


@endsection