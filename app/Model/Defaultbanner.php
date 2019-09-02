<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Defaultbanner extends Model
{
   
    protected $table = 'defaultbanners';
    protected $hidden = ['created_at', 'updated_at'];
    

    public function defaultbannermasters()
    {
        return $this->belongsTo(Defaultbannermaster::class, 'defaultbannermasters_id');
    }
       

}
