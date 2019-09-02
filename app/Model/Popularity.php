<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Popularity extends Model
{
    /**
     * @var string
     */
    protected $table = 'popularities';
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function citie()
    {
        return $this->belongsTo(Citie::class, 'city');
    }
       

}
