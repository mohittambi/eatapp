
<!DOCTYPE html>
<html lang="en">
<head>
  <title>404</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<style>
 .error-page h1{
   color:#557d87;
   font-size:91px;
   margin-bottom:0;
   margin-top:8px;
 }
 .error-page h1 span{
   color:#222222;
   
 }
.error-page h4{
   font-family:cursive;
 }
 .error-details{
    color:#565656; 
 }
 .back-btn{
   background-color:#333333;  
  border-color:#333333;
  color:#FFFFFF;
  border-radius:0;  
  padding:8px 20px;
  margin-top:20px;
 }
 .btn-default.back-btn:hover{
    background-color:#40b0ed;  
  border-color:#40b0ed;
  color:#FFFFFF;
  }
</style>
<body style="background-color:#f7f7f7">

<div class="container">
<div class="error-page" align="center" style="margin-top:80px;">

<img src="{{ asset('images/sad-face.png') }}" width="80">
    <h1 class="404error"> 4<span>0</span>4 </h1>
     <h4 style="margin-top:20px;">Oops! Page Not Found</h4>
    <div class="error-details">
        Sorry, an error has occured. Requested page not found!
    </div>
    
    
  </div>
</div>

</body>
</html>










