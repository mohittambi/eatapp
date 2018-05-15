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
                    <li><a href="#overview">OVERVIEW</a></li>
                    <li><a href="#FEATURES">ABOUT US</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right text-left">
                    <li><a href="#getApp">DOWNLOAD</a></li>
                    <li><a href="#getInTouch">CONTACT US</a></li>
                
        <?php if($user = Auth::user())
        { ?>

                <li><a href="{!! route('front.logout')!!}">LOGOUT</a></li>
               
        <?php }  else { ?>

                <li><a href="{!! route('front.login.signin')!!}">SIGN IN</a>/<a href="{!! route('front.login.signup')!!}">SIGN UP</a></li>
            
        <?php } ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>
</header>