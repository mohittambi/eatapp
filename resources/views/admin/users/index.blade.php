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
              <table class="table table-striped projects" id="users_datatables">
                <thead>
                <tr>
                  <th width="10%">Id</th>
                  <th width="25%"  >Full Name </th>
                  <th width="30%"  >Email </th>
                  <th width="15%"  >Created At</th>
                  <th width="10%"  >Status</th>
                  <th width="10%" class="noneedtoshort" >Action</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('uniquepagestyle')
  <link rel="stylesheet" href="{{asset('assets/admin/dataTables.bootstrap.css')}}"> 
  <link rel="stylesheet" href="{{asset('assets/admin/sweetalert.css')}}">  
@endsection

@section('uniquepagescript')
<script src="{{asset('assets/admin/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/admin/jquery.dataTables.min.js')}}"></script>


<script>

function changeStatus(id)
{
  swal({
      title: "Are you sure?",
      type: "warning",
      showCancelButton: "No",
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
    },
      function(){
        jQuery.ajax({
        url: '{{route('admin.users.status.update')}}',
        type: 'POST',
        data:{user_id:id},
        headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          $("#status_"+id).html(response);
        }
        });
      }
  );
}

  $(function() {
    var table = $('#users_datatables').DataTable({
    processing: true,
    serverSide: true,
    order: [[0, "desc" ]],
    "ajax":{
    "url": '{!! route('admin.users.datatables') !!}',
    "dataType": "json",
    "type": "POST",

    "data":{ _token: "{{csrf_token()}}"}
    },
    columns: [ 
      { data: 'id', name: 'id', orderable:true },
      { data: 'full_name', name: 'full_name', orderable:true  },
      { data: 'email', name: 'email', orderable:true},
      { data: 'created_at', name: 'created_at', orderable:false },
      { data: 'status', name: 'status', orderable:false},
      { data: 'action', name: 'action', orderable:false }  
    ],
    "columnDefs": [
    { "searchable": false, "targets": 0 }
    ]
    ,language: {
        searchPlaceholder: "Search by id,full name or email"
    }
    });    
  });
</script>
@endsection