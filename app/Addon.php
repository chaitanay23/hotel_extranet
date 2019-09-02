<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table = 'coupons';
    protected $fillable = ['user_id','hotel_id','couponcategory_id','discount','dis_type','flat_dis','cost_for_two'];


	public static function getstatus($coupon_id,$hotel_id)
	{
		$status = DB::table('coupons')->where('couponcategory_id',$coupon_id)->where('hotel_id',$hotel_id)->get()->toArray();
		$status_add = '0';
		foreach($status as $key =>$value)
		{	
			if($value->status == '1'){
				$status_add = '1';
			}
			else
				$status_add = '0';
		}
		
		return $status_add;
	}

	public static function getdis_type($coupon_id,$hotel_id)
	{
		$dis_data = DB::table('coupons')->where('couponcategory_id',$coupon_id)->where('hotel_id',$hotel_id)->get()->toArray();
		$coupon_data = null;
		foreach($dis_data as $key =>$value)
		{
			$coupon_data = $value;

		}
		$coupon_data = json_decode(json_encode($coupon_data),true);
		
		return $coupon_data;
	}

}