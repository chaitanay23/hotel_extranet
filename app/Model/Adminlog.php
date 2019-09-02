<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Adminlog extends Model
{
    /**
     * @var string
     */
    protected $table = 'adminlogs';
    
       
   public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

}
