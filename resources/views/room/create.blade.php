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

{!! Form::open(array('route' => 'room.store','method'=>'POST','enctype' => 'multipart/form-data' ,'id' => 'form_id')) !!}
<div class="form form-padding">
  <div class="row mt-4">
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name='hotel_id'>
          <option value="0">Select hotel</option>
          <input type="hidden" value="{{$hotel_id}}" id="hotel_id_session">
        </select>
      </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6" style="opacity: .75">
      <div class="form-group">
        <strong>User Name:</strong>
        <input type="text" name="user_name" placeholder="User name" class="form-control readonly" id="user_name" readonly>
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
        {{ Form::select('max_adult_allow',['1' => '1', '2' => '2', '3' => '3'], 3,['placeholder' => 'Max adult allowed','class' => 'form-control','required'])}}
      </div>
    </div>

    <div class="col-md-4 col-xs-4 col-sm-4">
      <div class="form-group">
        <strong>Max Child <small>(Age range 6-12yrs)</small>:</strong>
        {{ Form::select('max_child_allow',['0' => '0','1' => '1', '2' => '2'], 2,['placeholder' => 'Max child allowed','class' => 'form-control','required'])}}
      </div>
    </div>

    <div class="col-md-4 col-xs-4 col-sm-4">
      <div class="form-group">
        <strong>Max Infant <small>(Age range 0-5yrs)</small>:</strong>
        {{ Form::select('max_infant_allow',['0' => '0','1' => '1', '2' => '2'], 2,['placeholder' => 'Max infant allowed','class' => 'form-control','required'])}}
      </div>
    </div>

    <div class="col-md-12 col-xs-12 col-sm-12">
      <div class="form-group">
        <strong>Max Guest:</strong>
        {{ Form::select('max_guest_allow',['1' => '1', '2' => '2', '3' => '3','4'=>'4'], 4,['placeholder' => 'Max guest allowed','class' => 'form-control','required'])}}
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Room Facilities:</strong>
        {!! Form::select('facility_data[]', $facility,$default_facility, array('class' => 'form-control','multiple','id' => 'facility','required')) !!}
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Room Inclusion:</strong>
        {!! Form::select('inclusion_data[]', $inclusion,$default_inclusion, array('class' => 'form-control','multiple','id' => 'inclusion','required')) !!}
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Room Image:</strong>
        {!! Form::file('picture', array('class' => 'image form-control','required'))!!}
      </div>
    </div>

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
    $('.property').addClass('active-menu');
    $('.room').addClass('active-sub-menu');
    var _token = $('input[name="_token"]').val();
    var hotel_id_session = $('#hotel_id_session').val();
    $.ajax({
      url: "{{ route('room_hotel.fetch')}}",
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
          url: "{{ route('search_room_hotel.fetch')}}",
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
      url: "{{ route('room_user.fetch')}}",
      method: "POST",
      data: {
        hotel_get: hotel_id_session,
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
        url: "{{ route('room_user.fetch')}}",
        method: "POST",
        data: {
          hotel_get: hotel_get,
          _token: _token
        },
        success: function(data) {
          $.each(data, function(key, value) {
            jQuery('#user_name').val(value.name);
            jQuery('#user_id').val(value.id);
          });
        }
      });
    });

    $('#hotel_name').select2({
      allowClear: true,
    });

    $('#facility').select2({
      allowClear: true,
      placeholder: 'Choose room facilities',
    });
    $('#inclusion').select2({
      allowClear: true,
      placeholder: 'Choose room inclusion',
    });

  });
</script>
@endsection