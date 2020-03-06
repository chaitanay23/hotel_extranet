@extends('layouts.app')

@section('content')
<div class="row mt-4">
	<div class="col-sm-4">
		<h2 class="heder1">Property Status</h2>

	</div>
	<div class="col-sm-8">
		{!! Form::open(['method'=>'GET','url'=>'status_report','class'=>'col-lg-8 float-right','role'=>'search']) !!}
		<div class="input-group custom-search-form input-m">
			<input type="text" class="form-control" name="search" placeholder="Search..." autocomplete="off">
			<div class="input-group-append">
				<button class="btn btn-default-sm btn-color" type="submit">
					<i class="fa fa-search"></i>
				</button>
			</div>
		</div>
		{!! Form::close() !!}
	</div>



</div>

<table class="table table-striped table-dark index-table mt-4">
	<tr>
		<th>No</th>
		<th>Name</th>
		<th>Own Property</th>
		<th>City</th>
		<th>Rooms</th>
		<th>Contact</th>
		<th>Inventory</th>
		<th>Tax</th> <!-- this is commission-->
		<th>Discount Mapping</th>
		<th>Status</th>
	</tr>

	@foreach ($finalResult as $key => $data)
	<tr id="status_row">
		<td>{{ ++$i }}</td>
		<td>{{ $data->title }}</td>
		<td id="own_prop">{{ $data->own_property }}</td>
		<td>{{ $data->city_name}}</td>
		<td id="status_data">{{ $data->room_status}}</td>
		<td id="status_data">{{ $data->contacts_status}}</td>
		<td id="status_data">{{ $data->inventory_status}}</td>
		<td id="status_data">{{ $data->commissions_status}}</td>
		<td id="status_data">{{ $data->discount_map_hotel}}</td>
		<td><input type="checkbox" value="{{$data->launch_status}}" id="status-change" data-toggle="toggle" data-offstyle="danger" data-hotel="{{$data->id}}" data-room="{{ $data->room_status}}" data-contact="{{ $data->contacts_status}}" data-inven="{{$data->inventory_status}}" data-comm="{{ $data->commissions_status}}" data-dis="{{ $data->discount_map_hotel}}" data-onstyle="success"></td>
	</tr>
	@endforeach
</table>

{!!$finalResult->links()!!}
<script>
	jQuery(document).ready(function($) {
		var _token = $('input[name="_token"]').val();
		$('.property').addClass('active-menu');
		$('.status').addClass('active-sub-menu');
		$('table #status_data').each(function() {
			var value = $(this).html();
			if (value != null && value != '') {
				$(this).html('Yes');
				$(this).css('color', '#5cd45c');
			} else {
				$(this).html('No');
				$(this).css('color', 'red');
			}
		});
		$('table #own_prop').each(function() {
			var value = $(this).html();
			if (value != null && value != '') {
				$(this).html('OWN');
				$(this).css('color', '#5cd45c');
			} else {
				$(this).html('YATRA');
				$(this).css('color', 'white');
			}
		});
		$('table #status-change').each(function() {
			var value = $(this).val();
			if (value == '0') {
				$(this).bootstrapToggle('off');

			} else {

				$(this).bootstrapToggle('on');
			}
		});
		$('table #status-change').each(function() {
			var room = $(this).data("room");
			var contact = $(this).data("contact");
			var comm = $(this).data("comm");
			var dis = $(this).data("dis");
			var invent = $(this).data("inven");
			var launch_status = $(this).val();

			if (launch_status == '1') {
				$(this).attr("disabled", false);
			} else if (room == '' || contact == '' || comm == '' || dis == '' || invent == '') {
				$(this).next().children().next().css('cursor', 'not-allowed');
				$(this).attr("disabled", true);
			}
		});
		$('.toggle-group').click(function() {
			$('input:checkbox').change(function() {
				var hotel = $(this).data("hotel");
				var value = $(this).val();
				var tag = "#" + this.id;
				if (value == '0') {
					$.ajax({
						url: "{{ route('status_launch.change_on')}}",
						method: "POST",
						data: {
							hotel_id: hotel,
							_token: _token
						},
						success: function(data) {
							$(tag).val('1');
						}
					});
				} else {
					$.ajax({
						url: "{{ route('status_launch.change_off')}}",
						method: "POST",
						data: {
							hotel_id: hotel,
							_token: _token
						},
						success: function(data) {
							$(tag).val('0');
						}
					});
				}
			});
		});
	});
</script>
@endsection