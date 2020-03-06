    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal{{$booking_value->id }}payment">View / Edit </a> 

  <style>

/* .lightbox_table{width: 1width:250px;font-weight: bold; border:0px solid red;padding: 5px;font-size:14px;}
.lightbox_table tr{border-bottom: 1px solid #d9d9d9;}
.lightbox_table tr td{border-bottom: 1px solid #d9d9d9;} */

</style>  



  <!-- Modal -->
  <div class="modal fade" id="myModal{{$booking_value->id }}payment" role="dialog" style="padding-top:0px;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content payment-modal-bg">
        <div class="modal-header modal-payment">
           <div class="item w-20">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
   </div>
   <div class="item w-80">
          <h4 class="modal-title">Payment Status View</h4>
          <p>{{ $booking_value->hotel->title}}</p> 
   </div>
        </div>
        <?php           
          $cc_info = App\Model\Creditcard::all();
          $msbank = App\Model\Msbank::all();
         //  dd($msbank);
        ?>
        <div class="modal-body">
          <div>
            @if($payment_info_count > 0)
              <table class="table table-striped table-dark index-table mt-2">
                <tr>
                   <td > Payment Mode :  </td> 
                   <td class='table_data'> 
                    <select name="mode" class='form-control input-select1' onchange="check_Payment_info(this.value,{{$booking_value->id }})" id="mode{{$booking_value->id }}">
                     <option selected disabled value="">Select Payment Mode *</option>
                     <option value='1' @if($payment_info->mode == 1) selected="" @endif>Credit Card</option> 
                     <option value='2' @if($payment_info->mode == 2) selected="" @endif>Bank Transfer</option> 
                    </select>
                          
                    </td>
                </tr>
                 @if($hotel_info)
                <tr>
                   <td > Beneficiary Name : </td> 
                   <td class='table_data'>{{ $hotel_info->account_holder}}</td>
                </tr>
                <tr>
                   <td > Bank Account : </td> 
                   <td class='table_data'>{{ $hotel_info->account_no}}</td>
                </tr>
                <tr>
                   <td > IFSC Code : </td> 
                   <td class='table_data'>{{ $hotel_info->ifsc_code}}</td>
                </tr>
                <tr>
                   <td > Bank Name : </td> 
                   <td class='table_data'>{{ $hotel_info->bank->name }}</td>
                </tr>
                <tr>
                   <td > Branch : </td> 
                   <td class='table_data'>{{ $hotel_info->branch_name }}</td>
                </tr>
                <tr>
                   <td > GSTN : </td> 
                   <td class='table_data'>{{ $hotel_info->gst_number }}</td>
                </tr>
                @else
                    <tr>
                       <td > Beneficiary Name : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Bank Account : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > IFSC Code : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Bank Name : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Branch : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > GSTN : </td> 
                       <td class='table_data'></td>
                    </tr>
                @endif
                <tr>
                   <td > TrooSol Bank Name/Credit Card Name : </td> 
                   <td class='table_data'><select name="msbank" class='form-control input-select1' id="msbank{{$booking_value->id }}" @if($payment_info->mode == 2 && $payment_info->bank_id) @else style="display:none" @endif onchange="change_bank_info(this.value,{{$booking_value->id }})">
                     <option selected disabled value="">Select Bank Name *</option>
                      
                      @foreach ($msbank as $key => $value) 
                        {
                         <option value="{{$value->id}}" @if($payment_info->bank_id == $value->id) selected="" @endif >{{$value->bank->name}} ( {{$value->account_holder}} ) </option>";
                      @endforeach
                    </select>

                  <select name="cc_info" class='form-control input-select1' id="cc_info{{$booking_value->id }}" @if($payment_info->mode == 1 && $payment_info->creditcard_id) @else style="display:none" @endif onchange="change_cc_info(this.value,{{$booking_value->id }})">
                     <option selected disabled value="">Select Credit Card *</option>
                      
                      @foreach ($cc_info as $key => $value) 
                        {
                          <option value="{{$value->id}}"  @if($payment_info->creditcard_id == $value->id) selected="" @endif > {{$value->nameofcard}} ( {{$value->expire}} ) </option>";
                        }
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                   <td > TrooSol Bank A/C Number / Credit Card Number : </td> 
                   <td class='table_data'><input type="text" name="ac_number" id="ac_number{{$booking_value->id }}" readonly="" value="@if($payment_info->number) {{ $payment_info->number}} @endif"></td>
                </tr>
                <tr>
                   <td > Transaction ID : </td> 
                   <td class='table_data'><input type="text" name="t_id" placeholder="Transaction ID" id="t_id{{$booking_value->id }}" value="@if($payment_info->t_id) {{ $payment_info->t_id}} @endif"> </td>
                </tr>
                <tr>
                   <td > Payment Date : </td> 
                   <td class='table_data'><input type='text' readonly="" class="to_date_pay" value="@if($payment_info->date) {{ Carbon\Carbon::parse($payment_info->date)->format('d-m-Y') }} @else {{ Carbon\Carbon::now()->format('d-m-Y') }} @endif" id="pdate{{$booking_value->id }}"></td>
                </tr>
                <tr>
                   <td > Payment Status : </td> 
                   <td class='table_data'>
                    <select name="p_status" class='form-control input-select1' id="p_status{{$booking_value->id }}">
                     <option selected disabled value="">Select Payment Status *</option>
                     <option value='1' @if($payment_info->status == 1) selected="" @endif>Paid</option> 
                     <option value='0' @if($payment_info->status == 0) selected="" @endif>Pending</option> 
                    </select>
                   </td>
                </tr>
              </table>
            @else
              <table class='table table-striped table-dark index-table mt-2'>
                <tr>
                   <td > Payment Mode :  </td> 
                   <td class='table_data'> 
                    <select name="mode" class='form-control input-select1' onchange="check_Payment_info(this.value,{{$booking_value->id }})" id="mode{{$booking_value->id }}">
                     <option selected disabled value="">Select Payment Mode *</option>
                     <option value='1'>Credit Card</option> 
                     <option value='2'>Bank Transfer</option> 
                    </select>
                          
                    </td>
                </tr>
                @if($hotel_info)
                <tr>
                   <td > Beneficiary Name : </td> 
                   <td class='table_data'>{{ $hotel_info->account_holder}}</td>
                </tr>
                <tr>
                   <td > Bank Account : </td> 
                   <td class='table_data'>{{ $hotel_info->account_no}}</td>
                </tr>
                <tr>
                   <td > IFSC Code : </td> 
                   <td class='table_data'>{{ $hotel_info->ifsc_code}}</td>
                </tr>
                <tr>
                   <td > Bank Name : </td> 
                   <td class='table_data'>{{ $hotel_info->bank->name }}</td>
                </tr>
                <tr>
                   <td > Branch : </td> 
                   <td class='table_data'>{{ $hotel_info->branch_name }}</td>
                </tr>
                <tr>
                   <td > GSTN : </td> 
                   <td class='table_data'>{{ $hotel_info->gst_number }}</td>
                </tr>
                @else
                    <tr>
                       <td > Beneficiary Name : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Bank Account : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > IFSC Code : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Bank Name : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > Branch : </td> 
                       <td class='table_data'></td>
                    </tr>
                    <tr>
                       <td > GSTN : </td> 
                       <td class='table_data'></td>
                    </tr>
                @endif
            
                <tr>
                   <td > TrooSol Bank Name/Credit Card Name : </td> 
                   <td class='table_data'><select name="msbank" class='form-control input-select' id="msbank{{$booking_value->id }}" style="display:none" onchange="change_bank_info(this.value,{{$booking_value->id }})">
                     <option selected disabled value="">Select Bank Name *</option>
                      <?php
                      foreach ($msbank as $key => $value) 
                        {
                        echo "<option value='$value->id'>".$value->bank->name."( ".$value->account_holder." ) </option>";
                        }
                      ?>
                    </select>

                  <select name="cc_info" class='form-control input-select' id="cc_info{{$booking_value->id }}" style="display:none" onchange="change_cc_info(this.value,{{$booking_value->id }})">
                     <option selected disabled value="">Select Credit Card *</option>
                      <?php
                      foreach ($cc_info as $key => $value) 
                        {
                        echo "<option value='$value->id'>".$value->nameofcard."( ".$value->expire." ) </option>";
                        }
                      ?>
                    </select>
                  </td>
                </tr>
                <tr>
                   <td > TrooSol Bank A/C Number / Credit Card Number : </td> 
                   <td class='table_data'><input type="text" name="ac_number" id="ac_number{{$booking_value->id }}" readonly=""></td>
                </tr>
                <tr>
                   <td > Transaction ID : </td> 
                   <td class='table_data'><input type="text" name="t_id" placeholder="Transaction ID" id="t_id{{$booking_value->id }}"></td>
                </tr>
                <tr>
                   <td > Payment Date : </td> 
                   <td class='table_data'><input type='text' readonly="" class="to_date_pay" value="{{ Carbon\Carbon::now()->format('d-m-Y') }}" id="pdate{{$booking_value->id }}"></td>
                </tr>
                <tr>
                   <td > Payment Status : </td> 
                   <td class='table_data'>
                    <select name="p_status" class='form-control input-select1' id="p_status{{$booking_value->id }}">
                     <option selected disabled value="">Select Payment Status *</option>
                     <option value='1'>Paid</option> 
                     <option value='0'>Pending</option> 
                    </select>
                   </td>
                </tr>
              </table>
            @endif
        </div>
        <div class="modal-footer1">
          <button type="button" class="btn  custom-btn" onclick="save_info({{$booking_value->id }})">Save</button>
          <button type="button" class="btn  custom-btn" data-dismiss="modal" id="btn{{$booking_value->id }}">Close</button>
        </div>
      </div>
      
    </div>
  </div>
