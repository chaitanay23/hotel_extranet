<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Hotelapiinteraction extends Model
{
   
    protected $table = 'hotelapiinteractions';
    protected $hidden = ['created_at', 'updated_at'];

                     public function hotel()
                        {
                            return $this->belongsTo(Hotel::class, 'hotel_id');
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
