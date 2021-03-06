@extends('layouts.admin.admin')

@section('content')
    <div class="right_col" role="main">

        <div class="">
           @include('admin.includes.breadcum')
            <div class="row top_tiles">
                <a href = "{!! route('admin.farmers.index')!!}"> 
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-users"></i></div>
                            <div class="count">{!! $user_count !!}</div>
                            <h3>Total Farmers</h3>
                        </div>
                    </div>
                </a>
                <a href = "{!! route('users.index')!!}"> 
                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-users"></i></div>
                            <div class="count">{!! $customer_count !!}</div>
                            <h3>Total Customers</h3>
                        </div>
                    </div>
                </a>

            </div>


              

              
            
        </div>

    </div>



@endsection