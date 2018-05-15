@extends('layouts.front.front')
@section('content')

<section id="overview">
    <div class="container">
        <div class="row animatedParent">
            <h2 class="animated fadeIn">APPS OVERVIEW</h2>
            <hr class="border-line">
            <p class="tag animated fadeIn">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            <div class="col-md-3 col-sm-3 m-t-185 animated bounceInDown">
                <img src="{{asset('assets/front/img/mobileCase.png')}}" class="topImg"/>
                <h3>User Friendly Design</h3>
                <p>Add friends, share food, and create your feed to help grow your foodie network. Post photos for your
                    followers, like and comment on your friends’ photos, save where you want to eat in one easy
                    location.</p>
                <img src="{{asset('assets/front/img/blueArrow.png')}}" class="btmImg"/>
            </div>
            <div class="col-md-3  col-sm-3 m-t-40  animated bounceInDown">
                <img src="{{asset('assets/front/img/discover.png')}}" class="topImg"/>
                <h3>Discover New Restaurants</h3>
                <p>Find the hottest restaurants near your location your friends are eating at. Let them know you went
                    there because you saw their post.</p>
                <img src="{{asset('assets/front/img/greenArrow.png')}}" class="btmImg"/>
            </div>
            <div class="col-md-3 col-sm-3 m-t-50  animated bounceInDown">
                <img src="{{asset('assets/front/img/feed.png')}}" class="topImg"/>
                <h3>Customize Your Feed</h3>
                <p>Change your profile picture and create an aesthetic for your profile page. Post pictures and videos
                    of your favorite food and restaurants in one easy location.</p>
                <img src="{{asset('assets/front/img/purpalArrow.png')}}" class="btmImg"/>
            </div>
            <div class="col-md-3 col-sm-3 m-t-185  animated bounceInDown">
                <img src="{{asset('assets/front/img/ios-support.png')}}" class="topImg"/>
                <h3>IOS Supported</h3>
                <p>Feed For Friends is currently being developed for iPhone users only.</p>
                <img src="{{asset('assets/front/img/orangeArrow.png')}}" class="btmImg"/>
            </div>
            <div class="col-md-12 overimg  animated bounceInUp">
                <img src="{{asset('assets/front/img/over-mobile.png')}}"/></div>
        </div>
    </div>
</section>


<section id="FEATURES">
    <div class="container animatedParent">
        <div class="row">
            <div class="col-md-5  col-sm-12 animated bounceInLeft text-center"><img src="{{asset('assets/front/img/FeatureMob.png')}}"/></div>
                <div class="col-md-7  col-sm-7  btmbrdr animated bounceInRight">
                    <h2>ABOUT US</h2>
                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting industry
                        Lorem Ipsum has been the industry's standard dummy.</h3>
                    <p class="tag">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </p>
                    <p class="tag">  It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p>

                </div>
        </div>
    </div>
</section>


<section id="screenshots">
    <div class="container animatedParent">
        <div class="row">
            <h2 class="animated zoomIn">SCREENSHOT</h2>
            <hr class="border-line">
            <p class="tag animated zoomIn">Take a Look at Our Beautiful and User Friendly App Screens.</p>


            <div class="trending_now col-md-offset-1 col-md-10  animated zoomIn">


                <div id="owl-example" class="owl-carousel m-top5">
                    <div class="item item_ center">
                        <img src="{{asset('assets/front/img/screen-03.png')}}"/>
                    </div>
                    <div class="item item_ center">
                        <img src="{{asset('assets/front/img/screen-02.png')}}"/>
                    </div>
                    <div class="item item_ center">
                        <img src="{{asset('assets/front/img/screen-01.png')}}"/>
                    </div>
                    <div class="item item_ center">
                        <img src="{{asset('assets/front/img/screen-03.png')}}"/>
                    </div>
                    <div class="item item_ center">
                        <img src="{{asset('assets/front/img/screen-02.png')}}"/>
                    </div>


                </div>

            </div>


        </div>
    </div>
</section>


<section id="getApp">
    <div class="container animatedParent">
        <div class="row">
            <h2 class="animated fadeInUp">Get This App</h2>
            <p class="tag animated fadeInUp">Download EAT-APP For Farmers on the App Store</p>
            <div class="col-md-12 text-center animated fadeInUp">
                <a href="#" class="btn-default"><img src="{{asset('assets/front/img/appStore.png')}}"/> App Store</a>
                <a href="#" class="btn-default last"><img src="{{asset('assets/front/img/androidStore.png')}}" style="height: 23px"/> Google Play</a>
            </div>
        </div>
    </div>
</section>


<section id="getInTouch">
    <div class="container animatedParent">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 whtBg animated fadeInRight">
                <h2>GET IN TOUCH</h2>
                <hr class="border-line">
                <p class="tag">Have feedback, suggestion, or any thought about our app? Feel free to contact us anytime,<br/>
                    we will get back to you in 24 hours.</p>
                <div class="col-md-6 col-sm-6">
                    @include('message')
                    {!! Form::model(null, ['method' => 'POST','route' => ['front.post.contactForm'],'class'=>'validate','autocomplete'=>'off','id'=>'contact-form']) !!}
                    {{ csrf_field() }}
                    @include('message')
                    <div class="form-group">
                        {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Name*','autocomplete'=>'off','required'=>true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::email('email', null, ['class'=>'form-control','placeholder'=>'Email*','autocomplete'=>'off','required'=>true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('phone_number', null, ['class'=>'form-control','placeholder'=>'Phone Number*','autocomplete'=>'off','required'=>true]) !!}
                    </div>
                </div>
                <div class="col-md-6  col-sm-6">

                    <div class="form-group">
                        <!-- <textarea class="form-control" placeholder="Comment*" style="min-height: 187px;"></textarea> -->
                        {!! Form::textarea('comment', null, ['class'=>'form-control','placeholder'=>'Comment*','autocomplete'=>'off','required'=>true]) !!}
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">SEND MESSAGE</button>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
