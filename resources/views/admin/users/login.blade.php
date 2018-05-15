<!DOCTYPE html>
<html ng-app="app">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/util.css')}}">
     <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
</head>
<body>
   <div class="main-doc">
        <div class="container">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="assets/login/images/icons/favicon.ico"/>
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <div class="login100-form-title" style="background-image: url(assets/login/images/bg-01.jpg);">
          <span class="login100-form-title-1">
            Log In
          </span>
        </div>
      
       
         {!! Form::open(['route' => 'admin.make.login','class'=>'validate login100-form validate-form']) !!}
        {{ csrf_field() }}
        <div class="form-group">
           @include('message')
        </div>
          <div class="wrap-input100 validate-input m-b-26">
            <span class="label-input100">Username</span>
           

             {!! Form::text('username', null, ['class'=>'input100','id'=>'username','maxlength'=>'255','placeholder'=>'User Name','required'=>true]) !!}
             
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input m-b-18">
            <span class="label-input100">Password</span>
            <input type="password" name="password" id="password" class="input100"  required  placeholder="Password"/>
            
            <span class="focus-input100"></span>
          </div>
          <div class="container-login100-form-btn">
            <button class="login100-form-btn" >
              <span>Login</span>
             
            </button>

          </div>

        </form>
      </div>
    </div>
  </div>
  
  
<style>
  .login_error{color:red;}
  .invaliduserandpassword{
    margin-top: 10px;
  }

  .alert-danger {
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
}
.alert {
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
}
</style>
  </div>
  </div>

  <script src="{{asset('assets/admin/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->



<script src="{{asset('assets/admin/jquery.validate.min.js')}}"></script>
<script>
 $(".validate").validate();
 </script>
    <style type="text/css">
            

            .error{
            color:red;
            }

            
        </style>
   
</body>
</html>


