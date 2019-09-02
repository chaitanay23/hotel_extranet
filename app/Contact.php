<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table='contacts';
    protected $fillable=['hotel_id','user_id','phone_no','email','mobile','website','pemail','pmobile','pdesignation','pnego','pvoucher','semail','smobile','sdesignation','snego','svoucher','psms','ssms'];
}
