<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Advertisetype extends Model
{
    
    protected $table = 'advertisetypes';

   
    protected $fillable = ['name','description','status'];

    protected $hidden = ['created_at', 'updated_at'];




    public static function getCampaigncategory()
                     {
                        $data = Advertisetype::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) 
                        {
                            $data_new[$value->id] = $value->description; 
                        }

                        return $data_new;

                    }


}
