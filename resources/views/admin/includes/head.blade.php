 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! isset($title)?$title:'Dashboard'!!}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo.png')}}">
     <link rel="stylesheet" href="{{asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}">
     <link rel="stylesheet" href="{{asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css')}}">
     <link rel="stylesheet" href="{{asset('assets/admin/vendors/nprogress/nprogress.css')}}">
      <link rel="stylesheet" href="{{asset('assets/admin/vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
       <link rel="stylesheet" href="{{asset('assets/admin/custom.min.css')}}">  
       
       @yield('uniquepagestyle')

  </head>

      <style type="text/css">
      .breadcrumb {
      padding: @breadcrumb-padding-vertical @breadcrumb-padding-horizontal;
      margin-bottom: @line-height-computed;
      list-style: none;
      background-color: @breadcrumb-bg;
      border-radius: @border-radius-base;

      > li {
      display: inline-block;

      + li:before {
      content: "@{breadcrumb-separator}\00a0"; // Unicode space added since inline-block means non-collapsing white-space
      padding: 0 5px;
      color: @breadcrumb-color;
      }
      }

      > .active {
      color: @breadcrumb-active-color;
      }
      }

      .action_crud span {
      float: left;
      padding-left: 3px;
      }

      .error{
      color:red;
      }

      .no-padding-h{
      margin-top:10px;
      }
      .paginate_button{
        cursor: pointer;
      }
      .paginate_button.current {
  background-color: gray !important;
  color: white !important;
}
    </style>

        
  <meta name="csrf-token" content="{{ csrf_token() }}">