
@extends('layouts.app')


@section('content')

<div class="row mt-4 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Admin Activities</h2>
    </div>
  <!-- <div class="col-sm-6 margin-tb">
      <div class="pull-right">
        @can('hotel_create')
          <a class="btn btn-success" href="{{ route('hotel.create') }}"> Create New Hotel</a>
        @endcan
      </div> -->

  </div>
   <div class="col-sm-6"> 
    
    
   <form method="get" action="{{url('admin-activities')}}" accept-charset="UTF-8">
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

</div>


@if ($message = Session::get('success'))
<div class="alert alert-success mt-1">
  <p>{{ $message }}</p>
</div>
@endif

<table class="table table-striped table-dark index-table mt-2">
 <tr>
 <tr>
 <th># </th>
    <th>Login Id</th>
    <th>Name</th>
    <th>Ip Address</th>
    <th>Section</th>
    <th>Sub Section</th>
    <th>Hotel Name</th>
    <th>Record Modified</th>
    <th>Activity Time</th>
   
   @if($counter==0)
        <tr> <td colspan="12" style="color: rgb(245, 165, 34);text-align:center;padding:10px;font-size:16px;"> No Activity Logs Found</td> </tr>
        
      @else  
        @foreach ($booking as $booking_key => $booking_value) 


          <tr>
          <td> {{ $booking_key+1 }}  </td>
            <td> {{ $booking_value->admin->email}}  </td>
            <td> {{ $booking_value->admin->name }}</td>
            <td> {{ $booking_value->ip }} </td>
            <td> {{ $booking_value->section }} </td>
            @if($booking_value->section_id == 1)
              <td> {{ $booking_value->subsection }} - @if($booking_value->hoteldetail_id) {{ $booking_value->hoteldetail->cp_include }} @endif</td>
            @else
              <td> {{ $booking_value->subsection }} </td>
            @endif
            <td> {{ $booking_value->hotel->display_name }}  </td>
            <td> {{ $booking_value->modify }}  </td>
            <td> {{ Carbon\Carbon::parse($booking_value->created_at)->format('d-M-Y H:i:s')}}  </td>
          </tr> 
        @endforeach

      @endif

     
 </tr>
 
</table>

{{ $booking->links() }}
    

<style type="text/css">
	#image{
		width:200px;
	}
</style>

<script>
{
  jQuery(document).ready(function($){
    var from_date_def = $('#fromdate_def').val();
    var to_date_def = $('#todate_def').val();
    
    
    var d = new Date();
    var current_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
    d.setDate(d.getDate()-30);
    var last_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
    $('#fromdate').val(current_date);
    $('#todate').val(last_date);
    if(from_date_def != null)
    {
      $('#fromdate').val(from_date_def);
    }
    if(to_date_def != null)
    {
      $('#todate').val(to_date_def);
    }
    
});
</script>


@endsection


 	 	 	 	 	 	