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
{!! Form::model($contact, ['method' => 'PATCH','route' => ['contact.update', $contact->id],'enctype' => 'multipart/form-data','id'=>'form']) !!}
<div class="form form-padding">
	<div class="row mt-4">
		<div class="col-xs-6 col-sm-6 col-md-6">
			<div class="form-group">
				<strong>Hotel Name:</strong>
				<select class="form-control" id="hotel_name" name='hotel_id'>
					<option value="0"></option>
				</select>
				<input type="hidden" value={{$contact->hotel_id}} id="hotel_user">
				<input type="hidden" value={{$contact->user_id}} id="hotel_user_id">
			</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6" style="opacity: .75">
			<div class="form-group">
				<strong>User Name:</strong>
				<input type="text" name="user_name" placeholder="User name" class="form-control readonly" id="user_name" readonly>
				<input type="hidden" name="user_id" id="user_id">
			</div>
		</div>
	</div>
	<h3>Primary Details</h3>
	<div class="form-group row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Primary Email:</strong>
			{!! Form::text('pemail', null, array('placeholder' => 'Primary Email ','class' => 'form-control','id'=>'primary_email_id','required')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Primary Mobile:</strong>
			{!! Form::number('pmobile', null, array('placeholder' => 'Primary Mobile','class' => 'form-control','id'=>'primary_mobile','required')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Designation:</strong>
			{!! Form::text('pdesignation', null, array('placeholder' => 'Primary Designation','class' => 'form-control','required')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('pnego',$contact->pnego) !!}
				<small class="show-data"> Price Negotiable Email Subscribe</small></label>
		</div>
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('pvoucher',$contact->pvoucher) !!}
				<small class="show-data"> Booking Voucher Email Subscribe</small></label>
		</div>
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('psms',$contact->psms) !!}
				<small class="show-data"> SMS Subscribe</small></label>
		</div>
	</div>

	<h3>Secondary Details</h3>
	<div class="form-group row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Secondary Email:</strong>
			{!! Form::text('semail', null, array('placeholder' => 'Secondary Email ','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Secondary Mobile:</strong>
			{!! Form::number('smobile', null, array('placeholder' => 'Secondary Mobile ','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Designation:</strong>
			{!! Form::text('sdesignation', null, array('placeholder' => 'Secondary Designation','class' => 'form-control')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('snego',$contact->snego) !!}
				<small class="show-data"> Price Negotiable Email Subscribe</small></label>
		</div>
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('svoucher',$contact->svoucher) !!}
				<small class="show-data"> Booking Voucher Email Subscribe</small></label>
		</div>
		<div class="checkbox">
			<label style="font-size:20px;">
				{!! Form::checkbox('ssms',$contact->ssms) !!}
				<small class="show-data"> SMS Subscribe</small></label>
		</div>
	</div>
	<h3>Reception Details</h3>
	<div class="form-group row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Email:</strong>
			{!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Mobile Number:</strong>
			{!! Form::number('mobile', null, array('placeholder' => 'Mobile','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Phone Number:</strong>
			{!! Form::number('phone_no', null, array('placeholder' => 'Phone Number','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<strong>Website:</strong>
			{!! Form::text('website', null, array('placeholder' => 'Website','class' => 'form-control')) !!}
		</div>
	</div>
	<h3>Account Details</h3>
	<div class="form-group row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Account Email:</strong>
			{!! Form::text('aemail', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Account Mobile:</strong>
			{!! Form::number('amobile', null, array('placeholder' => 'Mobile','class' => 'form-control')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<!-- <label>Email</label> -->
			<strong>Account Phone Number:</strong>
			{!! Form::number('aphone', null, array('placeholder' => 'Phone number','class' => 'form-control')) !!}
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
		<button type="submit" class="btn btn-primary submit-btn-hex" id="update">Update</button>
	</div>
</div>
{{ Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
	jQuery(document).ready(function($) {
		var newValue = [];
		$('.property').addClass('active-menu');
		$('.contact').addClass('active-sub-menu');
		var _token = $('input[name="_token"]').val();
		var hotel_user = $('#hotel_user').val();
		var hotel_user_id = $('#hotel_user_id').val();
		var hotel_get = $('#hotel_name').val();
		$.ajax({
			url: "{{ route('edit_room_hotel.fetch')}}",
			method: "POST",
			data: {
				_token: _token,
				hotel_user: hotel_user
			},
			success: function(data) {
				console.log(data);
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
		// 	url:"{{ route('edit_room_hotel.fetch')}}",
		// 	method:"POST",
		// 	data:{_token:_token,hotel_id:hotel_user},
		// 	success:function(data)
		// 	{
		// 		$.each(data,function(key,value){
		// 			jQuery('#hotel_name').append($('<option></option>').attr('value',value.id).text(value.title).attr('selected','selected'));
		// 		});
		// 	}
		// });

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
				hotel_get: hotel_user,
				_token: _token
			},
			success: function(data) {
				$.each(data, function(key, value) {
					jQuery('#user_name').val(value.name);
					jQuery('#user_id').val(value.id);
				});
			}
		});

		$('#hotel_name').change(function() {
			var hotel_get = $('#hotel_name').val();

			$.ajax({
				url: "{{ route('contact_user.fetch')}}",
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
		$("form").submit(function() {

			var this_master = $(this);

			this_master.find('input[type="checkbox"]').each(function() {
				var checkbox_this = $(this);


				if (checkbox_this.is(":checked") == true) {
					checkbox_this.attr('value', '1');
				} else {
					checkbox_this.prop('checked', true);
					//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA    
					checkbox_this.attr('value', '0');
				}
			})
		});

		$('#hotel_name').select2({
			allowClear: true,
		});
	});
</script>
@endsection