<?php
// $booking_info =218;
$booking_details = App\Booking::find($id);
$hotel_voucher = App\hotel::find($booking_details->hotel_id); 
$hotel_contact = App\Contact::where('hotel_id',$booking_details->hotel_id)->first();
$full_name = $booking_details->full_name;
$get_path=url('');
$room_info = App\Hoteldetail::find($booking_details->hoteldetail_id);
$q=0;
// $meal_plan = App\Model\Dailyinventory::where('category_id',$booking_details->hoteldetail_id)->first();
$order_detail = App\Order::find($booking_details->order_id);
$meal_plan = $order_detail?$order_detail->meal_plan:'';


$coupon_name = array();
$coupons_quentity = array();
$ms_policy = App\Model\Cancellationpolicy::first();

$coupon_name = explode(',', $booking_details->select_coupons);

$image=preg_replace('/\s+/','%20',$hotel_voucher->picture);
$coupons_quentity = explode(',', $booking_details->coupons_quentity);

 ?>
<table width="1000" align="center" style="border:1px solid #ff5722">
            <tr>
                <td class="wrapper">
                    <table>
                        <tr>
                            <td>
                                <table width="1000px">
                                    <tr>
                                        <td align="left" width="20%">
                                            <a target="_blank" href="#">
                                                <img src="{{ url('')}}/assets/images/logo.png" alt="Biddr" title="Biddr" align="left" width="100%">
                                            </a>
                                        </td>
                                        <td align="right">
                                           <a href="mailto:care@biddr.in">Care@biddr.in</a>
                                           <p>Booking ID: {{ $booking_details->booking_code}}</p>
										   <p>{{ Carbon\Carbon::parse($booking_details->created_at)->format('l jS \\of F Y h:i A') }}</p>
                                           @if(!empty($yatraPNR))
										   <p>Hotel PNR :{{ $yatraPNR }}</p>
										   @endif
                                           <p>GSTIN : 09AAFCT5972Q1ZC</p>
                                        </td>

                                    </tr>
                                   </table>
                                   <table width="1000px">
                                    <tr>
                                        <h2><b>Booking Confirmed!!</b></h2>
                                        <p>Dear {{ $full_name }}</p>
                                        <p>Thank for using biddr!</p>
                                        <p>We have received your payment of <b>Rs {{ $booking_details->total_pay_amount }}</b></p>
                                        <p>Your hotel booking is confirmed. Please find your Voucher attached with this eMail. Details of your booking are enclosed below.</p>
                                        <p>We hope you have a pleasant stay and look forward to serving you again soon!</p>
                                        <p>Best</p>
                                        <p>Team biddr</p>
                                    </tr>
                                   </table>
                                   <table width="1000px" style="margin-bottom: 20px;">
                                       <hr>
                                    <tr>
                                        <h2><b>Booking Details</b></h2>
                                        <tr>
                                            <td align="left" width="50%">
											<img alt="{{ $hotel_voucher->title }}" src="{{url('').((substr($hotel_voucher->picture,0,1)=='/')?'':'/').$image }}" style=" width:100%;">
                                               <p> {{ strtoupper($hotel_voucher->title) }}<br>{{ $hotel_voucher->address }}</p>
                                               <p>
											   @if($hotel_voucher->rating == 5)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												@endif
												@if($hotel_voucher->rating == 4)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												@endif
												@if($hotel_voucher->rating == 3)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												@endif
												@if($hotel_voucher->rating == 2)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												@endif
												@if($hotel_voucher->rating == 1)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/color_star.png' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												@endif
												@if($hotel_voucher->rating == 0)
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												<span><img width="15px" src="{{ $get_path}}{{'/assets/images/gray_star.jpg' }}" alt="Star"></span>
												@endif</p>
                                            </td>
                                            <td align="right" style="border-left: 1px solid #000;">
                                                <p> <img alt="phone-image" src="{{ url('')}}/assets/images/phone_icon.png"
                                                    style="width: 15px;"> {{ $hotel_contact->phone_no }}</p>
                                               <p><img alt="email-image" src="{{url('')}}/assets/images/mail.png"
                                                style="width: 15px;"> {{ $hotel_contact->email }}</p>
                                            </td>
    
                                        </tr>
                                        
                                    </tr>
                                   </table>
                                   <table width="1000px">
                                      <tr style="background:#ff5722;">
								<th style="color:#fff; padding:10px 0px; ">Room</th>
								<th style="color:#fff; padding:10px 0px; ">Room Type</th>
								<th style="color:#fff; padding:10px 0px; ">Plan</th>
								<th style="color:#fff; padding:10px 0px">Check In</th>
								<th style="color:#fff; padding:10px 0px">Check Out</th>
								<th style="color:#fff; padding:10px 0px">Nights</th>
								<th style="color:#fff; padding:10px 0px">Adults</th>
								<th style="color:#fff; padding:10px 0px; ">Infant<br>(0-5 yrs.)</th>
                                <th style="color:#fff; padding:10px 0px; ">Child<br>(6-12 yrs.)</th>
                            </tr> 
                            <tr style="background:#f2ecec;">
								<td style="padding:10px 0px; text-align: center;">Room-1</td>
								<td style="padding:10px 0px; text-align: center;">{{$room_info->custom_category}}</td>
								<td style="padding:10px 0px; text-align: center;">
									@if($meal_plan == 'ep') Without Breakfast @else Includes Breakfast @endif
								</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }} <br>{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}<br>{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->no_of_nights }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->no_of_adult }}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->child_small}}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->no_of_children}}</td>
							</tr>
							@if($booking_details->no_of_rooms > 1)
							<tr style="background:#f2ecec;">
								<td style="padding:10px 0px; text-align: center;">Room-2</td>
								<td style="padding:10px 0px; text-align: center;">{{$room_info->custom_category}}</td>
								<td style="padding:10px 0px; text-align: center;">
								@if($meal_plan == 'ep') Without Breakfast @else Includes Breakfast @endif
								</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }} <br>{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}<br>{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->no_of_nights }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->adult_one }}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childsmall_one}}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childbig_one}}</td>
							</tr
							@endif
							@if($booking_details->no_of_rooms > 2)
							<tr style="background:#f2ecec;">
								<td style="padding:10px 0px; text-align: center;">Room-3</td>
								<td style="padding:10px 0px; text-align: center;">{{$room_info->custom_category}}</td>
								<td style="padding:10px 0px; text-align: center;">
								@if($meal_plan == 'ep') Without Breakfast @else Includes Breakfast @endif
								</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }} <br>{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}<br>{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->no_of_nights }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->adult_two }}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childsmall_two}}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childbig_two}}</td>
							</tr
							@endif
							@if($booking_details->no_of_rooms > 3)
							<tr style="background:#f2ecec;">
								<td style="padding:10px 0px; text-align: center;">Room-4</td>
								<td style="padding:10px 0px; text-align: center;">{{$room_info->custom_category}}</td>
								<td style="padding:10px 0px; text-align: center;">
								@if($meal_plan == 'ep') Without Breakfast @else Includes Breakfast @endif
								</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_in_time)->format('M d, Y') }} <br>{{ Carbon\Carbon::parse($hotel_voucher->check_in_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ Carbon\Carbon::parse($booking_details->check_out_time)->format('M d, Y') }}<br>{{ Carbon\Carbon::parse($hotel_voucher->check_out_time)->format('h A') }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->no_of_nights }}</td>
								<td style="padding:10px 0px; text-align: center;">{{ $booking_details->adult_three }}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childsmall_three}}</td>
                                <td style="padding:10px 0px; text-align: center;">{{$booking_details->childbig_three}}</td>
							</tr
							@endif
                                   </table>
								   <table width="100%"><tr style="background:#f2ecec;">
									@if($room_info->extra_adult_cost_collection==0)
									<td style="padding:10px 0px; text-align: center;"><p align="left" style="padding: 0 10px;margin: 0;color:#ff5722;"><img src="{{asset('assets/images/warningicon.svg')}}" style="width: 4%; margin:-16px 5px;  ">*Extra Adult charges payable at hotel</p></td></tr>
									@endif
									</table>
                                   <table width="1000px">
								   @if(count(@$coupon_name) > 0 && $coupon_name[0] != '')
                                    <tr style="background:#ff5722;">
                              <th style="color:#fff; padding:10px 0px; ">Sr. No.</th>
                              <th style="color:#fff; padding:10px 0px; ">Coupons Name</th>
                              <th style="color:#fff; padding:10px 0px; ">Coupons Quantity</th>
                              <th style="color:#fff; padding:10px 0px">Coupons Discount</th>
                             </tr> 
							 @for($i = 0; $i <count($coupon_name); $i++)
								@if($coupons_quentity[$i]>0)
												<tr style="background:#f2ecec;">
												<?php
								$coupon_code = App\Model\Coupon::where(['couponcategory_id'=>$coupon_name[$i],'hotel_id'=>$booking_details->hotel_id])->first();				
								$coupon_cat = App\Model\Couponcategory::find($coupon_code->couponcategory_id);

								?>
                              <td style="padding:10px 0px; text-align: center;">{{ ++$q }}</td>
                              <td style="padding:10px 0px; text-align: center;">{{ $coupon_cat->title }}</td>
                              <td style="padding:10px 0px; text-align: center;">{{ $coupons_quentity[$i] }}</td>
                              <td style="padding:10px 0px; text-align: center;">@if($coupon_code->dis_type == 1) {{ $coupon_code['discount'] }}% @else Rs. {{ $coupon_code['flat_dis'] }} @endif</td>
                             </tr>
							 @endif
							@endfor
							@else
							<p>No addon Purchased</p>
							@endif
                                 </table>
                            </td>
                          </tr>
                           <table width="1000px">
                        <tr>
                            <td>
                        <h2><b>Room Inclusions</b></h2>
                        <ul>
						@if($meal_plan == 'ep') <li>Room Only</li> @else <li>Includes Breakfast</li> @endif
						@if(count($room_info->roominclusions) > 0)
							@foreach($room_info->roominclusions as $attribute_value)
								<li>{{ $attribute_value->name }}</li>
							@endforeach
						@endif
                        </ul><br>
                        <h2><b>Hotel Policy</b></h2>
                        <p style="font-size: 15px; padding:0 5px; text-align: justify;">@if($ms_policy)
							<?php $htexts = preg_split("/[\n]+/", $ms_policy->htext);?>
							@foreach ($htexts as $htext)
						{{$htext}}
						<br/>
						@endforeach
						@endif</p>
                            <br>
                            <h2><b>Cancellation Policy</b></h2>
                            <p style="font-size: 15px; padding:0px 5px; text-align: justify; margin-bottom: 10px;">@if($ms_policy)
							<?php $ctexts=preg_split("/[\n]+/", $ms_policy->ctext);?>
							@foreach ($ctexts as $ctext)
							{{$ctext}}
							<br/>
							@endforeach
							@endif</p>
                                <h2><b>Need Help?</b></h2>
                                <p>For any queries regarding the
                                    hotel property please contact {{ strtoupper($hotel_voucher->title) }} at {{ $hotel_contact->phone_no }} or {{ $hotel_contact->email }}</p>
                                    <p><a href="mailto:care@biddr.in" style="color:#ab1500 ; text-decoration:none;">Click Here</a> to share your concerns with Biddr team and we will be happy to help you.</p>
                                    </td>
                                    </tr>
                    </table>
                    </table>
                   </td>
            </tr>
        </table>