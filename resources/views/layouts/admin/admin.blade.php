
<!DOCTYPE html>
<html lang="en">
  @include('admin.includes.head')


  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       @include('admin.includes.sidebar')
      

        <!-- top navigation -->
         @include('admin.includes.header')
        <!-- /top navigation -->

        <!-- page content -->
     	  @yield('content')
       
        
         @include('admin.includes.script')
        @include('admin.includes.footer')
      </div>
    </div>
    


   
  </body>
</html>










