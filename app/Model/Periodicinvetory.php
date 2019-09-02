<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Periodicinvetory extends Model
{
    /**
     * @var string
     */
    protected $table = 'periodicinvetories';
    
    /**
     * @var array
     */
    protected $fillable = ['user_id','hotel_id','category_id','mon','tue','wed','thu','fri','sat','sun','number_of_rooms'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];



         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }


         public function category()
        {
            return $this->belongsTo(Category::class, 'category_name');
        }


        

}
