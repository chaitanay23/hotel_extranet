<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Msbank extends Model
{
    

    protected $table = 'msbanks';

    protected $fillable = ['account_no','account_holder','branch_name','ifsc_code','branch_code','bank_id','bank_code','pan_number','name_of_pancard','service_tx_no'];

    protected $hidden = ['created_at', 'updated_at'];


    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }


}
