<?php

namespace App;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Bankdetail extends Model
{
    use NullableFields;

    protected $table = 'bankdetails';
    protected $fillable = ['hotel_id','user_id','account_no','account_holder','branch_name','ifsc_code','branch_code','bank_id','bank_code','pan_number','name_of_pancard','service_tx_no','gst_number','payment_id'];
    public function bank()
        {
        return $this->belongsTo(Bank::class, 'bank_id');
        }
    
}
