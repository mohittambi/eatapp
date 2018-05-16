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

              </div>
              </a>

              
            
            </div>

          </div>
        </div>


@endsection