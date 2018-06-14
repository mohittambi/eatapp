@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
            <h2 class="animated">{!! $ComposerServiceProvider->title !!}</h2>
            <hr class="border-line">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="inner_main clearfix">
                    <div class="col-sm-12 right_sect p-lr25 p-top30 m-bottom20">
                      <!--  <ul class="nav nav-tabs signup_tab">
                            <li class="active"><a data-toggle="tab" href="#home">customer</a></li>
                            <li><a data-toggle="tab" href="#menu1">Owner</a></li>
                        </ul>-->

                        <div class="tab-content">
                            <div id="home" class="tab-pane1">
                                {!! $ComposerServiceProvider->content !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--form section end now-->

@endsection
