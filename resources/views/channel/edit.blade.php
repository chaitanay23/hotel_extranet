@extends('layouts.app')


@section('content')

<div class="row">
	<div class="col-sm-12 mt-4">
		<h2 class="heder">Channel Manager</h2>
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

{!! Form::model($channel, ['method' => 'PATCH','route' => ['channel.update', $channel->id],'enctype' => 'multipart/form-data']) !!}
<div class="form form-padding">
	<div class="form-group row mt-4">

		<div class="col-xs-6 col-sm-6 col-md-6 ">
			<strong>Hotels</strong>

			<select class="form-control" id="hotel_name" name='hotel_id'>
				<option value="0">Select hotel</option>
				<input type="hidden" value={{$channel->hotel_id}} id="hotel_user">
			</select>


		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 ">
			<strong>Key</strong>
			{!! Form::text('hotelkey', null, array('placeholder' => 'Hotel key','class' => 'form-control')) !!}
		</div>

	</div>
	<div class="form-group">
		<strong>Channel Partners</strong>
		{!! Form::select('channel_partner', $partner,$channel->channel_partner, array('class' => 'form-control','id'=>'channel_partner','required')) !!}
		<!--fix for list in edit
			<select class="form-control" id="channel_partner" name='channel_partner'>
			<option value="0">Channel Partner</option>
			<input type="hidden" value="{{$channel->channel_partner}}" id="channel_partner_id">
		</select>-->
	</div>
	<div class="form-group">
		<strong>Password</strong>
		{!! Form::text('hotelpassword', null, array('placeholder' => 'Password','class' => 'form-control')) !!}
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
		<button type="submit" class="btn btn-primary submit-btn-hex">Update</button>
	</div>
</div>
{{ Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
	jQuery(document).ready(function($) {
		var newValue = [];
		$('.channel').addClass('active-menu');
		var _token = $('input[name="_token"]').val();
		var hotel_user = $('#hotel_user').val();
		var hotel_get = $('#hotel_name').val();
		$.ajax({
			url: "{{ route('edit_channel_hotel.fetch')}}",
			method: "POST",
			data: {
				_token: _token,
				hotel_id: hotel_user
			},
			success: function(data) {
				$.each(data, function(key, value) {
					if (value.id == hotel_user) {
						$('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title).attr('selected', 'selected'));
					} else {
						$('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
						newValue.push(value.id);
					}
				});
			}
		});
		// $.ajax({
		// 	url: "{{ route('channel_partner.fetch')}}",
		// 	method: "POST",
		// 	data: {
		// 		_token: _token
		// 	},
		// 	success: function(data) {
		// 		$.each(data, function(key, value) {
		// 			// if (value.id == channel_partner) {
		// 			// 	jQuery('#channel_partner').attr('value', value.id).text(value.name).attr('selected', 'selected');
		// 			// }
		// 			jQuery('#channel_partner').append($('<option></option>').attr('value', value.id).text(value.name));


		// 		});
		// 	}
		// });
		$('#hotel_name').change(function() {
			var hotel_get = $('#hotel_name').val();

			$.ajax({
				url: "{{ route('channel_user.fetch')}}",
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

		$('#hotel_name').select2({
			allowClear: true,
		});
		$('#channel_partner').select2({
			allowClear: true,
		});
	});
</script>
@endsection