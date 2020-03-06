<body>

 <?php 
 
 
 $booking = App\Model\RestaurantCouponOrder::find($id)->first();
 $Restaurant = App\Model\Restaurant::find($booking->restaurant_id)->first();

 $Coupon = DB::table('restaurantcoupons')->select()->where('id',$booking->restaurantcoupon_id)->first();

  $CouponCategory = DB::table('restaurantcouponcategories')->select()->where('id',$Coupon->restaurantcouponcategory_id)->first();

 ?>


<table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td style="padding-top:10px">
        <table width="630" cellpadding="10" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style="padding-top:2em;border-top-left-radius:7px; border-top-right-radius:7px;margin:0 auto">
          <tbody>
            <tr>
              <td width="100%">
                  <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="left" bgcolor="#ffffff" style="padding:14px" width="40%"><a target="_blank" href="http://Biddr.in">
                          <img src="http://in.Biddr.in/extranet/images/logo.png"></a>
                      </td>
                      <td style="text-align:right; font-size:14px; color:#878787;  vertical-align:top; padding:10px 0 0; font-family:arial">
                        Order ID/Voucher No:
                        <u>{{ $booking->coupon_code }}</u><br>
                        <p style="float:right"> </p>

                      </td>
                    </tr>
                    <tr><td colspan="2">
                        <img width="100%" src="{{ url('images/hotellogo.gif') }}">
                    </td></tr>
                  </tbody>
                </table></td>
            </tr>

          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>


    
    
<table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td><table width="630" cellpadding="15" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto">
          <tbody>
            <tr>
              <td width="100%"><table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
          <tr><td colspan="2" style=" font-size:14px; font-style:italic; font-weight:bold; padding:5px 0 0 0">Dear Business Partner,</td></tr>
        <tr><td colspan="2" style=" font-style:italic; color:#4f4f4f; font-size:12px; padding:5px 0 0 0">We thank you for your continued support to Magic Spree.</td></tr>
        
        
        <tr><td colspan="2" style=" font-style:italic; color:#4f4f4f; font-size:12px; padding:5px 0 0 0">Please confirm the following booking that is done as per the allocation assigned to us.</td></tr>   
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>



    
        
<table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td>
          <table width="630" cellpadding="15" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto">
          <tbody>

            <tr>
                <td>

             

                </td>
            </tr>
          </tbody>
          </table>
          </td>
        </tr>
    </tbody>
    </table>



<table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td><table width="630" cellpadding="15" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto">
          <tbody>
              

            <tr>
              <td width="100%">

              <table cellpadding="10" cellspacing="7" border="0" width="100%" style="background:#e0e0e2;">
                <tbody><tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial;">name of restaurant &amp; city</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px;font-size:11px; padding:3px; text-transform:uppercase; font-family:arial;">{{ $Restaurant->name}}</td>
                </tr>
                <tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial">name of guest</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px; font-size:11px; padding:3px; text-transform:uppercase; height:14px;font-family:arial">{{$booking->user->name}}
                           
                            
                        </td>
                </tr>
                <tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial">order date</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px; font-size:11px; padding:3px; text-transform:uppercase; height:14px;font-family:arial">{{ $booking->created_at}}</td>
                </tr>
                <tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial">coupon code</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px; font-size:11px; padding:3px; text-transform:uppercase; height:14px;font-family:arial">{{ $booking->coupon_code}}</td>
                </tr>
                <tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial">coupon price</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px; font-size:11px; padding:3px; text-transform:uppercase; height:14px;font-family:arial">{{$booking->coupon_price}}</td>
                </tr>
                <tr>
                        <td style=" color:#000; font-size:12px; text-transform:uppercase; width:175px;font-family:arial">Coupon category</td>
                        <td style="border:1px solid #c7c7c7; background:#fff; width:327px; font-size:11px; padding:3px; text-transform:uppercase; height:14px;font-family:arial">{{$CouponCategory->name}}</td>
                </tr>
                
              
        </tbody></table>
              </td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>


         


       
        
        

        <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td><table width="630" cellpadding="0" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style="margin:0 auto;padding: 0 20px;">
          <tbody>
            <tr>
              <td width="100%"><table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td>
                                      <h4 style="text-transform:uppercase; font-size:12px; font-weight:bold; color:#878789;  margin:0px; padding:5px 0 5px; text-align:left;font-family:arial;"> tariff applicable:</h4>
                      </td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>


        <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="">
  <tbody>
    <tr>
      <td><table width="630" cellpadding="15" bgcolor="#ffffff" cellspacing="0" border="0" align="center" class="devicewidth" style=" margin:0 auto">
          <tbody>
            <tr>
              <td width="100%">
              <table width="100%" cellpadding="0" cellspacing="0" border="1" style="border-collapse:collapse; border-color:#bfe3f9">

                <thead>
                        <tr>
                                <td style="background:#c2e8fb;  text-transform:uppercase; font-size:11px; color:#2e67b4; border-color:#f0f9fe; padding:10px 5px; text-align:center;font-family:arial">Order Number</td>
                                <td style="background:#c2e8fb;  text-transform:uppercase; font-size:11px; color:#2e67b4; border-color:#f0f9fe; padding:10px 5px; text-align:center;font-family:arial">Order Date</td>
                                <td style="background:#c2e8fb;  text-transform:uppercase; font-size:11px; color:#2e67b4; border-color:#f0f9fe; padding:10px 5px; text-align:center;font-family:arial">Price</td>
                                <td style="background:#c2e8fb;  text-transform:uppercase; font-size:11px; color:#2e67b4; border-color:#f0f9fe; padding:10px 5px; text-align:center;font-family:arial">currency</td>
                                <td style="background:#c2e8fb;  text-transform:uppercase; font-size:11px; color:#2e67b4; border-color:#f0f9fe; padding:10px 5px; text-align:center;font-family:arial">Coupon Code</td>
                                
                        </tr>
                </thead>
                <tbody>
                        
                                <tr>
                                        <td style="border-color:#bfe3f9; height:18px; text-transform:uppercase; text-align:center; padding:3px; font-size:11px; color:#2e6ab3;font-family:arial">{{ $booking->id}}</td>
                                        <td style="border-color:#bfe3f9; height:18px; text-transform:uppercase; text-align:center; padding:3px; font-size:11px; color:#2e6ab3;font-family:arial">{{ $booking->created_at}}</td>
                                        <td style="border-color:#bfe3f9; height:18px; text-transform:uppercase; text-align:center; padding:3px; font-size:11px; color:#2e6ab3;font-family:arial">{{ $booking->coupon_price}}</td>
                                        <td style="border-color:#bfe3f9; height:18px; text-transform:uppercase; text-align:center; padding:3px; font-size:11px; color:#2e6ab3;font-family:arial">INR</td>
                                        <td style="border-color:#bfe3f9; height:18px; text-transform:uppercase; text-align:center; padding:3px; font-size:11px; color:#2e6ab3;font-family:arial">{{ $booking->coupon_code}}</td>
                                        
                                </tr>
                        
                </tbody>
        </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>


       
</table>
</body>