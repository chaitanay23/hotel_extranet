<?php namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    /**
     * @var string
     */
    protected $table = 'policies';

    /**
     * @var array
     */
    protected $fillable = ['hotel_id','hotel_service'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    

   

}
