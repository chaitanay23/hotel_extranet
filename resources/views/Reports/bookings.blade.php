
@extends('layouts.app')


@section('content')

<div class="row mt-3 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Bookings</h2>
    </div>
  <!-- <div class="col-sm-6 margin-tb">
      <div class="pull-right">
        @can('hotel_create')
          <a class="btn btn-success" href="{{ route('hotel.create') }}"> Create New Hotel</a>
        @endcan
      </div> -->
    


  </div>
   <!-- <div class="col-sm-6"> 
   
  
   <form method="get" action="{{url('bookings')}}" accept-charset="UTF-8">
      <div class="input-group custom-search-form">
      <div class="col-xs-12 col-sm-12 col-md-12 ">
        <div class="form-group">
      
      <label class="radio">
        {{  Form::radio('result','booking_date',true,array('id' => 'booking_date')) }}<span class="show-data">Booking Date</span>
      </label>
      <label class="radio">
        {{  Form::radio('result','check_in_date',false,array('id' => 'check_in_date')) }}<span class="show-data">Check in Date</span>
      </label>
      <label class="radio">
        {{  Form::radio('result','check_out_date',false,array('id' => 'check_out_date')) }}<span class="show-data">Check out Date</span>
      </label>
      <label class="radio">
        {{  Form::radio('result','booking_id',false,array('id' => 'booking_id')) }}<span class="show-data">Booking Id</span>
      </label>
    </div>
  </div> -->
    
   
    <!-- <div class="input-group custom-search-form">
          <input type="hidden" value="" id="fromdate_def">
          <input type="hidden" value="" id="todate_def">
          <input type="date" class="form-control" name="fromdate" id="fromdate"  placeholder="fromdate" autocomplete="off">
          <input type="date" class="form-control" name="todate" id="todate" placeholder="todate" autocomplete="off">
          <input type="text" class="form-control" name="booking_id" id="booking_id" placeholder="Booking Id" autocomplete="off">
          <div class="input-group-append">
            <button class="btn btn-default-sm btn-color" type="submit" name="submit" value="submit">
                <i class="fa fa-search"></i>
            </button>
          </div>
      </div>  
      </div>
     
 

 


  </div>

</div> -->


<div class="">
  <div class="nav-link1">
  <ul class="nav nav-tabs">
    <li><a  data-toggle="tab" class="nav-link font-weight-bold head-links lead " href="#booking_date">Booking Date </a></li>
    <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#check_in_date">Check in Date </a></li>
    <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#check_out_date">Check out Date  </a></li>
    <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#booking_id">Booking Id </a></li>
  </ul>
</div>
  <div class="tab-content">
   <div id="booking_date" class="tab-pane  tab-panel1">
    <!-- <div class="col-xs-12 col-sm-12 col-md-12"> -->
    <form method="get" action="{{url('bookings')}}" accept-charset="UTF-8">
    	
    	<div class="row mt-4 col-md-12">
	      <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
        
		        <div class="form-group">
		          <strong>Select From Date :</strong>
              <input type = "date" id = "start_date_booking" class="form-control" name="start_date" autocomplete="off">
              <input type="hidden" value="1" name="search" id="search">
              <input type = "hidden" value = "{{$fromdate}}" id="start_date_old">
		        </div>
		    </div>
		    <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select To Date :</strong>
              <input type = "date" id = "end_date_booking" class="form-control" name="end_date" autocomplete="off">
              <input type = "hidden" value = "{{$todate}}" id="end_date_old">
              <!-- <button type="submit" name="submit" value="submit" class="btn btn-default-sm btn-color booking-search"><i class="fa fa-search"></i></button> -->
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 text-center mb-4">
		      <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
		    </div>
          </div>
       
        
    </div>
</form>
    
   <!-- </div> -->
 
   <div id="check_in_date" class="tab-pane tab-panel1">
   <form method="get" action="{{url('bookings')}}" accept-charset="UTF-8">
    	<div class="row mt-4 col-md-12">
	      <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select From Date :</strong>
              <input type = "date" id = "start_date_check_in" class="form-control" name="start_date" autocomplete="off">
              <input type="hidden" value="2" name="search" id="search">
		        </div>
		    </div>
		    <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select To Date :</strong>
		          <input type = "date" id = "end_date_check_in" class="form-control" name="end_date" autocomplete="off">
              <!-- <button type="submit" name="submit" value="submit" class="btn btn-default-sm btn-color booking-search"><i class="fa fa-search"></i></button> -->
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 text-center mb-4">
		      <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
		    </div>
        	</div>
  </div>
</form>
  <div id="check_out_date" class="tab-pane tab-panel1">
  <form method="get" action="{{url('bookings')}}" accept-charset="UTF-8">
    	<div class="row mt-4 col-md-12">
        <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select From Date :</strong>
              <input type = "date" id = "start_date_check_out" class="form-control" name="start_date" autocomplete="off">
              <input type="hidden" value="3" name ="search" id="search">
		        </div>
		    </div>
		    <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Select To Date :</strong>
		          <input type = "date" id = "end_date_check_out" class="form-control" name="end_date" autocomplete="off">
              <!-- <button type="submit" name="submit" value="submit" class="btn btn-default-sm btn-color booking-search"><i class="fa fa-search"></i></button> -->
            </div>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 text-center mb-4">
		      <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
		    </div>
        	</div>
  </div>
</form>
  <div id="booking_id" class="tab-pane tab-panel1">
  <form method="get" action="{{url('bookings')}}" accept-charset="UTF-8">
    	<div class="row mt-4 col-md-12">
        <div class="col-xs-5 col-sm-5 col-md-5 mt-2">
		        <div class="form-group">
		          <strong>Booking Id :</strong>
              <input type = "text" id = "booking_id" class="form-control" name="booking_id" autocomplete="off" value="{{$id}}">
              <input type="hidden" value="4" name="search" id="search">
		        </div>
		    </div>
		   
        <div class="col-xs-2 col-sm-2 col-md-2 text-center mb-4">
		      <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
		    </div>
        	</div>
  </div>
</form>
 </div>


@if ($message = Session::get('success'))
<div class="alert alert-success mt-1">
  <p>{{ $message }}</p>
</div>
@endif


<div class="over mt-4">
<table class="table table-striped table-dark index-table mt-2 mb-4">
 <tr>
   <th>#</th>  	 	 		 	 	 	 	


   <th>Hotel Name</th>
   <th>Booking ID</th>
   <th>Booking Date</th>
   <th>Check-in</th>
   <th>Check-out</th>
   <th>Net Payable To Hotel</th>
   <th>Guest Name</th>
   <th>User Voucher</th>
   <th>Hotel Voucher</th>
   </tr>
   @if($counter==0)
        <tr> <td colspan="18" style="color: rgb(245, 165, 34);text-align:center;padding:10px;font-size:16px;"> No Record Found</td> </tr>
      @else  
   @foreach ($booking as $booking_key => $booking_value)
   <?php
                $total_amt = $booking_value->extra_charge + $booking_value->total_amount;

                $total_amt_tax = $booking_value->extra_charge + $booking_value->base_amount;
                
                    if($total_amt_tax > 0 && $total_amt_tax < 1000)
                        {
                            $tax_str = 1;
                            $gst_tax = 0;
                        }
                        elseif ($total_amt_tax >= 1000 && $total_amt_tax < 2500) 
                        {
                            $tax_str = 2;
                            $gst_tax = 12;
                        }
                        elseif ($total_amt_tax >= 2500 && $total_amt_tax < 7500) 
                        {
                            $tax_str = 3;
                            $gst_tax = 18;
                        }
                        else
                        {
                            $tax_str = 4;
                            $gst_tax = 28;
                        }
                    
                $taxes = App\Tax::where(['hotel_id'=>$booking_value->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->first();
                $taxes_count = App\Tax::where(['hotel_id'=>$booking_value->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->count();
                
                        
                        if($taxes_count > 0)
                        {
                            if($taxes->service_text_title == 'Sell Rate')
                            {
                                $service_tax = ($total_amt * $taxes->service_text_value) / 100;
                            }
                            else if($taxes->service_text_title == 'Rack Rate')
                            {
                                $service_tax = round(($rack_tax * $taxes->service_text_value) / 100);
                
                                $service_tax = $service_tax * $booking_value->no_of_nights * $booking_value->no_of_rooms;
                            }
                            else
                            {
                                $service_tax = $taxes->service_text_value;
                            }
                
                
                
                            if($taxes->luxary_text_title == 'Sell Rate')
                            {
                                $luxary_tax = ($total_amt * $taxes->luxary_text_value) / 100;
                            }
                            else if($taxes->luxary_text_title == 'Rack Rate')
                            {
                                $luxary_tax = round(($rack_tax * $taxes->luxary_text_value) / 100);
                
                                $luxary_tax = $luxary_tax * $booking_value->no_of_nights * $booking_value->no_of_rooms;
                            }
                            else
                            {
                                $luxary_tax = $taxes->luxary_text_value;
                            }
                
                            if($taxes->other_text_title == 'Sell Rate')
                            {
                                $other_tax = ($total_amt * $taxes->other_tax_value) / 100;
                            }
                            else if($taxes->other_text_title == 'Rack Rate')
                            {
                                $other_tax = round(($rack_tax * $taxes->other_tax_value) / 100);
                
                                $other_tax = $other_tax * $booking_value->no_of_nights * $booking_value->no_of_rooms;
                            }
                            else
                            {
                                $other_tax = $taxes->other_tax_value;
                            }
                
                            $hotel_take_tax = $service_tax + $luxary_tax + $other_tax;
                        }
                        else
                        {
                            $hotel_take_tax = ($total_amt * $gst_tax) / 100;
                        }
                
                $gross = $total_amt + $hotel_take_tax;
                
                $tac = App\Commission::where('hotel_id',$booking_value->hotel_id)->first();
                $tac_count = App\Commission::where('hotel_id',$booking_value->hotel_id)->count();
                // dd($booking_value);
                if($tac_count > 0)
                {
                    $tac_c_percentage = $tac->commission - $tac->pbc;
                    $tac_value = ($total_amt * ($tac->commission - $tac->pbc) ) / 100 ;
                }
                else
                {       $tac_c_percentage = 10;
                        $tac_value = ($total_amt * 10) / 100 ;
                }
                
                $mstax =  App\Mstax::where('status',1)->first();
                $mstax_count =  App\Mstax::where('status',1)->count();
                
                if($mstax_count > 0)
                {
                    $tac_value_percentage = $mstax->tax;
                    $tac_tds_percentage = $tac->tds;
                    $tac_value_tax = ($tac_value * $mstax->tax) / 100 ;
                }
                else
                {
                    $tac_value_percentage = 10;
                    $tac_tds_percentage = $tac->tds;
                    $tac_value_tax = ($tac_value * 20) / 100 ;
                }
                
                $tds = ($tac_value * $tac->tds) / 100;
                
                $netpay = ($gross + $tds) - ($tac_value + $tac_value_tax );
                
               
            
            ?>
   <tr>
            <td> {{ $booking_key+1 }}  </td>
            <td> {{$booking_value->Hotel->title}} </td>
            <td> {{ $booking_value->booking_code}}  </td>
            <td> {{ Carbon\Carbon::parse($booking_value->created_at)->format('d-M-Y')}}  </td>
            <td> {{ Carbon\Carbon::parse($booking_value->check_in_time)->format('d-M-Y')}} </td>
            <td> {{ Carbon\Carbon::parse($booking_value->check_out_time)->format('d-M-Y')}}  </td>
            <td> {{round($netpay)}} </td>
            <td>{{$booking_value->full_name}}</td>
            <td><a href="{{ url('users/hotelvoucher') }}/{{$booking_value->id}}" target="blank"> User Voucher</a> </td>
            <td><a href="{{ url('hotels/hotelvoucher') }}/{{$booking_value->id}}" target="blank"> Hotel Voucher</a> </td>
            
          </tr> 
    @endforeach
    @endif

  
  </tr>
</table>
</div>

{{ $booking->appends(['start_date'=>$fromdate,'end_date'=>$todate,'search'=>$search])->links() }}
    

<style type="text/css">
	#image{
		width:200px;
	}
</style>
<script>
    jQuery(document).ready(function($){
      $('.fin-report').addClass('active-menu');
      $('.booking').addClass('active-sub-menu');
      var old_start_date = $('#start_date_old').val();
      var old_end_date = $('#end_date_old').val();
      var d = new Date();
      var current_date = d.getFullYear() + '-' + (((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + ('0' + d.getDate()).slice(-2);
      d.setDate(d.getDate() - 15);
      var last_date = d.getFullYear() + '-' + (((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1)) + '-' + ('0' + d.getDate()).slice(-2);
      $('#start_date_booking').val(last_date);
      $('#end_date_booking').val(current_date);
      $('#start_date_check_in').val(last_date);
      $('#end_date_check_in').val(current_date);
      $('#start_date_check_out').val(last_date);
      $('#end_date_check_out').val(current_date);
      
      if(old_start_date != '')
      {
        console.log(old_start_date);
        $('#start_date_booking').val(old_start_date);
        $('#start_date_check_in').val(old_start_date);
        $('#start_date_check_out').val(old_start_date);
      }
      if(old_end_date != '')
      {
        $('#end_date_booking').val(old_end_date);
        $('#end_date_check_in').val(old_end_date);
        $('#end_date_check_out').val(old_end_date);
      }
    });
    
</script>


@endsection

 	 	 	 	 	 	