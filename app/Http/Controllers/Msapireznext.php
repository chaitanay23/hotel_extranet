<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DOMDocument;
use App\Http\Requests;
use App\Model\Hotel as hotel;
use Carbon\Carbon;
use App\Model\Hoteldetail;
use App\Model\Dailyinventory;
use App\Model\Hotelapiinteraction;

class Msapireznext extends Controller
{
    
    public function get_request(Request $request)
    {
        if($request->method == 'property')
        {

            $json = file_get_contents("php://input");
            $xml = simplexml_load_string($json);


            if ($xml === false)
            {        
                echo "Failed loading XML: ";
                foreach(libxml_get_errors() as $error) 
                {
                    echo "<br>", $error->message;
                }
            
            } 

            else 
            {
                    
                if($xml->getName() == 'RN_HotelRoomRatePlanReadRQ')
                {
                    $uname = $xml->Authentication->attributes()->Username;
                    $token = $xml->attributes()->EchoToken;
                    $upass = $xml->Authentication->attributes()->Password;
                    $id = $xml->RoomRatePlan->HotelCriteria->attributes()->HotelCode;

                    $channel_id = Hotelapiinteraction::where(['hotel_id'=>$id,'status'=>1])->first();
                    $ch_id = $channel_id->channel_partner;
                    //Old Code
                    //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>'Reznext','status'=>1,'hotel_id'=>$id])->first();
                    //New Code
                    $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->first(); 
                    $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->count(); 
                    $token = $xml->attributes()->EchoToken;
                    
                    if($search_hotel_count > 0)
                    {
                        $data = hotel::find($id);
                         //Old code
                         //$room_info = Hoteldetail::where(['hotel_id'=>$id,'status'=>1,'cp_include'=>'CP'])->get();
                          //Change Code 
                         $room_info = Hoteldetail::where(['hotel_id'=>$id,'status'=>1])->get();
                    
                        if($data == null)
                        { 
                            $status = 3; 
                        }
                        else 
                        { 
                            $status = 10;
                        }
                    }
                    else
                    {
                        $status = 4;
                    }

                            
                           
                    /* create a dom document with encoding utf8 */
                    $domtree = new DOMDocument('1.0', 'UTF-8');


                    $domtree->preserveWhiteSpace = false;
                    $domtree->formatOutput = true;
                    /* create the root element of the xml tree */
                    $xmlRoot = $domtree->createElement("RN_HotelRoomRatePlanReadRS");
                    /* append it to the document created */
                    $xmlRoot = $domtree->appendChild($xmlRoot);

                    $xmlns = $domtree->createAttribute('xmlns');
                    $xmlns->value = "http://www.opentravel.org/OTA/2003/05";
                    $xmlRoot->appendChild($xmlns);

                    $TimeStamp = $domtree->createAttribute('TimeStamp');
                    $TimeStamp->value = Carbon::now()->toDateString().'T'.Carbon::now()->toTimeString();
                    $xmlRoot->appendChild($TimeStamp);

                    $EchoToken = $domtree->createAttribute('EchoToken');
                    $EchoToken->value = $token;
                    $xmlRoot->appendChild($EchoToken);

                    $Version = $domtree->createAttribute('Version');
                    $Version->value = "1.2";
                    $xmlRoot->appendChild($Version);

                    if($status == 4)
                    {
                        
                        $Errors = $domtree->createElement("Errors");

                        $xmlRoot->appendChild($Errors);

                        $Error = $domtree->createElement('Error','Authentication');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '3';
                                $Error->appendChild($Type);

                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '400';
                                $Error->appendChild($Code);

                                
                                $ShortText = $domtree->createAttribute('ShortText');
                                $ShortText->value = 'Unable to process';
                                $Error->appendChild($ShortText);
                                
                                $Errors->appendChild($Error);

                    }
                    elseif($status == 3)
                    {
                        
                        $Errors = $domtree->createElement("Errors");

                        $xmlRoot->appendChild($Errors);

                        $Error = $domtree->createElement('Error','Invalid hotel code.');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '3';
                                $Error->appendChild($Type);

                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '400';
                                $Error->appendChild($Code);

                                
                                $ShortText = $domtree->createAttribute('ShortText');
                                $ShortText->value = 'Unable to process';
                                $Error->appendChild($ShortText);

                                $Errors->appendChild($Error);
                    }
                    else
                    {

                        $HotelCriteria = $domtree->createElement("HotelCriteria");

                        $HotelCode = $domtree->createAttribute('HotelCode');
                        $HotelCode->value = $data->id;
                        $HotelCriteria->appendChild($HotelCode);

                        $xmlRoot->appendChild($HotelCriteria);

                        $RoomTypes = $domtree->createElement("RoomTypes");
                        $xmlRoot->appendChild($RoomTypes);

                        $RatePlans = $domtree->createElement("RatePlans");
                        $xmlRoot->appendChild($RatePlans);

                        foreach ($room_info as $value) 
                        {
                            
                            //Repeat Data 

                            $RoomType = $domtree->createElement('RoomType');
                            $RoomTypes->appendChild($RoomType);

                            $InvTypeCode = $domtree->createAttribute('InvTypeCode');
                            $InvTypeCode->value = $value->id;
                            $RoomType->appendChild($InvTypeCode);

                            $RoomName = $domtree->createAttribute('RoomName');
                            $RoomName->value = $value->custom_category;
                            $RoomType->appendChild($RoomName);

                            $BaseOccupancy = $domtree->createAttribute('BaseOccupancy');
                            $BaseOccupancy->value = '1';
                            $RoomType->appendChild($BaseOccupancy);

                            $MaxOccupancy = $domtree->createAttribute('MaxOccupancy');
                            $MaxOccupancy->value = $value->max_guest_allow;
                            $RoomType->appendChild($MaxOccupancy);


                            $Quantity = $domtree->createAttribute('Quantity');
                            $Quantity->value ='10';
                            $RoomType->appendChild($Quantity);

                            $IsRoomActive = $domtree->createAttribute('IsRoomActive');
                            $IsRoomActive->value = '1';
                            $RoomType->appendChild($IsRoomActive);

                            $RoomDescription = $domtree->createElement('RoomDescription');
                            $RoomType->appendChild($RoomDescription);

                            $Text = $domtree->createAttribute('Text');
                            $Text->value = str_replace("&","and",$value->description);
                            $RoomDescription->appendChild($Text);

                            $start_date = Dailyinventory::where('category_id',$value->id)->orderBy('date','ASC')->first(['date']);

                            $end_date = Dailyinventory::where('category_id',$value->id)->orderBy('date','DESC')->first(['date']);


                            for ($i=0; $i < 2; $i++) 
                            { 
                                
                                if($i == 0)
                                {
                                    $rateplanname = 'Include Breakfast';
                                    $rateplanid = 'CP';
                                }
                                else
                                {
                                    $rateplanname = 'Room Only';
                                    $rateplanid = 'EP';
                                }
                                //Repeat Data 
                                $RatePlan = $domtree->createElement('RatePlan');
                                $RatePlans->appendChild($RatePlan);

                                $RatePlanCode = $domtree->createAttribute('RatePlanCode');
                                $RatePlanCode->value = $rateplanid;
                                $RatePlan->appendChild($RatePlanCode);

                                $RatePlanStatusType = $domtree->createAttribute('RatePlanStatusType');
                                $RatePlanStatusType->value = 'Active';
                                $RatePlan->appendChild($RatePlanStatusType);

                                $RatePlanName = $domtree->createAttribute('RatePlanName');
                                $RatePlanName->value = $rateplanname;
                                $RatePlan->appendChild($RatePlanName);

                                $Description = $domtree->createAttribute('Description');
                                $Description->value = $rateplanname;
                                $RatePlan->appendChild($Description);


                                $InvTypeCode = $domtree->createAttribute('InvTypeCode');
                                $InvTypeCode->value = $value->id;
                                $RatePlan->appendChild($InvTypeCode);


                                $Start = $domtree->createAttribute('Start');
                                $Start->value = $start_date->date;
                                $RatePlan->appendChild($Start);

                                $End = $domtree->createAttribute('End');
                                $End->value = $end_date->date;
                                $RatePlan->appendChild($End);

                                $CurrencyCode = $domtree->createAttribute('CurrencyCode');
                                $CurrencyCode->value = 'INR';
                                $RatePlan->appendChild($CurrencyCode);
                                
                                
                                $MealPlanCode = $domtree->createAttribute('MealPlanCode');
                                $MealPlanCode->value = $rateplanid;
                                $RatePlan->appendChild($MealPlanCode);

                                $RateType = $domtree->createAttribute('RateType');
                                $RateType->value = 'Net';
                                $RatePlan->appendChild($RateType);

                                # code...
                            }

                           
                        }

                    }           

                        echo $domtree->saveXML();                      

                }           

                if($xml->getName() == 'OTA_HotelRatePlanNotifRQ')
                {
                               
                    $uname = $xml->Authentication->attributes()->Username;
                    $upass = $xml->Authentication->attributes()->Password;
                    $id = $xml->RatePlans->attributes()->HotelCode;
                    $token = $xml->attributes()->EchoToken;
                    

                   $channel_id = Hotelapiinteraction::where(['hotel_id'=>$id,'status'=>1])->first();
                   $ch_id = $channel_id->channel_partner;
                    //Old code  
                    //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>'Reznext','status'=>1,'hotel_id'=>$id])->first();
                     //New Code
                     $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->first();
                     $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->count();

                    if($search_hotel_count > 0)
                    {
                        $data = hotel::find($id);

                        if($data == null)
                        { 
                            $status = 3;
                        }
                        
                        else
                        { 

                            $data_return = count($xml->RatePlans->RatePlan);

                            $InvTypeCode = $xml->RatePlans->RatePlan[0]->Rates->Rate->attributes()->InvTypeCode;

                            $room_count = Hoteldetail::find($InvTypeCode);
                            $room_count_count = Hoteldetail::find($InvTypeCode)->count();
                            //dd($data_return);

                            if($room_count_count > 0)
                            {
                                for ($i=0; $i < $data_return; $i++) 
                                { 

                                    $cat_id = $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->InvTypeCode;

                                    $rateplanid = $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->MealPlanCode;

                                    if($rateplanid == 'EP')
                                    {
                                        $fetch_meal_plan = Hoteldetail::where(['room_id'=>$cat_id,'hotel_id'=>$id])->first();
                                        $cat_id = $fetch_meal_plan->id;
                                    }

                                    $date1 =  $xml->RatePlans->RatePlan[$i]->attributes()->Start;

                                    $todate =  $xml->RatePlans->RatePlan[$i]->attributes()->End;

                                    $Sun= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Sun;
                                    $Mon= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Mon;
                                    $Tue= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Tue;
                                    $Wed= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Wed;
                                    $Thu= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Thu;
                                    $Fri= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Fri;
                                    $Sat= $xml->RatePlans->RatePlan[$i]->Rates->Rate->attributes()->Sat;

                                    $adult = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[0]->attributes()->NumberOfGuests;

                                    $age_count = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[0]->attributes()->AgeQualifyingCode;

                                    if($adult == 1 && $age_count == 10)
                                    {
                                       $single_price = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[0]->attributes()->Amount; 
                                    }
                                    elseif($adult == 2 && $age_count == 10)
                                    {
                                       $double_price = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[0]->attributes()->Amount; 
                                    }

                                    $adult1 = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[1]->attributes()->NumberOfGuests;

                                    $age_count1 = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[1]->attributes()->AgeQualifyingCode;


                                    if($adult1 == 1 && $age_count1 == 10)
                                    {
                                       $single_price = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[1]->attributes()->Amount; 
                                    }
                                    elseif($adult1 == 2 && $age_count1 == 10)
                                    {
                                       $double_price = $xml->RatePlans->RatePlan[$i]->Rates->Rate->BaseByGuestAmts->BaseByGuestAmt[1]->attributes()->Amount; 
                                    }

                                    $extra_adult = $xml->RatePlans->RatePlan[$i]->Rates->Rate->AdditionalGuestAmounts->AdditionalGuestAmount[1]->attributes()->Amount; 

                                
                                    $array_day  = ['Sun'=>$Sun,'Mon'=>$Mon,'Tue'=>$Tue,'Wed'=>$Wed,'Thu'=>$Thu,'Fri'=>$Fri,'Sat'=>$Sat];


                                    $date_1=date_create(Carbon::now()->toDateString());
                                    $date_2=date_create($date1);
                                    $diff12 = date_diff($date_1, $date_2);
                                    $days = $diff12->format("%R%a days");


                                    $date_start = date_create($date1);
                                    $date_end = date_create($todate);
                                    $give_diff = date_diff($date_start, $date_end);
                                    $cal_days = $give_diff->format("%R%a days");
                                    $cal_days = (int)$cal_days + 1;

                                    //dd($days);
                                    if($days >= 0) 
                                    { 
                                        $status = 10;
                                        $price = (int)$single_price;
                                        $price1 = (int)$double_price;
                                        $extraamount = (int)$extra_adult;
                                        
                                        for ($j = 0; $j < $cal_days ; $j++) 
                                        { 
                                            # code...

                                            $new_date = (new Carbon($date1))->addDays($j)->toDateString();
                                            

                                            $whichday = date('D', strtotime($new_date));


                                            
                                            if($array_day[$whichday] == 1 || $array_day[$whichday] == Null)
                                            {
                                               //Old Code
                                                // Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereDate('date','=',$new_date)->update(['single_occupancy_price'=>$price,'double_occupancy_price'=>$price1,'extra_adult'=>$extraamount,'flag'=>1]);

                                                //New Code changes cp and ep
                                                 if($rateplanid == 'EP') {
                                                    Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereDate('date','=',$new_date)->update(['single_occupancy_price_ep'=>$price,'double_occupancy_price_ep'=>$price1,'extra_adult_ep'=>$extraamount,'flag'=>1,'ep_status'=>1]);
                                                 } elseif($rateplanid == 'CP') {
                                                    Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereDate('date','=',$new_date)->update(['single_occupancy_price_cp'=>$price,'double_occupancy_price_cp'=>$price1,'extra_adult_cp'=>$extraamount,'flag'=>1,'cp_status'=>1]);
                                                 } 

                                            }
                                           
                                            //echo $whichday;
                                        }
                                                     
                                    }
                                    else 
                                    { 
                                        //for current date
                                        $status = 5;
                                    }
                                                                              
                                    

                                }

                            }
                            else
                            {
                                $status = 4;
                            }
                        }
                    }
                    else
                    {
                        $status = 6;
                    }

                        /* create a dom document with encoding utf8 */
                        $domtree = new DOMDocument('1.0', 'UTF-8');
                        $domtree->preserveWhiteSpace = false;
                        $domtree->formatOutput = true;
                        /* create the root element of the xml tree */
                        $xmlRoot = $domtree->createElement("OTA_HotelRatePlanNotifRS");
                        /* append it to the document created */
                        $xmlRoot = $domtree->appendChild($xmlRoot);

                        $xmlns = $domtree->createAttribute('xmlns');
                        $xmlns->value = "http://www.opentravel.org/OTA/2003/05";
                        $xmlRoot->appendChild($xmlns);

                        $TimeStamp = $domtree->createAttribute('TimeStamp');
                        $TimeStamp->value = Carbon::now()->toDateString().'T'.Carbon::now()->toTimeString();
                        $xmlRoot->appendChild($TimeStamp);

                        $EchoToken = $domtree->createAttribute('EchoToken');
                        $EchoToken->value = $token;
                        $xmlRoot->appendChild($EchoToken);

                        $Version = $domtree->createAttribute('Version');
                        $Version->value = "1.2";
                        $xmlRoot->appendChild($Version);

                        if($status == 6)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Authentication');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 3)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid hotel code.');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 4)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid room type');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 5)
                        {
                            // $Errors = $domtree->createElement("Error","Invalid Start Date");

                            // $xmlRoot->appendChild($Errors);

                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Invalid Start Date');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        else
                        {

                            $xmlRoot->appendChild($domtree->createElement('Success'));

                        }
                                
                        echo $domtree->saveXML();


                }

                if($xml->getName() == 'OTA_HotelInvCountNotifRQ')
                {
                    $uname = $xml->Authentication->attributes()->Username;
                    $upass = $xml->Authentication->attributes()->Password;
                    $token = $xml->attributes()->EchoToken;
                    $id = $xml->Inventories->attributes()->HotelCode;
                   
                    $channel_id = Hotelapiinteraction::where(['hotel_id'=>$id,'status'=>1])->first();
                    $ch_id = $channel_id->channel_partner;
                    //Old code
                    //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>'Reznext','status'=>1,'hotel_id'=>$id])->first();
                    //New Code
                     $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->first();
                     $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->count();
                    if($search_hotel_count > 0)
                    {

                        $data = hotel::find($id);

                        if($data == null)
                        { 
                            $status = 3;
                        }
                    
                        else
                        { 

                            $data_return = count($xml->Inventories->Inventory);

                            $InvTypeCode = $xml->Inventories->Inventory->StatusApplicationControl[0]->attributes()->InvTypeCode;

                            $room_count = Hoteldetail::find($InvTypeCode);
                            $room_count_count = Hoteldetail::find($InvTypeCode)->count();
                            //dd($data_return);

                            if($room_count_count > 0)
                            {
                                for ($i=0; $i < $data_return ; $i++) 
                                { 

                                    $cat_id = $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->InvTypeCode;
                                    $date1 =  $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Start;

                                    $todate =  $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->End;

                                    $Sun= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Sun;
                                    $Mon= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Mon;
                                    $Tue= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Tue;
                                    $Wed= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Wed;
                                    $Thu= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Thu;
                                    $Fri= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Fri;
                                    $Sat= $xml->Inventories->Inventory[$i]->StatusApplicationControl->attributes()->Sat;

                                    $Allotment = $xml->Inventories->Inventory[$i]->InvCounts->InvCount->attributes()->Count;

                            
                                    $array_day  = ['Sun'=>$Sun,'Mon'=>$Mon,'Tue'=>$Tue,'Wed'=>$Wed,'Thu'=>$Thu,'Fri'=>$Fri,'Sat'=>$Sat];


                                    $date_1=date_create(Carbon::now()->toDateString());
                                    $date_2=date_create($date1);
                                    $diff12 = date_diff($date_1, $date_2);
                                    $days = $diff12->format("%R%a days");


                                    $date_start = date_create($date1);
                                    $date_end = date_create($todate);
                                    $give_diff = date_diff($date_start, $date_end);
                                    $cal_days = $give_diff->format("%R%a days");
                                    $cal_days = (int)$cal_days + 1;

                                
                                    if($days >= 0) 
                                    { 
                                        $status = 10;

                                    
                                        for ($j = 0; $j < $cal_days ; $j++) 
                                        { 
                                        # code...

                                            $new_date = (new Carbon($date1))->addDays($j)->toDateString();
                                        

                                            $whichday = date('D', strtotime($new_date));


                                        
                                            if($array_day[$whichday] == 1 || $array_day[$whichday] == Null)
                                            {

                                                // Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereDate('date','=',$new_date)->update(['rooms'=>$Allotment]);
                                                 
                                                 //Old code 
                                                //Dailyinventory::where('hotel_id',$id)->whereDate('date','=',$new_date)->update(['rooms'=>$Allotment]);
                                            //New Code
                                             Dailyinventory::where('hotel_id',$id)->whereDate('date','=',$new_date)->update(['rooms_cp'=>$Allotment,'rooms_ep'=>$Allotment]);
                                            }
                                            else
                                            {
                                                      
                                            
                                                                                                                  
                                            }
                                            //echo $whichday;
                                        }
                                                 
                                    }
                                    else 
                                    { 
                                        //for current date
                                        $status = 5;
                                    }
                                                                          
                                

                                }

                            }
                            else
                            {
                                $status = 4;
                            }
                        }
                    }
                    else
                    { 
                        $status = 6;
                    }

                        
                            
                        /* create a dom document with encoding utf8 */
                        $domtree = new DOMDocument('1.0', 'UTF-8');


                        $domtree->preserveWhiteSpace = false;
                        $domtree->formatOutput = true;
                        /* create the root element of the xml tree */
                        $xmlRoot = $domtree->createElement("OTA_HotelInvCountNotifRS");
                        /* append it to the document created */
                        $xmlRoot = $domtree->appendChild($xmlRoot);


                        $xmlns = $domtree->createAttribute('xmlns');
                        $xmlns->value = "http://www.opentravel.org/OTA/2003/05";
                        $xmlRoot->appendChild($xmlns);

                        $TimeStamp = $domtree->createAttribute('TimeStamp');
                        $TimeStamp->value = Carbon::now()->toDateString().'T'.Carbon::now()->toTimeString();
                        $xmlRoot->appendChild($TimeStamp);

                        $EchoToken = $domtree->createAttribute('EchoToken');
                        $EchoToken->value = $token;
                        $xmlRoot->appendChild($EchoToken);

                        $Version = $domtree->createAttribute('Version');
                        $Version->value = "1.2";
                        $xmlRoot->appendChild($Version);

                        if($status == 6)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Authentication');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 3)
                        {

                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid hotel code.');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 4)
                        {
                           
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid room type');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 5)
                        {
                            
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Invalid Start Date');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        else
                        {

                            $xmlRoot->appendChild($domtree->createElement('Success'));

                        }
                                
                        echo $domtree->saveXML();

                                
                        
                }

                if($xml->getName() == 'OTA_HotelAvailNotifRQ')
                {
                    $uname = $xml->Authentication->attributes()->Username;
                    $upass = $xml->Authentication->attributes()->Password;
                    $token = $xml->attributes()->EchoToken;
                    $id = $xml->AvailStatusMessages->attributes()->HotelCode;
                    $channel_id = Hotelapiinteraction::where(['hotel_id'=>$id,'status'=>1])->first();
                    $ch_id = $channel_id->channel_partner;    
                     //Old Code
                    //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>'Reznext','status'=>1,'hotel_id'=>$id])->first();
                    //New Code
                    $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->first();
                    $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$uname,'hotelpassword'=>$upass,'channel_partner'=>$ch_id,'status'=>1,'hotel_id'=>$id])->count();
                    if($search_hotel_count > 0)
                    {
                        $data = hotel::find($id);

                        if($data == null)
                        { 
                            $status = 3;
                        }
                        
                        else
                        { 

                            $data_return = count($xml->AvailStatusMessages->AvailStatusMessage);

                            $InvTypeCode = $xml->AvailStatusMessages->AvailStatusMessage->StatusApplicationControl[0]->attributes()->InvTypeCode;

                            $room_count = Hoteldetail::find($InvTypeCode);
                            $room_count_count = Hoteldetail::find($InvTypeCode)->count();
                            //dd($data_return);

                            if($room_count_count > 0)
                            {
                                for ($i=0; $i < $data_return ; $i++) 
                                { 

                                    $cat_id = $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->InvTypeCode;
                                    $date1 =  $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Start;

                                    $rateplanid = $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->MealPlanCode;

                                    //Old code comment by himanshu 
                                   /* if($rateplanid == 'EP')
                                    {
                                        $fetch_meal_plan = Hoteldetail::where(['room_id'=>$cat_id,'hotel_id'=>$id])->first();
                                        $cat_id = $fetch_meal_plan->id;
                                    }*/

                                    $todate =  $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->End;

                                    $Sun= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Sun;
                                    $Mon= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Mon;
                                    $Tue= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Tue;
                                    $Wed= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Wed;
                                    $Thu= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Thu;
                                    $Fri= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Fri;
                                    $Sat= $xml->AvailStatusMessages->AvailStatusMessage[$i]->StatusApplicationControl->attributes()->Sat;

                                    $Status = $xml->AvailStatusMessages->AvailStatusMessage[$i]->RestrictionStatus->attributes()->Status;

                                    if($Status == 'Open')
                                    {
                                        $flag = 1;
                                    }
                                    else
                                    {
                                        $flag = 0;
                                    }

                                
                                    $array_day  = ['Sun'=>$Sun,'Mon'=>$Mon,'Tue'=>$Tue,'Wed'=>$Wed,'Thu'=>$Thu,'Fri'=>$Fri,'Sat'=>$Sat];


                                    $date_1=date_create(Carbon::now()->toDateString());
                                    $date_2=date_create($date1);
                                    $diff12 = date_diff($date_1, $date_2);
                                    $days = $diff12->format("%R%a days");


                                    $date_start = date_create($date1);
                                    $date_end = date_create($todate);
                                    $give_diff = date_diff($date_start, $date_end);
                                    $cal_days = $give_diff->format("%R%a days");
                                    $cal_days = (int)$cal_days + 1;


                                    if($days >= 0) 
                                    { 
                                        $status = 10;

                                        
                                        for ($j = 0; $j < $cal_days ; $j++) 
                                        { 
                                            # code...

                                            $new_date = (new Carbon($date1))->addDays($j)->toDateString();
                                            

                                            $whichday = date('D', strtotime($new_date));


                                            
                                            if($array_day[$whichday] == 1 || $array_day[$whichday] == Null)
                                            {

                                                Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereDate('date','=',$new_date)->update(['flag'=>$flag,'cp_status'=>$flag,'ep_status'=>$flag]);
                                            }
                                            else
                                            {
                                                          
                                                
                                                                                                                      
                                            }
                                            //echo $whichday;
                                        }
                                                     
                                    }
                                    else 
                                    { 
                                        //for current date
                                        $status = 5;
                                    }
                                                                              
                                    

                                }

                            }
                            else
                            {
                                $status = 4;
                            }
                        }

                    }
                    else
                    {
                        $status = 6;
                    }
                        
                            
                        /* create a dom document with encoding utf8 */
                        $domtree = new DOMDocument('1.0', 'UTF-8');


                        $domtree->preserveWhiteSpace = false;
                        $domtree->formatOutput = true;
                        /* create the root element of the xml tree */
                        $xmlRoot = $domtree->createElement("OTA_HotelInvCountNotifRS");
                        /* append it to the document created */
                        $xmlRoot = $domtree->appendChild($xmlRoot);

                        $xmlns = $domtree->createAttribute('xmlns');
                        $xmlns->value = "http://www.opentravel.org/OTA/2003/05";
                        $xmlRoot->appendChild($xmlns);

                        $TimeStamp = $domtree->createAttribute('TimeStamp');
                        $TimeStamp->value = Carbon::now()->toDateString().'T'.Carbon::now()->toTimeString();
                        $xmlRoot->appendChild($TimeStamp);

                        $EchoToken = $domtree->createAttribute('EchoToken');
                        $EchoToken->value = $token;
                        $xmlRoot->appendChild($EchoToken);

                        $Version = $domtree->createAttribute('Version');
                        $Version->value = "1.2";
                        $xmlRoot->appendChild($Version);

                        if($status == 6)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Authentication');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 3)
                        {

                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid hotel code.');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 4)
                        {

                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error',' Invalid room type');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 5)
                        {
                           
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Invalid Start Date');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '400';
                            $Error->appendChild($Code);

                            
                            $ShortText = $domtree->createAttribute('ShortText');
                            $ShortText->value = 'Unable to process';
                            $Error->appendChild($ShortText);

                            $Errors->appendChild($Error);

                        }
                        else
                        {

                            $xmlRoot->appendChild($domtree->createElement('Success'));

                        }
                                
                        echo $domtree->saveXML();

                                
                        
                }

                
            }

        }
    }
    

}