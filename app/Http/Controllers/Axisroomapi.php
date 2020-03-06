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

class Axisroomapi extends Controller
{
    public function get_request(Request $request)
    {
        
        if($request->method == 'productinfo')
        {
            
            $json = file_get_contents("php://input");
            
            $decode_data = json_decode($json);
            $key = $decode_data->{'auth'}->{'key'};
            $hotel_id = $decode_data->{'propertyId'};
            
            /*$hotel_id = $request->hotel_id;
             $key = $request->auth;*/
            //Get channel id new code
            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            $ch_id = $channel_id->channel_partner;
            //Old code
           // $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>'Axisroom','status'=>1])->first();
            // New Code Changes  get channel_partner id
            $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->first();
            //New Code count 
            $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->count();
            // New Code
            if($search_hotel_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if($search_hotel_count > 0)
                {   
                    //Old code
                    //$room_details = Hoteldetail::where(['hotel_id'=>$hotel_id,'status'=>1,'cp_include'=>'CP'])->get();
                    //New Code him + chatan changes cp_include remove
                    $room_details = Hoteldetail::where(['hotel_id'=>$hotel_id,'status'=>1])->get();
                    foreach ($room_details as $key => $value) {
                        # code...
                        $list_data[] = array('name'=>$value->custom_category,'id'=>$value->id);
                    }

                    return response()->json(['message' => 'Get ProductInfo','status' => 'success','data' => $list_data]);

                }
                else
                {
                    return response()->json(['message' => 'Invalid PropertyId','status' => 'failure','data' => []]);
                }

            }
            else
            {
                return response()->json(['message' => 'Invalid Key','status' => 'failure','data' => []]);
            }

            //echo $hotel_id;    

        }
        if($request->method == 'ratePlanInfo')
        {
           
            $json = file_get_contents("php://input");
            
            $decode_data = json_decode($json);

            $hotel_id = $decode_data->{'propertyId'};

            $room_id = $decode_data->{'roomId'};

            $key = $decode_data->{'auth'}->{'key'};

            /* $room_id = $request->roomId;
             $hotel_id = $request->hotel_id;
             $key = $request->auth;*/
            //Change channel_partner 
           // $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>'Axisroom','status'=>1])->first();
            //New Code By Himanshu
            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            $ch_id = $channel_id->channel_partner;
            $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->first();
            $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->count();
            if($search_hotel_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if($search_hotel_count > 0)
                {
                    $room_details = Hoteldetail::where(['id'=>$room_id,'hotel_id'=>$hotel_id])->count();

                    $room_avail = ["Single", "Double","ExtraAdult"];

                    if($room_details == 1)
                    {
                        // 07-04-2019 as discussed with amitesh sir no need to send commission in response
                        
                        // $commission = Commission::where('hotel_id',$hotel_id)->first();
                        // //New Code count
                        // $commission_count = Commission::where('hotel_id',$hotel_id)->count();
                        // if($commission_count > 0)
                        // {
                        //     $commission_value = $commission->commission;   
                        // }
                        // else
                        // {
                        //     $commission_value = 10;
                        // }

                        $taxe = Tax::where('hoteldetail_id',$room_id)->first();

                        $taxeCount = Tax::where('hoteldetail_id',$room_id)->count();

                        if($taxeCount > 0)
                        {
                            $taxe_value = $taxe->luxary_text_value + $taxe->service_text_value + $taxe->other_tax_value;   
                        }
                        else
                        {
                            $taxe_value = 18.0;
                        }

                        $date1 = Dailyinventory::where('category_id',$room_id)->orderBy('date','ASC')->first(['date']);

                        $date2 = Dailyinventory::where('category_id',$room_id)->orderBy('date','DESC')->first(['date']);

                        $validity = ["startDate"=>$date1->date, "endDate"=>$date2->date];

                        $mydata[] = [
                                            "rateplanId"=>'CP',
                                            "ratePlanName"=>"Include Breakfast",
                                            "occupancy"=>$room_avail,
                                            "validity"=>$validity,
                                            // "commissionPerc"=>$commission_value,
                                            "taxPerc"=>$taxe_value
                                    ];
                        $mydata[] = [
                                            "rateplanId"=>'EP',
                                            "ratePlanName"=>"Room Only",
                                            "occupancy"=>$room_avail,
                                            "validity"=>$validity,
                                            // "commissionPerc"=>$commission_value,
                                            "taxPerc"=>$taxe_value
                                    ];
                        $output =   [
                                        "message"=>"Get RatePlanInfo",
                                        "status"=>"success",
                                        "data"=>$mydata
                                    ];


                        return response()->json($output);
                    }
                    else
                    {
                        return response()->json(['message' => 'Invalid roomId','status' => 'failure','data' => []]);
                    }


                    

                }
                else
                {
                    return response()->json(['message' => 'Invalid PropertyId','status' => 'failure','data' => []]);
                }

            }
            else
            {
                return response()->json(['message' => 'Invalid Key','status' => 'failure','data' => []]);
            }

            //echo $hotel_id;    

        }
        if($request->method == 'inventoryUpdate')
        {

            $json = file_get_contents("php://input");
            
            $decode_data = json_decode($json);

            $hotel_id = $decode_data->{'data'}->{'propertyId'};

            $room_id = $decode_data->{'data'}->{'roomType'};//room id

            $key = $decode_data->{'auth'}->{'key'};

            $invdata = $decode_data->{'data'}->{'inventory'};

             //$room_id = $request->roomId;
             //$hotel_id = $request->hotel_id;
             //$key = $request->auth;
            // $invdata = $request->inventory;

            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            $ch_id = $channel_id->channel_partner;
            //Change code  
            //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>'Axisroom','status'=>1])->first();
             $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->first();
              //New Code count
             $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->count();
            if($search_hotel_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if($search_hotel_count > 0)
                {
                    $room_details = Hoteldetail::where(['id'=>$room_id,'hotel_id'=>$hotel_id])->count();
                   
                    if($room_details == 1)
                    {
                        foreach ($invdata as $value) 
                        {
                            # code...
                            $startDate = $value->startDate;
                             //$startDate =  '2019-03-2';
                            
                            $endDate = $value->endDate;

                              //$endDate = '2019-03-3';

                              $rooms = $value->free;

                              //$rooms = 6;



                          
                            

                            $date_1=date_create(Carbon::now()->toDateString());
                            $date_2=date_create($startDate);
                            $diff12 = date_diff($date_1, $date_2);

                            $days = $diff12->format("%R%a days");
                            //$days = 3;


                            if($days >= 0) 
                            {
                                $date_start = date_create($startDate);
                                $date_end = date_create($endDate);
                                $give_diff = date_diff($date_start, $date_end);
                                $cal_days = $give_diff->format("%R%a days");

                                $cal_days = (int)$cal_days + 1;
                                //$cal_days = 5;
                                for ($j = 0; $j < $cal_days ; $j++)
                                { 
                                        

                                    $new_date = (new Carbon($startDate))->addDays($j)->toDateString();

                                    
                                    //Old code Change by himanshu + chaitan
                                   // Dailyinventory::where(['hotel_id'=>$hotel_id])->whereDate('date','=',$new_date)->update(['rooms'=>$rooms,'flag'=>1]);
                                    //New COde change by rooms_cp and rooms_ep

                                   Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['rooms_cp'=>$rooms,'rooms_ep'=>$rooms,'flag'=>1]);         
                                }

                                
                            }
                            else
                            {
                                return response()->json(['message' => 'Invalid StartDate','status' => 'failure']);
                            }

                        }

                        return response()->json(['message' => 'Update sucessfully','status' => 'success']);
                    }
                    else
                    {
                        return response()->json(['message' => 'Invalid roomId','status' => 'failure','data' => []]);
                    }

                }
                else
                {
                    return response()->json(['message' => 'Invalid PropertyId','status' => 'failure','data' => []]);
                }

            }
            else
            {
                return response()->json(['message' => 'Invalid Key','status' => 'failure','data' => []]);
            }

            //echo $hotel_id;    

        }
        if($request->method == 'rateUpdate')
        {

            $json = file_get_contents("php://input");
            
            $decode_data = json_decode($json);

            $hotel_id = $decode_data->{'data'}->{'propertyId'};

            $room_id = $decode_data->{'data'}->{'roomType'};
            

            $key = $decode_data->{'auth'}->{'key'};

            $rateplanId = $decode_data->{'data'}->{'ratePlanId'};

            $invdata = $decode_data->{'data'}->{'rate'};

            /* $room_id = $request->roomId;
             $hotel_id = $request->hotel_id;
             $key = $request->auth;
             $invdata[] = $request->rate;*/

	        //Change Code
            $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
            $channel_id_count = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->count();
            $ch_id = $channel_id->channel_partner;
            //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>'Axisroom','status'=>1])->first();
             $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$key,'channel_partner'=>$ch_id,'status'=>1])->first();
            if($channel_id_count > 0 && $search_hotel->hotel_id == $hotel_id)
            {
                $hotel_search = hotel::find($hotel_id);

                if($channel_id_count > 0)
                {
                   //Old code comment by Himanshu 
                   /* $fetch_meal_plan = Hoteldetail::where(['room_id'=>$room_id,'hotel_id'=>$hotel_id])->first();
                    
                    if($rateplanId == 'EP')
                    {
                        $room_id = $fetch_meal_plan->id;
                    }*/

                    $room_details = Hoteldetail::where(['id'=>$room_id,'hotel_id'=>$hotel_id])->count();
                   
                    if($room_details == 1)
                    {  
                       
                        foreach($invdata as $value) 
                        {
                            $startDate = $value->startDate;

                            //$startDate =  '2019-03-2';   
                            $endDate = $value->endDate;

                             //$endDate =  '2019-03-3';  
                            
                            

                            $date_1=date_create(Carbon::now()->toDateString());
                            $date_2=date_create($startDate);
                            $diff12 = date_diff($date_1, $date_2);
                            $days = $diff12->format("%R%a days");
                            //$days = 1; 

                            if($days >= 0) 
                            {
                               
                                $date_start = date_create($startDate);
                                $date_end = date_create($endDate);
                                $give_diff = date_diff($date_start, $date_end);
                                $cal_days = $give_diff->format("%R%a days");
                                $cal_days = (int)$cal_days + 1;

                                 //$cal_days = 2;

                                for ($j = 0; $j < $cal_days ; $j++) 
                                { 
                                    $new_date = (new Carbon($startDate))->addDays($j)->toDateString();
                                    
                                    if($rateplanId == 'EP') {
                                        $inventory=Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->first();                                        
                                        if(isset($value->Single))
                                        {
                                            $inventory->single_occupancy_price_ep = $value->Single;
                                        }
            
                                        if(isset($value->Double))
                                        {
                                            $inventory->double_occupancy_price_ep = $value->Double;
                                        }
            
                                        if(isset($value->ExtraAdult))
                                        {
                                            $inventory->extra_adult_ep = $value->ExtraAdult;
                                        }
                                        $inventory->save();
                                     //Old code
                                    // Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price'=>$single_price,'double_occupancy_price'=>$double_price,'extra_adult'=>$extra_adult]);
                                    //New code add  ep_price and cp_price 
                                    // Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_ep'=>$single_price,'double_occupancy_price_ep'=>$double_price,'extra_adult_ep'=>$extra_adult]);
                                    
                                  } else {
                                    $inventory=Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->first();
                                    if(isset($value->Single))
                                        {
                                            $inventory->single_occupancy_price_cp = $value->Single;
                                        }
            
                                        if(isset($value->Double))
                                        {
                                            $inventory->double_occupancy_price_cp = $value->Double;
                                        }
            
                                        if(isset($value->ExtraAdult))
                                        {
                                            $inventory->extra_adult_cp = $value->ExtraAdult;
                                        }
                                        $inventory->save();
                                    //  Dailyinventory::where(['hotel_id'=>$hotel_id,'category_id'=>$room_id])->whereDate('date','=',$new_date)->update(['single_occupancy_price_cp'=>$single_price,'double_occupancy_price_cp'=>$double_price,'extra_adult_cp'=>$extra_adult]);

                                  }       
                                }

                                
                            }
                            else
                            {
                                return response()->json(['message' => 'Invalid StartDate','status' => 'failure']);
                            }

                        }

                        return response()->json(['message' => 'Update sucessfully','status' => 'success']);
                    }
                    else
                    {
                        return response()->json(['message' => 'Invalid roomId','status' => 'failure','data' => []]);
                    }

                }
                else
                {
                    return response()->json(['message' => 'Invalid PropertyId','status' => 'failure','data' => []]);
                }

            }
            else
            {
                return response()->json(['message' => 'Invalid Key','status' => 'failure','data' => []]);
            }

            //echo $hotel_id;    

        }
                   
    }
    

}
