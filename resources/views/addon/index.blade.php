@extends('layouts.app')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
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
{!! Form::open(array('route' => 'addon.show','method'=>'POST')) !!}
<div class="form" style="margin-top:6%">
  <div class="row mt-4">
    <div class="col-sm-12">
      <h2 class="heder">Addon</h2>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name='hotel_id'>
          <option value="0">Select hotel</option>
          <!--  -->
        </select>
      </div>
    </div>
    <div class="col-sm-2">
      <button type="submit" class="btn btn-primary mt-4 submit-btn-hex" name="submit" value="submit">Submit</button>
    </div>
  </div>
</div>
{!!Form::close()!!}

<script>
  jQuery(document).ready(function($) {
    var newValue = [];
    $('.add-on').addClass('active-menu');
    $('.addon-inn').addClass('active-sub-menu');
    var _token = $('input[name="_token"]').val();
    $.ajax({
      url: "{{ route('addon_hotel.fetch')}}",
      method: "POST",
      data: {
        _token: _token
      },
      success: function(data) {
        $.each(data, function(key, value) {
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
          url: "{{ route('search_addon_hotel.fetch')}}",
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
  });
</script>
@endsection