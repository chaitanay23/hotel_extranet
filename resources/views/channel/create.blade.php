@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12 mt-4">
		<h2 class="heder">Channel Manager</h2>
	</div>
	<div class="col-sm-4 ml-2">

		<a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>

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
{!! Form::open(array('route' => 'channel.store','method'=>'POST')) !!}
@csrf
<div class="form form-padding">
	<div class="form-group row mt-4">

		<div class="col-xs-6 col-sm-6 col-md-6 ">
			<strong>Hotels</strong>
			<!-- <input type="searchString" name="searchString" class="form-control" placeholder="List of Hotels"/> -->
			<select class="form-control" id="hotel_name" name='hotel_id'>
				<option value="0">Select hotel</option>
				<input type="hidden" value="{{$hotel_id}}" id="hotel_id_session">
			</select>


		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 ">
			<strong>Key</strong>
			<input type="text" name="hotelkey" id="hotelkey" class="form-control" placeholder="Key" />
		</div>

	</div>
	<div class="form-group">
		<strong>Channel Partners</strong>
		<!-- <input type="searchString" name="searchString" class="form-control" placeholder="List of Hotels"/> -->
		<select class="form-control" id="channel_partner" name='channel_partner'>
			<option value="0">Channel Partner</option>
		</select>


	</div>
	<div class="form-group">
		<strong>Password</strong>
		<input type="text" name="hotelpassword" id="hotelpassword" class="form-control" placeholder="Password" />
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
		<button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
		@can('contact_detail_create')
		<div class="text-right">
			<button type="submit" class="btn" name="submit" style="color:black !important" value="skip">Skip</button>
			<button type="submit" class="btn btn-success" name="submit" value="proceed">Proceed to next</button>
		</div>
		@endcan
	</div>
</div>

{!! Form::close() !!}
<script>
	jQuery(document).ready(function($) {
		var newValue = [];
		$('.channel').addClass('active-menu');
		var _token = $('input[name="_token"]').val();
		var hotel_id_session = $('#hotel_id_session').val();
		$.ajax({
			url: "{{ route('channel_hotel.fetch')}}",
			method: "POST",
			data: {
				_token: _token
			},
			success: function(data) {
				$.each(data, function(key, value) {
					if (value.id == hotel_id_session) {
						jQuery('#hotel_name').attr('value', value.id).text(value.title).attr('selected', 'selected');
					}
					jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
					newValue.push(value.id);
				});
			}
		});
		$.ajax({
			url: "{{route('channel_user.fetch')}}",
			method: "POST",
			data: {
				hotel_get: hotel_id_session,
				_token: _token
			},
			success: function(data) {
				$.each(data, function(key, value) {
					jQuery('#hotelkey').val(value.email);

				});
			}
		});
		$.ajax({
			url: "{{ route('channel_partner.fetch')}}",
			method: "POST",
			data: {
				_token: _token
			},
			success: function(data) {
				$.each(data, function(key, value) {
					jQuery('#channel_partner').append($('<option></option>').attr('value', value.id).text(value.name));
				});
			}
		});

		var option = [];

		$(document).on('keyup', '.select2-search__field', function(e) {
			var search_hotel = $(this).val();
			var _token = $('input[name="_token"]').val();
			if (search_hotel.length >= 2) {
				$.ajax({
					url: "{{ route('search_channel_hotel.fetch')}}",
					method: "POST",
					data: {
						search_hotel: search_hotel,
						_token: _token
					},
					success: function(data) {
						for (var i = 0; i < data.length; i++) {
							let result = newValue.includes(data[i].id);
							if (result == false) {
								option[i] = '<option value="' + data[i].id + '">' + data[i].title + '</option>';
								newValue.push(data[i].id)
								$('#hotel_name').append(option[i]);
							}
						}
					}
				});
			}
		});
		$('#hotel_name').change(function() {
			var hotel_get = $('#hotel_name').val();
			$.ajax({
				url: "{{route('channel_user.fetch')}}",
				method: "POST",
				data: {
					hotel_get: hotel_get,
					_token: _token
				},
				success: function(data) {
					$.each(data, function(key, value) {
						jQuery('#hotelkey').val(value.email);

					});
				}
			});
		});
		$('#hotel_name').select2({
			allowClear: true,
		});
		$('#channel_partner').select2({
			allowClear: true,
		});
	});
</script>



@endsection