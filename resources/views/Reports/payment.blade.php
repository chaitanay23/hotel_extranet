@extends('layouts.app')


@section('content')

<div class="row mt-3 mb-2">
  <div class="col-sm-12">
        <h2 class="heder">Hotel Payment</h2>
    </div>
  <!-- <div class="col-sm-6 margin-tb">
      <div class="pull-right">
        @can('hotel_create')
          <a class="btn btn-success" href="{{ route('hotel.create') }}"> Create New Hotel</a>
        @endcan
      </div> -->

  </div>
   <!-- <div class="col-sm-6"> 
    
    
   <form method="get" action="{{url('payment')}}" accept-charset="UTF-8">
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
<form method="get" action="{{url('payment')}}" accept-charset="UTF-8">
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
</div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success mt-1">
  <p>{{ $message }}</p>
</div>
@endif

<div class="over">
<table class="table table-striped table-dark index-table payment-table mt-2">
 <tr>
 <tr>
    <th rowspan="2">#</th>
    <th rowspan="2">Booking ID</th>
    <th rowspan="2">Booking Date</th>
    <th rowspan="2">Hotel Name</th>
    <th rowspan="2">Payment Method</th>
    <th colspan="5">Payable To Hotel</th>
    <th rowspan="2">Payment Status</th>
    <th rowspan="2">Payment Due Date</th>
    <th rowspan="2">Payment Delay</th>
    <th rowspan="2">Reason For Delay</th>
    <th rowspan="2">Payment Date</th>
    <th rowspan="2">Payment Details</th>
    <th rowspan="2">Hotel Invoice</th>
  </tr>
  <tr>
    <th>Tariff</th>
    <th>GST</th>
    <th>Commission</th>
    <th>GST On Commission</th>
    <th>Net Payable To Hotel</th>
    
  </tr>
   @if($counter==0)
        <tr> <td colspan="12" style="color: rgb(245, 165, 34);text-align:center;padding:10px;font-size:16px;"> No Record Found</td> </tr>
      @else  
      @foreach ($booking as $booking_key => $booking_value) 
        @php
          $hotel_info = App\Bankdetail::where('hotel_id',$booking_value->hotel_id)->first();
            $tac_value_tax = ($booking_value->ms_commission * $mstax->tax) / 100 ;
            $tac_value_add = round($tac_value_tax + $booking_value->ms_taxes);
         
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
                    
                $taxes_count = App\Tax::where(['hotel_id'=>$booking_value->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->count();
                $taxes = App\Tax::where(['hotel_id'=>$booking_value->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->first();
                
                        
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

          $payment_info = App\Payment::where('booking_id',$booking_value->id)->first();
          $payment_info_count = App\Payment::where('booking_id',$booking_value->id)->count();


          if($payment_info_count)
          {
            if($payment_info->status)
            {
              $status = 1;
            }
            else
            {
              $status = 0;
            }
          }
          else
          {
            $status = 0;
          }

          $b_date = Carbon\Carbon::parse($booking_value->created_at)->format('d/m/Y');
          $d_date = Carbon\Carbon::parse($booking_value->created_at)->addDays(3)->format('d/m/Y');
          $c_date = Carbon\Carbon::now();

          $diff = Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($booking_value->created_at)->addDays(3));

        @endphp
        <tr>
          <td> {{ $booking_key+1}}  </td>
          <td> {{ $booking_value->booking_code}}  </td>
          <td>{{ $b_date }}</td>
          <td> {{ $booking_value->title}}</td>
          <td>@if($hotel_info!='') @if($hotel_info->payment_id == 1) NEFT @else Credit Card @endif @else -- @endif  </td>
          <td> {{ $booking_value->total_amount}}  </td>
          <td> {{ round($hotel_take_tax) }}  </td>
          <td> {{ $booking_value->ms_commission }}  </td>
          <td> {{ $tac_value_add }} </td>
          <td> {{ round($netpay) }} </td>
          @if($status)
            <td><span style="color:green">Paid</span></td>
            <td><span style="color:green">{{ $d_date }}</span></td>
            <td><span style="color:green">--</span></td>
            <td><span style="color:green">--</span></td>
            
            <td><span style="color:green">{{Carbon\Carbon::parse($payment_info->date)->format('d/m/Y')}}</span></td>
          @else
            <td><span style="color:red">Pending</span></td>
            <td><span style="color:red">{{ $d_date }}</span></td>
            <td><span style="color:red">{{$diff}} days</span></td>
            @if($payment_info_count > 0)
              <td>  @if($payment_info->reason == 1)
                       Bank Details Incorrect
                    @elseif($payment_info->reason == 2)
                        GSTN Not Updated
                    @elseif($payment_info->reason == 3)
                        Confirmation from hotel pending
                    @elseif($payment_info->reason == 4)
                        Hotel Invoice Awaited
                    @elseif($payment_info->reason == 5)
                        Others
                    @endif
                    
                    @if($payment_info->reason == 5) <br/> {{ $payment_info->r_txt }} @endif 
              </td>
              @else
                <td><span style="color:red">--</span></td>
            @endif
            <td><span style="color:red">--</span></td>
          @endif
          
          
          <td> --</td>
          <td> -- </td>
        </tr> 
      @endforeach

    @endif

    
     
 </tr>
 
</table>
        </div>
{{ $booking->links() }}

    

<style type="text/css">
	#image{
		width:200px;
	}
</style>
<script>
    jQuery(document).ready(function($){
      $('.fin-report').addClass('active-menu');
      $('.payment').addClass('active-sub-menu');
      var old_start_date = $('#start_date_old').val();
      var old_end_date = $('#end_date_old').val();
      var d = new Date();
      var current_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
      d.setDate(d.getDate() -30);
      var last_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
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