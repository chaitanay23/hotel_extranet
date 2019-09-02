<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;

class Dailyinventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyinventory:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
   
     //Inventory insert script
     $hotel_list = DB::table('hoteldetails')->join('dailyinventories', 'hoteldetails.id', '=', 'dailyinventories.category_id')
     ->where('hoteldetails.cp_include','EP')
     ->select('dailyinventories.hotel_id as hotel_id','dailyinventories.date as date',
             'hoteldetails.cp_include as cp_include','hoteldetails.id as id',
             'dailyinventories.booked','dailyinventories.extra_adult as extra_adult',
             'dailyinventories.contractual_room as contractual_room',
             'dailyinventories.single_occupancy_discount as single_occupancy_discount',
             'dailyinventories.double_occupancy_discount as double_occupancy_discount',
             'dailyinventories.single_occupancy_price as single_occupancy_price','dailyinventories.double_occupancy_price as double_occupancy_price',
             'dailyinventories.flag as flag','dailyinventories.single_occupancy_discount as single_occupancy_discount','dailyinventories.double_occupancy_discount as double_occupancy_discount',
             'dailyinventories.rooms as rooms'
     )
     ->get();
     foreach($hotel_list as $val) {
        
        DB::table('dailyinventories_test_ep')->insert([
        'hotel_id' =>$val->hotel_id,
        'date' =>$val->date,
        //'rooms_cp' =>$val->rooms,
        'rooms_ep' =>$val->rooms,
        'contractual_room' =>$val->contractual_room,
        'category_id' =>$val->id,
        'ep_status' =>1,
        'single_occupancy_price_ep' =>$val->single_occupancy_price,
        'double_occupancy_price_ep' =>$val->double_occupancy_price,
        'extra_adult_ep' =>$val->extra_adult,
        // 'single_occupancy_price_cp' =>$val->single_occupancy_price,
        // 'double_occupancy_price_cp' =>$val->double_occupancy_price,
        // 'extra_adult_cp' =>$val->extra_adult,
        'single_occupancy_discount' =>$val->single_occupancy_discount,
        'double_occupancy_discount' => $val->double_occupancy_discount,
        'flag' =>1,
        ]);
    
   }  

     //Update  EP price script
   
   
    //     $hotel_list = DB::table('dailyinventories')->join('hoteldetails', 'dailyinventories.hotel_id', '=', 'hoteldetails.hotel_id')
    //     ->where('hoteldetails.cp_include','EP')
    //     ->select('dailyinventories.hotel_id as hotel_id','dailyinventories.date as date',
    //            'hoteldetails.cp_include as cp_include','hoteldetails.id as id',
    //           'dailyinventories.booked','dailyinventories.extra_adult as extra_adult',
    //           'dailyinventories.contractual_room as contractual_room',
    //           'dailyinventories.single_occupancy_discount as single_occupancy_discount',
    //           'dailyinventories.double_occupancy_discount as double_occupancy_discount',
    //           'dailyinventories.single_occupancy_price as single_occupancy_price','dailyinventories.double_occupancy_price as double_occupancy_price',
    //           'dailyinventories.flag as flag','dailyinventories.single_occupancy_discount as single_occupancy_discount','dailyinventories.double_occupancy_discount as double_occupancy_discount',
    //           'dailyinventories.rooms as rooms'
    //   )
    //     ->get();
      
    //     foreach($hotel_list as $val) {
    //        DB::table('dailyinventories_test')
    //          ->where('hotel_id', $val->hotel_id)
    //          ->update([
    //              'rooms_ep' =>$val->rooms,
    //              'contractual_room' =>$val->contractual_room,
    //              //'category_id' =>$val->room_id,
    //              'ep_status' =>1,
    //              'single_occupancy_price_ep' =>$val->single_occupancy_price,
    //              'double_occupancy_price_ep' =>$val->double_occupancy_price,
    //              'extra_adult_ep' =>$val->extra_adult,
    //              'single_occupancy_discount' =>$val->single_occupancy_discount,
    //              'double_occupancy_discount' => $val->double_occupancy_discount,
    //              'flag' =>1
                 
    //              ]);
    //           }  

   //Hotel details update only EP price
//     $query = DB::TABLE('hoteldetails')->where('cp_include','CP')->get();
//    foreach($query as $val) {
//     DB::table('hoteldetails1')->insert([
//       'hotel_id' =>$val->hotel_id,
//       'user_id' =>$val->user_id,
//       'custom_category' =>$val->custom_category,
//       'adult_charge' =>$val->adult_charge,
//       'bed_charge' =>$val->bed_charge,
//       'max_guest_allow' =>$val->max_guest_allow,
//       'max_adult_allow' =>3,
//       'max_child_allow' =>2,
//       'max_infant_allow' =>2,
//       'description' =>$val->description,
//       'cp_include' => $val->cp_include,
//       'single_occupancy_price' =>$val->single_occupancy_price,
//       'double_occupancy_price' =>$val->double_occupancy_price,
//       'single_occupancy_rac_price'=>$val->single_occupancy_rac_price,
//       'double_occupancy_rac_price'=>$val->double_occupancy_rac_price,
//       'picture' => $val->picture,
//       'min_price' => $val->min_price,
//       'max_price' => $val->max_price,
//       'status' => $val->status,
//       'room_order' => $val->room_order,
//       'room_id' => $val->room_id,
//       ]);
    

//       }
    }
}
