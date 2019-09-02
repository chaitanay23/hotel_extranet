@extends('layouts.app')

@section('content')
<div class="row mt-4">
  <div class="col-sm-12">
        <h2 class="heder">Update Inventory</h2>
    </div>
    </div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
<div class="container tabs-wrap mt-4">
  <div class="nav-link1">
  <ul class="nav nav-tabs">
    <li class="active"><a  data-toggle="tab" class="nav-link font-weight-bold head-links lead active" href="#inventory_bulk">Update Bulk Inventory</a></li>
    <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#price_bulk">Update Bulk Price</a></li>
  </ul>
</div>
  <div class="tab-content">
   <div id="inventory_bulk" class="tab-pane active tab-panel1">
    <h3 class="mt-4 four">Update Bulk Inventory</h3>
    <div class="col-xs-12 col-sm-12 col-md-12">
        {!! Form::open(array('route' => 'update_inventory.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}
        <div class="row mt-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                  <strong>Hotel Name:</strong>
                    <select class="form-control" id="hotel_name" name = 'hotel_id'>
                      <option value="0">Select hotel</option> 
                      <!--  -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                  <strong>Room Name:</strong>
                  <select class="form-control" id="room_name" name="category_id">
                    <option value="0">Select Room</option>
                  </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>Select From Date :</strong>
                  <input type = "date" id = "start_date" class="form-control" name="start_date" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>Select To Date :</strong>
                  <input type = "date" id = "end_date" class="form-control" name="end_date" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>No. of Rooms <small>(For EP)</small>:</strong>
                  {!! Form::number('rooms_ep', null, array('placeholder' => 'Number of rooms for EP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>No. of Rooms <small>(For CP)</small>:</strong>
                  {!! Form::number('rooms_cp', null, array('placeholder' => 'Number of rooms for CP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Blocked <small>(For EP)</small>:</strong>
                    {{ Form::checkbox('ep_booked',null,null) }}
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>Blocked <small>(For CP)</small>:</strong>
                    {{ Form::checkbox('cp_booked',null,null) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-4">
              <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
   </div>
 
   <div id="price_bulk" class="tab-pane tab-panel1 show">
    <h3 class="mt-4 four">Update Bulk Price</h3>
    <div class="col-xs-12 col-sm-12 col-md-12">
        {!! Form::open(array('route' => 'update_inventory_price.update','method'=>'POST','enctype' => 'multipart/form-data')) !!}
        <div class="row mt-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Hotel Name:</strong><br/>
                    <select class="form-control" id="hotel_name_price" name = 'hotel_id' style="width: 100%">
                        <option value="0">Select hotel</option> 
                        <!--  -->
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                  <strong>Room Name:</strong>
                  <select class="form-control" id="room_name_price" name="category_id" style="width: 100%">
                    <option value="0">Select Room</option>
                  </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>Select From Date :</strong>
                  <input type = "date" id = "start_date_price" class="form-control" name="start_date"  autocomplete="off">
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>Select To Date :</strong>
                  <input type = "date" id = "end_date_price" class="form-control" name="end_date" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Single Occupancy <small>(For EP)</small>:</strong>
                  {!! Form::number('single_occupancy_price_ep', null, array('placeholder' => 'Single occupancy for EP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Double Occupancy <small>(For EP)</small>:</strong>
                  {!! Form::number('double_occupancy_price_ep', null, array('placeholder' => 'Double occupancy for EP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Extra Occupancy <small>(For EP)</small>:</strong>
                  {!! Form::number('extra_adult_ep', null, array('placeholder' => 'Extra occupancy for EP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Child Occupancy <small>(For EP)</small>:</strong>
                  {!! Form::number('child_price_ep', null, array('placeholder' => 'Child occupancy for EP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Single Occupancy <small>(For CP)</small>:</strong>
                  {!! Form::number('single_occupancy_price_cp', null, array('placeholder' => 'Single occupancy for CP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Double Occupancy <small>(For CP)</small>:</strong>
                  {!! Form::number('double_occupancy_price_cp', null, array('placeholder' => 'Double occupancy for CP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Extra Occupancy <small>(For CP)</small>:</strong>
                  {!! Form::number('extra_adult_cp', null, array('placeholder' => 'Extra occupancy for CP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                  <strong>Child Occupancy <small>(For CP)</small>:</strong>
                  {!! Form::number('child_price_cp', null, array('placeholder' => 'Child occupancy for CP','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mb-4">
              <button type="submit" class="btn btn-primary submit-btn-hex" name="submit" value="submit">Submit</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
   </div>
 </div>
 <script>
    jQuery(document).ready(function($){
        $('.inventory').addClass('active-menu');
        $('.update_invent').addClass('active-sub-menu');
        var _token = $('input[name="_token"]').val();
        $.ajax({
          url:"{{ route('update_inventory_hotel.fetch')}}",
          method:"POST",
          data:{_token:_token},
          success:function(data)
          {
            $.each(data,function(key,value){
              jQuery('#hotel_name').append($('<option></option>').attr('value',value.id).text(value.title));    
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
              url:"{{ route('search_update_inventory_hotel.fetch')}}",
              method:"POST",
              data:{search_hotel:search_hotel,_token:_token},
              success:function(data)
              {
                
                $.each(data, function (key,value) {
                  var newOption = new Option(value.title, value.id, false, false);
                  newValue.push(newOption);
                  //jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
                });
                $('#hotel_name').append(newValue);
              }
            });
          }
        });

        $('#hotel_name').change(function(){
            var hotel_name = $('#hotel_name').val();
            $.ajax({
                url:"{{ route('room_update_inventory.fetch')}}",
                method:"POST",
                data:{hotel_name:hotel_name,_token:_token},
                success:function(data)
                {
                    jQuery('#room_name').empty().append('<option selected="selected" value="">Select Room</option>');
                    $.each(data,function(key,value){
                        jQuery('#room_name').append($('<option></option>').attr('value', value.id).text(value.custom_category));
                    });
                }
            })
        });

        $('#hotel_name_price').change(function(){
            var hotel_name = $('#hotel_name_price').val();
            $.ajax({
                url:"{{ route('room_update_inventory.fetch')}}",
                method:"POST",
                data:{hotel_name:hotel_name,_token:_token},
                success:function(data)
                {
                    jQuery('#room_name_price').empty().append('<option selected="selected" value="">Select Room</option>');
                    $.each(data,function(key,value){
                        jQuery('#room_name_price').append($('<option></option>').attr('value', value.id).text(value.custom_category));
                    });
                }
            })
        });

        var _token = $('input[name="_token"]').val();
        $.ajax({
          url:"{{ route('update_inventory_hotel.fetch')}}",
          method:"POST",
          data:{_token:_token},
          success:function(data)
          {
            $.each(data,function(key,value){
              jQuery('#hotel_name_price').append($('<option></option>').attr('value',value.id).text(value.title));  
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
              url:"{{ route('search_update_inventory_hotel.fetch')}}",
              method:"POST",
              data:{search_hotel:search_hotel,_token:_token},
              success:function(data)
              {
                $.each(data, function (key,value) {
                  var newOption = new Option(value.name, value.id, false, false);
                  newValue.push(newOption);
                  //jQuery('#hotel_name_price').append($('<option></option>').attr('value', value.id).text(value.title));
                });
                
                $('#hotel_name_price').append(newValue);
              }
            });
          }
        });

        $('#hotel_name').select2({
            allowClear:true,
        });
        $('#room_name').select2({
            allowClear:true,
        });
        $('#hotel_name_price').select2({
            allowClear:true,
        });
        $('#room_name_price').select2({
            allowClear:true,
        });
        
        var d = new Date();
        var current_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
        d.setDate(d.getDate() + 15);
        var last_date = d.getFullYear()+'-'+('0'+(d.getMonth()+1))+'-'+('0'+d.getDate()).slice(-2);
        $('#start_date').val(current_date);
        $('#end_date').val(last_date);
        $('#start_date_price').val(current_date);
        $('#end_date_price').val(last_date);
    });
 </script>
@endsection
