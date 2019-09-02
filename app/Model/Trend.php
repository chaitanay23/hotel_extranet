<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Trend extends Model
{
    /**
     * @var string
     */
    protected $table = 'trends';

    /**
     * @var array
     */
    //protected $fillable = ['title', 'contact', 'min_price', 'max_price', 'location_id', 'picture' ];

    /**
     * @var array
     */
    protected $hidden = ['updated_at'];



        public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'type_id');
        }



    //      public function companies()
    // {
    //     return $this->belongsToMany(Company::class, 'company_contact', 'contact_id');
    // }

   

}
