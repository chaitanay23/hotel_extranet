<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Citie extends Model
{
   
    protected $table = 'cities';
  
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function hotels()
        {
            return $this->hasMany(Hotel::class);
        }

      
      public function states()
        {
          return $this->belongsTo(State::class, 'state_id')->where('status', 1);
        }


            public static function getList()
                    {
                        $data = citie::where('status', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                            # code...
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }




            public static function getCitynamelist()
                    {
                        $data = citie::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            $data_new[$value->id] = $value->name; 
                        }
                        return $data_new;
                    }



                    public static function getCitylist()
                    {
                        $data = citie::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            $select_state = State::where('id',$value->state_id)->first();
                            $data_new[$value->id] = $value->name.' , '.$select_state->name; 
                        }
                        return $data_new;
                    }



        

}
