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
                  <h3>{!! ucfirst($row->title) !!}</h3>
                </center>


                <table class="table table-hover table-striped">
                  <tbody>
                    <tr>
                      <td>Content:</td>
                      <td>{{ $row->content }}</td>
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