@extends('layouts.app')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
 <p>{{ $message }}</p>
</div>
@endif
@if (count($errors) > 0)
 <div class="alert alert-danger">
   <strong>Whoops!</strong> There were some problems with your input.<br><br>
   <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
   </ul>
 </div>
@endif
{!! Form::open(array('route' => 'inventory.show','method'=>'POST')) !!}
<div class="form form-padding" style="margin-top:6% !important; padding-bottom:30px;">
<div class="row mt-4">
  <div class="col-sm-12">
        <h2 class="heder">Inventory</h2>
    </div>

 <div class="col-xs-3 col-sm-3 col-md-3">
   <div class="form-group">
     <strong>Select From Date :</strong>
     <input type = "date" id = "start_date" class="form-control" name="start_date"  autocomplete="off" >
   </div>
 </div>
 <div class="col-xs-3 col-sm-3 col-md-3">
   <div class="form-group">
     <strong>Select To Date :</strong>
     <input type = "date" id = "end_date" class="form-control" name="end_date"  autocomplete="off">
   </div>
 </div>
 <div class="col-xs-3 col-sm-3 col-md-3">
     <div class="form-group">
       <strong>Hotel Name:</strong>
         <select class="form-control" id="hotel_name" name = 'hotel_id' required>
           <option value="0">Select hotel</option>
           
         </select>
     </div>
 </div>
 <div class="col-xs-3 col-sm-3 col-md-3">
   <div class="form-group">
     <strong>Room Name:</strong>
     <select class="form-control" id="room_name" name="category_id" required>
       <option value="0">Select Room</option>
     </select>
   </div>
 </div>

 <div class="col-xs-12 col-sm-12 col-md-12 text-center">
   <button type="submit" class="btn btn-primary form-btn-hex" id="submit_button" name="submit" value="submit">Submit</button>
 </div>

</div>
</div>
<div>
  @if(!empty($hotel_name))
  <div class="tab" style="background: #1c1c2d; padding-top: 10px;">
    <h3 class="mt-4 heding">{{$hotel_name->title}}</h3>
    <h5 class="heding1"><strong>Room Name: </strong>{{$room_name->custom_category}}</h5>
    <input type="hidden" value="{{$start_date}}" id="start_date_old">
    <input type="hidden" value="{{$end_date}}" id="end_date_old">
    <input type="hidden"value="{{$hotel_name->id}}" id="hotel_id_old">
    <input type="hidden" value="{{$room_name->id}}" id="room_id_old">
    <div id="exTab1" class="container tabs-wrap mt-4">
      <div class="nav-link1"> 
        <ul  class="nav nav-tabs">
          <li class="active">
            <a  href="#1a" class="nav-link font-weight-bold head-links lead active show" data-toggle="tab">EP Room Plan</a>
          </li>
          <li><a href="#2a" class="nav-link font-weight-bold head-links lead" data-toggle="tab">CP Room Plan</a>
          </li>
        </ul>
      </div>
      <div class="tab-content clearfix">
        <div class="tab-pane active" id="1a">

          <div class="tab col-md-12 col-xs-12 inventory-back">

            <div class="row">
              
              <div class="col-md-4 cp">
                <strong>EP Status: </strong>
                <input type="checkbox" value="{{$inventory[0]->ep_status}}" data-room="{{$inventory[0]->category_id}}" data-change="ep" data-toggle="toggle" data-offstyle="danger" id="ep" data-onstyle="success">
              </div>
              
            </div>
            <div class="over">
              <table id="table" class="table table-striped table-dark index-table table-hover col-md-12">
                
                <tr class="tr-row">
                  <th class="inventory-date th-line">Date </th>
                  <th class="inventory-date th-line">Inventory Available</th>
                  <th class="inventory-date th-line">Inventory Sold</th>
                  <th class="inventory-date th-line">Single Occupancy</th>
                  <th class="inventory-date th-line">Double Occupancy</th>
                  <th class="inventory-date th-line">Extra<br>Adult</th>
                  <th class="inventory-date th-line">Child Occupancy</th>
                </tr>
                
                @foreach($inventory as $key => $value)
                <tr class="tr-row">
                  <th class="inventory-date th-line">
                    {{$value->date}}
                  </th>
                  <td contenteditable id="{{$value->id}}" data-column="rooms_ep" class="inventory-data td-line rooms_inventory">
                      @if ($value->ep_status > '0')
                        {{$value->rooms_ep}}
                      @else
                        Blocked
                      @endif
                  </td>
                  <td id="{{$value->id}}" data-column="booked" class="inventory-data td-line" style="background-color: rgb(187, 182, 182);color: black;">
                    {{$value->booked}}
                  </td>
                  <td contenteditable id="{{$value->id}}" data-column="single_occupancy_price_ep" class="inventory-data td-line">
                    {{$value->single_occupancy_price_ep}}
                  </td>
                  <td contenteditable id="{{$value->id}}" data-column="double_occupancy_price_ep" class="inventory-data td-line">
                    {{$value->double_occupancy_price_ep}}
                  </td>
                  <td contenteditable id="{{$value->id}}" data-column="extra_adult_ep" class="inventory-data td-line">
                    {{$value->extra_adult_ep}}
                  </td>
                  <td contenteditable id="{{$value->id}}" data-column="child_price_ep" class="inventory-data td-line">
                    {{$value->child_price_ep}}
                  </td>
                  
                </tr>
                @endforeach
              </table>
            </div>
            
          </div>

        </div>
        <div class="tab-pane" id="2a">
          <div class="tab col-md-12 col-xs-12 inventory-back">

            <div class="row">
              
              <div class="col-md-4 cp">
                <strong>CP Status: </strong>
                <input type="checkbox" value="{{$inventory[0]->cp_status}}" data-room="{{$inventory[0]->category_id}}" data-change="cp" data-toggle="toggle" data-offstyle="danger" id ="cp" data-onstyle="success">
              </div>
            </div>
            <div class="over">
              <table id="table" class="table table-striped table-dark index-table table-hover col-md-12 mt-2">
                
                <tr class="tr-row">
                  <th class="inventory-date th-line">Date </th>
                  <th class="inventory-date th-line">Inventory Available</th>
                  <th class="inventory-date th-line">Inventory Sold</th>
                  <th class="inventory-date th-line">Single Occupancy</th>
                  <th class="inventory-date th-line">Double Occupancy</th>
                  <th class="inventory-date th-line">Extra<br>Adult</th>
                  <th class="inventory-date th-line">Child Occupancy</th>
                </tr>
                
                @foreach($inventory as $key => $data)
                <tr class="tr-row">
                  <th class="inventory-date th-line">
                    {{$data->date}}
                  </th>
                  <td contenteditable id="{{$data->id}}" data-column="rooms_cp" class="inventory-data td-line rooms_inventory">
                      @if ($data->cp_status > '0')
                        {{$data->rooms_cp}}
                      @else
                        Blocked
                      @endif
                  </td>
                  <td id="{{$data->id}}" data-column="booked" class="inventory-data td-line" style="background-color: rgb(187, 182, 182);color: black;">
                    {{$data->booked}}
                  </td>
                  <td contenteditable id="{{$data->id}}" data-column="single_occupancy_price_cp" class="inventory-data td-line">
                    {{$data->single_occupancy_price_cp}}
                  </td>
                  <td contenteditable id="{{$data->id}}" data-column="double_occupancy_price_cp" class="inventory-data td-line">
                    {{$data->double_occupancy_price_cp}}
                  </td>
                  <td contenteditable id="{{$data->id}}" data-column="extra_adult_cp" class="inventory-data td-line">
                    {{$data->extra_adult_cp}}
                  </td>
                  <td contenteditable id="{{$data->id}}" data-column="child_price_cp" class="inventory-data td-line">
                    {{$data->child_price_cp}}
                  </td>
                  
                </tr>
                @endforeach
              </table>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
{!! Form::close() !!}
<script>
  jQuery(document).ready(function($){
  $('.inventory').addClass('active-menu');
  $('.daily_invent').addClass('active-sub-menu');
  var _token = $('input[name="_token"]').val();
  var old_hotel_id = $('#hotel_id_old').val();
  var old_room_id = $('#room_id_old').val();
  $('.rooms_inventory').each(function()
  {
    let inventory_val = $(this).html().trim();
    if(inventory_val=='Blocked'){
      $(this).css('background-color','#c51f1a');
      $(this).prop('contenteditable', false );
    }
  });
  $.ajax({
       url:"{{ route('inventory_hotel.fetch')}}",
       method:"POST",
       data:{_token:_token},
       success:function(data)
       {
        
         $.each(data,function(key,value){
          if (value.id == old_hotel_id)
          {
            jQuery('#hotel_name').attr('value',value.id).text(value.title).attr('selected','selected');
          }
           jQuery('#hotel_name').append($('<option></option>').attr('value',value.id).text(value.title));
         });
       }
     });
     //for pre select of hotel if not in list 
     $.ajax({
      url:"{{ route('edit_inventory_hotel.fetch')}}",
      method:"POST",
      data:{_token:_token,hotel_user:old_hotel_id},
      success:function(data)
      {
        $.each(data, function (key,value) {
          if(value.id == old_hotel_id)
          {
            $('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title).attr('selected','selected'));
          }
          else{
            $('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
          }
        });
      }
    });
    $.ajax({
      url:"{{ route('room_inventory.fetch')}}",
      method:"POST",
      data:{hotel_name:old_hotel_id,_token:_token},
      success:function(data)
      {
     
        $.each(data,function(key,value){
        //  if (value.id == old_room_id)
        //  {
        //   jQuery('#room_name').attr('value',value.id).text(value.custom_category).attr('selected','selected');
        //  }
          jQuery('#room_name').append($('<option></option>').attr('value', value.id).text(value.custom_category));
        });
      }
    });

     $(document).on('keyup','.select2-search__field',function(e){
       var search_hotel = $(this).val();
       var _token = $('input[name="_token"]').val();
       var newValue = [];
       if(search_hotel.length>=2)
       {
         $.ajax({
           url:"{{ route('search_inventory_hotel.fetch')}}",
           method:"POST",
           data:{search_hotel:search_hotel,_token:_token},
           success:function(data)
           {
             $.each(data, function (key,value) {
               //jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
               var newOption = new Option(value.title, value.id, false, false);
               newValue.push(newOption);
             });
             $('#hotel_name').append(newValue).trigger('change');
           }
         });
       }
     });
     
     $('#hotel_name').change(function(){
       var hotel_name = $('#hotel_name').val();
       $('#submit_button').show();
       $.ajax({
         url:"{{ route('room_inventory.fetch')}}",
         method:"POST",
         data:{hotel_name:hotel_name,_token:_token},
         success:function(data)
         {
           
           jQuery('#room_name').empty().append('<option selected="selected" value="">Select Room</option>');
           $.each(data,function(key,value){
            // if (value.id == old_room_id)
            // {
            //  jQuery('#room_name').html($('<option selected="selected"></option>').attr('value',value.id).text(value.custom_category));
            // }
             jQuery('#room_name').append($('<option></option>').attr('value', value.id).text(value.custom_category));
           });
         }
       })
     });
     $('#room_name').change(function(){
      $('#submit_button').show();
     });
      $('.tab').ready(function(){
        $("table").each(function() {
          var $this = $(this);
          var newrows = [];
          $this.find("tr").each(function(){
            var i = 0;
            $(this).find("th").each(function(){
              i++;
              if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
              newrows[i].append($(this));
            });
            $(this).find("td").each(function(){
              i++;
              if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
              newrows[i].append($(this));
            });
          });
          $this.find("tr").remove();
          $.each(newrows, function(){
            $this.append(this);
          });
        });
      });
      
      $('td').blur(function(){
        var updated_value = ($(this).html().trim());
        var updated_value = parseInt(updated_value);
        var column = $(this).data("column");
        var id = this.id;
        $.ajax({
          url:"{{ route('update_inventory_data.fetch')}}",
          method:"POST",
          data:{_token:_token,updated_value:updated_value,column:column,id:id},
          success:function(data)
          {
            
          }
        });
      });
      $('input:checkbox').ready(function(){
        $('input:checkbox').each(function(){
          var value = $(this).val();
          if(value=='0')
          {
            $(this).bootstrapToggle('off');
            $(this).bootstrapToggle('off');
          }
          else
          {
            $(this).bootstrapToggle('on');
            $(this).bootstrapToggle('on');
          }
        });
      });
      $('.toggle-group').click(function(){
        $('input:checkbox').change(function(){
          var room = $(this).data("room");
          var change = $(this).data("change");
          var value = $(this).val();
          if(value=='0')
          {
            $.ajax({
              url:"{{ route('inventory_status_on.fetch')}}",
              method:"POST",
              data:{_token:_token,change:change,room:room,value:value},
              success:function(data)
              {
                $(this).val('1');
              }
            });
          }
          else
          {
            $.ajax({
              url:"{{ route('inventory_status_off.fetch')}}",
              method:"POST",
              data:{_token:_token,change:change,room:room,value:value},
              success:function(data)
              {
                $(this).val('0');
              }
            });
          }
        });
      });
      
    
     $('#hotel_name').select2({
       allowClear:true,
     });
     $('#room_name').select2({
       allowClear:true,
     });
    var old_start_date = $('#start_date_old').val();
    var old_end_date = $('#end_date_old').val();
    
    
    var d = new Date();
    var current_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
    d.setDate(d.getDate() + 15);
    var last_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
    $('#start_date').val(current_date);
    $('#end_date').val(last_date);
    if(old_start_date != null)
    {
      $('#start_date').val(old_start_date);
    }
    if(old_end_date != null)
    {
      $('#end_date').val(old_end_date);
    }
    $('#submit_button').hide();
  });
</script>
@endsection
