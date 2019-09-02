@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4 ml-2">
    <h2 class="heder">Hotel</h2>
  </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-3">
            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
        </div>
    </div>
</div>
<br/>
<div class="container">
  <div class="form form-padding">
  <div class="row">
    <div class="col-sm-8">
      <div class="form-group hotal-detail"> 
        {!! Html::image($hotel_show->picture, 'alt',array('class' => 'img-thumbnail hotel-image'))!!}
      </div>
    </div>
      <div class="col-sm-6 mt-4">
           <table class="table table-bordered table1">
    <tbody>
      <tr>
        <td><strong>Hotel User:</strong></td>
        <td><span class="show-data">{{ $user->name }}</span>  </td>
        </tr>
         <tr>
        <td> <strong>Relationship Manager User:</strong></td>
        <td><span class="show-data">{{ $rm?$rm->name:'' }}</span></td>
        </tr>
         <tr>
        <td><strong>Hotel Branch Name:</strong></td>
        <td><span class="show-data">{{ $hotel_show->hotel_branch_name }}</span></td>
        </tr>
        <tr>
          <td><strong>Hotel Type:</strong></td>
          <td>  <span class="show-data">{{ $type->name }}</span></td>
          </tr>
          <tr>
            <td><strong>Star Rating:</strong></td>
            <td><span class="show-data">{{ $hotel_show->rating }} Stars</span></td>
          </tr>
     </tbody>
  </table>

      </div>
      <div class="col-sm-6 mt-4">
           <table class="table table-bordered table1">
    <tbody>
      <tr>
        <td><strong>Hotel Name:</strong></td>
        <td><span class="show-data">{{ $hotel_show->title }}</span></td>
        </tr>
         <tr>
        <td><strong>Display Name:</strong></td>
        <td><span class="show-data">{{ $hotel_show->display_name }}</span></td>
        </tr>
         <tr>
        <td><strong> Hotel Description:</strong></td>
        <td><span class="show-data">{{ $hotel_show->hote_description}}</span></td>
        </tr>
        <tr>
          <td><strong>Check In Time:</strong></td>
          <td><span class="show-data">{{ $hotel_show->check_in_time }}</span></td>
        </tr>
        <tr>
          <td><strong>Check Out Time:</strong></td>
          <td><span class="show-data">{{ $hotel_show->check_out_time }}</span></td>
        </tr>
     </tbody>
  </table>

      </div>
      <div class="col-sm-12">
      <h3>Address Details</h3>
    </div>
      <div class="col-sm-6 mt-4">
           <table class="table table-bordered table1">
    <tbody>
      <tr>
        <td><strong>Hotel Address:</strong></td>
        <td><span class="show-data">{{ $hotel_show->address }}</span></td>
        </tr>
         <tr>
        <td><strong>City:</strong></td>
        <td> <span class="show-data">{{ $city->name }}</span></td>
        </tr>
         <tr>
        <td><strong>Region:</strong></td>
        <td><span class="show-data">{{ $region_name->name }}</span></td>
        </tr>
        </tbody>
  </table>

      </div>
      <div class="col-sm-6 mt-4">
           <table class="table table-bordered table1">
    <tbody>
      <tr>
        <td><strong>Area:</strong></td>
        <td><span class="show-data">{{ $area_name->name }}</span></td>
        </tr>
         <tr>
        <td> <strong>Longitude:</strong></td>
        <td><span class="show-data">{{ $hotel_show->longitude }}</span></td>
        </tr>
         <tr>
        <td><strong>Latitude:</strong></td>
        <td> <span class="show-data">{{ $hotel_show->latitude }}</span></td>
        </tr>
        </tbody>
  </table>

      </div>

    </div>
    
   <div class="container row">
    <marquee> 
      @foreach(explode(',',$hotel_show->pictures) as $pic)
        <!-- <span class="show-data">@php print_r($pic) @endphp</span> -->
        {!! Html::image($pic,'alt',array('class' => 'img-thumbnail multi-hotel-img ml-2'))!!}
      @endforeach
    </marquee>
  </div>
  <table class="table table-dark index-table table-striped mt-4">
    <thead>
      <th scope="col"><i class="fas fa-chevron-down"></i></th>
      <th scope="col">Room Name</th>
      <th scope="col">No of Inventory CP</th>
      <th scope="col">No of Inventory EP</th>
    </thead>
    <tbody>
      @foreach ($room_details as $key => $room)
      <tr>
        <td><i class="fas fa-bed"></i></td>
        <td>{{ $room->custom_category }}</td>
        <td>{{ $room->rooms_cp}}</td>
        <td>{{ $room->rooms_ep}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>
<script>
  jQuery(document).ready(function($){
    $('.property').addClass('active-menu');
    $('.hotel').addClass('active-sub-menu');
  });
</script>
@endsection