<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Bannersetting extends Model
{
   
    protected $table = 'listbannermasters';
    protected $hidden = ['created_at', 'updated_at'];

}
