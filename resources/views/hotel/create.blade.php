@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Hotel</h2>
  </div>
  <div class="col-sm-4 margin-tb">
    <div class="pull-right ml-2">
      <a class="eye1" href="{{ url()->previous() }}"> <i class="fas fa-long-arrow-alt-left"></i></a>
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
{!! Form::open(array('route' => 'hotel.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}

<div class="form form-padding">
  <div class="row mt-4">
    <!-- condition if hotel side or revenue manager logged in to system the RM and Revenue manager field will be hidden for them-->
    <input type="hidden" value="{{$user_id}}" id="user_id_session" name="user_id">
    <!-- @if(Auth::user()->user_role_define == '1') -->
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Hotel Branch Name:</strong>
        <select class="form-control" id="hotel_user_id">
          <option value="0">Select hotel user</option>

        </select>
      </div>
    </div>
    <!-- <input type = "text" name="user_id" id="user_id_branch"> -->
    <div class="col-xs-4 col-sm-4 col-md-4" id="rm_field">
      <div class="form-group">
        <strong>Relationship Manager User:</strong>
        <select class="form-control" id="single_select_rm" name='rm_user_id'>
          <option value="0">Select relationship manager</option>

        </select>
      </div>
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Revenue Manager User:</strong>
        <select class="form-control" id="revenue_user_id" name='revenue_user'>
          <option value="0">Select revenue manager</option>

        </select>
      </div>
    </div>
    @else
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Hotel Branch Name:</strong>
        <select class="form-control" id="hotel_user_id">
          <option value="0">Select hotel user</option>

        </select>
      </div>
    </div>
    @endif
    <div class="col-xs-12 col-sm-12 col-md-12">

      <div class="form-group">

        <strong>Hotel Name:</strong>

        {!! Form::text('title', null, array('placeholder' => 'Hotel name','class' => 'form-control','required')) !!}

      </div>

    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Same as hotel name:</strong>
        <label class="radio">
          {{ Form::radio('result','same',true,array('id' => 'yes')) }}<span class="show-data">Yes</span>
        </label>
        <label class="radio">
          {{ Form::radio('result','not_same',false,array('id' => 'no')) }}<span class="show-data">No</span>
        </label>
      </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Own Property?:</strong>
        <label class="radio">
          {{ Form::radio('own_property','1',true,array('id' => 'own_yes')) }}<span class="show-data">Yes</span>
        </label>
        <label class="radio">
          {{ Form::radio('own_property','0',false,array('id' => 'own_no')) }}<span class="show-data">No</span>
        </label>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group" id='displayName'>
        <strong>Display Name:</strong>
        {!! Form::text('display_name', null, array('placeholder' => 'Display name','class' => 'form-control')) !!}
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">

      <div class="form-group">

        <strong>Hotel Key Facilities:</strong>

        {!! Form::select('attribute_id[]', $fac,$default_attributes, array('class' => 'form-control','multiple','id' => 'multiple_select2','required')) !!}

      </div>

    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Note:</strong>
        {{ Form::textarea('note',null,['placeholder' => 'Note for hotel','class' => 'form-control','style' => 'height:60px'])}}
      </div>
    </div>

    <div class="container-fluid tabs-wrap">
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" class="nav-link font-weight-bold head-links lead active" href="#detail">Hotel Details</a></li>
        <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#address" id="address_tab">Address</a></li>
        <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#photo" id="photo_tab">Photos</a></li>
      </ul>

      <div class="tab-content">
        <div id="detail" class="tab-pane active">
          <h3 class="mt-4">Hotel Details</h3>

          <!-- 1st tab start-->
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <strong>Hotel Type:</strong>

                {{ Form::select('hoteltype_id',$hotel_type,null,['placeholder' => 'Select hotel type','class' => 'form-control','required'])}}
              </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

              <div class="form-group">
                <strong>Hotel Rating:</strong>

                {{ Form::select('rating',['5' => '5 Star','4' => '4 Star','3' => '3 Star','2' => '2 Star','1' => '1 Star','0' => 'No Star'],null,['placeholder' => 'Select star rating','class' => 'form-control'])}}
              </div>

            </div>
          </div>
          <div class="row">
            <div class='col-xs-6 col-sm-6 col-md-6'>
              <div class="form-group">
                <strong>Check In Time:</strong>
                {!! Form::text('check_in_time', null, array('placeholder' => 'Check in','class' => 'form-control','id' => 'check_in')) !!}
              </div>
            </div>

            <div class='col-xs-6 col-sm-6 col-md-6'>
              <div class="form-group">
                <strong>Check Out Time:</strong>
                {!! Form::text('check_out_time', null, array('placeholder' => 'Check out','class' => 'form-control','id' => 'check_out')) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">

              <div class="form-group">
                <strong>Hotel Description:</strong>

                {{ Form::textarea('hote_description',null,['placeholder' => 'Description','class' => 'form-control','style' => 'height:60px'])}}
              </div>

            </div>
          </div>
          <div class="text-center">
            <a class="btn btn-info btnNext">Next</a>
          </div>
        </div>

        <!-- End of 1st tab-->
        <!-- 2nd tab-->
        <div id="address" class="tab-pane">
          <h3 class="mt-4">Address</h3>
          <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4">
              <div class="form-group">
                @csrf
                <strong>Select City:</strong>
                <select name='location_id' id='city_id' class="form-control dynamic">
                  <option value="0">Select city</option>

                </select>
              </div>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4">
              <div class="form-group">
                <strong>Select Region:</strong>
                <select id="region" name='region_id' class="form-control">
                  <option value="0">Select region</option>

                </select>
              </div>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4">
              <div class="form-group">
                <strong>Select Area:</strong>
                <select name='area_id' class="form-control" id="area">
                  <option value="0">Select area</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">

              <div class="form-group">

                <strong>Longitude:</strong>

                {!! Form::text('longitude', null, array('placeholder' => 'Enter longitude','class' => 'form-control','required')) !!}

              </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

              <div class="form-group">

                <strong>Latitude:</strong>

                {!! Form::text('latitude', null, array('placeholder' => 'Enter latitude','class' => 'form-control','required')) !!}

              </div>

            </div>

          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">

              <div class="form-group">
                <strong>Hotel Address:</strong>

                {{ Form::textarea('address',null,['placeholder' => 'Enter address','class' => 'form-control','style' => 'height:60px'])}}
              </div>

            </div>
          </div>
          <div class="text-center">
            <a class="btn btn-info btnPrevious">Previous</a>
            <a class="btn btn-info btnNext">Next</a>
          </div>
        </div>
        <!--End of 2nd tab
   <div id="facility" class="tab-pane fade">
     


   </div>-->

        <!-- 3rd tab-->
        <div id="photo" class="tab-pane">
          <h3 class="mt-4">Photos</h3>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <strong>Hotel Facade Image:</strong>
                {!! Form::file('picture', array('class' => 'image form-control','required'))!!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <strong>Hotel Image:</strong>

              <div class="input-group control-group increment">
                <input type="file" name="pictures[]" multiple class="form-control" required="required">

              </div>

            </div>
          </div>

          <br />

          <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <a class="btn btn-info btnPrevious">Previous</a>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            @can('room_create')
            <div class="text-right">
              <button type="submit" class="btn btn-success" name="submit" value="proceed">Proceed to next</button>
            </div>
            @endcan
          </div>

        </div>
        <!-- End of 3rd tab -->
      </div>
    </div>
  </div>
</div>
{!! Form::close() !!}
<script>
  jQuery(document).ready(function($) {
    var newValue = [];
    $('.property').addClass('active-menu');
    $('.hotel').addClass('active-sub-menu');
    var _token = $('input[name="_token"]').val();
    var user_id_session = $('#user_id_session').val();
    $.ajax({
      url: "{{ route('hotel.fetch')}}",
      method: "POST",
      data: {
        _token: _token
      },
      success: function(data) {
        $.each(data, function(key, value) {
          if (value.id == user_id_session) {
            jQuery('#hotel_user_id').attr('value', value.id).text(value.name).attr('selected', 'selected');
          }
          jQuery('#hotel_user_id').append($('<option></option>').attr('value', value.id).text(value.name));
          newValue.push(value.id);
        });
      }
    });
    $('#hotel_user_id').change(function() {
      var branch_id = $('#hotel_user_id').val();
      $('#user_id_session').val(branch_id);
    });

    var option = [];

    $(document).on('keyup', '.select2-search__field', function(e) {
      var search_hotel = $(this).val();
      var _token = $('input[name="_token"]').val();
      if (search_hotel.length >= 2) {
        $.ajax({
          url: "{{ route('search_hotel.fetch')}}",
          method: "POST",
          data: {
            search_hotel: search_hotel,
            _token: _token
          },
          success: function(data) {
            for (var i = 0; i < data.length; i++) {
              let result = newValue.includes(data[i].id);
              if (result == false) {
                option[i] = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                newValue.push(data[i].id)
                $('#hotel_user_id').append(option[i]);
              }
            }
          }
        });
      }
    });

    $.ajax({
      url: "{{route('rm.fetch')}}",
      method: "POST",
      data: {
        _token: _token
      },
      success: function(data) {
        $.each(data, function(key, value) {
          jQuery('#single_select_rm').append($('<option></option>').attr('value', value.id).text(value.name));
        });
      }
    });

    $.ajax({
      url: "{{route('revenue.fetch')}}",
      method: "POST",
      data: {
        _token: _token
      },
      success: function(data) {
        $.each(data, function(key, value) {
          jQuery('#revenue_user_id').append($('<option></option>').attr('value', value.id).text(value.name));
        });
      }
    });

    //load all cities
    $.ajax({
      url: "{{ route('city.fetch')}}",
      method: "POST",
      data: {
        _token: _token
      },
      success: function(city) {
        $.each(city, function(key, value) {
          jQuery('#city_id').append($('<option></option>').attr('value', value.id).text(value.name));
        });
      }
    });

    //load all regions according to selected city
    $('#city_id').change(function() {
      var city_id = $('#city_id').val();
      $.ajax({
        url: "{{ route('region.fetch')}}",
        method: "POST",
        data: {
          _token: _token,
          city_id: city_id
        },
        success: function(region) {
          jQuery('#region').empty().append('<option selected="selected" value="">Select region</option>');
          jQuery('#area').empty().append('<option selected="selected" value="">Select area</option>');
          $.each(region, function(key, value) {
            jQuery('#region').append($('<option></option>').attr('value', value.id).text(value.name));
          });
        }
      });
    });

    //load all area according to selected regions
    $('#region').change(function() {
      var region = $('#region').val();
      $.ajax({
        url: "{{ route('area.fetch')}}",
        method: "POST",
        data: {
          _token: _token,
          region: region
        },
        success: function(area) {
          jQuery('#area').empty().append('<option selected="selected" value="">Select area</option>');
          $.each(area, function(key, value) {
            jQuery('#area').append($('<option></option>').attr('value', value.id).text(value.name));
          });
        }
      });
    });


    //next and previous button 
    $('.btnNext').click(function() {
      $('.nav-tabs > .active').next('li').find('a').trigger('click');
      $('.nav-tabs > .active').prev('li').find('a').removeClass('active');
      $('.nav-tabs > .active').find('a').addClass('active');
    });

    $('.btnPrevious').click(function() {
      $('.nav-tabs > .active').prev('li').find('a').trigger('click');
      $('.nav-tabs > .active').next('li').find('a').removeClass('active');
      $('.nav-tabs > .active').find('a').addClass('active');
    });
    //on load hide display name field
    var dvname = $('#displayName');
    dvname.css('display', 'none');
    $('#no').click(function() {
      dvname.css('display', 'block');
    })
    $('#yes').click(function() {
      dvname.css('display', 'none');
    })

    //select2 for dropdown 
    $('#hotel_user_id').select2({
      allowClear: true,
    });
    $('#single_select_rm').select2({
      allowClear: true,
    });
    $('#revenue_user_id').select2({
      allowClear: true,
    });
    $('#multiple_select2').select2({
      placeholder: 'Choose facilities',
      allowClear: true,

    });
    $('#city_id').select2({
      allowClear: true,
    });
    $('#region').select2({
      allowClear: true,
    });
    $('#area').select2({
      allowClear: true,
    });
    $('#multiple_select2').css('width', '-moz-available');


  });
</script>

{!! Form::close() !!}

@endsection