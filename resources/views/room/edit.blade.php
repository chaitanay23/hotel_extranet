@extends('layouts.app')


@section('content')

<div class="row">
	<div class="col-sm-12 mt-4">
		<h2 class="heder">Room</h2>
	</div>

    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">

            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>

        </div>

    </div>

</div>
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

{!! Form::model($room, ['method' => 'PATCH','route' => ['room.update', $room->id],'enctype' => 'multipart/form-data']) !!}
	<div class="form form-padding">
	<div class="row mt-4">

	    <div class="col-xs-6 col-sm-6 col-md-6">
	        <div class="form-group">
	          <strong>Hotel Name:</strong>
	            <select class="form-control" id="hotel_name" name = 'hotel_id'>
	              <option value="0">Select hotel</option> 
	            </select>
	            <input type="hidden" value={{$room->hotel_id}} id="hotel_user">
	            <input type="hidden" value={{$room->user_id}} id="hotel_user_id">
	        </div>
	    </div>

	    <div class="col-xs-6 col-sm-6 col-md-6" style="opacity: .75">
	        <div class="form-group">
	          <strong>User Name:</strong>
	            <input type="text" name ="user_name" placeholder="User name" class="form-control readonly" id="user_name" readonly>
	            <input type="hidden" name="user_id" id="user_id">
	        </div>
	    </div>
	    
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Category Name:</strong>
	            {!! Form::text('custom_category', null, array('placeholder' => 'Room category name','class' => 'form-control','required')) !!}
	        </div>
    	</div>

    	<div class="col-md-4 col-xs-4 col-sm-4">
    	  <div class="form-group">
    	    <strong>Max Adult:</strong>
    	    {{ Form::select('max_adult_allow',['1' => '1', '2' => '2', '3' => '3'], null,['placeholder' => 'Max adult allowed','class' => 'form-control','required'])}}
    	  </div>
    	</div>

    	<div class="col-md-4 col-xs-4 col-sm-4">
    	  <div class="form-group">
    	    <strong>Max Child <small>(Age range 6-12yrs)</small>:</strong>
    	    {{ Form::select('max_child_allow',['0' => '0','1' => '1', '2' => '2'], null,['placeholder' => 'Max child allowed','class' => 'form-control','required'])}}
    	  </div>
    	</div>
    	
    	<div class="col-md-4 col-xs-4 col-sm-4">
    	  <div class="form-group">
    	    <strong>Max Infant <small>(Age range 0-5yrs)</small>:</strong>
    	    {{ Form::select('max_infant_allow',['0' => '0','1' => '1', '2' => '2'], null,['placeholder' => 'Max infant allowed','class' => 'form-control'])}}
    	  </div>
    	</div>

    	<div class="col-md-12 col-xs-12 col-sm-12">
    		<div class="form-group">
    			<strong>Max Guest:</strong>
    			{{ Form::select('max_guest_allow',['1' => '1', '2' => '2', '3' => '3','4'=>'4'], null,['placeholder' => 'Max guest allowed','class' => 'form-control','required'])}}
    		</div>
    	</div>

    	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Room Facilities:</strong>
	            {!! Form::select('facility_data[]', $facility,$pre_facility, array('class' => 'form-control','multiple','id' => 'facility','required')) !!}
	        </div>
	    </div>

	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Room Inclusion:</strong>
	            {!! Form::select('inclusion_data[]', $inclusion,$pre_inclusion, array('class' => 'form-control','multiple','id' => 'inclusion','required')) !!}
	        </div>
	    </div>

    	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group"> 
	          <strong>Room Image:</strong>
	          {!! Form::file('picture', array('class' => 'image form-control'))!!}
	        </div>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type ="submit" class="btn btn-primary submit-btn-hex">Update</button>
      </div>
	</div>
</div>
{{  Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
	jQuery(document).ready(function($){
		$('.property').addClass('active-menu');
		$('.room').addClass('active-sub-menu');
		var _token = $('input[name="_token"]').val();
		var hotel_user = $('#hotel_user').val();
		var hotel_user_id = $('#hotel_user_id').val();
		var hotel_get = $('#hotel_name').val();
		$.ajax({
      url:"{{ route('edit_room_hotel.fetch')}}",
      method:"POST",
      data:{_token:_token,hotel_user:hotel_user},
      success:function(data)
      {
        $.each(data, function (key,value) {
          if(value.id == hotel_user)
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
			url:"{{ route('room_user.fetch')}}",
			method:"POST",
			data:{hotel_get:hotel_user,_token:_token},
			success:function(data)
			{
				$.each(data,function(key,value){
					jQuery('#user_name').val(value.name);	
						jQuery('#user_id').val(value.id);
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
	            url:"{{ route('search_room_hotel.fetch')}}",
	            method:"POST",
	            data:{search_hotel:search_hotel,_token:_token},
	            success:function(data)
	            {
	            	
	              $.each(data, function (key,value) {
	                //jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
									var newOption = new Option(value.title, value.id, false, false);
              		newValue.push(newOption);
	              });
								$('#hotel_name').append(newValue);
	            }
	          });
	      }
        });
        
		$('#hotel_name').change(function(){
			var hotel_get = $('#hotel_name').val();

			$.ajax({
	        url:"{{ route('room_user.fetch')}}",
	        method:"POST",
	        data:{hotel_get:hotel_get,_token:_token},
	        success:function(data)
	        {
	        	$.each(data,function(key,value){
	          	jQuery('#user_name').val(value.name);	
	          	jQuery('#user_id').val(value.id);
	          });
	        }
	        });
		});
       
		$('#hotel_name').select2({
            allowClear:true,
        });
        
        $('#facility').select2({
            closeOnSelect: false,
            allowClear:true,
            placeholder:'Choose room facilities',
      	});
      	$('#inclusion').select2({
            closeOnSelect: false,
            allowClear:true,
            placeholder:'Choose room inclusion',
      	});
	});
</script>
@endsection
