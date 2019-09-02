@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4 ml-3">
    <h2 class="heder">Discount Mapping</h2>
  </div>
    <div class="col-sm-4 margin-tb">
         <div class="pull-right ml-4">
            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
        </div>
    </div>
</div>
<div class="container">
	<div class="col-xs-12 col-sm-12 col-md-12 mt-1">
        <div class="">
            <strong>Hotel Name:</strong>
            <span class="show-data"> {{ $hotel->title }}</span>
        </div>
    </div>
     <div class="discount-card">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
          <h3 style="text-align: center;" class="card-show-dis pt-3 pb-3 mt-2 mb-4">Discount Mapping For Same Day Check In</h3>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <h4>Time Slot</h4>
          <ul>
            <li>12 am - 6 am</li>
            <li>6 am - 9 am</li>
            <li>9 am - 12 pm</li>
            <li>12 pm - 3 pm</li>
            <li>3 pm onwards</li>
          </ul>
        </div>
      </div>
        <div class="col-sm-4 ">
          <div class="box">
            <h4>Weekday Discount</h4>
            <ul>
              <li>{{$discount->today_12_6_sdis}}%</li>
            <li>{{$discount->today_6_9_sdis}}%</li>
            <li>{{$discount->today_9_12_sdis}}%</li>
            <li>{{$discount->today_12_3_sdis}}%</li>
            <li>{{$discount->today_3_sdis}}%</li>
          </ul>
          </div>
        </div>
        <div class="col-sm-4 ">
          <div class="box">
            <h4>Weekend Discount</h4>
            <ul>
            <li>{{$discount->today_12_6_edis}}%</li>
            <li>{{$discount->today_6_9_edis}}%</li>
            <li>{{$discount->today_9_12_edis}}%</li>
            <li>{{$discount->today_12_3_edis}}%</li>
            <li>{{$discount->today_3_edis}}%</li>
          </ul>
          </div>
        </div>
      </div>
      <div class="discount-card">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
          <h3 style="text-align: center;" class="card-show-dis pt-3 pb-3 mt-2 mb-4">Discount Mapping for Subsequent Day Check In</h3>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <h4>Inventory</h4>
          <ul>
            <li>0-2 rooms left</li>
            <li>3-5 rooms left</li>
            <li>6-8 rooms left</li>
            <li>9-10 rooms left</li>
            <li>more than 10 rooms left</li>
          </ul>
        </div>
      </div>
        <div class="col-sm-4 ">
          <div class="box">
            <h4>Weekday Discount</h4>
            <ul>
              <li>{{$discount->tom_2_sdis}}%</li>
            <li>{{$discount->tom_5_sdis}}%</li>
            <li>{{$discount->tom_8_sdis}}%</li>
            <li>{{$discount->tom_10_sdis}}%</li>
            <li>{{$discount->tom_11_sdis}}%</li>
          </ul>
          </div>
        </div>
        <div class="col-sm-4 ">
          <div class="box">
            <h4>Weekend Discount</h4>
            <ul>
            <li>{{$discount->tom_2_edis}}%</li>
            <li>{{$discount->tom_5_edis}}%</span></li>
            <li>{{$discount->tom_8_edis}}%</li>
            <li>{{$discount->tom_10_edis}}%</li>
            <li>{{$discount->tom_11_edis}}%</li>
          </ul>
          </div>
        </div>
      </div>
    
    
  </div>
  <script>
    jQuery(document).ready(function($){
      $('.discount').addClass('active-menu');
    });
  </script>
@endsection