@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-sm-12 mt-4 ml-2">
		<h2 class="heder">Room</h2>
	</div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-3">
            <a class="eye1" href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left"></i></a>
        </div>
    </div>
</div>
<br/>

<div class="container">
	<div class="form form-padding">
	<div class="row">
		<div class="col-sm-8">
			<div class="form-group hotal-detail"> 
				{!! Html::image($room_show ? $room_show->picture :'', 'alt',array('class' => 'img-thumbnail'))!!}
			</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 mt-4">
			<div class="form-group"> 
				
			</div>
		</div>
	</div>
</div>
</div>

<script>
	jQuery(document).ready(function($){
    	$('.property').addClass('active-menu');
    	$('.room').addClass('active-sub-menu');
	});
</script>
@endsection
