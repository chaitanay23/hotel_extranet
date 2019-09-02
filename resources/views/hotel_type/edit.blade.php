@extends('layouts.app')


@section('content')

<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Hotel Type</h2>
  </div>

    <div class="col-sm-4 margin-tb">

        <div class="pull-right ml-2">

            <a class="eye1" href="{{ route('hotel_type.index') }}"><i class="fas fa-long-arrow-alt-left"></i></a>

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

{!! Form::model($type,['method' => 'PATCH','route' => ['hotel_type.update',$type->id]]) !!}

<div class="form form-padding">
<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="form-group">

            <strong>Hotel Type:</strong>

            {!! Form::text('name', null, array('placeholder' => 'Hotel Type','class' => 'form-control')) !!}

        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary submit-btn-hex">Submit</button>

    </div>

</div>
</div>
{!! Form::close() !!}
<script>
  jQuery(document).ready(function($){
    $('.property').addClass('active-menu');
    $('.hotel_type').addClass('active-sub-menu');
  });
</script>
@endsection
