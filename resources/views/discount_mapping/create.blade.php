@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Discount Mapping</h2>
  </div>
  <div class="col-sm-4 margin-tb">
    <div class="pull-right ml-2">
      <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
    </div>
  </div>
</div>

@if (count($errors) > 0)
<div class="alert alert-danger mt-1">
  <strong>Whoops!</strong> There were some problems with your input.<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
{!! Form::open(array('route' => 'discount_mapping.store','method'=>'POST')) !!}
<div class="form form-padding">
  <div class="row mt-4">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name="hotel_id">
          <option value="0">Select hotel</option>
          <input type="hidden" value="{{$hotel_id}}" id="hotel_id_session">
        </select>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Choose Your Weekdays <small>(unchecked days will be considered as weekends)</small></strong>
        <div style="text-align: left">
          <input type="checkbox" name="mon" checked><label class="ml-3 show-data">Monday</label>
          <input type="checkbox" name="tue" class="ml-1" checked><label class="ml-3 show-data">Tuesday</label>
          <input type="checkbox" name="wed" class="ml-1" checked><label class="ml-3 show-data">Wednesday</label>
          <input type="checkbox" name="thu" class="ml-1" checked><label class="ml-3 show-data">Thursday</label>
          <input type="checkbox" name="fri" class="ml-1" checked><label class="ml-3 show-data">Friday</label>
          <input type="checkbox" name="sat" class="ml-1"><label class="ml-3 show-data">Saturday</label>
          <input type="checkbox" name="sun" class="ml-1"><label class="ml-3 show-data">Sunday</label>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group  mb-4">
        <h3 style="text-align: center;" class="card-show-dis pt-3 pb-3 card1">Discount Mapping For Same Day Check In</h3>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <h4>Time Slot</h4>
            <ul>
              <li>12 am - 6 am</li>
              <li>6 am - 9 am</li>
              <li>9 am - 12 pm</li>
              <li>12 pm - 3 pm</li>
              <li>3 pm onwards</li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="box">
            <h4>Weekday Discount</h4>
            <ul>
              <li>{!! Form::select('today_12_6_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_6_9_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_9_12_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_12_3_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_3_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="box">
            <h4>Weekend Discount</h4>
            <ul>
              <li>{!! Form::select('today_12_6_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_6_9_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_9_12_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_12_3_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('today_3_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <h3 style="text-align: center;" class="card-show-dis pt-3 pb-3 mt-2 mb-4 card1">Discount Mapping for Subsequent Day Check In</h3>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <h4>Inventory</h4>
            <ul>
              <li>0-2 rooms left</li>
              <li>3-5 rooms left</li>
              <li>6-8 rooms left</li>
              <li>9-10 rooms left</li>
              <li>more than 10 rooms left</li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="box">
            <h4>Weekday Discount</h4>
            <ul>
              <li>{!! Form::select('tom_2_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_5_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_8_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_10_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_11_sdis',$percentage,15,array('class'=> 'form-control')) !!}</li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="box">
            <h4>Weekend Discount</h4>
            <ul>
              <li>{!! Form::select('tom_2_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_5_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_8_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_10_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
              <li>{!! Form::select('tom_11_edis',$percentage,15,array('class'=> 'form-control')) !!}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xs-12 mt-3 col-sm-12 col-md-12 text-center">
      <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
      @can('addon')
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
    $('.discount').addClass('active-menu');
    var _token = $('input[name="_token"]').val();
    var hotel_id_session = $('#hotel_id_session').val();
    $.ajax({
      url: "{{ route('discount_hotel.fetch')}}",
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
          url: "{{ route('search_discount_hotel.fetch')}}",
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