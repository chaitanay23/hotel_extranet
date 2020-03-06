    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal{{$booking_value->id }}hotel"> {{ $booking_value->hotel->title}} </a> 

  <style>

.lightbox_table{width: 100%;}
.table_heading{width:250px;font-weight: bold; border:0px solid red;padding: 5px;font-size:14px;}
.lightbox_table tr{border-bottom: 1px solid #d9d9d9;}
.lightbox_table tr td{border-bottom: 1px solid #d9d9d9;}

</style>  



  <!-- Modal -->
  <div class="modal fade" id="myModal{{$booking_value->id}}hotel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content payment-modal-bg">
      <div class="modal-header modal-payment">
      <div class="item w-20">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
   </div>
   <div class="item w-80">
          <h4 class="modal-title">{{ $booking_value->hotel->title}}</h4>
          <p>{{ $booking_value->hotel->address }}, India</p> 
  </div>
        </div>
        <?php 
          $admin_info = App\Admin::find($booking_value->hotel->user_id);
          $contact_info = App\Model\Contact::where('hotel_id',$booking_value->hotel->id)->first();
        ?>
        <div class="modal-body">
          <div>
              <table class="table table-striped table-dark index-table mt-2">
                <tr>
                   <td class='table_heading'> Extranet User :  </td> 
                   <td class='table_data'> 
                      {{ $admin_info->name }}         
                    </td>
                </tr>
                <tr>
                   <td class='table_heading'> Extranet User Name : </td> 
                   <td class='table_data'>
                   {{ $admin_info->email }}
                  </td>
                </tr>
                 <tr>
                   <td class='table_heading'> Primary Email : </td> 
                   <td class='table_data'>{{ $admin_info->primary_email }}</td>
                </tr>
                 <tr>
                   <td class='table_heading'> Secondary Email : </td> 
                   <td class='table_data'>{{ $admin_info->secondary_email }}</td>
                </tr>
                <tr>
                   <td class='table_heading' colspan="2"> Hotel Contact Info : </td>
                </tr>
                @if($contact_info)
                 <tr>
                   <td class='table_heading'> Contact Email : </td> 
                   <td class='table_data'>{{ $contact_info->email }}</td>
                </tr>
                <tr>
                   <td class='table_heading'> Phone No. : </td> 
                   <td class='table_data'>{{ $contact_info->phone_no }}</td>
                </tr>

                <tr>
                   <td class='table_heading'> Mobile No : </td> 
                   <td class='table_data'>{{ $contact_info->mobile }}</td>
                </tr>

                 <tr>
                   <td class='table_heading'> Website : </td> 
                   <td class='table_data'>{{ $contact_info->website }}</td>
                </tr>
                @endif
                <tr>
                   <td class='table_heading'> Build Year : </td> 
                   <td class='table_data'>{{ $booking_value->hotel->build_year }}</td>
                </tr>
                <tr>
                   <td class='table_heading'> Number of rooms : </td> 
                   <td class='table_data'>{{ $booking_value->hotel->no_of_rooms }}</td>
                </tr>
                <tr>
                   <td class='table_heading'> Star Rating: </td> 
                   <td class='table_data'>{{ $booking_value->hotel->rating }}</td>
                </tr>
                <tr>
                   <td class='table_heading'> Check-in-Time : </td> 
                   <td class='table_data'>{{ Carbon\Carbon::parse($booking_value->hotel->check_in_time)->format('h:i:s A') }}</td>
                </tr>
                <tr>
                   <td class='table_heading'> Check-Out-Time : </td> 
                   <td class='table_data'>{{ Carbon\Carbon::parse($booking_value->hotel->check_out_time)->format('h:i:s A') }}</td>
                </tr>
              </table>
         
 
          

          
        </div>
        <div class="modal-footer1">
          <button type="button" class="btn custom-btn" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
