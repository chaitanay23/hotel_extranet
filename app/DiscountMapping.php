<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountMapping extends Model
{
    protected $table = 'ratemappings';
    protected $fillable = ['hotel_id','mon','tue','wed','thu','fri','sat','sun','today_12_6_sdis','today_12_6_edis','today_6_9_sdis','today_6_9_edis','today_9_12_sdis','today_9_12_edis','today_12_3_sdis','today_12_3_edis','today_3_sdis','today_3_edis','tom_2_sdis','tom_2_edis','tom_5_sdis','tom_5_edis','tom_8_sdis','tom_8_edis','tom_10_sdis','tom_10_edis','tom_11_sdis','tom_11_edis']; 
}
