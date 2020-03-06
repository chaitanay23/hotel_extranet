<?php



$booking_details = App\Model\Booking::find($id);



$full_name = $booking_details->full_name;



$bank_detail = App\Model\Bankdetail::where('hotel_id',$booking_details->hotel_id)->first();
// dd($bank_detail);


$hotel_voucher = App\Model\Hotel::find($booking_details->hotel_id); 

$hotel_contact = App\Model\Contact::where('hotel_id',$booking_details->hotel_id)->first();



$room_info = App\Model\Hoteldetail::find($booking_details->hoteldetail_id);


//*** meal plan change by chaitanay ***//

$order_detail = App\Order::find($booking_details->order_id);
$meal_plan = $order_detail?$order_detail->meal_plan:'';

$ms_policy = App\Model\Cancellationpolicy::first();



$coupon_name = explode(',', $booking_details->select_coupons);



$coupons_quentity = explode(',', $booking_details->coupons_quentity);







$total_amt = $booking_details->extra_charge + $booking_details->total_amount;



$total_amt_tax = $booking_details->extra_charge + $booking_details->base_amount;



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

    

$taxes = App\Model\Tax::where(['hotel_id'=>$booking_details->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->first();
$tax_count=App\Model\Tax::where(['hotel_id'=>$booking_details->hotel_id,'status'=>1,'gsttaxes_id'=>$tax_str])->count();


        // if(count($taxes) > 0)

        // {

        //     if($taxes->service_text_title == 'Sell Rate' || $taxes->service_text_title == 'Rack Rate')

        //     {

        //         $service_tax = ($total_amt * $taxes->service_text_value) / 100;

        //     }

        //     else

        //     {

        //         $service_tax = $taxes->service_text_value;

        //     }







        //     if($taxes->luxary_text_title == 'Sell Rate' || $taxes->luxary_text_title == 'Rack Rate')

        //     {

        //         $luxary_tax = ($total_amt * $taxes->luxary_text_value) / 100;

        //     }

        //     else

        //     {

        //         $luxary_tax = $taxes->luxary_text_value;

        //     }



        //     if($taxes->other_text_title == 'Sell Rate' || $taxes->other_text_title == 'Rack Rate')

        //     {

        //         $other_tax = ($total_amt * $taxes->other_tax_value) / 100;

        //     }

        //     else

        //     {

        //         $other_tax = $taxes->other_tax_value;

        //     }



        //     $hotel_take_tax = $service_tax + $luxary_tax + $other_tax;

        // }

        // else

        // {

        //     $hotel_take_tax = ($total_amt * 20) / 100;

        // }





        if($tax_count > 0)

        {

            if($taxes->service_text_title == 'Sell Rate')

            {

                $service_tax = ($total_amt * $taxes->service_text_value) / 100;

            }

            else if($taxes->service_text_title == 'Rack Rate')

            {

                $service_tax = round(($rack_tax * $taxes->service_text_value) / 100);



                $service_tax = $service_tax * $booking_details->no_of_nights * $booking_details->no_of_rooms;

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



                $luxary_tax = $luxary_tax * $booking_details->no_of_nights * $booking_details->no_of_rooms;

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



                $other_tax = $other_tax * $booking_details->no_of_nights * $booking_details->no_of_rooms;

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



$tac = App\Model\Commission::where('hotel_id',$booking_details->hotel_id)->first();
$tac_count = App\Model\Commission::where('hotel_id',$booking_details->hotel_id)->count();
if($tac_count > 0)

{

    $tac_c_percentage = $tac->commission - $tac->pbc;

    $tac_value = ($total_amt * ($tac->commission - $tac->pbc) ) / 100 ;

}

else

{       $tac_c_percentage = 10;

        $tac_value = ($total_amt * 10) / 100 ;

}



$mstax =  App\Model\Mstax::where('status',1)->first();
$mstax_count =  App\Model\Mstax::where('status',1)->count();


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

    <div style="background:#eaeaea; width:100%; padding:10px 0;" >      

        <div style="width:100%; max-width:790px; margin:0 auto; float:none; border:solid 1px #ab1500; font-family:Arial, Helvetica, sans-serif; background: #fff">        

            <div style="width:100%; float:left; padding:10px; box-sizing:border-box; ">

                <div style="float:left; width:40%; min-width:300px;"><a href="{{ url('/home') }}"><img src="{{asset('assets/images/logo.png')}}"></a></div>

                <div style="width:60%; float:left; box-sizing:border-box; border-right:solid 40px #ab1500;">    

                   <p style="box-sizing:border-box; font-size:24px; float:left; width:100%; text-align:right; padding:10px 10px 0 0; margin:0;"><b>Hotel Booking Voucher</b></p>

                   <p style="box-sizing:border-box; font-size:18px; float:left; width:100%; text-align:right; padding:3px 10px 0px 0; margin:0;">Booking ID: {{ $booking_details->booking_code }}</p>

                   <p style="box-sizing:border-box; font-size:18px; float:left; width:100%; text-align:right; padding:3px 10px 12px 0; margin:0;">Booking Date: {{ Carbon\Carbon::parse($booking_details->created_at)->format('D jS \\of M Y h:i A') }}</p>                   

            </div>

           </div>

                       

            

            <div style="width:100%; padding:10px; box-sizing:border-box; float:left; border-top:solid 1px #ab1500; margin-top:1px;">           

           <p style="font-size:15px; float:left; width:100%; margin:0 0 0 0; padding:0;">Dear Hotel Partner,</p>

            <p style="font-size:15px; float:left; margin:15px 0 0 0; width:100%;">Biddr.in has received a booking for your hotel with details below. The primary traveller <b>{{ $full_name }}</b> shall carry a copy of the e-voucher. Booking ID for this booking is : <b>{{ $booking_details->booking_code }}</b></p>

            <p style="font-size:15px; float:left; margin:15px 0 0 0; width:100%;">Please provide the guest with the following inclusions and Add on deals as mentioned in this e-voucher</p>

              <p style="font-size:15px; float:left; margin:15px 0 0 0; width:100%;">Best, <br />Team Biddr.in</p>

            </div>

            

            <div style="width:100%; padding:10px; box-sizing:border-box; float:left; border-top:solid 1px #ab1500; margin-top:1px;">

                <h2 style="font-size:22px; float:left; width:100%; margin:5px 0 0px 0; padding:0"><b>Booking Details</b></h2>

                <div style="width:100%; float:left; padding:5px 0; margin:0 0 15px 0; border-left:solid 2px #ab1500">

                    <p style="width:98%; font-size:15px; float:left; margin:0 0 5px 1%;"><b>Primary Guest Name:</b> {{ $full_name }}</p>

                    <p style="width:98%; font-size:15px; float:left; margin:0 0 5px 1%;"><b>Room Type:</b> {{ $room_info->custom_category }}</p>

                    <p style="width:98%; font-size:15px; float:left; margin:0 0 5px 1%;"><b>Meal Plan:</b>  @if($meal_plan == 'ep') Room Only @else Includes Breakfast @endif</p>

                   

                    <p style="width:98%; font-size:15px; float:left; margin:0 0 0px 1%;"><b>Inclusions:</b> @if($meal_plan == 'ep') Room Only, @else Includes Breakfast, @endif

                            @if(count($room_info->roominclusions) > 0)

                                @foreach($room_info->roominclusions as $attribute_value)

                                    {{ $attribute_value->name }},

                                @endforeach

                            @endif</p>

                </div>

                

                <div style="width:100%; float:left; margin:10px 0 1px 0;">

                    <div style="width:100%; float:left; box-sizing:border-box;">

                        <div style="width:50%; float:left; margin:0 0 0 0; box-sizing:border-box; font-size:14px">

                            <h2 style="font-size:22px; float:left; width:100%; margin:5px 0 10px 0; padding:0"><b>Hotels Details</b></h2>

                            <div style="width:100%; float:left; padding:5px 5px; box-sizing:border-box; margin:0 0 15px 0; border-left:solid 2px #ab1500">                        

                                <p style="width:100%; float:left; margin:0; padding:0px 0;">{{ strtoupper($hotel_voucher->title) }}</p>

                                <div style="width:100%; float:left; margin:2px 0 5px 0;">

                                @if($hotel_voucher->rating == 5)
                                 <img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star">
                                @endif
                                @if($hotel_voucher->rating == 4)
                                <img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star">
                                @endif
                                @if($hotel_voucher->rating == 3)
                                <img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star">
                                @endif
                                @if($hotel_voucher->rating == 2)
                                <img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star">
                                @endif
                                @if($hotel_voucher->rating == 1)
                                <img src="{{asset('assets/images/color_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star">
                                @endif
                                @if($hotel_voucher->rating == 0)
                                <img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star"><img src="{{asset('assets/images/gray_star.jpg')}}" alt="Star">
                                @endif

                                </div>

                                <p style="float:left; width:100%; padding:0 0 5px 0; margin:0;">{{ $hotel_voucher->address }}</p>

                            </div>

                        </div>

                        <div style="width:50%; float:left; margin:0 0 0 0; box-sizing:border-box;">

                            <h2 style="font-size:22px; float:left; width:100%; margin:5px 0 10px 0; padding:0"><b>Contact Details</b></h2>

                            <div style="width:100%; float:left; padding:5px 5px; box-sizing:border-box; margin:0 0 15px 0; border-left:solid 2px #ab1500">

                                <p style="float:left; font-size:13px; width:100%; padding:5px 0 5px 0; margin:0;">

                            <span style="width:auto; float:left; padding:0px 0 0 0;"><img src="{{asset('assets/images/icons/mobile.png')}}" /> </span>

                            <span style="width:auto; float:left; padding:2px 0 0 0;">&nbsp;{{ $booking_details->contact }}</span>

                        </p>

                                <p style="float:left; font-size:13px; width:100%; padding:0 0 5px 0; margin:0;">

                                <span style="width:auto; float:left; padding:0px 0 0 0;"><img src="{{asset('assets/images/icons/mail.png')}}" /></span>

                                <a href="#" style="color:#000; text-decoration:none; padding:0px 0 0 2px; float:left">{{ $booking_details->email }}</a>

                        </p>              

                            </div>          

                        </div>

                    </div>

                    <div style="width:50%; float:left; box-sizing:border-box;"></div>

                </div>

                                

                <div style="width:100%; float:left; margin:0 0 0px 0">

                    <h2 style="font-size:22px; float:left; width:100%; margin:5px 0 2px 0; padding:0">

                        <b>ADD On Services</b>

                    </h2>
                    @if(count(@$coupon_name) > 0 && $coupon_name[0] != '') 
                    <table style="width:100%; text-align:center;">



                        <tr style="font-size:15px; color:#fff; background:#ab1500">

                            <th style="padding:7px 10px;">Coupons</th>

                            <th style="padding:7px 10px;">Quantity</th>

                            <th style="padding:7px 10px;">Discount</th>

                        </tr>

                         



                @for($i = 0; $i <count($coupon_name); $i++)

                                <tr>

<?php $coupon_code = App\Model\Coupon::where(['couponcategory_id'=>$coupon_name[$i],'hotel_id'=>$booking_details->hotel_id])->first(); 

      $coupon_cat = App\Model\Couponcategory::find($coupon_code->couponcategory_id);  



?>                   

            <tr style="font-size:14px; background:#f2ecec;">

                <td style="padding:7px 10px;">{{ $coupon_cat->title }}</td>

                <td style="padding:7px 10px;">{{ $coupons_quentity[$i] }}</td>

                <td style="padding:7px 10px">@if($coupon_code->dis_type == 1) {{ $coupon_code->discount }}% @else Rs. {{ $coupon_code->flat_dis }} @endif</td>

            </tr>

            @endfor
            @else
				No AddOn Purchased
            @endif                     

                    </table>                    

                </div>

                

            </div>

            

            

            <div style="width:100%; float:left; margin:10px 0 0 0; padding:0 10px; box-sizing:border-box;">

            <h2 style="font-size:20px; float:left; margin:0 0 0; width:100%;">

                <b>Payment Details </b> <span style="font-size:11px; font-weight:normal; margin:0;">All figures in INR</span>

            </h2>

                <table style="width:100%; float:left; margin:10px 0 0px; text-align:center">

                    <tr style="background:#ab1500; color:#fff; font-size:15px; font-weight:normal;">

                        <th style="padding:10px 0">  </th>

                        <th style="padding:10px 0">Particulars</th>

                        <th style="padding:10px 0">Amount</th>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle"> </td>

                        <td style="padding:5px 0">Base Price </td>

                        <td style="padding:5px 0">{{ $booking_details->total_amount }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">+</td>

                        <td style="padding:5px 0">Additional Charges</td>

                        <td style="padding:5px 0">{{ $booking_details->extra_charge }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">+</td>

                        <td style="padding:5px 0">Total Tax (GST + Other tax)</td>

                        <td style="padding:5px 0">{{ round($hotel_take_tax) }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle"></td>

                        <td style="padding:5px 0">Gross Total</td>

                        <td style="padding:5px 0">{{ round($gross) }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">-</td>

                        <td style="padding:5px 0">TAC ({{ $tac_c_percentage }}%)</td>

                        <td style="padding:5px 0">{{ $tac_value }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">-</td>

                        <td style="padding:5px 0">GST on TAC ({{ $tac_value_percentage }}%) </td>

                        <td style="padding:5px 0">{{ $tac_value_tax }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">+</td>

                        <td style="padding:5px 0">TDS On TAC ({{ $tac_tds_percentage }}%)</td>

                        <td style="padding:5px 0">{{ $tds }}</td>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle"></td>

                        <td style="padding:5px 0; font-size:22px; font-weight:bold">Net Payable To Hotel</td>

                        <td style="padding:5px 0">{{ round($netpay) }}</td>

                    </tr>

                </table>

                <p style="float:left; width:100%; font-size:12px; margin:0; padding:0 0 20px 0;">In case of any discrepancy kindly write in to <a href="#" style="color:#FF0000;">accounts@Biddr.in</a> and we will be happy to help</p>

            </div>

            

            

            

            <div style="width:100%; display:inline-block; margin:10px 0 0 0; padding:10px; box-sizing:border-box; border-top:solid 1px #ab1500;">

            <h2 style="font-size:20px; float:left; margin:10px 0 0; width:100%;"><b>Check In / Check Out Details</b></h2>

                <table style="width:100%; float:left; margin:10px 0 0; text-align:center">

                    <tr style="background:#ab1500; color:#fff; font-size:14px; font-weight:normal;">

                        <th style="padding:10px 0">Room </th>

                        <th style="padding:10px 0">Check In </th>

                        <th style="padding:10px 0">Check Out </th>

                        <th style="padding:10px 0">Nights </th>

                        <th style="padding:10px 0">Travellers </th>

                    </tr>

                    <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">Room-1</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;"> {{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">{{ $booking_details->no_of_nights }}</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->no_of_adult }}</span> Adults

                            </div>

                            <div style="padding:4px 0px 0 0;display:none;">  

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->no_of_children + $booking_details->child_small }}</span> Children

                            </div>

                        </td>

                    </tr>



                    @if($booking_details->no_of_rooms > 1)

                        <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">Room-2</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;"> {{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">{{ $booking_details->no_of_nights }}</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->adult_one }}</span> Adults

                            </div>

                            <div style="padding:4px 0px 0 0;display:none;">  

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->childbig_one + $booking_details->childsmall_one }}</span> Children

                            </div>

                        </td>

                    </tr>

                    @endif



                    @if($booking_details->no_of_rooms > 2)

                        <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">Room-3</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;"> {{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">{{ $booking_details->no_of_nights }}</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->adult_two }}</span> Adults

                            </div>

                            <div style="padding:4px 0px 0 0;display:none;">  

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->childbig_two + $booking_details->childsmall_two }}</span> Children

                            </div>

                        </td>

                    </tr>

                    @endif



                    @if($booking_details->no_of_rooms > 3)

                        <tr style="color:#000; background:#f2ecec; font-size:13px; font-weight:normal;">

                        <td style="padding:5px 0" valign="middle">Room-4</td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;"> {{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}</div>

                            <div style="padding:4px 0 0;">{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</div>

                        </td>

                        <td style="padding:5px 0">{{ $booking_details->no_of_nights }}</td>

                         <td style="padding:5px 0">

                            <div style="border-bottom:solid 1px #fff; padding:0px 0 4px;">

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->adult_three }}</span> Adults

                            </div>

                            <div style="padding:4px 0px 0 0;display:none;">  

                                <span style="padding:0 10px 0 0; font-weight:bold;">{{ $booking_details->childbig_three + $booking_details->childsmall_three }}</span> Children

                            </div>

                        </td>

                    </tr>

                    @endif

                </table>
                <table width="100%"><tr style="background:#f2ecec;">
                @if($room_info->extra_adult_cost_collection==0)
                <td style="padding:10px 0px; text-align: center;"><p align="left" style="padding: 0 10px;margin: 0;color:#ff5722;"><img src="{{asset('assets/images/warningicon.svg')}}" style="width: 4%; margin:-7px 5px;  ">*Extra Adult charges payable at hotel</p></td></tr>
                @endif
                </table>

            </div> 

            @if($bank_detail)

              @if($bank_detail->payment_id == 2)



            <?php

                $cc_info = App\Model\Creditcard::where('status',1)->first();
                $cc_count = App\Model\Creditcard::where('status',1)->count();
// dd($cc_info);
            ?>

            <div style="width:100%; padding:10px; box-sizing:border-box; display:inline-block; border-top:solid 1px #ab1500; margin-top:1px;">           

          <h2 style="font-size:22px; float:left; width:100%; margin:5px 0 0px 0; padding:0"><b>Credit Card Payment Authorization</b></h2>

            <p style="font-size:15px; float:left; margin:15px 0 10px 0; width:100%;">I,<b>{{ $cc_info->nameofcard }}</b>,hereby authorize <b>{{ strtoupper($hotel_voucher->title) }}</b>, to charge the below mentioned credit card against hotel booking ID <b> {{ $booking_details->booking_code }}</b> made for a net payable amount not exceeding <b>Rs. {{ $netpay }}&nbsp;/-. :</b></p>

                        <p style="font-size:15px; float:left; margin:5px 0 0 0; width:100%;"><b>Name :- {{ $cc_info->nameofcard }}</b></p>

                        <p style="font-size:15px; float:left; margin:5px 0 10px 0; width:100%;"><b>Address :- {{ $cc_info->address }}</b></p>

            @if($cc_count > 0)

		<div style="width:100%; float:left;">

<img src="{{asset($cc_info->front)}}" width="245px" height="180" style="float: left;margin: 0 10px 0 0;">

<img src="{{asset($cc_info->bcard)}}" width="245px" height="180" style="float: left;margin: 0 10px 0 0;">

 <img src="{{asset($cc_info->back)}}" width="245px" height="180" style="float: right;"><span style="float: right;position: relative;z-index: 99;width: auto;margin: 125px -205px 0 11px;font-weight: bold;">Authorized Signatory</span>

 </div>

            @endif

              <p style="float:left; width:100%; font-size:10px; margin:0; padding:10px 0 20px 0;">The hotel is not authorized to share details/disclose details of the mentioned credit card to anyone under any circumstance or charge any amount other than that authorized by the company. Any such act may aTract suitable puniPve acPon as governed by the law of India.</p>

            </div>  

             @endif

        @endif

        </div>

        </div>



