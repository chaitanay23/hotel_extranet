<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DOMDocument;
use App\Http\Requests;
use App\Model\Hotel as hotel;
use Carbon\Carbon;
use App\Model\Hoteldetail;
use App\Model\Dailyinventory;
use App\Model\Commission;
use App\Model\Tax;
use App\Model\Hotelapiinteraction;

class Staahrooms extends Controller
{
    public function get_request(Request $request)
    {   
        if($request->method == 'RateMappig')
        {

             $json = file_get_contents("php://input");
            
             $decode_data = json_decode($json, true);

            // dd($decode_data['roomrequest']["username"]);

             $hotel_id = $decode_data['roomrequest']["hotel_id"];

             $username = $decode_data['roomrequest']["username"];

             $password = $decode_data['roomrequest']["password"];

           
            $response = array();
            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            if($channel_id)
            {
                $ch_id = $channel_id->channel_partner;
            }
            else{
                return response()->json(['roomresponse'=>['error' => 'Invalid credentials','status' => 'failure']]);
            }
           
            //Old code
            //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Staah','status'=>1])->first();
             //New Code changes channel manager id
            $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first();
            if($search_hotel)
            {
                $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->count();
            }
            else{
                return response()->json(['roomresponse'=>['error' => 'Invalid credentials','status' => 'failure']]);
            }

            if($search_hotel_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if($search_hotel_count > 0)
                {
                    
                    $room_details = Hoteldetail::where(['hotel_id'=>$hotel_id,'status'=>1])->get();

                    $current_date=date_create(Carbon::now()->toDateString());
                    $current_date = $current_date->format('Y-m-d');
                    foreach ($room_details as $key => $value) {
                        # code...
                        //New Code Dailyinventory find cp_status
                       $cp_status = Dailyinventory::select('cp_status')->where(['hotel_id'=>$hotel_id,'category_id'=>$value->id,'date'=>$current_date])->first();
                       $ep_status = Dailyinventory::select('ep_status')->where(['hotel_id'=>$hotel_id,'category_id'=>$value->id,'date'=>$current_date])->first();

                        if($cp_status->cp_status == 1)
                        {
                            $rateplanname = 'Include Breakfast';
                            $roomtypeid = $value->id;
                            $rateplanid = 'BAR'; //if cp = BAR Then ok

                            $list_data[] = array('roomtypeid'=>$roomtypeid,'roomtypename'=>$value->custom_category,'rateplanid'=>$rateplanid,'rateplanname'=>$rateplanname);
                        }
                        if($ep_status->ep_status == 1)
                        {
                            $rateplanname_ep = 'Room Only';
                            $roomtypeid_ep = $value->id;
                            $rateplanid_ep = 'EP';

                            $list_data[] = array('roomtypeid'=>$roomtypeid_ep,'roomtypename'=>$value->custom_category,'rateplanid'=>$rateplanid_ep,'rateplanname'=>$rateplanname_ep);
                        }
                        
                       
                    }

                    return response()->json(['roomresponse'=>['roomtypes'=>['roomtype'=>$list_data]]]);

                }
                else
                {
                    return response()->json(['roomresponse'=>['error' => 'Invalid hotel_id','status' => 'failure']]);
                }

            }
            else
            {
                return response()->json(['roomresponse'=>['error' => 'Invalid credentials','status' => 'failure']]);
            }

            //echo $hotel_id;    

        }
        if($request->method == 'rateUpdate')
        {

             $json = file_get_contents("php://input");
            
             $decode_data = json_decode($json,true);

            //  dd($decode_data['raterequest']);

             $hotel_id = $decode_data['raterequest']["hotel_id"];

             $username = $decode_data['raterequest']["username"];

             $password = $decode_data['raterequest']["password"];

             $response = array();

            // dd($decode_data['raterequest']["room"]);

             $room_contain = $decode_data['raterequest']["room"];

             $date_data = $room_contain['rate']['dates'];

             $room_id = $room_contain['id'];

             $mealplan_id = $room_contain['rate']['id'];
            
            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            if($channel_id)
            {
                $ch_id = $channel_id->channel_partner;
            }
            else{
                return response()->json(['roomresponse'=>['error' => 'Invalid credentials','status' => 'failure']]);
            }
            // $room_id = $request->room_id;
             //Old code
            //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Staah','status'=>1])->first();
            //New Code
             $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first();
             if($search_hotel)
            {
                $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->count();
            }
            else{
                return response()->json(['roomresponse'=>['error' => 'Invalid credentials','status' => 'failure']]);
            }
           
            if( $search_hotel_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if( $search_hotel_count > 0)
                {
                    //Old code changes
                    /*$fetch_meal_plan = Hoteldetail::where(['room_id'=>$room_id,'hotel_id'=>$hotel_id])->first();
                    
                    if($mealplan_id == 'EP')
                    {
                        $room_id = $fetch_meal_plan->id;
                    }*/

                    $room_details = Hoteldetail::where(['id'=>$room_id,'hotel_id'=>$hotel_id])->count();

                    if($room_details == 1)
                    {
                        
                         foreach ($date_data as $key => $value) 
                          {

                             $startDate = $value['fromdate'];

                             $endDate = $value['todate'];

                             $single_price = $value['occupancy']['A1'];

                             $double_price = $value['occupancy']['A2'];

                             $extra_price = $value['occupancy']['AA'];

                             $stopsell = $value['closed'];

                             $inv_value = $value['roomstosell'];

                            
                             $date_1=date_create(Carbon::now()->toDateString());
                             $date_2=date_create($startDate);
                             $diff12 = date_diff($date_1, $date_2);
                             $days = $diff12->format("%R%a days");
                           
                           
                            
                            
                            //dd($days);

                            if($days >= 0) 
                            {
                            
                                $avial_data = Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$startDate)->count();

                                if($avial_data > 0)
                                {
                                    $date_start = date_create($startDate);
                                    $date_end = date_create($endDate);
                                    $give_diff = date_diff($date_start, $date_end);
                                    $cal_days = $give_diff->format("%R%a days");
                                    $cal_days =(int)$cal_days + 1;

                                    for ($j = 0; $j < $cal_days ; $j++) 
                                    { 
                                        $new_date = (new Carbon($startDate))->addDays($j)->toDateString();    
                                        
                                        if($stopsell == 0)
                                        {
                                            //Old Code
                                           // Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price'=>$single_price,'double_occupancy_price'=>$double_price,'extra_adult'=>$extra_price,'rooms'=>$inv_value,'flag'=>1]);
                                            //New Code
                                            if($mealplan_id == 'EP') {
                                            Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_ep'=>$single_price,'double_occupancy_price_ep'=>$double_price,'extra_adult_ep'=>$extra_price,'rooms_ep'=>$inv_value,'flag'=>1,'ep_status'=>1]);
                                            } else if($mealplan_id == 'CP' || 'BAR') {
                                              Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_cp'=>$single_price,'double_occupancy_price_cp'=>$double_price,'extra_adult_cp'=>$extra_price,'rooms_cp'=>$inv_value,'flag'=>1,'cp_status'=>1]);
                                            }

                                        }
                                        else
                                        {
                                            Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_ep'=>$single_price,'double_occupancy_price_ep'=>$double_price,'extra_adult_ep'=>$extra_price,'rooms_ep'=>$inv_value,'flag'=>0,'rooms_ep'=>0,'ep_status'=>0]);

                                             Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_cp'=>$single_price,'double_occupancy_price_cp'=>$double_price,'extra_adult_cp'=>$extra_price,'rooms_cp'=>$inv_value,'flag'=>0,'rooms_cp'=>0,'cp_status'=>0]);
                                        }
                                                
                                    }

                            
                                }
                                else
                                {
                                    return response()->json(['rateresponse'=>['error' => 'Data not exists','updated' => 'Fail']]);
                                }

                            
                            }
                            else
                            {
                                 return response()->json(['rateresponse'=>['error' => 'Invalid StartDate','updated' => 'Fail']]);
                            }

                         }
                        return response()->json(['rateresponse'=>['updated' => 'Success']]);

                    }
                    else
                    {
                         return response()->json(['rateresponse'=>['error' => 'Invalid Room','updated' => 'Fail']]);
                    }

                }
                else
                {
                    return response()->json(['rateresponse'=>['error' => 'Invalid hotel_id','updated' => 'Fail']]);
                }

            }
            else
            {
                return response()->json(['rateresponse'=>['error' => 'Invalid credentials','updated' => 'Fail']]);
            }

            // //echo $hotel_id;    

        }
        
        
                   
    }
    

}
