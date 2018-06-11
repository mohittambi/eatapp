@extends('layouts.admin.admin')

@section('content')

  <div class="right_col" role="main">
    <div class="">
      @include('admin.includes.breadcum')
      <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>{!! $title !!}</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <center>
                  <div class="profile_img row">
                    <div id="profile-avatar">
                      <!-- Current avatar -->
                      <img class="img-responsive avatar-view" src="{{ $row->image?asset('public/uploads/farmers/thumb/'.$row->image.''):asset('images/user.png') }}" alt="Avatar" title="Change the avatar">
                    </div>
                  </div>
                  <h3>{!! ucfirst($row->full_name) !!}</h3>
                </center>


                <table class="table table-hover table-striped">
                  <tbody>
                    <tr>
                      <td>Company Name:</td>
                      <td>{{ $row->farmerDetails->company_name }}</td>
                      <td>Address:</td>
                      <td>{{ $row->locations->address }}</td>
                    </tr>
                    <tr>
                      <td>Email:</td>
                      <td>{!! $row->email !!}</td>
                      <td>Phone Number:</td>
                      <td>{!! '+'.$row->phonecode.'-'.$row->phone_number !!}</td>
                    </tr>
                    <tr>
                      <td>VAT Number:</td>
                      <td>{!! $row->farmerDetails->vat_number !!}</td>
                      <td>CF:</td>
                      <td>{!! $row->farmerDetails->cf !!}</td>
                    </tr>
                    <tr>
                      <td>Farmer Code:</td>
                      <td>{{ $row->farmerDetails->farmer_code }}</td>
                      <td>Categories:</td>
                      <td>{{ $catListName }}</td>
                    </tr>
                    <tr>
                      	<td>Description:</td>
                      	<td>{!! $row->farmerDetails->description !!}</td>
                    	<td>Service Type:</td>
                    	<td>{!! $all_service_types[@$row->farmerDetails->service_type] !!}</td>
                    </tr>
                    <tr>
                      	<td>Contract Start Date:</td>
                      	<td>{!! $row->farmerDetails->contract_start_date !!}</td>
                    	<td>Contract End Date:</td>
                    	<td>{!! $row->farmerDetails->contract_end_date !!}</td>
                    </tr>
                    <tr>
                      <td>Created At:</td>
                      <td>{!! date('d-m-Y H:i A',strtotime($row->created_at)) !!}</td>
                      <td>Status:</td>
                      <td>{!! $row->status==1?'<span class="btn btn-success btn-xs">Active</span>':'<span class="btn btn-danger btn-xs">InActive</span>' !!}</td>
                    </tr>
                    <tr><th colspan="4">Non Availability Days:</th></tr>
	                    <tr>
	                    	@if(isset($all_na) && !empty($all_na))
		                    	<td colspan="4"><?php unset($all_na[0]);?>
								@foreach ($all_na as $value)
									<div class="col-md-3" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">{!! date('l jS F Y',strtotime($value['start'])) !!}  </div>
								@endforeach
		                    	</td>
		                    @else
                				<div class="col-md-12" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">Non Availability days are not selected.</div>
	                		@endif	
	                    </tr>
                   
                    <tr><th>Available Hours:</th></tr>
                    <tr>
					    <th>Day Name</th>
					    <th>Opening Time</th>
					    <th>Closing Time</th>
					    <th>Max Visitors</th>
					</tr>
					@if(isset($data) && !empty($data))
						@foreach ($data as $day => $value)
	                    <tr>
	                        <td> {!! $value['day_name'] !!}
	                        </td>
	                        <td>
	                            {!! $value['opening_time'] !!}
	                        </td>
	                        <td>
	                            {!! $value['closing_time'] !!}
	                        </td>
	                        <td>
	                            {!! $value['visitors'] !!}
	                        </td>
	                    </tr>
	                    @endforeach
                    @else
                		<div class="col-md-12" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">No working hours entered.</div>
	                @endif

                    <tr><th colspan="4">Amenities:</th></tr>
                    <tr>
                    	<td colspan="4">
                    		@if(isset($amenities) && !empty($amenities))
			                    @foreach($amenities as $amenity)
			                    	<div class="col-md-3" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">{{$amenity[0]->name}}</div>
			                    @endforeach
		                	@else
		                		<div class="col-md-12" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">No amenities selected.</div>
		                	@endif
                    	</td>	
                    </tr>
                    <tr><th>Banners:</th></tr>
                    <tr>
                    	<td colspan="8">
                      	@if(isset($farmers_banners) && !empty($farmers_banners))
		                    @foreach($farmers_banners as $farmers_banner)
		                    <div class="col-md-3">
		                    	<div class="profile_img row">
						            <div id="profile-avatar col-md-9">
							            <!-- Current avatar -->
							            @if(isset($farmers_banner['name']) && !empty($farmers_banner['name']))
							            <img class="img-responsive avatar-view" src="{{ $farmers_banner['name']?asset('public/uploads/farmers-banners/thumb/'.$farmers_banner['name'].''):asset('images/user.png') }}" alt="Banner Image" title="Farmer Banner Image" style="margin: 0 auto;">
							            @endif
							        </div>
	                    			<div class="banner-desc col-md-3" style="width: 100%;">
	                    				<p>{{$farmers_banner['description']}}</p>
	                    			</div>
						        </div>
		                    </div>
		                    @endforeach
		                </td>
	                	@else
	                		<div class="col-md-12" style="padding-top:5px;padding-bottom:5px;border-bottom:1px solid #ddd;">No banner image selected.</div>
	                	@endif
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
      


@endsection