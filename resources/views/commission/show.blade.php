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
<br/>
<div class="container">
	<div calss="form form-padding">
	<div class="card-show col-md-4 pt-3 mt-4 pb-3 pl-4">
	    <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
	        <div class="form-group">
	            <strong>Hotel Name:</strong>
	            <span class="show-data"> {{ $hotel->title }}</span>
	        </div>
	    </div>
	    <div class="col-xs-12 col-sm-12 col-md-12">
		    <div class="form-group">
		      <strong>Commission (in %):</strong>
		      <span class="show-data"> {{ $commission->commission}}%</span>
		    </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  	<div class="form-group">
		      <strong>Pass-Back-Commission (in %):</strong>
		      <span class="show-data"> {{$commission->pbc}}%</span>
		    </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-group">
		      <strong>TDS on Commission (in %):</strong>
		      <span class="show-data"> {{ $commission->tds}}%</span>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
		    <strong>MagicSpree Fee:</strong>
		    <span class="show-data"> {{ $commission->magicspree_fee}}</span>
		  </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
		    <strong>Additional Discount (in %):</strong>
		    <span class="show-data"> {{ $commission->additional_discount}}</span>
		  </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
		    <strong>Maximum Discount:</strong>
		    <span class="show-data"> {{ $commission->max_discount}}</span>
		  </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
		  <div class="form-group">
		    <strong>PSB Comments:</strong>
		    <span class="show-data"> {{ $commission->comment}}</span>
		  </div>
		</div>
	</div>
</div>
</div>
<script>
	jQuery(document).ready(function($){
	  $('.commission').addClass('active-menu');
	  $('.ms_com').addClass('active-sub-menu');
	});
</script>
@endsection
