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

class magicspreeapi extends Controller
{
    //
    public function get_property(Request $request)
    {
        
        
        $operation = $request->operation;

       // $type = 'text/xml';

        

        if($operation == 2)
        {
            echo 'ugnujg';
            $id = $request->hcode;
            
            if($request->uname != 'magicspree' || $request->upass != 'magicspree')
            {
                $status = 4;
                return response()->view('xml.responce',['status'=>$status])->header('Content-Type', $type);
            }

            $data = hotel::find($id);
            $room_info = Hoteldetail::where(['hotel_id'=>$id,'status'=>1])->get();

            if($data == null)
            {
                $status = 3;
            }
            else
            {
                $status = 10;
            }
            return response()->view('xml.responce',['data'=>$data,'room_info'=>$room_info,'status'=>$status])->header('Content-Type', $type);
        }
        if($operation == 1)
        {
            
            if($request->uname != 'magicspree' || $request->upass != 'magicspree')
            {
                $status = 4;
                return response()->view('xml.result',['status'=>$status])->header('Content-Type', $type);
            }
            $id = 1;
            $data = hotel::find($id);

            if($data == null)
            {
                $status = 3;
            }
            else
            {
                $status = 10;
            }
            return response()->view('xml.result',['data'=>$data,'status'=>$status])->header('Content-Type', $type);
        }
        if($operation == 3)
        {
            

            if($request->uname_fetch != 'magicspree' || $request->pass_fetch != 'magicspree')
            {
                $status = 4;
                return response()->view('xml.inven',['status'=>$status])->header('Content-Type', $type);
            }
            $hotel_id = $request->hcode_fetch;
            $cat_id = $request->InvTypeCode;
            $date1 = $request->fromdate;
            $to_date = $request->todate;


            $inv = Dailyinventory::where('hotel_id',$hotel_id)
                            ->where('category_id',$cat_id)
                            ->whereBetween('date',[$date1,$to_date])
                            ->first();
             
            //Ask Sir
            $disc = Hoteldetail::where('hotel_id',$hotel_id)
                                ->where('category_id',$cat_id)
                                ->first();
                            //dd($inv);

                        $count = count($inv);
            if($count == 0)
            {
                $status = 3;
            }
            else
            {
                $status = 10;
            }

            return response()->view('xml.inven',['data'=>$inv,'status'=>$status,'todate'=>$to_date,'disc'=>$disc])->header('Content-Type', $type);


        }
    
    }

    public function get_request(Request $request)
    {
        if($request->method == 'property')
        {
                
                $json = file_get_contents("php://input");
                $xml = simplexml_load_string($json);

                if ($xml === false)
                {        echo "Failed loading XML: ";
                        foreach(libxml_get_errors() as $error) {
                            echo "<br>", $error->message;
                        }
                } 

                else 
                {

                   if($xml->getName() == 'HotelPropertyListGetRQ')
                    {
                        $username = $xml->Authentication->attributes()->UserName;
                        $password = $xml->Authentication->attributes()->Password;
                         //New Code
                        $hotel_id = $xml->HotelARIGetRequests->attributes()->HotelCode;
                        $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
                       
                        $ch_id = $channel_id->channel_partner;
                         //Old code
                        //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Rategain','status'=>1])->first();
                        //New Code channel id
                         $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first();
                         $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->count();
                        if($search_hotel_count > 0)
                            {
                                $id = $search_hotel->hotel_id;
                                $data = hotel::find($id);

                                if($data == null){ $status = 3;}
                                else{ $status = 10;}
                            }
                        else{ $status = 4;}

                                
                                   
                                
                            /* create a dom document with encoding utf8 */
                            $domtree = new DOMDocument('1.0', 'UTF-8');


                            $domtree->preserveWhiteSpace = false;
                            $domtree->formatOutput = true;
                            /* create the root element of the xml tree */
                            $xmlRoot = $domtree->createElement("HotelPropertyListGetRS");
                            /* append it to the document created */
                            $xmlRoot = $domtree->appendChild($xmlRoot);

                            $domAttribute = $domtree->createAttribute('xmlns');
                            $domAttribute->value = 'http://cgbridge.rategain.com/OTA/2012/05';
                            $xmlRoot->appendChild($domAttribute);


                            $TimeStamp = $domtree->createAttribute('TimeStamp');
                            $TimeStamp->value = Carbon::now();
                            $xmlRoot->appendChild($TimeStamp);


                            $Target = $domtree->createAttribute('Target');
                            $Target->value = 'Production';
                            $xmlRoot->appendChild($Target);


                            $Version = $domtree->createAttribute('Version');
                            $Version->value = '1.1';
                            $xmlRoot->appendChild($Version);


                            if($status == 3)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','No hotels found which match this input');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '3';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '424';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);

                            }
                            elseif($status == 4)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Password invalid');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '4';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            elseif($status == 5)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Unknown');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '1';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            else
                            {

                                $xmlRoot->appendChild($domtree->createElement('Success'));


                                $Hotels = $domtree->createElement("Hotels");

                                $ChainCode = $domtree->createAttribute('ChainCode');
                                $ChainCode->value = '0';
                                $Hotels->appendChild($ChainCode);

                                $xmlRoot->appendChild($Hotels);

                                $Hotel = $domtree->createElement('Hotel');

                                $HotelCode = $domtree->createAttribute('HotelCode');
                                $HotelCode->value = $data->id;
                                $Hotel->appendChild($HotelCode);

                                $Hotels->appendChild($Hotel);


                                $Name = $domtree->createAttribute('Name');
                                $Name->value = str_replace('&','and',$data->title);
                                $Hotel->appendChild($Name);

                                $Hotels->appendChild($Hotel);
                                /* you should enclose the following two lines in a cicle */
                                
                                /* get the xml printed */
                                // header('Content-type: text/xml');
                            }
                                    echo $domtree->saveXML();
                                

                    }



                    if($xml->getName() == 'HotelProductListGetRQ')
                    {
                                
                        $username = $xml->Authentication->attributes()->UserName;
                        $password = $xml->Authentication->attributes()->Password;

                         $hotel_id = $xml->HotelARIGetRequests->attributes()->HotelCode;
                        
                         $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
                         $ch_id = $channel_id->channel_partner;
                         //Old Code
                        //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Rategain','status'=>1])->first();
                        //New Code
                          $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first(); 
                          $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->count(); 
                        if( $search_hotel_count > 0)
                            {
                                $id = $xml->HotelProductListRequest->attributes()->HotelCode;
                                // $id = $search_hotel->hotel_id;
                                $data = hotel::find($id);
                                
                                 //Channel Ask sir   
                                $room_info = Hoteldetail::where(['hotel_id'=>$id,'status'=>1])->get();
                                if($data == null)
                                    { $status = 3;}
                                else{ $status = 10;}
                            }
                         else{ $status = 4;}
                                
                               
                            /* create a dom document with encoding utf8 */
                            $domtree = new DOMDocument('1.0', 'UTF-8');


                            $domtree->preserveWhiteSpace = false;
                            $domtree->formatOutput = true;
                            /* create the root element of the xml tree */
                            $xmlRoot = $domtree->createElement("HotelProductListGetRS");
                            /* append it to the document created */
                            $xmlRoot = $domtree->appendChild($xmlRoot);

                            $domAttribute = $domtree->createAttribute('xmlns');
                            $domAttribute->value = 'http://cgbridge.rategain.com/OTA/2012/05';
                            $xmlRoot->appendChild($domAttribute);


                            $TimeStamp = $domtree->createAttribute('TimeStamp');
                            $TimeStamp->value = Carbon::now();
                            $xmlRoot->appendChild($TimeStamp);


                            $Target = $domtree->createAttribute('Target');
                            $Target->value = 'Production';
                            $xmlRoot->appendChild($Target);


                            $Version = $domtree->createAttribute('Version');
                            $Version->value = '1.1';
                            $xmlRoot->appendChild($Version);


                            if($status == 3)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','No hotels found which match this input');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '3';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '424';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);

                            }
                            elseif($status == 4)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Password invalid');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '4';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            elseif($status == 5)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Unknown');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '1';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            else
                            {

                                $xmlRoot->appendChild($domtree->createElement('Success'));


                                $HotelProducts = $domtree->createElement("HotelProducts");

                                $HotelCode = $domtree->createAttribute('HotelCode');
                                $HotelCode->value = $data->id;
                                $HotelProducts->appendChild($HotelCode);

                                $xmlRoot->appendChild($HotelProducts);



                            foreach ($room_info as $value) 
                            {
                                    # code...
                                
                                //Repeat Data 
                                $HotelProduct = $domtree->createElement('HotelProduct');

                                $ProductReference = $domtree->createElement('ProductReference');
                                $InvTypeCode = $domtree->createAttribute('InvTypeCode');
                                $InvTypeCode->value = $value->id;
                                $ProductReference->appendChild($InvTypeCode);

                                $RatePlanCode = $domtree->createAttribute('RatePlanCode');
                                $RatePlanCode->value = 'BAR';
                                $ProductReference->appendChild($RatePlanCode);

                                $HotelProduct->appendChild($ProductReference);


                                $RoomTypeName = $domtree->createElement('RoomTypeName',$value->custom_category);
                                
                                $RateTypeName = $domtree->createElement('RateTypeName','BAR');
                                $HotelProduct->appendChild($RoomTypeName);

                                $HotelProduct->appendChild($RateTypeName);

                                $HotelProducts->appendChild($HotelProduct);

                            }

                            }           

                            echo $domtree->saveXML();                      

                    }           

                    if($xml->getName() == 'HotelARIGetRQ')
                    {
                                   
                        // $username = $xml->Authentication->attributes()->UserName;
                        // $password = $xml->Authentication->attributes()->Password;
                        
                        // $hotel_id = $xml->HotelARIGetRequests->attributes()->HotelCode;
                        $username = $request->user;
                        $password = $request->pass;
                        $hotel_id = $request->hotel;
                        $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
                        $ch_id = $channel_id->channel_partner;
                        //OLD CODE
                        //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Rategain','status'=>1])->first();
                        $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first(); 
                        $search_hotel_count = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->count(); 
                        if($search_hotel_count > 0)
                            {
                                
                            $hotel_id = $xml->HotelARIGetRequests->attributes()->HotelCode;
                            $cat_id = $xml->HotelARIGetRequests->HotelARIGetRequest->ProductReference->attributes()->InvTypeCode;
                            $date1 =  $xml->HotelARIGetRequests->HotelARIGetRequest->ApplicationControl->attributes()->Start;
                            $todate =  $xml->HotelARIGetRequests->HotelARIGetRequest->ApplicationControl->attributes()->End;
                            
                            $date_1=date_create(Carbon::now()->toDateString());
                            $date_2=date_create($date1);
                            $diff12 = date_diff($date_1, $date_2);
                            $diff_day = $diff12->format("%R%a days");

                            $start_date=date_create($date1);
                            $end_date=date_create($todate);
                            $diffnew = date_diff($start_date, $end_date);
                            $diff_day_new = $diffnew->format("%a");
                            


                            if($diff_day >= 0) 
                            { 
                                $data_in = Dailyinventory::where('hotel_id',$hotel_id)
                                                ->where('category_id',$cat_id)
                                                ->whereBetween('date',[$date1,$todate])
                                                ->get();


                                
                                $hotel_data = hotel::where('id',$hotel_id)->count();

                                $disc = Hoteldetail::where(['hotel_id'=>$hotel_id,'id'=>$cat_id])->first();
                                //Change calculate count
                                $disccount = Hoteldetail::where(['hotel_id'=>$hotel_id,'id'=>$cat_id])->count();
                                    
                                $counter = 1;
                                $count = $disccount;

                                if($hotel_data == 0)
                                { 
                                    $status = 3;
                                }
                                elseif ($count == 0) {
                                    $status = 7;
                                }

                                else
                                { 
                                     $status = 10;
                                } 
                            }
                            else 
                            { 
                                $status = 5;
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
                            $xmlRoot = $domtree->createElement("HotelARIGetRS");
                            /* append it to the document created */
                            $xmlRoot = $domtree->appendChild($xmlRoot);

                            $domAttribute = $domtree->createAttribute('xmlns');
                            $domAttribute->value = 'http://cgbridge.rategain.com/OTA/2012/05';
                            $xmlRoot->appendChild($domAttribute);


                            $TimeStamp = $domtree->createAttribute('TimeStamp');
                            $TimeStamp->value = Carbon::now();
                            $xmlRoot->appendChild($TimeStamp);


                            $Target = $domtree->createAttribute('Target');
                            $Target->value = 'Production';
                            $xmlRoot->appendChild($Target);


                            $Version = $domtree->createAttribute('Version');
                            $Version->value = '1.1';
                            $xmlRoot->appendChild($Version);


                        if($status == 3)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Unknown Hotel code');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '3';
                            $Error->appendChild($Type);

                            $Errors->appendChild($Error);


                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '424';
                            $Error->appendChild($Code);

                            $Errors->appendChild($Error);

                        }
                        elseif($status == 4)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Authentication Fail');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '4';
                            $Error->appendChild($Type);

                            $Errors->appendChild($Error);


                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '450';
                            $Error->appendChild($Code);

                            $Errors->appendChild($Error);
                        }
                        elseif($status == 5)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Invalid Start Date');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '4';
                            $Error->appendChild($Type);

                            $Errors->appendChild($Error);


                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '136';
                            $Error->appendChild($Code);

                            $Errors->appendChild($Error);
                        }
                        elseif($status == 6)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Unknown');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '1';
                            $Error->appendChild($Type);

                            $Errors->appendChild($Error);


                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '175';
                            $Error->appendChild($Code);

                            $Errors->appendChild($Error);
                        }
                        elseif($status == 7)
                        {
                            $Errors = $domtree->createElement("Errors");

                            $xmlRoot->appendChild($Errors);

                            $Error = $domtree->createElement('Error','Invalid room type code');

                            $Type = $domtree->createAttribute('Type');
                            $Type->value = '1';
                            $Error->appendChild($Type);

                            $Errors->appendChild($Error);


                            $Code = $domtree->createAttribute('Code');
                            $Code->value = '402';
                            $Error->appendChild($Code);

                            $Errors->appendChild($Error);
                        }
                        else
                        {

                            $xmlRoot->appendChild($domtree->createElement('Success'));


                            $HotelARIDataSet = $domtree->createElement("HotelARIDataSet");

                            $HotelCode = $domtree->createAttribute('HotelCode');
                            $HotelCode->value = $disc->hotel_id;
                            $HotelARIDataSet->appendChild($HotelCode);

                            $xmlRoot->appendChild($HotelARIDataSet);

                            foreach ($data_in as $key => $data) 
                            {
                            

                                $HotelARIData = $domtree->createElement("HotelARIData");

                                $ItemIdentifier = $domtree->createAttribute('ItemIdentifier');
                                $ItemIdentifier->value = $counter;
                                $HotelARIData->appendChild($ItemIdentifier);

                                $HotelARIDataSet->appendChild($HotelARIData);


                                $ProductReference = $domtree->createElement('ProductReference');
                                $InvTypeCode = $domtree->createAttribute('InvTypeCode');
                                $InvTypeCode->value = $data->category_id;
                                $ProductReference->appendChild($InvTypeCode);

                                $RatePlanCode = $domtree->createAttribute('RatePlanCode');
                                $RatePlanCode->value = 'BAR';
                                $ProductReference->appendChild($RatePlanCode);

                                $HotelARIData->appendChild($ProductReference);


                                $ApplicationControl = $domtree->createElement('ApplicationControl');
                                $Start = $domtree->createAttribute('Start');
                                $Start->value = $data->date;
                                $ApplicationControl->appendChild($Start);

                                $End = $domtree->createAttribute('End');
                                $End->value = $data->date;
                                $ApplicationControl->appendChild($End);

                                $HotelARIData->appendChild($ApplicationControl);


                                $RateAmounts = $domtree->createElement('RateAmounts');
                                $Currency = $domtree->createAttribute('Currency');
                                $Currency->value = 'INR';
                                $RateAmounts->appendChild($Currency);

                                $HotelARIData->appendChild($RateAmounts);


                                $Base1 = $domtree->createElement('Base');
                                $OccupancyCode = $domtree->createAttribute('OccupancyCode');
                                $OccupancyCode->value = 'A1';
                                $Base1->appendChild($OccupancyCode);

                                $Amount = $domtree->createAttribute('Amount');
                                $Amount->value = $data->single_occupancy_price_cp.'.00';
                                $Base1->appendChild($Amount);

                                $RateAmounts->appendChild($Base1);

                                $Base2 = $domtree->createElement('Base');
                                $OccupancyCode = $domtree->createAttribute('OccupancyCode');
                                $OccupancyCode->value = 'A2';
                                $Base2->appendChild($OccupancyCode);

                                $Amount = $domtree->createAttribute('Amount');
                                $Amount->value = $data->double_occupancy_price_cp.'.00';
                                $Base2->appendChild($Amount);


                                $RateAmounts->appendChild($Base2);

                                $Additional = $domtree->createElement('Additional');
                                $RateAmounts->appendChild($Additional);

                                $OccupancyCode = $domtree->createAttribute('OccupancyCode');
                                $OccupancyCode->value = 'AA';
                                $Additional->appendChild($OccupancyCode);

                                $Amount = $domtree->createAttribute('Amount');
                                $Amount->value = $data->extra_adult_cp.'.00';
                                $Additional->appendChild($Amount);


                                $MealPlans = $domtree->createElement('MealPlans');
                                $RateAmounts->appendChild($MealPlans);

                               
                                $Discount = $domtree->createElement('Discount');
                                $RateAmounts->appendChild($Discount);

                                $Amount2 = $domtree->createAttribute('Amount');
                                $Amount2->value = '';
                                $Discount->appendChild($Amount2);

                                $Availability = $domtree->createElement('Availability');
                                $HotelARIData->appendChild($Availability);

                                $Master = $domtree->createAttribute('Master');
                                if($data->flag == 1)
                                {
                                    $Master->value = 'Open';
                                }
                                else
                                {
                                    $Master->value = 'Closed';
                                }
                                
                                $Availability->appendChild($Master);

                               
                                $BookingLimit = $domtree->createElement('BookingLimit');
                                $HotelARIData->appendChild($BookingLimit);

                                $Sold = $domtree->createAttribute('Sold');
                                $Sold->value = $data->booked;
                                $BookingLimit->appendChild($Sold);

                                $FreeSale = $domtree->createAttribute('FreeSale');
                                $FreeSale->value = 'false';
                                $BookingLimit->appendChild($FreeSale);


                                //Start of BaseAllotment

                                $BaseAllotment = $domtree->createElement('BaseAllotment');
                                $BookingLimit->appendChild($BaseAllotment);


                                $ReleaseDays = $domtree->createAttribute('ReleaseDays');
                                $ReleaseDays->value = '0';
                                $BaseAllotment->appendChild($ReleaseDays);

                                $Sold = $domtree->createAttribute('Sold');
                                $Sold->value = $data->booked;
                                $BaseAllotment->appendChild($Sold);

                                $Allotment = $domtree->createAttribute('Allotment');
                                $Allotment->value = $data->contractual_room;
                                $BaseAllotment->appendChild($Allotment);


                                //Start of TransientAllotment
                                $TransientAllotment = $domtree->createElement('TransientAllotment','');
                                $BookingLimit->appendChild($TransientAllotment);
                                
                                
                                $ReleaseDays = $domtree->createAttribute('ReleaseDays');
                                $ReleaseDays->value = '0';
                                $TransientAllotment->appendChild($ReleaseDays);

                                $Sold = $domtree->createAttribute('Sold');
                                $Sold->value = $data->booked;
                                $TransientAllotment->appendChild($Sold);

                                $Allotment = $domtree->createAttribute('Allotment');
                                $Allotment->value = $data->rooms;
                                $TransientAllotment->appendChild($Allotment);

                                
                                //Start of BookingRules
                                $BookingRules = $domtree->createElement('BookingRules');
                                $HotelARIData->appendChild($BookingRules);

                               
                                $HotelARIData->appendChild($domtree->createElement('Description',preg_replace( "/\r|\n|'|&/", "", $disc->description) ));
                                $counter++;

                            }

                            if(count($data_in) != ($diff_day_new + 1))
                            {
                               $new_date = (new Carbon($date1))->addDays(count($data_in))->toDateString();

                                $HotelARIStatus = $domtree->createElement("HotelARIStatus");

                                $ItemIdentifier = $domtree->createAttribute('ItemIdentifier');
                                $ItemIdentifier->value = count($data_in) + 1;
                                $HotelARIStatus->appendChild($ItemIdentifier);

                                $HotelARIDataSet->appendChild($HotelARIStatus);


                                $ProductReference = $domtree->createElement('ProductReference');
                                $InvTypeCode = $domtree->createAttribute('InvTypeCode');
                                $InvTypeCode->value = $disc->id;
                                $ProductReference->appendChild($InvTypeCode);

                                $RatePlanCode = $domtree->createAttribute('RatePlanCode');
                                $RatePlanCode->value = 'BAR';
                                $ProductReference->appendChild($RatePlanCode);

                                $HotelARIStatus->appendChild($ProductReference);


                                $ApplicationControl = $domtree->createElement('ApplicationControl');

                                $Start1 = $domtree->createAttribute('Start');
                                $Start1->value = $new_date;
                                $ApplicationControl->appendChild($Start1);

//dd($new_date);
                                $End = $domtree->createAttribute('End');
                                $End->value = $todate;
                                $ApplicationControl->appendChild($End);

                                $HotelARIStatus->appendChild($ApplicationControl);

                                $Status = $domtree->createElement('Status','No inventory loaded for the requested dates');
                                $Code = $domtree->createAttribute('Code');
                                $Code->value = "842";
                                $Status->appendChild($Code);
                                
                                $HotelARIStatus->appendChild($Status);

                            }
                        }
                        echo $domtree->saveXML();

                    }

                    if($xml->getName() == 'HotelARIUpdateRQ')
                    {
                        
                        $username = $xml->Authentication->attributes()->UserName;
                        $password = $xml->Authentication->attributes()->Password;
                         
                        $hotel_id = $xml->HotelARIGetRequests->attributes()->HotelCode;
                        $channel_id = Hotelapiinteraction::where(['hotel_id'=>$hotel_id,'status'=>1])->first();
                        $ch_id = $channel_id->channel_partner;
                        
                         //Old COde
                        //$search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>'Rategain','status'=>1])->first();
                        //New Code
                        $search_hotel = Hotelapiinteraction::where(['hotelkey'=>$username,'hotelpassword'=>$password,'channel_partner'=>$ch_id,'status'=>1])->first(); 
                        if(count($search_hotel) > 0)
                            {
                                $id = $xml->HotelARIUpdateRequest->attributes()->HotelCode;
                                $data_return = count($xml->HotelARIUpdateRequest->HotelARIData);

                                // $date_one =  $xml->HotelARIUpdateRequest->HotelARIData[0]->ApplicationControl->attributes()->Start;
                                
                                // $new_date = (new Carbon($date_one))->addDays(count(1))->toDateString();

                                // $cat_id = $xml->HotelARIUpdateRequest->HotelARIData[0]->ProductReference->attributes()->InvTypeCode;

                                // $data_in = Dailyinventory::where('hotel_id',$id)
                                //                 ->where('category_id',$cat_id)
                                //                 ->whereBetween('date',[$date_one,$new_date])
                                //                 ->count();

                                // dd($data_in);

                                $amount = $xml->HotelARIUpdateRequest->HotelARIData[0]->RateAmounts->Base->attributes()->Amount;

                            if($amount >= 0)
                            {
                                for ($i=0; $i < $data_return ; $i++) 
                                { 

                                    $cat_id = $xml->HotelARIUpdateRequest->HotelARIData[$i]->ProductReference->attributes()->InvTypeCode;
                                    $date1 =  $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Start;
                                    $todate =  $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->End;
                                    $codecheck = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Base;

                                    $codeval = $codecheck->attributes()->OccupancyCode;

                                    if($codeval == 'A1')
                                    {
                                        $amount = $codecheck->attributes()->Amount;
                                    }
                                    else
                                    {
                                        $amount1 = $codecheck->attributes()->Amount;
                                    }

                                    //$amount = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Base->attributes()->Amount;
                                    $extraamount = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Additional->attributes()->Amount;
                                    $code = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Base->attributes()->OccupancyCode;

                                    $code1 = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Base[1];

                                    $codeval1 = $code1->attributes()->OccupancyCode;

                                    if($codeval1 == 'A2')
                                    {
                                        $amount1 = $code1->attributes()->Amount;
                                    }
                                    else
                                    {
                                        $amount = $code1->attributes()->Amount;
                                    }

                                    //$amount1 = $xml->HotelARIUpdateRequest->HotelARIData[$i]->RateAmounts->Base[1]->attributes()->Amount;

                                    $Sun= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Sun;
                                    $Mon= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Mon;
                                    $Tue= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Tue;
                                    $Wed= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Wed;
                                    $Thu= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Thu;
                                    $Fri= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Fri;
                                    $Sat= $xml->HotelARIUpdateRequest->HotelARIData[$i]->ApplicationControl->attributes()->Sat;
                                    $Allotment = $xml->HotelARIUpdateRequest->HotelARIData[$i]->BookingLimit->TransientAllotment->attributes()->Allotment;
                                    $master = $xml->HotelARIUpdateRequest->HotelARIData[$i]->Availability->attributes()->Master;

                                    
                                    $array_day  = ['Sun'=>$Sun,'Mon'=>$Mon,'Tue'=>$Tue,'Wed'=>$Wed,'Thu'=>$Thu,'Fri'=>$Fri,'Sat'=>$Sat];

                                    
                                    //dd($amount);
                                    //check date is correct or not

                                    $date_1=date_create(Carbon::now()->toDateString());
                                    $date_2=date_create($date1);
                                    $diff12 = date_diff($date_1, $date_2);
                                    $days = $diff12->format("%R%a days");


                                // $count = Dailyinventory::where('hotel_id',$id)
                                //                     ->where('category_id',$cat_id)
                                //                     ->count();



                                    if($code == 'A1' || $code == 'A2')
                                    {
                                        if($days >= 0) 
                                        { 
                                            $status = 10;

                                                // $inventory = Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)-> whereBetween('date',[$date1,$todate])->select()->first();

                                                // foreach ($inventory as $key => $value) 
                                                // {

                                            $whichday = date('D', strtotime($date1));
                                                        
                                            if($array_day[$whichday] == 'false')
                                            {
                                               //Noting to Update 
                                            }
                                            else
                                            {
                                                $price = (int)$amount;
                                                $price1 = (int)$amount1;
                                                $extraamount = (int)$extraamount;             
                                              if($master == 'Open')
                                              {
                                                Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereBetween('date',[$date1,$todate])->update(['rooms'=>$Allotment,'single_occupancy_price'=>$price,'double_occupancy_price'=>$price1,'extra_adult'=>$extraamount,'flag'=>1]);
                                                }
                                                else
                                                {
                                                Dailyinventory::where('hotel_id',$id)->where('category_id',$cat_id)->whereBetween('date',[$date1,$todate])->update(['rooms'=>$Allotment,'single_occupancy_price'=>$price,'double_occupancy_price'=>$price1,'extra_adult'=>$extraamount,'flag'=>0]);
                                                }
                                                                                                                      
                                            }
                                                         
                                        }
                                        else 
                                        { 
                                            //for current date
                                            $status = 5;
                                        }
                                    }                                            
                                    else
                                    {
                                                $status = 6;
                                    }

                                }

                            }
                            else
                            {
                                $status = 9;
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
                            $xmlRoot = $domtree->createElement("HotelARIUpdateRS");
                            /* append it to the document created */
                            $xmlRoot = $domtree->appendChild($xmlRoot);

                            $domAttribute = $domtree->createAttribute('xmlns');
                            $domAttribute->value = 'http://cgbridge.rategain.com/OTA/2012/05';
                            $xmlRoot->appendChild($domAttribute);


                            $TimeStamp = $domtree->createAttribute('TimeStamp');
                            $TimeStamp->value = Carbon::now();
                            $xmlRoot->appendChild($TimeStamp);


                            $Target = $domtree->createAttribute('Target');
                            $Target->value = 'Production';
                            $xmlRoot->appendChild($Target);


                            $Version = $domtree->createAttribute('Version');
                            $Version->value = '1.1';
                            $xmlRoot->appendChild($Version);


                            if($status == 3)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','No Records found which match this input');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '3';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '424';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);

                            }
                            elseif($status == 4)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Password invalid');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '4';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            elseif($status == 5)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Invalid Start Date');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '4';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '136';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                             elseif($status == 6)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Occupancy must include an adult');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '41';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '136';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            elseif($status == 8)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Unknown');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '1';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '175';
                                $Error->appendChild($Code);

                                $Errors->appendChild($Error);
                            }
                            elseif($status == 9)
                            {
                                $Errors = $domtree->createElement("Errors");

                                $xmlRoot->appendChild($Errors);

                                $Error = $domtree->createElement('Error','Invalid Room -Rate Code');

                                $Type = $domtree->createAttribute('Type');
                                $Type->value = '1';
                                $Error->appendChild($Type);

                                $Errors->appendChild($Error);


                                $Code = $domtree->createAttribute('Code');
                                $Code->value = '402';
                                $Error->appendChild($Code);

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
