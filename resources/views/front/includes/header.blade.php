<style type="text/css">
    
    #navbar_d {
        background: #93b23a;
            padding: 10px 8px;
            border-radius: 4px;
    }
    #navbar_d li a{
         padding: 0px 10px;
        margin: 6px 10px;
    display: inline-block;
    }
    #navbar_d li a:hover,#navbar_d li a:focus{
        background-color: transparent;
    }
    @media(max-width: 767px){
         .navbar-collapse{
        z-index: 999999;
            background: #793846 !important;
    }
    }
   

</style>


<script type="text/javascript">
$(document).ready(function(){
    $(".ac").click(function(){
        $("#navbar_d").slideDown(2000);
    });
});
</script>


</script>
<header>
  <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>
            <a class="navbar-brand" href="{!! route('front.home')!!}">

                <img src="{{asset('assets/front/img/logo.png')}}"/></a>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left text-right">
                    <li><a href="{!! route('front.home')!!}#overview">OVERVIEW</a></li>
                    <li><a href="{!! route('front.home')!!}#FEATURES">ABOUT US</a></li>
                    <li><a href="{!! route('front.home')!!}#getApp">DOWNLOAD</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right text-left">
                    
                    <li><a href="{!! route('front.home')!!}#getInTouch">CONTACT US</a></li>
               <!--  <li><a href="#" data-toggle="collapse" data-target="#navbar_d">MY PROFILE</a>

    <div class="dropdown">
   <ul id="navbar_d"   class="dropdown-menu">
                   <li><a href="{!! route('front.login.changePassword')!!}">CHANGE PASSWORD</a></li>
                <li><a href="{!! route('front.logout')!!}">LOGOUT</a></li>   

                </ul>
</div>
                </li> -->

               
            
        <?php if($user = Auth::user())
        { ?>
               <!--  <li ><a href="{!! route('front.page.profile')!!}" data-toggle="collapse" data-target="#navbar_d">MY PROFILE</a></li> -->
            <li><a href="#"  class="ac">MY PROFILE <span class="caret"></span></a>
                <ul id="navbar_d"   class="dropdown-menu">
                    <li><a href="#">SETTINGS</a></li>
                    <li><a href="{!! route('front.page.profile')!!}">EDIT PROFILE</a></li>
                    <li><a href="{!! route('front.login.changePassword')!!}">CHANGE PASSWORD</a></li>
                    <li><a href="{!! route('front.logout')!!}">LOGOUT</a></li>   
                </ul>
            </li>
               
        <?php }  else { ?>

                <li><a href="{!! route('front.login.signin')!!}">SIGN IN</a></li>
                <li><a href="{!! route('front.login.signup')!!}">SIGN UP</a></li>
            
        <?php } ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>
</header>

