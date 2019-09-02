<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Userlog extends Model
{
    /**
     * @var string
     */
    protected $table = 'userlogs';
    
       
       public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

}
