<!-- 

@section('inventroy_find')

<div class="tab col-md-12 col-xs-12">

	<h3 class="mt-4">{{$hotel_name->title}}</h3>
	
	<h5 class="mt-4 mb-2"><strong>Room Name: </strong>{{$room_name->custom_category}}</h5>
	
	<div class="row">
		<input type="hidden" value="{{$start_date}}" id="start_date">
		<input type="hidden" value="{{$end_date}}" id="end_date">
		<div class="col-md-6">
			<strong>EP Status: </strong>
			<input type="checkbox" value="{{$inventory[0]->ep_status}}" data-room="{{$inventory[0]->hoteldetails_id}}" data-change="ep" data-toggle="toggle" data-offstyle="danger" id="ep" data-onstyle="success">
		</div>
		<div class="col-md-6">
			<strong>CP Status: </strong>
			<input type="checkbox" value="{{$inventory[0]->cp_status}}" data-room="{{$inventory[0]->hoteldetails_id}}" data-change="cp" data-toggle="toggle" data-offstyle="danger" id ="cp" data-onstyle="success">
		</div>
	</div>
	
	<table id="table" class="table table-striped table-dark index-table table-hover col-md-12 mt-4">
		
		<tr class="tr-row">
			<th class="inventory-date th-line">Date</th>
			<th class="inventory-date th-line" style="border-top: 3px solid white;border-bottom: 3px solid white">EP Plan Price</th>
			<th class="inventory-date th-line">Inventory Available</th>
			<th class="inventory-date th-line">Single Occupancy</th>
			<th class="inventory-date th-line">Double Occupancy</th>
			<th class="inventory-date th-line">Extra<br>Adult</th>
			<th class="inventory-date th-line">Child Occupancy</th>
			<th class="inventory-date th-line" style="border-top: 3px solid white;border-bottom: 3px solid white">CP Plan Price</th>
			<th class="inventory-date th-line">Inventory Available</th>
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
			<td style="border-top: 3px solid white;border-bottom: 3px solid white" class="inventory-date td-line"></td>
			<td contenteditable id="{{$value->id}}" data-column="rooms_ep" class="inventory-data td-line">
				{{$value->rooms_ep}}
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
			
			<td style="border-top: 3px solid white;border-bottom: 3px solid white" class="inventory-date td-line"></td>
			<td contenteditable id="{{$value->id}}" data-column="rooms_cp" class="inventory-data td-line">
				{{$value->rooms_cp}}
			</td>
			<td contenteditable id="{{$value->id}}" data-column="single_occupancy_price_cp" class="inventory-data td-line">
				{{$value->single_occupancy_price_cp}}
			</td>
			<td contenteditable id="{{$value->id}}" data-column="double_occupancy_price_cp" class="inventory-data td-line">
				{{$value->double_occupancy_price_cp}}
			</td>
			<td contenteditable id="{{$value->id}}" data-column="extra_adult_cp" class="inventory-data td-line">
				{{$value->extra_adult_cp}}
			</td>
			<td contenteditable id="{{$value->id}}" data-column="child_price_cp" class="inventory-data td-line">
				{{$value->child_price_cp}}
			</td>
		</tr>
		@endforeach
	</table>
	
</div>

<script>
	jQuery(document).ready(function($){
		$('.inventory').addClass('active-menu');
		$('.daily_invent').addClass('active-sub-menu');
		var _token = $('input[name="_token"]').val();
		$('.tab').ready(function(){
			$("#table").each(function() {
				var $this = $(this);
				var newrows = [];
				$this.find(".tr-row").each(function(){
					var i = 0;
					$(this).find(".th-line").each(function(){
						i++;
						if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
						newrows[i].append($(this));
					});
					$(this).find(".td-line").each(function(){
						i++;
						if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
						newrows[i].append($(this));
					});
				});
				$this.find(".tr-row").remove();
				$.each(newrows, function(){
					$this.append(this);
				});
			});
		});
		$.ajax({
		    url:"{{ route('inventory_hotel.fetch')}}",
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
		    $.ajax({
		      url:"{{ route('search_inventory_hotel.fetch')}}",
		      method:"POST",
		      data:{search_hotel:search_hotel,_token:_token},
		      success:function(data)
		      {
		        jQuery('#hotel_name').empty().append('<option selected="selected" value="">Select hotel</option>');
		        $.each(data, function (key,value) {
		          jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
		        });
		      }
		    });
		  });
		  $('#hotel_name').change(function(){
		    var hotel_name = $('#hotel_name').val();
		    $.ajax({
		      url:"{{ route('room_inventory.fetch')}}",
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
		$('td').blur(function(){
			var updated_value = ($(this).html());
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
				var start_date = $('#start_date').val();
				var end_date = $('#end_date').val()
				if(value=='0')
				{
					$.ajax({
						url:"{{ route('inventory_status_on.fetch')}}",
						method:"POST",
						data:{_token:_token,change:change,room:room,value:value,start_date:start_date,end_date:end_date},
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
						data:{_token:_token,change:change,room:room,value:value,start_date:start_date,end_date:end_date},
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
		$('#start_date').datepicker();
		$('#end_date').datepicker();
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		$('#start_date').val(start_date);
		$('#end_date').val(end_date);
	});
</script>
@endsection -->
