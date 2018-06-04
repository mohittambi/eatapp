@extends('layouts.front.front')
@section('content')
<!--form section start now-->
<section id="signup" class="main_section">
    <div class="container">
        <div class="row">
			<h2 class="animated">Settings Panel</h2>
            <hr class="border-line">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="inner_main clearfix">					
                    <div class="col-sm-12 right_sect p-lr25 p-top30 m-bottom20">
                        <div class="tab-content">

                            <div id="home" class="tab-pane1">
                               

                                    <div class="form-group">
                                        <label>Non Availibility Days</label>
                                        <div id='calendar'></div>
                                    </div>
                                    
                                    
                                
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
