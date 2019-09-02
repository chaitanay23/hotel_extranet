@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-sm-12 mt-4">
		<h2 class="heder">Addon</h2>
	</div>
    <div class="col-sm-4 margin-tb">
        <div class="pull-right ml-2">
            <a class="eye1" href="{{ route('addon.index') }}"><i class="fas fa-long-arrow-alt-left"></i></a>
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
<div class="form form-padding">

	<h1 class="mt-4 heding">{{$hotel_name->title}}</h1>
	<div class="row">
	@foreach($addon_type as $key => $addon)
	<div class="col-md-4 col-lg-4 col-sm-6 mt-4">
		<div class="hotel-deal">
			<div class="dealimg text-center">
				{!! Html::image($addon->picture, 'alt',array('class' => 'img-thumbnail addon-img'))!!}
				<div class="deal-input">
					@php 
						$coupon_data = \App\Addon::getdis_type($addon->id,$hotel_id);
					@endphp
					<div class="form-group mt-4 pl-3 pr-3 select_discount">
			            {{ Form::select('dis_type',['1'=>'Percentage','2'=>'Flat'],$coupon_data['dis_type'],['placeholder' => 'Select discount type','class' => 'form-control addon-field','id'=>'discount_type'])}}
			        </div>
			        <div class="form-group pt-1 pl-3 pr-3 percent_discount"> 
						{{ Form::select('discount',$discount,$coupon_data['discount'],['placeholder' => 'Select discount','class' => 'form-control addon-field','id'=>'discount_percentage'])}}
					</div>
					<div class="form-group pt-1 pl-3 pr-3 flat_discount"> 
						{{ Form::number('flat_dis',$coupon_data['flat_dis'],['placeholder' => 'Discount offered','class' => 'form-control addon-field','id'=>'discount_flat'])}}
					</div>
					<div class="form-group pt-1 pl-3 pr-3 avg_cost_two"> 
						{{ Form::number('cost_for_two',$coupon_data['cost_for_two'],['placeholder' => 'Avg cost of service','class' => 'form-control addon-field','id'=>'avg_cost'])}}
					</div>
					<div class="toggle_switch">
						<div class="condition_detail">
							<a class = "view_tc" data-id="condition_link" data-iteration="{{$loop->iteration}}">View Coupon T&C</a>
							<div class="termcon-box" id="divMsg" style="color:#1d1d2e">
								<h5 style="color: #f5a522">Terms & Conditions <button type="button" class="close">&times;</span><span class="sr-only">Close</span></button></h5>
								<ol>
									<li>Valid for 1 way transfers only. For 2 way transfers please buy 2 coupons</li>
									<li>Valid for 1 time usage only</li>
									<li>Valid for resident guests only</li>
									<li>All hotel terms & conditions apply</li>
									<li>Rights to admission reserved by the hotel</li>
									<li>Pick up & drop timings apply</li>
									<li>Coupon cannot be used post check out </li>
									<li>Cannot be clubbed with any other promotion/offer</li>
								</ol>
							</div>
						</div>
						<div class="toggle_button toggle_button1">
							@php 
								$status_add = \App\Addon::getstatus($addon->id,$hotel_id);
							@endphp
							
							<input type="checkbox" value="{{$status_add}}" data-toggle="toggle" data-offstyle="danger" data-onstyle="success" data-addon="{{$addon->id}}" data-hotel_id="{{$hotel_id}}" id="addon_status">
						</div>
					</div>
				</div>
				<div class="deal-termcondition">
					<p>By Switching on the offer, you agree to the Terms & Conditions of MagicSpree</p>
				</div>

			</div>
		</div>
		<div class="deal-title">
			<h1 class="addon-title">{{$addon->title}}</h1>
		</div>

	</div>
	@endforeach
	</div>

	<script>
		jQuery(document).ready(function($){
			$('.add-on').addClass('active-menu');
	    	$('.addon-inn').addClass('active-sub-menu');
			var _token = $('input[name="_token"]').val();
			$('.termcon-box').css("display","none");
			$('.flat_discount').css("display","none");
			$(".select_discount").each(function(){
				var discount_type = $(this).children().val();
				if(discount_type == '1')
				{
					$(this).next().css("display","block");
				}
				else
				{
					$(this).next().css("display","none");
					$(this).next().next().css("display","block");
				}
			})
			$(document).on("click",".view_tc",function(){
				$(this).next().css("display","block");
			});
			$(document).on("click",".close",function(){
				$(this).parent().parent().css("display","none");
			});
			$(".select_discount").change(function(){
				var dis = $(this).children().val();
				if(dis=='1')
				{
					$(this).next().css("display","block");
					$(this).next().next().css("display","none");
				}
				else if(dis=='2')
				{
					$(this).next().css("display","none");
					$(this).next().next().css("display","block");
				}
			});
			$('input:checkbox').ready(function(){
				$('input:checkbox').each(function(){
					var value = $(this).val();
					if(value=='1')
					{
						$(this).bootstrapToggle('on');
						$(this).parent().parent().parent().parent().parent().parent().next().css("background","linear-gradient(to right, #9bc256 0%, #6ad044 100%)");
					}
				});
			});


			$(".toggle_button").click(function(){
				$(".toggle_button").change(function(){
					var check = $(this).children().children().val();
					var addontype_id = $(this).children().children().data('addon');
					var hotel_id = $(this).children().children().data('hotel_id');
					var dis_type_value = $(this).parent().parent().children('.select_discount').children().val();
					var dis_percent = $(this).parent().parent().children('.percent_discount').children().val();
					var dis_flat = $(this).parent().parent().children('.flat_discount').children().val();
					var avg_cost = $(this).parent().parent().children('.avg_cost_two').children().val();
					if(check=='1'){
						$.ajax({
							url:"{{ route('addon_status_off.fetch')}}",
							method:"POST",
							data:{_token:_token,hotel_id:hotel_id,dis_type_value:dis_type_value,dis_percent:dis_percent,dis_flat:dis_flat,avg_cost:avg_cost,addontype_id:addontype_id},
							success:function(data)
							{
								$(this).children().children().val('0');
							}
						})
						$(this).parent().parent().parent().parent().next().css("background","linear-gradient(to right, #d74142 0%, #eb6e29 100%)");
					}

					else
					{
						$.ajax({
							url:"{{ route('addon_status_on.fetch')}}",
							method:"POST",
							data:{_token:_token,hotel_id:hotel_id,dis_type_value:dis_type_value,dis_percent:dis_percent,dis_flat:dis_flat,avg_cost:avg_cost,addontype_id:addontype_id},
							success:function(data)
							{
								$(this).children().children().val('1');
							}
						})
						$(this).parent().parent().parent().parent().next().css("background","linear-gradient(to right, #9bc256 0%, #6ad044 100%)");
					}
					
				})
			})
		});
	</script>

	@endsection

