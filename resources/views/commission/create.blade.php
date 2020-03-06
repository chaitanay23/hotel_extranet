@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Biddr Commissions</h2>
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

{!! Form::open(array('route' => 'commission.store','method'=>'POST')) !!}
<div class="form form-padding">
  <div class="row mt-4">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name='hotel_id'>
          <option value="0">Select hotel</option>
          <input type="hidden" value="{{$hotel_id}}" id="hotel_id_session">
        </select>
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Commission (in %):</strong>
        {!! Form::number('commission', null, array('placeholder' => 'Commission in %','class' => 'form-control','required')) !!}
      </div>
    </div>
    @can('commission_delete')
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Pass-Back-Commission (in %):</strong>
        {!! Form::number('pbc', null, array('placeholder' => 'Pass-Back-Commission in %','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Comments:</strong>
        {{ Form::textarea('comment',null,['placeholder' => 'Comments for pass-back-commission','class' => 'form-control','style' => 'height:60px'])}}
      </div>
    </div>
    @endcan
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>TDS on Commission (in %):</strong>
        {!! Form::number('tds',10, array('placeholder' => 'TDS on Commission in %','class' => 'form-control')) !!}
      </div>
    </div>
    @can('commission_delete')
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Biddr Fee:</strong>
        {!! Form::number('magicspree_fee', null, array('placeholder' => 'Biddr fee','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Additional Discount (in %):</strong>
        {!! Form::number('additional_discount', null, array('placeholder' => 'Additional discount','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Maximum Discount:</strong>
        {!! Form::number('max_discount', null, array('placeholder' => 'Maximum discount','class' => 'form-control')) !!}
      </div>
    </div>
    @endcan
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
      <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
      @can('bank_detail_create')
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
    $('.commission').addClass('active-menu');
    $('.ms_com').addClass('active-sub-menu');
    var _token = $('input[name="_token"]').val();
    var hotel_id_session = $('#hotel_id_session').val();
    $.ajax({
      url: "{{ route('commission_hotel.fetch')}}",
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
          url: "{{ route('search_commission_hotel.fetch')}}",
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