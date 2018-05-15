

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Change Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <style>
  .btn-default.btn-submit{
  background-color:#40b0ed;  
  border-color:#40b0ed;
  color:#FFFFFF;
  border-radius:0;  
  padding:8px 20px;
  }
  .btn-default.btn-submit:hover{
    background-color:#40b0ed;  
  border-color:#40b0ed;
  color:#FFFFFF;
  }
  .NewErr{
    color:#FF0308;  
  }
  .form-control{
   border-radius:0; 
   height:40px; 
  }
  button{
    background-color: #40b0ed;
  }
  </style>
</head>
<body >

<div class="container" style="margin-top:35px;">
<div class="col-sm-6 col-sm-offset-3">
<div align="center" style="border-bottom:1px solid #f7f7f7;padding-bottom:10px;background-color: #557d87">
<a href="#"><img src="{{ asset('images/logo.png') }}" width="150"></a>
</div>
<h3 class="text-uppercase" style="margin-bottom:15px; font-size:19px;">Update Password</h3>
 <form action="{!! route('front.user.post.updated.password',['token'=>$token])!!}" method="post">
   {{ csrf_field() }}
    <div class="form-group">
      <label for="email">Password:</label>
       <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required>
        @if ($errors->has('password'))
      <span class="help-block NewErr" style="margin-top:5px;">
         {{ $errors->first('password') }}
      </span>
      @endif
    </div>


    <div class="form-group">
      <label for="pwd">Confirm Password:</label>
       <input type="password" class="form-control" id="pwd" placeholder="Enter Confirm Password" name="confirm_password" required>

        @if ($errors->has('confirm_password'))
      <span class="help-block NewErr" style="margin-top:5px;">
        {{ $errors->first('confirm_password') }}
      </span>
      @endif
    </div>
     <button type="submit" class="btn btn-default btn-submit"  style="background-color: #557d87">Submit</button>
  </form>
  
   </div>
  </div>

 </body>

</html>



