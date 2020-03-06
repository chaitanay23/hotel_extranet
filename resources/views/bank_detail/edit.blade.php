@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Bank Details</h2>
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
{!! Form::model($bank, ['method' => 'PATCH','route' => ['bank_detail.update', $bank->id]]) !!}
<div class="form form-padding">
  <div class="row mt-4">
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name='hotel_id'>
          <option value="0">Select hotel</option>
        </select>
        <input type="hidden" value={{$bank->hotel_id}} id="hotel_user">
        <input type="hidden" value={{$bank->user_id}} id="hotel_user_id">
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6" style="opacity: .75">
      <div class="form-group">
        <strong>User Name:</strong>
        <input type="text" name="user_name" placeholder="User name" class="form-control readonly" id="user_name" readonly>
        <input type="hidden" name="user_id" id="user_id">
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Account Number:</strong>
        {!! Form::number('account_no', null, array('placeholder' => 'Account number','class' => 'form-control','required'=>'required')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Account Holder:</strong>
        {!! Form::text('account_holder', null, array('placeholder' => 'Account holder name','class' => 'form-control','required')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Branch Name:</strong>
        {!! Form::text('branch_name', null, array('placeholder' => 'Branch name','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>IFSC Code:</strong>
        {!! Form::text('ifsc_code', null, array('placeholder' => 'IFSC code','class' => 'form-control','required')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Branch Code:</strong>
        {!! Form::text('branch_code', null, array('placeholder' => 'Branch code','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Bank Name:</strong>
        {!! Form::select('bank_id', $bank_all,null, array('placeholder' => 'Select bank','class' => 'form-control','id' => 'bank_all','required')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Bank Code:</strong>
        {!! Form::text('bank_code', null, array('placeholder' => 'Bank code','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>PAN Number:</strong>
        {!! Form::text('pan_number', null, array('placeholder' => 'PAN number','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
        <strong>Name on PAN card:</strong>
        {!! Form::text('name_of_pancard', null, array('placeholder' => 'Name on pan card','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Service Tax Number:</strong>
        {!! Form::text('service_tx_no', null, array('placeholder' => 'Service tax number','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>GST Number:</strong>
        {!! Form::text('gst_number', null, array('placeholder' => 'GST number','class' => 'form-control','required')) !!}
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
        <strong>Payment Option:</strong>
        {!! Form::select('payment_id', ['1' => 'National Electronic Funds Transfer (NEFT)', '2' => 'Credit Card'],null, array('placeholder' => 'Select payment option','class' => 'form-control')) !!}
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
      <button type="submit" class="btn btn-primary submit-btn-hex">Update</button>
    </div>
  </div>
</div>
{{ Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
  jQuery(document).ready(function($) {
    var newValue = [];
    $('.commission').addClass('active-menu');
    $('.bank').addClass('active-sub-menu');
    var _token = $('input[name="_token"]').val();
    var hotel_user = $('#hotel_user').val();
    var hotel_user_id = $('#hotel_user_id').val();
    var hotel_get = $('#hotel_name').val();
    $.ajax({
      url: "{{ route('edit_bank_detail_hotel.fetch')}}",
      method: "POST",
      data: {
        _token: _token,
        hotel_user: hotel_user
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
    //   url:"{{ route('edit_bank_detail_hotel.fetch')}}",
    //   method:"POST",
    //   data:{_token:_token,hotel_id:hotel_user},
    //   success:function(data)
    //   {
    //     $.each(data,function(key,value){
    //       jQuery('#hotel_name').append($('<option></option>').attr('value',value.id).text(value.title).attr('selected','selected'));  
    //     });
    //   }
    // });

    $.ajax({
      url: "{{ route('bank_detail_user.fetch')}}",
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

    var option = [];

    $(document).on('keyup', '.select2-search__field', function(e) {
      var search_hotel = $(this).val();
      var _token = $('input[name="_token"]').val();
      if (search_hotel.length >= 2) {
        $.ajax({
          url: "{{ route('search_bank_hotel.fetch')}}",
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
    $('#bank_all').select2({
      allowClear: true,
    });
  });
</script>
@endsection