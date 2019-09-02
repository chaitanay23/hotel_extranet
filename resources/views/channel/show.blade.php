@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12 mt-4">
        <h2 class="heder">Channel Manager</h2>
    </div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">

				<a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
	
			</div>
        
    </div>
</div>
<br/>
<div class="form form-padding">
<div class="card-show col-md-4 pt-3 mt-4 pb-3 pl-4">
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Hotel Name:</strong>
            <span class="show-data"> {{ $hotel->title }}</span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Property Id:</strong>
            <span class="show-data"> {{ $channel->hotel_id }}</span>
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Hotel Key:</strong>
            <span class="show-data"> {{ $channel->hotelkey }}</span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Hotel Password:</strong>
            <span class="show-data"> {{ $channel->hotelpassword }}</span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
        <div class="form-group">
            <strong>Channel Partner:</strong>
            <span class="show-data"> {{ $partner_name->name }}</span>
        </div>
    </div>
      
</div>
</div>
<script>
    jQuery(document).ready(function($){
        $('.channel').addClass('active-menu');
    });
</script>
@endsection
