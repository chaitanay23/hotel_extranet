


@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Booked</h2>
    </div>
  <!-- <div class="col-sm-6 margin-tb">
      <div class="pull-right">
        @can('hotel_create')
          <a class="btn btn-success" href="{{ route('hotel.create') }}"> Create New Hotel</a>
        @endcan
      </div> -->

  </div>
   <!-- <div class="col-sm-6"> 
    
    
   <form method="get" action="{{url('booked')}}" accept-charset="UTF-8">
      <div class="input-group custom-search-form">
          <input type="hidden" value="" id="fromdate_def">
          <input type="hidden" value="" id="todate_def">
          <input type="date" class="form-control" name="fromdate" id="fromdate" value="10-04-2019" placeholder="fromdate" autocomplete="off">
          <input type="date" class="form-control" name="todate" id="todate" placeholder="todate" autocomplete="off">
          <div class="input-group-append">
            <button class="btn btn-default-sm btn-color" type="submit" name="submit" value="submit">
                <i class="fa fa-search"></i>
            </button>
          </div>
      </div>  
    

  </div>

</div> -->
<div class="col-sm-12">
      <form method="get" action="{{url('booked')}}" accept-charset="UTF-8">
<div class="row mt-4 index-table">
	      <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
            <div class="form-group">
		          <strong>Select From Date :</strong>
              <input type = "date" id = "start_date" class="form-control" name="start_date" autocomplete="off">
              <input type = "hidden" value = "{{$fromdate}}" id="start_date_old">
		        </div>
		    </div>
		    <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select To Date :</strong>
              <input type = "date" id = "end_date" class="form-control" name="end_date" autocomplete="off">
              <input type = "hidden" value = "{{$todate}}" id="end_date_old">
              <!-- <button type="submit" name="submit" value="submit" class="btn btn-default-sm btn-color booking-search1"><i class="fa fa-search"></i></button>!-->
            </div> 
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 text-center mb-4">
          <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
</form>
</div> 


@if ($message = Session::get('success'))
<div class="alert alert-success mt-1">
  <p>{{ $message }}</p>
</div>
@endif
<div class="over">
<table class="table table-striped table-dark index-table mt-2">
 <tr>
 <tr>
    <th># </th>
    <th>Hotel Name</th>
    <th>Room Type</th>
    <th>User-Email </th>
    <th>User city</th>
    <th>Bid Price</th>
    <th>Listed Price</th>
    <th>Selling Price</th>
    <th>Meal Plan</th>
    <th>Date</th>
  </tr>
   
   @if($counter==0)
        <tr> <td colspan="12" style="color: rgb(245, 165, 34);text-align:center;padding:10px;font-size:16px;"> No Record Found</td> </tr>
      @else  
        @foreach ($booking as $booking_key => $booking_value) 
         
          <tr>
          <td> {{ $booking_key+1 }}  </td>
            <td>@if($booking_value->hotel != ''){{$booking_value->hotel->title}}@else --  @endif</td>
            <td>@if($booking_value->hoteldetail!='') {{ $booking_value->hoteldetail->custom_category }} @else --  @endif </td>
            <td>@if($booking_value->user!=''){{$booking_value->user->email}} @else --  @endif</td>
            <td> {{ $booking_value->user_city }}  </td>
            <td> {{ $booking_value->bidprice }}  </td>
            <td> {{ $booking_value->listing_price }}  </td>
            <td> {{ $booking_value->selling_price }}  </td>
            <td> {{ $booking_value->meal_plan }}  </td>
            <td> {{ Carbon\Carbon::parse($booking_value->created_at)->format('d-M-Y')}}  </td>
          </tr> 
        @endforeach

      @endif

    
     
 </tr>
 
</table>
</div>
</div>
{{ $booking->appends(['start_date'=>$fromdate,'end_date'=>$todate])->links() }}
    


    

<style type="text/css">
	#image{
		width:200px;
	}
</style>

<script>
    jQuery(document).ready(function($){
      $('.bid-report').addClass('active-menu');
      $('.booked').addClass('active-sub-menu');
      var old_start_date = $('#start_date_old').val();
      var old_end_date = $('#end_date_old').val();
      var d = new Date();
      var current_date = d.getFullYear() + '-' + (((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + ('0' + d.getDate()).slice(-2);
      d.setDate(d.getDate() - 15);
      var last_date = d.getFullYear() + '-' + (((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + ('0' + d.getDate()).slice(-2);
      $('#start_date').val(last_date);
      $('#end_date').val(current_date);
      
      
      if(old_start_date != '')
      {
        console.log(old_start_date);
        $('#start_date').val(old_start_date);
       
      }
      if(old_end_date != '')
      {
        $('#end_date').val(old_end_date);
       
      }
    });
    
</script>



@endsection


 	 	 	 	 	 	