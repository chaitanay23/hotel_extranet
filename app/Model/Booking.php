<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Booking extends Model
{
    /**
     * @var string
     */
    protected $table = 'bookings';
    protected $fillable = ['user_id', 'hotel_id', 'category_id', 'room_id', 'extra_bed', 'extra_person','no_of_rooms','base_amount','service_tex','total_amount','booking_ip'];
    protected $hidden = ['created_at', 'updated_at'];

       
       public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

          



         public function hoteldetail()
        {
            return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
        }


         public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }


        



         /* public function permissions()
        {
            return $this->belongsToMany(Permission::class);
        }

       
        public function givePermissionTo(Permission $permission)
        {
            return $this->permissions()->save($permission);
        } 
     
        
        public function admins()
        {
            return $this->belongsToMany(Admin::class);
        }  */








}
