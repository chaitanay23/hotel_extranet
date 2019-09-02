@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">MagicSpree Commissions</h2>
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

{!! Form::model($commission, ['method' => 'PATCH','route' => ['commission.update', $commission->id]]) !!}
<div class="form form-padding">
<div class="row mt-4">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <strong>Hotel Name:</strong>
        <select class="form-control" id="hotel_name" name = 'hotel_id'>
          <option value="0">Select hotel</option> 
          <input type="hidden" value={{$commission->hotel_id}} id="hotel_user">
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
      {!! Form::number('tds', null, array('placeholder' => 'TDS on Commission in %','class' => 'form-control')) !!}
    </div>
  </div>
  @can('commission_delete')
  <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      <strong>MagicSpree Fee (in %):</strong>
      {!! Form::number('magicspree_fee', null, array('placeholder' => 'MagicSpree fee','class' => 'form-control')) !!}
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
      <button type="submit" class="btn btn-primary submit-btn-hex">Update</button>
  </div>
</div>
</div>
{{  Form::hidden('url',URL::previous())  }}
{!! Form::close() !!}
<script>
jQuery(document).ready(function($){
  $('.commission').addClass('active-menu');
  $('.ms_com').addClass('active-sub-menu');
  var _token = $('input[name="_token"]').val();
  var hotel_user = $('#hotel_user').val();
  $.ajax({
    url:"{{ route('edit_commission_hotel.fetch')}}",
    method:"POST",
    data:{_token:_token,hotel_user:hotel_user},
    success:function(data)
    {
      $.each(data, function (key,value) {
        if(value.id == hotel_user)
        {
          $('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title).attr('selected','selected'));
        }
        else{
          $('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
        }
      });
    }
  });

  $(document).on('keyup','.select2-search__field',function(e){
    var search_hotel = $(this).val();
    var _token = $('input[name="_token"]').val();
    var newValue = [];
    if(search_hotel.length>=3)
    {
      $.ajax({
        url:"{{ route('search_commission_hotel.fetch')}}",
        method:"POST",
        data:{search_hotel:search_hotel,_token:_token},
        success:function(data)
        {
          
          $.each(data, function (key,value) {
            //jQuery('#hotel_name').append($('<option></option>').attr('value', value.id).text(value.title));
            var newOption = new Option(value.title, value.id, false, false);
            newValue.push(newOption);
            console.log(value.title);
          });
          console.log(newValue);
          $('#hotel_name').append(newValue);
        }
      });
    }
  });
  
  $('#hotel_name').select2({
      allowClear:true,
    });
  });
</script>
@endsection
