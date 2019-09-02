<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Country extends Model
{

    protected $table = 'countries';
    protected $fillable = ['title', 'status', 'status2'];

    protected $hidden = ['created_at', 'updated_at'];

   

    public function contacts()
    {
       return $this->hasMany(Contact::class);
    }


    public function states()
    {
        // return $this->hasMany(State::class);
    }


    public static function getList()
    {
        $data = country::where('status', 1)->get();

        $data_new = array();
        foreach ($data as $key => $value) {
            # code...
            $data_new[$value->id] = $value->title; 
        }

        return $data_new;

    }





}
