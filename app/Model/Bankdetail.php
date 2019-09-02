<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Bankdetail extends Model
{
    

    protected $table = 'bankdetails';

    protected $fillable = ['hotel_id', 'account_no','account_holder','branch_name','ifsc_code','branch_code','bank_id','bank_code','pan_number','name_of_pancard','service_tx_no'];
    protected $hidden = ['created_at', 'updated_at'];


    public function bank()
         {
            return $this->belongsTo(Bank::class, 'bank_id');
         }

         public function hotel()
         {
            return $this->belongsTo(Hotel::class, 'hotel_id');
         }

     
	    


         public static function getList()
				    {
				        $data = area::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            $data_new[$value->id] = $value->name; 
				        }
				        return $data_new;

				    }


}
