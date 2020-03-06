@extends('layouts.app')


@section('content')

<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Hotel</h2>
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

{!! Form::model($hotel, ['method' => 'PATCH','route' => ['hotel.update', $hotel->id],'enctype' => 'multipart/form-data']) !!}

<div class="form form-padding">
  <div class="row mt-4">
    <!-- condition if hotel side or revenue manager logged in to system the RM and Revenue manager field will be hidden for them-->
    @if(Auth::user()->user_role_define == '1')
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Hotel Branch Name:</strong>
        <select class="form-control" id="single_select2" name='user_id'>
          <option value="0">Select hotel user</option>
        </select>
        <input type="hidden" value={{$hotel->user_id}} id="hotel_user">
      </div>
    </div>


    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Relationship Manager User:</strong>
        {!! Form::select('rm_user_id', $rm,$hotel->rm_user_id, array('class' => 'form-control','id'=>'single_select_rm','required')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Revenue Manager User:</strong>
        {!! Form::select('revenue_user', $revenue,$hotel->revenue_user, array('class' => 'form-control','id'=>'revenue_user_id')) !!}
      </div>
    </div>


    @else
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Hotel Branch Name:</strong>
        <select class="form-control" id="single_select2" name='user_id'>
          <option value="0">Select hotel user</option>
        </select>
        <input type="hidden" value={{$hotel->user_id}} id="hotel_user">
      </div>
    </div>
    <div style="display: none">
      <div style="display: none">
        <div class="col-xs-4 col-sm-4 col-md-4">
          <div class="form-group">
            <strong>Relationship Manager User:</strong>
            {!! Form::select('rm_user_id', $rm,$hotel->rm_user_id, array('class' => 'form-control','id'=>'single_select_rm','required')) !!}
          </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4">
          <div class="form-group">
            <strong>Revenue Manager User:</strong>
            {!! Form::select('revenue_user', $revenue,$hotel->revenue_user, array('class' => 'form-control','id'=>'revenue_user_id')) !!}
          </div>
        </div>
      </div>
      @endif
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
          <strong>Hotel Name:</strong>
          {!! Form::text('title', null, array('placeholder' => 'Hotel name','class' => 'form-control')) !!}
        </div>
      </div>

      <script type="text/javascript">
        function hideField() {
          var chkYes = document.getElementById("yes");
          var dvname = document.getElementById("displayName");
          dvname.style.display = chkYes.checked ? "none" : "block";
        }
      </script>
      <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
          <strong>Same as hotel name:</strong>
          <label class="radio">
            {{ Form::radio('result','same',false,array('onclick' => 'hideField()','id' => 'yes')) }}<span class="show-data">Yes</span>
          </label>
          <label class="radio">
            {{ Form::radio('result','not_same',true,array('onclick' => 'hideField()','id' => 'no')) }}<span class="show-data">No</span>
          </label>
        </div>
      </div>

      <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
          <strong>Own Property?:</strong>
          <label class="radio">
            {{ Form::radio('own_property','1',null,array('id' => 'own_yes')) }}<span class="show-data">Yes</span></label>
          <label class="radio">
            {{ Form::radio('own_property','0',null,array('id' => 'own_no')) }}<span class="show-data">No</span></label>
        </div>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group" id='displayName'>

          <strong>Display Name:</strong>

          {!! Form::text('display_name', null, array('placeholder' => 'Display name','class' => 'form-control')) !!}

        </div>

      </div>


      <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group" id='displayName'>

          <strong>Hotel Key Facilities:</strong>

          {!! Form::select('attribute_id[]',$fac,$pre_facility, array('class' => 'form-control','multiple','id'=>'multiple_select2','required')) !!}

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
          <li class="active"><a data-toggle="tab" class="nav-link font-weight-bold lead head-links active" href="#detail">Hotel Details</a></li>
          <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#address" id="address_tab">Address</a></li>
          <li><a class="nav-link font-weight-bold head-links lead" data-toggle="tab" href="#photo" id="photo_tab">Photos</a></li>
        </ul>

        <!-- 1st tab-->
        <div class="tab-content">
          <div id="detail" class="tab-pane active">
            <h3 class="mt-4">Hotel Details</h3>
            <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                  <strong>Hotel Type:</strong>
                  {{ Form::select('hoteltype_id',$hotel_type,null,['placeholder' => 'Select hotel type','class' => 'form-control'])}}
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

          <!--End of 1st tab-->
          <!-- 2nd tab-->
          <div id="address" class="tab-pane">
            <h3 class="mt-4">Address</h3>
            <div class="row">
              <div class="col-xs-4 col-sm-4 col-md-4">
                <div class="form-group">

                  @csrf
                  <strong>Select City:</strong>
                  {{ Form::select('location_id',$city_list,null, array('placeholder' => 'Select city','class' => 'form-control','id' => 'city_id','required')) }}
                  <!-- <select name='location_id' id='city_id' class="form-control dynamic">
                    <option>Select city</option>
                  </select> -->
                  <input type="hidden" name="" id="city_input_db" value={{ $hotel->location_id }}>
                </div>
              </div>

              <div class="col-xs-4 col-sm-4 col-md-4">
                <div class="form-group">
                  <strong>Select Region:</strong>
                  <select id="region" name='region_id' class="form-control">
                    <option value="0">Select region</option>
                  </select>
                  <input type="hidden" name="" id="region_input_db" value={{ $hotel->region_id }}>
                </div>
              </div>

              <div class="col-xs-4 col-sm-4 col-md-4">
                <div class="form-group">
                  <strong>Select Area:</strong>
                  <select name='area_id' class="form-control" id="area">
                    <option value="0">Select area</option>
                  </select>
                  <input type="hidden" name="" id="area_input_db" value={{ $hotel->area_id }}>
                </div>
              </div>
            </div>
            <!-- <script >
            //this is script to select city region and area from drop downs
            //below menthion code for on click functionality of city drop down 
            jQuery(document).ready(function($){ 
              $('#city_id').click(function(){
                if($('#city_id').val() != '')
                {
                  var citsec = $('#city_id option:selected').val();
                  var _token = $('input[name="_token"]').val();
                  $.ajax({
                    url:"{{ route('region.fetch')}}",
                    method: "POST",
                    data:{citsec:citsec,_token:_token},
                    success:function(region)
                    {
                      jQuery('#region').empty().append('<option selected="selected" value="">Select region</option>');
                      $.each(region, function (key,value) {
                        jQuery('#region').append($('<option></option>').attr('value', value.id).text(value.name));
                      });
                    }
                  })
                }
              });
            // below methion code is for select regions on click of region dropdown
            $('#region').click(function(){
              if($('#region').val() != '')
              {
                var regionsec = $('#region').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                  url:"{{ route('area.fetch')}}",
                  method: "POST",
                  data:{regionsec:regionsec,_token:_token},
                  success:function(area)
                  {
                    jQuery('#area').empty().append('<option selected="selected" value="">Select area</option>');
                    $.each(area, function (key,value) {
                      jQuery('#area').append($('<option></option>').attr('value', value.id).text(value.name));
                    });
                  }
                })
              }
            });
          });
            //code for to pre select area and regions 
            jQuery(document).ready(function($){
              var area_input = $('#area_input_db').val();
              var citsec = $('#city_id option:selected').val();
              var region_input = $('#region_input_db').val();
              var _token = $('input[name="_token"]').val();
              $.ajax({
                url:"{{ route('region.fetch')}}",
                method: "POST",
                data:{citsec:citsec,_token:_token},
                success:function(region)
                {
                  $.each(region, function (key,value) {
                    if (value.id == region_input)
                    {
                      jQuery('#region').attr('value',value.id).text(value.name).attr('selected','selected');
                    }
                    jQuery('#region').append($('<option></option>').attr('value', value.id).text(value.name));
                  });
                }
              })
              $.ajax({
                url:"{{ route('area.fetch')}}",
                method: "POST",
                data:{regionsec:region_input,_token:_token},
                success:function(area)
                {
                  $.each(area, function (key,value) {
                    if(value.id == area_input){
                      jQuery('#area').attr('value',value.id).text(value.name).attr('selected','selected');
                    }
                    jQuery('#area').append($('<option></option>').attr('value', value.id).text(value.name));
                  });
                }
              })
            });
          </script> -->

            <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group" id='displayName'>
                  <strong>Longitude:</strong>
                  {!! Form::text('longitude', null, array('placeholder' => 'Enter longitude','class' => 'form-control','required')) !!}
                </div>
              </div>

              <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group" id='displayName'>
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
          <!--End of 2nd tab-->
          <!--<div id="facility" class="tab-pane fade">
       <h3 class="mt-4">Hotel Facility</h3>

          

     </div>

     End of 3rd tab-->
          <!--3rd tab-->
          <div id="photo" class="tab-pane">
            <h3 class="mt-4">Photos</h3>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">

                  <strong>Hotel Facade Image:</strong>

                  {!! Form::file('picture', array('class' => 'image form-control'))!!}

                </div>

              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>Hotel Image:</strong>

                <div class="input-group control-group increment">
                  <input type="file" name="pictures[]" multiple class="form-control">
                </div>

              </div>
            </div>
            <br />

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <a class="btn btn-info btnPrevious">Previous</a>
              <button type="submit" class="btn btn-primary form-btn-hex">Update</button>

            </div>

          </div>
          <!-- End of 3rd tab-->

        </div>
      </div>
    </div>
  </div>
  {{ Form::hidden('url',URL::previous())  }}
  {!! Form::close() !!}
  <script>
    jQuery(document).ready(function($) {
      var newValue = [];
      $('.property').addClass('active-menu');
      $('.hotel').addClass('active-sub-menu');
      var _token = $('input[name="_token"]').val();
      var hotel_user = $('#hotel_user').val();
      var rm_user = $('#rm_user').val();
      var revenue_user = $('#revenue_user_db').val();
      var old_city = $('#city_input_db').val();
      var old_region = $('#region_input_db').val();
      var old_area = $('#area_input_db').val();

      $.ajax({
        url: "{{ route('edit_hotel.fetch')}}",
        method: "POST",
        data: {
          _token: _token,
          hotel_user: hotel_user
        },
        success: function(data) {
          $.each(data, function(key, value) {
            if (value.id == hotel_user) {
              $('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name).attr('selected', 'selected'));
            } else {
              $('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name));
              newValue.push(value.id);
            }
          });
        }
      });
      // $.ajax({
      //   url:"{{ route('edit_hotel.fetch')}}",
      //   method:"POST",
      //   data:{_token:_token,hotel_user:hotel_user},
      //   success:function(data)
      //   {
      //     $.each(data, function (key,value) {
      //       $('#single_select2').append($('<option></option>').attr('value', value.id).text(value.name).attr('selected','selected'));
      //     });
      //   }
      // });

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
                console.log(data[i]);
                let result = newValue.includes(data[i].id);
                if (result == false) {
                  option[i] = '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                  newValue.push(data[i].id)
                  $('#single_select2').append(option[i]);
                }
              }
            }
          });
        }
      });


      //load all cities
      // $.ajax({
      //   url: "{{ route('city.fetch')}}",
      //   method: "POST",
      //   data: {
      //     _token: _token
      //   },
      //   success: function(city) {
      //     $.each(city, function(key, value) {
      //       if (value.id == old_city) {
      //         jQuery('#city_id').html($('<option selected="selected"></option>').attr('value', value.id).text(value.name));
      //       }
      //       jQuery('#city_id').append($('<option></option>').attr('value', value.id).text(value.name));
      //     });
      //   }
      // });

      $.ajax({
        url: "{{ route('region.fetch')}}",
        method: "POST",
        data: {
          _token: _token,
          city_id: old_city
        },
        success: function(region) {
          $.each(region, function(key, value) {
            if (value.id == old_region) {
              jQuery('#region').html($('<option selected="selected"></option>').attr('value', value.id).text(value.name));
            }
            jQuery('#region').append($('<option></option>').attr('value', value.id).text(value.name));
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

      $.ajax({
        url: "{{ route('area.fetch')}}",
        method: "POST",
        data: {
          _token: _token,
          region: old_region
        },
        success: function(area) {
          $.each(area, function(key, value) {
            if (value.id == old_area) {
              jQuery('#area').html($('<option selected="selected"></option>').attr('value', value.id).text(value.name));
            }
            jQuery('#area').append($('<option></option>').attr('value', value.id).text(value.name));
          });
        }
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

      //select2 for dropdown 
      $('#single_select_rm').select2({
        allowClear: true,
      });
      $('#revenue_user_id').select2({
        allowClear: true,
      });
      $('#single_select2').select2({
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

      $('#multiple_select2').select2({
        allowClear: true,
        placeholder: 'Choose facilities',
      });
      $('#multiple_select2').css('width', '-moz-available');
    });
  </script>
  @endsection