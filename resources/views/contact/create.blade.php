@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12 mt-4">
		<h2 class="heder">Contact</h2>
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
{!! Form::open(array('route' => 'contact.store','method'=>'POST')) !!}
@csrf
<div class="form form-padding">
	<div class="row mt-4">
		<div class="col-xs-6 col-sm-6 col-md-6 form-group">
			<strong>Hotel Name:</strong>
			<select class="form-control" id="hotel_name" name='hotel_id'>
				<option value="0">Select hotel</option>
				<input type="hidden" value="{{$hotel_id}}" id="hotel_id_session">
			</select>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 form-group" style="display: none">
			<strong>User Id:</strong>
			<input type="hotel-Detail" name="user_id" id="user_id" class="form-control" placeholder="ID" readonly />

		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 form-group" style="opacity: .75">
			<strong>User Name:</strong>
			<input type="hotel-Detail" name="hotel-Detail" id="user_name" class="form-control readonly" placeholder="User name" readonly />
		</div>
	</div>
	<h3>Primary Details</h3>
	<div class="form-group row">
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Primary Email:</strong>
			{!! Form::text('pemail', null, array('placeholder' => 'Primary Email ','class' => 'form-control','id'=>'primary_email_id','required')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Primary Mobile:</strong>
			{!! Form::number('pmobile', null, array('placeholder' => 'Primary Mobile','class' => 'form-control','id'=>'primary_mobile','required')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Designation:</strong>
			{!! Form::text('pdesignation', null, array('placeholder' => 'Primary Designation','class' => 'form-control','required')) !!}
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" checked="checked" name="pnego" value="1"><small class="show-data"> Price Negotiable Email Subscribe</small></label>
		</div>
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" checked="checked" name="pvoucher" value="1"><small class="show-data"> Booking Voucher Email Subscribe</small></label>

		</div>
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" checked="checked" name="psms" value="1"><small class="show-data"> SMS Subscribe</small></label>


		</div>
	</div>

	<h3>Secondary Details</h3>
	<div class="form-group row">
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Secondary Email:</strong>
			{!! Form::text('semail', null, array('placeholder' => 'Secondary Email ','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Secondary Mobile:</strong>
			{!! Form::number('smobile', null, array('placeholder' => 'Secondary Mobile ','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Designation:</strong>
			{!! Form::text('sdesignation', null, array('placeholder' => 'Secondary Designation','class' => 'form-control')) !!}
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" checked="checked" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" name="snego" value="1"><small class="show-data"> Price Negotiable Email Subscribe</small></label>
		</div>
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" checked="checked" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" name="svoucher" value="1"><small class="show-data"> Booking Voucher Email Subscribe</small></label>

		</div>
		<div class="col-sm-4">
			<label style="font-size:20px;"> <input type="checkbox" checked="checked" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" name="ssms" value="1"><small class="show-data"> SMS Subscribe</small></label>
		</div>
	</div>
	<h3>Reception Details</h3>
	<div class="form-group row">
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Email:</strong>
			{!! Form::text('email', null, array('placeholder' => 'Reception email','class' => 'form-control','required')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Mobile Number:</strong>
			{!! Form::number('mobile', null, array('placeholder' => 'Reception mobile','class' => 'form-control','required')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Phone Number:</strong>
			{!! Form::number('phone_no', null, array('placeholder' => 'Phone Number','class' => 'form-control','required')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<strong>Website:</strong>
			{!! Form::text('website', null, array('placeholder' => 'Website','class' => 'form-control')) !!}
		</div>
	</div>
	<h3>Account Details</h3>
	<div class="form-group row">
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Account Email:</strong>
			{!! Form::text('aemail', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Account Mobile:</strong>
			{!! Form::number('amobile', null, array('placeholder' => 'Mobile','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<!-- <label>Email</label> -->
			<strong>Account Phone Number:</strong>
			{!! Form::number('aphone', null, array('placeholder' => 'Phone number','class' => 'form-control')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-xs-12 col-sm-12 col-md-12 text-center">
			<button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
			@can('commission_create')
			<div class="text-right">
				<button type="submit" class="btn btn-success" name="submit" value="proceed">Proceed to next</button>
			</div>
			@endcan
		</div>
	</div>
</div>

{!! Form::close() !!}
<script>
	jQuery(document).ready(function($) {
		var newValue = [];
		$('.contact').addClass('active-sub-menu');
		$('.property').addClass('active-menu');
		var _token = $('input[name="_token"]').val();
		var hotel_id_session = $('#hotel_id_session').val();
		$.ajax({
			url: "{{ route('contact_hotel.fetch')}}",
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

		var option = [];

		$(document).on('keyup', '.select2-search__field', function(e) {
			var search_hotel = $(this).val();
			var _token = $('input[name="_token"]').val();
			if (search_hotel.length >= 2) {
				$.ajax({
					url: "{{ route('search_contact_hotel.fetch')}}",
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

		$.ajax({
			url: "{{ route('contact_user.fetch')}}",
			method: "POST",
			data: {
				hotel_get: hotel_id_session,
				_token: _token
			},
			success: function(data) {
				$.each(data, function(key, value) {
					jQuery('#user_name').val(value.name);
					jQuery('#user_id').val(value.id);
					jQuery('#primary_email_id').val(value.primary_email);
					jQuery('#primary_mobile').val(value.mobile);
				});
			}
		});
		$('#hotel_name').change(function() {
			var hotel_get = $('#hotel_name').val();
			$.ajax({
				url: "{{route('contact_user.fetch')}}",
				method: "POST",
				data: {
					hotel_get: hotel_get,
					_token: _token
				},
				success: function(data) {
					$.each(data, function(key, value) {
						jQuery('#user_name').val(value.name);
						jQuery('#user_id').val(value.id);
						jQuery('#primary_email_id').val(value.primary_email);
						jQuery('#primary_mobile').val(value.mobile);
					});
				}
			});
		});
		$('#hotel_name').select2({
			allowClear: true,
		});
	});
</script>

@endsection