 <head>
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! isset($title)?$title:'Dashboard'!!}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo.png')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendors/nprogress/nprogress.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendors/bootstrap-daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/custom.min.css')}}"> -->


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! isset($title)?$title:'EatApp'!!}</title>
    <link href="{{asset('assets/front/css/fontawesome-all.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/front/css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/front/css/owl.theme.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front/css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front/css/responsive.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/front/css/animations.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/front/css/slider.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front/css/main.css')}}" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo_temp.png') }}"/>

       
       <!-- @yield('uniquepagestyle') -->
  </head>

        
  <meta name="csrf-token" content="{{ csrf_token() }}">