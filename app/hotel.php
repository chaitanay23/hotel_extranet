<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Launch;

class hotel extends Model
{
    
    protected $casts = ['is_active' => 'boolean'];

		public function citie()
        {
            return $this->belongsTo(citie::class, 'location_id');
        }


        public function region()
        {
            return $this->belongsTo(Region::class, 'region_id');
        }


         public function admin()
        {
            return $this->belongsTo(Admin::class, 'user_id');
        }



         public function area()
        {
            return $this->belongsTo(Area::class, 'area_id');
        }

        public function attributes()
        {
            return $this->belongsToMany('App\attribute');
        }

        public function cities()
        {
            return $this->belongsToMany('App\citie','city_hotel','hotel_id','city_id');
        }


        public function launchs()
        {
        	return Launch::where(['hotel_id'=>$this->id,'status'=>1])->get();
        }



}