<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="{!! route('admin.dashboard')!!}" class="site_title"><i class="fa fa-paw" style = "opacity: 0;"></i> <span>{!! getSettings()['SITE_NAME'] !!}</span></a>
      </div>

      <div class="clearfix"></div>

      
      <div class="profile clearfix">
        <div class="profile_pic">
          <img src="{{ getLoggedUserInfo()->image?asset('uploads/users/thumb/'.getLoggedUserInfo()->image.''):asset('images/user.png') }}" alt="..." class="img-circle profile_img">
        </div>
        <div class="profile_info">
          <span>Welcome,</span>
          <h2>{!! ucfirst(getLoggedUserInfo()->full_name) !!}</h2>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
     

     <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <h3>General</h3>
          <ul class="nav side-menu">

            <li><a href="{!! route('admin.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{!! route('users.index')!!}"><i class="fa fa-users"></i> Customers</a></li>
            <li><a href="{!! route('admin.farmers.index')!!}"><i class="fa fa-users"></i> Farmers<!-- <span class="fa fa-chevron-down"></span> --></a>
              <!-- <ul class="nav child_menu">
                <li><a href="{!! route('admin.farmers.index')!!}">Farmers</a></li>
              </ul> -->
            </li>

            <li><a><i class="fa fa-edit"></i> Categories Manager <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{!! route('admin.categories.index')!!}">Categories</a></li>
                  <li><a href="{!! route('admin.categories.add')!!}">Add Category</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-desktop"></i> Banners Manager <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{!! route('admin.banner.index')!!}">Banners</a></li>
                  <li><a href="{!! route('admin.banner.add')!!}">Add Banner</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-desktop"></i> Amenities Manager <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{!! route('admin.amenities.index')!!}">Amenities</a></li>
                  <li><a href="{!! route('admin.amenities.add')!!}">Add Amenity</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-desktop"></i> Front Pages Manager <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{!! route('admin.frontPages.index')!!}">Front Pages</a></li>
                  <li><a href="{!! route('admin.frontPages.add')!!}">Add Front Page</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-envelope"></i> Email Templates <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                  <li><a href="{!! route('admin.emailTemplates.index')!!}">Email Templates</a></li>
                  <li><a href="{!! route('admin.emailTemplates.add')!!}">Add Email Template</a></li>
                </ul>
            </li>
            <li><a href="{!! route('admin.subscribers.index')!!}"><i class="fa fa-cog"></i> Feedback</a></li>
            <li><a href="{!! route('settings.index')!!}"><i class="fa fa-cog"></i> Settings</a></li>

          </ul>
        </div>
        <div class="menu_section">
          <!--h3>Live On</h3-->
          <ul class="nav side-menu">

          </ul>
        </div>

      </div>
      <!-- /sidebar menu -->

      <!-- /menu footer buttons -->
       <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{!! route('admin.logout')!!}">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      </div>
      <!-- /menu footer buttons -->
    </div>
  </div>