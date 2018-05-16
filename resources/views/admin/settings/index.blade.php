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
              @include('message')
              <table class="table table-striped projects">
                <thead>
                <tr>
                  <th width="10%">S.No.</th>
                  <th width="40%"  >Key </th>
                  <th width="40%"  >Value</th>
                  <th  width="10%" class="noneedtoshort" >Action</th>
                </tr>
                </thead>
                <tbody>
                  @if(isset($lists) && !empty($lists))
                  <?php $b = 1;?>
                  @foreach($lists as $list)
                    <tr>
                      <td>{!! $b !!}</td>
                      <td>{!! $list->name !!}</td>
                      <td>
                        <?php 
                          if($list->parameter_type == 'boolean')
                          {
                            echo $list->description=='1'?'Yes':'No';
                          }
                          else
                          {
                            echo $list->description;
                          }
                        ?>
                      </td>
                      <td class = "action_crud">
                        <span>
                        <a title="Edit" href="{{ route($model.'.edit', $list->slug) }}" ><img src="{!! asset('images/edit.png') !!}" alt="">
                        </a>
                        </span>
                      </td>
                    </tr>
                  <?php $b++; ?>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection