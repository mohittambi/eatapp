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
              <div class="col-md-6 col-sm-6 col-xs-12">
                <center>
                  <div class="profile_img row">
                    <div id="profile-avatar">
                      <!-- Current avatar -->
                      <img class="img-responsive avatar-view" src="{{ $row->image?asset('public/uploads/farmers/thumb/'.$row->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                    </div>
                  </div>
                  <h3>{!! ucfirst($row->full_name) !!}</h3>
                </center>


                <table class="table table-hover table-striped">
                  <tbody>
                    <tr>
                      <td>Email:</td>
                      <td>{!! $row->email !!}</td>
                    </tr>
                    <!-- <tr>
                      <td>Phone Number:</td>
                      <td>{!! $row->country_id.'-'.$row->phone_number !!}</td>
                    </tr> -->
                    <!-- <tr>
                      <td>Gender:</td>
                      <td>{{ $row->gender=='M' ? 'Male' : 'Female' }}</td>
                    </tr> -->
                    <tr>
                      <td>Location:</td>
                      <td>{{ $row->locations->location_name }}</td>
                    </tr>
                    <tr>
                      <td>Address:</td>
                      <td>{{ $row->locations->address }}</td>
                    </tr>
                    <tr>
                      <td>Farmer Code:</td>
                      <td>{{ $farmer_code }}</td>
                    </tr>
                    <tr>
                      <td>Description:</td>
                      <td>{!! $row->farmerDetails->description !!}</td>
                    </tr>
                    <tr>
                      <td>Categories:</td>
                      <td>{{ $catListName }}</td>
                    </tr>
                    <tr>
                      <td>Banner Image:</td>
                      <td>
                        <img class="img-responsive avatar-view" src="{{ $row->farmerDetails->banner_image?asset('public/uploads/banners/thumb/'.$row->farmerDetails->banner_image.''):'' }}" alt="Banner Image" title="Change the avatar">
                      </td>
                    </tr>
                    <tr>
                      <td>Created At:</td>
                      <td>{!! date('d-m-Y H:i A',strtotime($row->created_at)) !!}</td>
                    </tr>
                    <tr>
                      <td>Status:</td>
                      <td>{!! $row->status==1?'<span class="btn btn-success btn-xs">Active</span>':'<span class="btn btn-danger btn-xs">InActive</span>' !!}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
      


@endsection