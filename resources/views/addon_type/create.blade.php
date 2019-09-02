@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12 mt-4">
    <h2 class="heder">Addon Type</h2>
  </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">
            <a class="eye1" href="{{ route('addon_type.index') }}"><i class="fas fa-long-arrow-alt-left"></i></a>
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
{!! Form::open(array('route' => 'addon_type.store','method'=>'POST','enctype' => 'multipart/form-data' ,'id' => 'form_id')) !!}
<div class="form form-padding">
	<div class="row mt-4">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Title:</strong>
	            {!! Form::text('title', null, array('placeholder' => 'Addon title','class' => 'form-control')) !!}
	        </div>
    	</div>

    	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Tagline:</strong>
	            {!! Form::text('tagline', null, array('placeholder' => 'Addon tagline','class' => 'form-control')) !!}
	        </div>
    	</div>

    	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group">
	            <strong>Amount:</strong>
	            {!! Form::number('amount', null, array('placeholder' => 'Coupon amount','class' => 'form-control')) !!}
	        </div>
    	</div>

    	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group"> 
	          <strong>Payment Page Image:</strong>
	          {!! Form::file('picture', array('class' => 'image form-control'))!!}
	        </div>
      	</div>

      	<div class="col-xs-12 col-sm-12 col-md-12">
	        <div class="form-group"> 
	          <strong>Coupon Image:</strong>
	          {!! Form::file('list_img', array('class' => 'image form-control'))!!}
	        </div>
      	</div>

      	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
	        <button type="submit" class="btn btn-primary">Submit</button>
	    </div>
    </div>
  </div>
{!! Form::close() !!}
<script>
  jQuery(document).ready(function($){
    $('.add-on').addClass('active-menu');
    $('.add-type').addClass('active-sub-menu');
  });
</script>
@endsection
