<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App;
use App\User;
use App\Model\Citie;
use App\Model\Region;
use App\Model\Area;
use App\Model\State;
use App\Model\Hoteldetail;
use App\Model\Hotel;
use App\Model\Attribute;
use App\Admin;
use App\Model\Roomcategoryfacilitie;
use DB;
use Illuminate\Http\Request;
use App\Model\Dailyinventory;
use App\Model\Masterinventory;
use App\Model\Tax;
use App\Model\Contact;
use App\Model\Commission;
use App\Model\Ratemapping;

class YatraUpdate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yatraupdate {cityname*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cronjob Run For All City';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

         //echo ' ------------ Start Cron Job Successfully ' . date('Y-m-d H:i:s') . ' -----------------------' . "\n ";
        $dataStringreq = '';
        $dataStringreq .=  "\n ".'------------ Start Cron Job Successfully ' . date('Y-m-d H:i:s') . ' ------------' . "\n ";
         


        $cities = $this->argument('cityname');
        $city = implode(",", $cities);


        try {
            $searchString = ',';
            if (strpos($city, $searchString) !== false) {
                $city_array = explode(",", $city);
            } else {
                $city_array = array($city);
            }

            ini_set('memory_limit', '7168M');
            ini_set('max_execution_time', '-1');
            error_reporting(E_ALL);
            ini_set('display_errors', true);
            ini_set('display_startup_errors', true);
            error_reporting(-1);
            
            $city_array = array_filter($city_array, 'strlen');

            foreach ($city_array as $citykey => $city) {
                
                $dataStringreq .=  "\n ".'------------ Start Cron For City ' . $city . "-" . date('Y-m-d H:i:s') . ' ------------' . "\n ";
                
                $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                $wrote = fwrite($fWrite, $dataStringreq . "\n \n");
                fclose($fWrite);

                /* $indentifycronstatus = '';
                  $indentifycronstatus .= ' ------------ Start Cron Job Successfully ' . date('Y-m-d H:i:s') . ' -----------------------' . "\n \n";
                  $indentifycronstatus .= $city;
                  $fWrite = fopen(base_path() . "/yatra/" . $city . "-cronstatus-" . ".txt", "a");
                  $wrote = fwrite($fWrite, $indentifycronstatus . "\n \n");
                  fclose($fWrite); */

                $check_city_exits = Citie::where(['name' => $city])->first();
                if (isset($check_city_exits)) {
                    if ($city == 'NEW DELHI') {
                        $city_id = '45';
                    } else {
                        $city_id = $check_city_exits->id;
                    }
                } else {
                    //echo 'City does not exist in our DB!!';
                    //continue;
                }
                $city_ids = array('45', '113', '132', '126', '125', '135', '115', '41', '43', '57', '3', '4', '50', '54', '66', '10', '32', '121', '76', '42', '116', '48', '137', '118', '63', '122', '58', '117', '64', '136', '60',
                    '22', '114', '119', '21', '47', '52', '129', '38', '128', '68', '130', '40', '2', '134', '120', '131', '127', '101', '123', '138', '133', '124', '18', '59', '71', '155', '74', '80', '69', '140', '142', '39', '158',
                    '152', '17', '139', '157', '151', '153', '154', '144', '141', '145', '143', '86', '46', '149', '62', '35', '148', '79', '51', '44', '90', '150', '146', '147', '53', '55');

                if (in_array($city_id, $city_ids)) {
                    
                } else {
                    //echo 'City does not exist in our DB!!';
                    //die;
                }

                //$dataStringreq .= " ------------ City Found in DATABASE  ------------" . "\n \n";

                $soapUrl = 'http://api2.travelguru.com/services-2.0/tg-services/TGServiceEndPoint';
                $soapBookingUrl = 'http://api2.travelguru.com/services-2.0/tg-services/TGBookingServiceEndPoint';
                $UserName = 'Magicspree';
                $Password = 'Magics@123';
                $PropertyId = '1300001316';
                //$dataString = '';
                $HotelData = array();
                for ($date = 1; $date <= 5; $date++) {
                    $StartDate = date('Y-m-d');
                    $EndDate = date('Y-m-d', strtotime($StartDate . ' +' . $date . ' day'));
                    if ($date > 1) {
                        $StartDate = date('Y-m-d', strtotime($StartDate . ' +' . ($date - 1) . ' day'));
                    }

                    /* $fWrite = fopen(base_path() . "/yatra/" . $city . "-request-" . $StartDate . ".txt", "a");
                      $wrote = fwrite($fWrite, $dataStringreq . "\n \n");
                      fclose($fWrite); */


                    for ($GuestCount = 1; $GuestCount <= 3; $GuestCount++) {
                        $dataStringnew = '';
                        $dataStringloop = '';
                        $dataStringres = '';
                        $dataStringreq = '';

                        //$dataStringreq .= " ------------ Start  Cron Job For City '" . $city . " - " . $GuestCount . " - ' ------------" . "\n \n";

                        $xml_post_string = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
							<soap:Body>
							  <OTA_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" RequestedCurrency="INR" SortOrder="TG_RANKING" Version="0.0" PrimaryLangID="en" SearchCacheLevel="VeryRecent">
								<AvailRequestSegments>
								  <AvailRequestSegment>
									<HotelSearchCriteria>
									  <Criterion>
										<Address>
										  <CityName>' . ucfirst($city) . '</CityName>
										  <CountryName Code="India"></CountryName>
										</Address>
										<HotelRef />
										<StayDateRange End="' . $EndDate . '" Start="' . $StartDate . '"/>
										<RoomStayCandidates>
										  <RoomStayCandidate>
										  <GuestCounts>
												<GuestCount AgeQualifyingCode="10" Count="' . $GuestCount . '"/>
											</GuestCounts>
										  </RoomStayCandidate>
										</RoomStayCandidates>
										<TPA_Extensions>
										  <Pagination enabled="false" hotelsFrom="1" hotelsTo="10"/>
										  <HotelBasicInformation>
											<Reviews />
										  </HotelBasicInformation>
										  <UserAuthentication password="' . $Password . '" propertyId="' . $PropertyId . '" username="' . $UserName . '" />
										</TPA_Extensions>
									  </Criterion>
									</HotelSearchCriteria>
								  </AvailRequestSegment>
								</AvailRequestSegments>
							  </OTA_HotelAvailRQ>
							</soap:Body>
						  </soap:Envelope>';

                        /* $dataStringreq .= $xml_post_string;
                          $dataStringreq .= "\n \n" . " ------------ Sent Request on yatra with above string For City '" . $city . " - " . $GuestCount . " - ' ------------" . "\n \n";
                          $fWrite = fopen(base_path() . "/yatra/" . $city . "-request-" . $StartDate . ".txt", "a");
                          $wrote = fwrite($fWrite, $dataStringreq . "\n \n");
                          $dataStringreq = '';
                          fclose($fWrite); */


                        $headers = array(
                            "POST /package/package_1.3/packageservices.asmx HTTP/1.1",
                            "Host: api2.travelguru.com",
                            "Content-Type: application/soap+xml; charset=utf-8",
                            "Content-Length: " . strlen($xml_post_string)
                        );
                        $url = $soapUrl;
                        try {
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $response = curl_exec($ch);

                            // Check if any error occurred
                            if (curl_errno($ch)) {
                                echo 'Curl error: ' . curl_error($ch);
                            }
                            curl_close($ch);
                            $response1 = str_replace("<soap:Body>", "", $response);
                            $response2 = str_replace("</soap:Body>", "", $response1);
                            
                            $parser = simplexml_load_string($response2);
                            
                            /* $dataStringres .= "\n \n" . " ------------ Response For  City '" . $city . " - " . $GuestCount . " - ' ------------" . "\n \n";
                              $dataStringres .= $response2;
                              $fWrite = fopen(base_path() . "/yatra/" . $city . "-response-" . $StartDate . ".txt", "a");
                              $wrote = fwrite($fWrite, $dataStringres . "\n \n");
                              $dataStringres = '';
                              fclose($fWrite); */
                        } catch (\Exception $e) {
                             $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                            //$fWrite = fopen('php://stderr', 'w');
                            $wrote = fwrite($fWrite, $e->getMessage() . "1 \n \n");
                            fclose($fWrite);
                        }
                        $roomPlanID = array();
                        $roomTypeCode = array();
                        $roomBaseAdultOccupancy = array();
                        $roomBaseChildOccupancy = array();
                        $roomTaxes = array();
                        $roomRateDate = array();
                        $roomRatePrice = array();
                        $roomDiscountPrice = array();
                        $roomAffiliateCommission = array();
                        $roomAdditionalGuestAmounts = array();
                        $roomAGDiscountPrice = array();
                        $roomAvailableQuantity = array();
                        $roomRatePlanName = array();
                        $roomRatePlanType = array();
                        $checkMinPrice = 0;
                        //$checkMinEPPrice = 0;
                        //$checkMinCPPrice = 0;
                        $roomID = 0;
                        //$roomEPTypeID = 0;
                        //$roomCPTypeID = 0;
                        $roomRatePlanInclusions = array();
                        $checkHotelCode = 0;
                        //echo "test - " . $date ." - " .$GuestCount;
                        //echo '<pre>'; print_r($parser);die;
                        try {
                            if (!empty($parser)) {
                                if (isset($parser->OTA_HotelAvailRS->Errors->Error)) {
                                    
                                } else {

                                    foreach ($parser->OTA_HotelAvailRS->RoomStays->RoomStay as $data) {

                                        /* $dataStringloop .= "\n \n" . " ------------ Start Loop and reading XML nodes ------------" . "\n \n";
                                          // $dataStringloop .= json_encode($data, true);
                                          $fWrite = fopen(base_path() . "/yatra/" . $city . "-loopwrite-" . $GuestCount . "-" . $StartDate . ".txt", "a");
                                          $wrote = fwrite($fWrite, $dataStringloop . "\n \n");
                                          $dataStringloop = '';
                                          fclose($fWrite); */

                                        $NewHotelCode = (string) $data->BasicPropertyInfo->attributes()->HotelCode;
                                        $roomEPID = 0;
                                        $roomCPID = 0;
                                        $roomEPTypeID = 0;
                                        $roomCPTypeID = 0;
                                        $checkMinEPPrice = 0;
                                        $checkMinCPPrice = 0;
                                        $finalcheckMinPrice = 0;
                                        $checkEP = 0;
                                        $checkCP = 0;
                                        $notAdd = 0;
                                        $roomDiscountPrice = (array) (0);
                                        if (!empty($data->RoomRates)) {
                                            foreach ($data->RatePlans->RatePlan as $Roomplandatakey => $Roomplandataval) {
                                                $roomAvailableQuantity["'" . $Roomplandataval->attributes()->RatePlanCode . "'"] = (array) $Roomplandataval->attributes()->AvailableQuantity;
                                                $roomRatePlanName["'" . $Roomplandataval->attributes()->RatePlanCode . "'"] = (array) $Roomplandataval->attributes()->RatePlanName;
                                                $roomRatePlanType["'" . $Roomplandataval->attributes()->RatePlanCode . "'"] = (array) $Roomplandataval->attributes()->RatePlanType;
                                                try {
                                                    $roomRatePlanInclusions["'" . $Roomplandataval->attributes()->RatePlanCode . "'"] = (array) $Roomplandataval->RatePlanInclusions->RatePlanInclusionDesciption->Text;
                                                } catch (\Exception $e) {
                                                     $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                                                    //$fWrite = fopen('php://stderr', 'w');
                                                    $wrote = fwrite($fWrite, $e->getMessage() . "2 \n \n");
                                                    fclose($fWrite);
                                                }
                                            }

                                            foreach ($data->RoomRates->RoomRate as $Roomtypedatakey => $Roomtypedataval) {
                                                $breakfast = 0;
                                                if (isset($roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0])) {
                                                    $roomRatePlanInclusionsex = explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]);
                                                    $count_PlanInclusions = count(explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]));
                                                    for ($g = 0; $g < $count_PlanInclusions; $g++) {
                                                        if (ucfirst($roomRatePlanInclusionsex[$g]) == 'Breakfast') {
                                                            $breakfast = 1;
                                                            break;
                                                        } else if (stristr(ucfirst($roomRatePlanInclusionsex[$g]), "Breakfast")) {
                                                            $breakfast = 1;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"] = (array) (0);
                                                }
                                                $finalAmountBeforeTax = (array) $Roomtypedataval->Rates->Rate->Base->attributes()->AmountBeforeTax;
                                                try {
                                                    if (isset($Roomtypedataval->Rates->Rate->Discount)) {
                                                        $roomDiscountPrice = (array) $Roomtypedataval->Rates->Rate->Discount->attributes()->AmountBeforeTax;
                                                    } else {
                                                        $roomDiscountPrice = (array) (0);
                                                    }
                                                } catch (\Exception $e) {
                                                     $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                                                    //$fWrite = fopen('php://stderr', 'w');
                                                    $wrote = fwrite($fWrite, $e->getMessage() . "3 \n \n");
                                                    fclose($fWrite);
                                                }
                                                if (!empty($roomDiscountPrice)) {
                                                    $finalAmountBeforeTax = (array) ($finalAmountBeforeTax[0] - $roomDiscountPrice[0]);
                                                } else {
                                                    $roomDiscountPrice = (array) (0);
                                                    //$finalAmountBeforeTax = (array) $Roomtypedataval->Rates->Rate->Base->attributes()->AmountBeforeTax;
                                                }
                                                $finalroomPlanID = (array) $Roomtypedataval->attributes()->RoomID;
                                                $finalroomTypeCode = (array) $Roomtypedataval->attributes()->RatePlanCode;
                                                //$finalAmountBeforeTax = (array) $Roomtypedataval->Rates->Rate->Base->attributes()->AmountBeforeTax;
                                                if (($finalcheckMinPrice > $finalAmountBeforeTax[0]) || $finalcheckMinPrice == 0) {
                                                    $roomPlanID = $finalroomPlanID[0];
                                                    $roomTypeCode = $finalroomTypeCode[0];
                                                    $finalcheckMinPrice = $finalAmountBeforeTax[0];
                                                    if ($breakfast == 1) {
                                                        $checkCP = 1;
                                                    } else {
                                                        $checkEP = 1;
                                                    }
                                                }
                                                //echo $finalroomPlanID[0] . ' -- ' . $finalroomTypeCode[0] . ' -- ' . $finalAmountBeforeTax[0]  . ' -- ' . $finalcheckMinPrice . " -- ". $breakfast . '<br/>';
                                            }
                                            //echo $roomPlanID . ' -- ' . $roomTypeCode . ' -- ' .$finalcheckMinPrice . "--" . $checkEP." -- " .$checkCP.'<br/>';
                                            //echo $breakfast;
                                            //echo $roomPlanID .' -- '. $roomTypeCode;
                                            foreach ($data->RoomRates->RoomRate as $Roomtypedatakey => $Roomtypedataval) {
                                                $breakfast = 0;
                                                if (isset($roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0])) {
                                                    $roomRatePlanInclusionsex = explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]);
                                                    $count_PlanInclusions = count(explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]));
                                                    for ($g = 0; $g < $count_PlanInclusions; $g++) {
                                                        if (ucfirst($roomRatePlanInclusionsex[$g]) == 'Breakfast') {
                                                            $breakfast = 1;
                                                            break;
                                                        } else if (stristr(ucfirst($roomRatePlanInclusionsex[$g]), "Breakfast")) {
                                                            $breakfast = 1;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"] = (array) (0);
                                                }
                                                $checkroomPlanID = (array) $Roomtypedataval->attributes()->RoomID;
                                                $checkroomTypeCode = (array) $Roomtypedataval->attributes()->RatePlanCode;
                                                if ($roomPlanID == $checkroomPlanID[0] && $checkroomTypeCode[0] != $roomTypeCode) {
                                                    if ($checkEP == 1 && $breakfast == 1) {
                                                        $roomEPTypeID = $roomTypeCode;
                                                        $roomCPTypeID = $checkroomTypeCode[0];
                                                        $roomCPID = $roomPlanID;
                                                    } else if ($checkCP == 1 && $breakfast == 0) {
                                                        $roomCPTypeID = $roomTypeCode;
                                                        $roomEPTypeID = $checkroomTypeCode[0];
                                                        $roomEPID = $roomPlanID;
                                                    }
                                                    break;
                                                } else if ($roomPlanID == $checkroomPlanID[0] && $checkroomTypeCode[0] == $roomTypeCode) {
                                                    if ($checkEP == 1 && $breakfast == 0) {
                                                        $roomEPID = $roomPlanID;
                                                        $roomEPTypeID = $checkroomTypeCode[0];
                                                        $roomCPTypeID = 0;
                                                    }
                                                    if ($checkCP == 1 && $breakfast == 1) {
                                                        $roomCPID = $roomPlanID;
                                                        $roomCPTypeID = $checkroomTypeCode[0];
                                                        $roomEPTypeID = 0;
                                                    }
                                                }
                                            }
                                            //echo $roomEPID . ' -- ' . $roomCPID . ' -- ' . $roomEPTypeID  . ' -- ' . $roomCPTypeID . '<br/>';
                                            //die;
                                            $roomDiscountPrice = array();
                                            //get lowest price room ID
                                            foreach ($data->RoomRates->RoomRate as $Roomtypedatakey => $Roomtypedataval) {
                                                $roomPlanID = (array) $Roomtypedataval->attributes()->RoomID;
                                                $roomTypeCode = (array) $Roomtypedataval->attributes()->RatePlanCode;
                                                $breakfast = 0;
                                                if (isset($roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0])) {
                                                    $roomRatePlanInclusionsex = explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]);
                                                    $count_PlanInclusions = count(explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]));
                                                    for ($g = 0; $g < $count_PlanInclusions; $g++) {
                                                        if (ucfirst($roomRatePlanInclusionsex[$g]) == 'Breakfast') {
                                                            $breakfast = 1;
                                                            break;
                                                        } else if (stristr(ucfirst($roomRatePlanInclusionsex[$g]), "Breakfast")) {
                                                            $breakfast = 1;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"] = (array) (0);
                                                }
                                                //echo $Roomtypedataval->Rates->Rate->Base->attributes()->AmountBeforeTax. '<br/>';
                                                //echo $checkMinCPPrice.'<br/>';
                                                //echo $checkMinEPPrice.'<br/>';
                                                //echo $roomCPTypeID.'<br/>';
                                                //echo $roomEPTypeID.'<br/>';
                                                //echo $roomEPID.'<br/>';
                                                //echo $roomCPID.'<br/>';

                                                $roomRateDate = (array) $Roomtypedataval->Rates->Rate->attributes()->EffectiveDate;
                                                $roomBaseAdultOccupancy = (array) $Roomtypedataval->Rates->Rate->TPA_Extensions->Rate->attributes()->BaseAdultOccupancy;
                                                $roomBaseChildOccupancy = (array) $Roomtypedataval->Rates->Rate->TPA_Extensions->Rate->attributes()->BaseChildOccupancy;
                                                $roomRatePrice = (array) $Roomtypedataval->Rates->Rate->Base->attributes()->AmountBeforeTax;
                                                try {
                                                    if (isset($Roomtypedataval->Rates->Rate->Discount)) {
                                                        $roomDiscountPrice = (array) $Roomtypedataval->Rates->Rate->Discount->attributes()->AmountBeforeTax;
                                                    } else {
                                                        $roomDiscountPrice = (array) (0);
                                                    }
                                                } catch (\Exception $e) {
                                                     $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                                                    //$fWrite = fopen('php://stderr', 'w');
                                                    $wrote = fwrite($fWrite, $e->getMessage() . "4 \n \n");
                                                    fclose($fWrite);
                                                }
                                                if (!empty($roomDiscountPrice)) {
                                                    $roomRatePrice = (array) ($roomRatePrice[0] - $roomDiscountPrice[0]);
                                                } else {
                                                    $roomDiscountPrice = (array) (0);
                                                }
                                                $roomTaxes = (array) $Roomtypedataval->Rates->Rate->Base->Taxes->attributes()->Amount;
                                                if ($Roomtypedataval->Rates->Rate->TPA_Extensions->AffiliateCommission) {
                                                    $roomAffiliateCommission = (array) $Roomtypedataval->Rates->Rate->TPA_Extensions->AffiliateCommission->attributes()->Percent;
                                                } else {
                                                    $roomAffiliateCommission[0] = 0;
                                                }

                                                if ($roomAvailableQuantity["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0] > 0 && isset($roomRatePrice[0])) {


                                                    //echo $checkHotelCode . '<br/>';
                                                    //echo $NewHotelCode .'<br/>';
                                                    //echo $roomPlanID[0] .'<br/>' ;
                                                    //echo $roomTypeCode[0] .'<br/>';
                                                    //echo $roomEPID .'<br/>';
                                                    //echo $roomCPID .'<br/>'; 
                                                    //echo $roomEPTypeID .'<br/>';
                                                    //echo $roomCPTypeID .'<br/>';
                                                    //echo $Roomtypedataval->attributes()->RoomID .'testing <br/>';	

                                                    if (($roomEPID == $roomPlanID[0] || $roomCPID == $roomPlanID[0] || $checkHotelCode != $NewHotelCode) && ($roomEPTypeID == $roomTypeCode[0] || $roomCPTypeID == $roomTypeCode[0])) {
                                                        if (!empty($HotelData[$NewHotelCode])) {
                                                            foreach ($HotelData[$NewHotelCode] as $checkArraykey => $checkArrayvalue) {
                                                                if ($checkArraykey != $roomPlanID[0]) {
                                                                    $notAdd = 1;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if ($notAdd == 0) {
                                                            /* $dataString = $NewHotelCode.",".$GuestCount.", ".$StartDate;
                                                              $fWrite = fopen(base_path() . "/yatra/guestbook.txt","a");
                                                              $wrote = fwrite($fWrite, $dataString. "\n");
                                                              fclose($fWrite); */
                                                            $inclusionbreakfast = 'no';
                                                            if (isset($roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0])) {
                                                                $roomRatePlanInclusionsex = explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]);
                                                                $count_PlanInclusions = count(explode(",", $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0]));
                                                                for ($g = 0; $g < $count_PlanInclusions; $g++) {
                                                                    if (ucfirst($roomRatePlanInclusionsex[$g]) == 'Breakfast') {
                                                                        $inclusionbreakfast = 'yes';
                                                                        break;
                                                                    } else if (stristr(ucfirst($roomRatePlanInclusionsex[$g]), "Breakfast")) {
                                                                        $inclusionbreakfast = 'yes';
                                                                        break;
                                                                    }
                                                                }
                                                            } else {
                                                                $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"] = (array) (0);
                                                            }
                                                            $checkExistingType = 0;
                                                            if (!empty($HotelData[$NewHotelCode])) {
                                                                foreach ($HotelData[$NewHotelCode] as $checkArraykey => $checkArrayvalue) {
                                                                    foreach ($checkArrayvalue as $checkArraynewkey => $checkArraynewvalue) {
                                                                        $checkExistingType = $checkArraynewkey;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            $dataStringloop .= "\n \n" . " ------------ Checking For minimun Price ------------" . "\n \n";


                                                            $checkHotelCode = $NewHotelCode;
                                                            if ($GuestCount == 3 && isset($Roomtypedataval->Rates->Rate->AdditionalGuestAmounts)) {
                                                                if ($Roomtypedataval->Rates->Rate->AdditionalGuestAmounts->AdditionalGuestAmount->Amount->attributes()->AmountBeforeTax) {
                                                                    $roomAdditionalGuestAmounts = (array) $Roomtypedataval->Rates->Rate->AdditionalGuestAmounts->AdditionalGuestAmount->Amount->attributes()->AmountBeforeTax;
                                                                }
                                                                try {
                                                                    if (!empty($Roomtypedataval->Rates->Rate->Discount) && count($Roomtypedataval->Rates->Rate->Discount) > 1) {
                                                                        $roomAGDiscountPrice = (array) $Roomtypedataval->Rates->Rate->Discount[1]->attributes()->AmountBeforeTax;
                                                                    }
                                                                } catch (\Exception $e) {
                                                                     $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                                                                    //$fWrite = fopen('php://stderr', 'w');
                                                                    $wrote = fwrite($fWrite, $e->getMessage() . "5 \n \n");
                                                                    fclose($fWrite);
                                                                }
                                                                if (!empty($roomAGDiscountPrice)) {
                                                                    $roomAdditionalGuestAmounts = (array) ($roomAdditionalGuestAmounts[0] - $roomAGDiscountPrice[0]);
                                                                } else {
                                                                    $roomAGDiscountPrice = (array) (0);
                                                                }
                                                                if (!isset($HotelData[$NewHotelCode][$roomPlanID[0]][$checkExistingType][$StartDate])) {
                                                                    $HotelData[$NewHotelCode][$roomPlanID[0]][$roomTypeCode[0]][$StartDate][$GuestCount] = array('roomPlanID' => $roomPlanID[0], 'roomTypeCode' => $roomTypeCode[0], 'roomRateDate' => $roomRateDate[0], 'roomBaseAdultOccupancy' => $roomBaseAdultOccupancy[0], 'roomBaseChildOccupancy' => $roomBaseChildOccupancy[0]
                                                                        , 'roomRatePrice' => $roomRatePrice[0], 'roomTaxes' => $roomTaxes[0], 'roomAffiliateCommission' => $roomAffiliateCommission[0], 'roomAvailableQuantity' => $roomAvailableQuantity["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanName' => $roomRatePlanName["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanType' => $roomRatePlanType["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'AdditionalGuestAmounts' => $roomAdditionalGuestAmounts[0],
                                                                        'roomDiscountPrice' => $roomAGDiscountPrice[0], 'roomRatePlanInclusions' => $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'Breakfast' => $inclusionbreakfast
                                                                    );
                                                                } else if (isset($HotelData[$NewHotelCode][$roomPlanID[0]][$checkExistingType][$StartDate][$GuestCount - 1]['roomTypeCode']) && $HotelData[$NewHotelCode][$roomPlanID[0]][$checkExistingType][$StartDate][$GuestCount - 1]['roomTypeCode'] == $roomTypeCode[0]) {
                                                                    $HotelData[$NewHotelCode][$roomPlanID[0]][$roomTypeCode[0]][$StartDate][$GuestCount] = array('roomPlanID' => $roomPlanID[0], 'roomTypeCode' => $roomTypeCode[0], 'roomRateDate' => $roomRateDate[0], 'roomBaseAdultOccupancy' => $roomBaseAdultOccupancy[0], 'roomBaseChildOccupancy' => $roomBaseChildOccupancy[0]
                                                                        , 'roomRatePrice' => $roomRatePrice[0], 'roomTaxes' => $roomTaxes[0], 'roomAffiliateCommission' => $roomAffiliateCommission[0], 'roomAvailableQuantity' => $roomAvailableQuantity["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanName' => $roomRatePlanName["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanType' => $roomRatePlanType["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'AdditionalGuestAmounts' => $roomAdditionalGuestAmounts[0],
                                                                        'roomDiscountPrice' => $roomAGDiscountPrice[0], 'roomRatePlanInclusions' => $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'Breakfast' => $inclusionbreakfast
                                                                    );
                                                                }
                                                            } else {
                                                                if (isset($Roomtypedataval->Rates->Rate->AdditionalGuestAmounts)) {
                                                                    $roomAdditionalGuestAmounts = (array) $Roomtypedataval->Rates->Rate->AdditionalGuestAmounts->AdditionalGuestAmount->Amount->attributes()->AmountBeforeTax;
                                                                    $roomRatePrice[0] = $roomRatePrice[0] + $roomAdditionalGuestAmounts[0];
                                                                }
                                                                if (!isset($HotelData[$NewHotelCode][$roomPlanID[0]][$checkExistingType][$StartDate])) {
                                                                    $HotelData[$NewHotelCode][$roomPlanID[0]][$roomTypeCode[0]][$StartDate][$GuestCount] = array('roomPlanID' => $roomPlanID[0], 'roomTypeCode' => $roomTypeCode[0], 'roomRateDate' => $roomRateDate[0], 'roomBaseAdultOccupancy' => $roomBaseAdultOccupancy[0], 'roomBaseChildOccupancy' => $roomBaseChildOccupancy[0]
                                                                        , 'roomRatePrice' => $roomRatePrice[0], 'roomTaxes' => $roomTaxes[0], 'roomAffiliateCommission' => $roomAffiliateCommission[0], 'roomAvailableQuantity' => $roomAvailableQuantity["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanName' => $roomRatePlanName["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanType' => $roomRatePlanType["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'AdditionalGuestAmounts' => 0,
                                                                        'roomDiscountPrice' => $roomDiscountPrice[0], 'roomRatePlanInclusions' => $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'Breakfast' => $inclusionbreakfast);
                                                                } else {
                                                                    $HotelData[$NewHotelCode][$roomPlanID[0]][$roomTypeCode[0]][$StartDate][$GuestCount] = array('roomPlanID' => $roomPlanID[0], 'roomTypeCode' => $roomTypeCode[0], 'roomRateDate' => $roomRateDate[0], 'roomBaseAdultOccupancy' => $roomBaseAdultOccupancy[0], 'roomBaseChildOccupancy' => $roomBaseChildOccupancy[0]
                                                                        , 'roomRatePrice' => $roomRatePrice[0], 'roomTaxes' => $roomTaxes[0], 'roomAffiliateCommission' => $roomAffiliateCommission[0], 'roomAvailableQuantity' => $roomAvailableQuantity["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanName' => $roomRatePlanName["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'roomRatePlanType' => $roomRatePlanType["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'AdditionalGuestAmounts' => 0,
                                                                        'roomDiscountPrice' => $roomDiscountPrice[0], 'roomRatePlanInclusions' => $roomRatePlanInclusions["'" . $Roomtypedataval->attributes()->RatePlanCode . "'"][0], 'Breakfast' => $inclusionbreakfast);
                                                                }
                                                            }

                                                            //$dataStringloop .= json_encode($HotelData, true);
                                                            /* $fWrite = fopen(base_path() . "/yatra/" . $city . "-loopwrite-" . $GuestCount . "-" . $StartDate . ".txt", "a");
                                                              $wrote = fwrite($fWrite, $dataStringloop . "\n \n");
                                                              $dataStringloop = '';
                                                              fclose($fWrite); */
                                                        }
                                                    }

                                                    $checkMinPrice = $roomRatePrice[0];
                                                }
                                            }
                                            //echo '<pre>'; print_r($HotelData);die;
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                            //$fWrite = fopen('php://stderr', 'w');
                            $wrote = fwrite($fWrite, $e->getMessage() . "6 \n \n");
                            fclose($fWrite);
                        }
                    }
                }
                //$fWrite = fopen(base_path() . "/yatra/" . $all_hotel->hotel_code . '-' . date('Y-m-d') . ".txt","a");
                /* $dataString = '';
                  $dataString .= json_encode($HotelData, true);
                  $fWrite = fopen(base_path() . "/yatra/" . $city . "-array_data-" . date('Y-m-d') . ".txt", "a");
                  $wrote = fwrite($fWrite, $dataString . "\n \n");
                  fclose($fWrite); */

                try {//now we are not using ep and we are assuming ep and cp both have same record so we will comment get_all_hotel_code_ep and foreach loop for that variable
                    // $get_all_hotel_code_ep = DB::table('yatrainteractions')->where(['status' => 1, 'city_id' => $city_id])->where('rate_id', '>', 0)->get();
                    $get_all_hotel_code_cp = DB::table('yatrainteractions')->where(['status' => 1, 'city_id' => $city_id])->where('ratecp_id', '>', 0)->get();
                    $dataString = '';
                    if (!empty($get_all_hotel_code_cp)) {
                        $not_found_count = 0;
                        $found_count = 0;
                        foreach ($get_all_hotel_code_cp as $all_hotel) {
                            $deleteDailyInventoryRows = Dailyinventory::where('category_id', $all_hotel->hoteldetail_id)->delete();
                            $deleteMasterInventoryRows = Masterinventory::where('hoteldetail_id', $all_hotel->hoteldetail_id)->delete();
                            $deleteTaxesRows = Tax::where('hoteldetail_id', $all_hotel->hoteldetail_id)->delete();
                            //$deleteLaunchRows = DB::table('launches')->where('hotel_id', $all_hotel->hotel_id)->delete();
                            //DB::table('launches')->insert(['hotel_id' => $all_hotel->hotel_id, 'status' => 0]);
                            //Update Price and Inventory 
                            // if (isset($HotelData[$all_hotel->hotel_code]) && ($all_hotel->hotel_code == '00000349' || $all_hotel->hotel_code == '00004034')) {
                            if (isset($HotelData[$all_hotel->hotel_code])) {


                                foreach ($HotelData[$all_hotel->hotel_code] as $roomDatekey => $roomDateval) {
                                    foreach ($roomDateval as $roomGuestkeynew => $roomGuestvalnew) {
                                        for ($date = 1; $date <= 5; $date++) {

                                            $StartDate = date('Y-m-d');
                                            $EndDate = date('Y-m-d', strtotime($StartDate . ' +' . $date . ' day'));
                                            if ($date > 1) {
                                                $StartDate = date('Y-m-d', strtotime($StartDate . ' +' . ($date - 1) . ' day'));
                                            }

                                            /* $dataString = " ------------ Start Write EP Data ' $StartDate ' ------------" . "\n \n";
                                              $dataString .= " ------------ Hotel Code : ' $all_hotel->hotel_code  ' --------------" . "\n \n";
                                              $dataString .= json_encode($HotelData[$all_hotel->hotel_code], true);
                                              //$fWrite = fopen(base_path() . "/yatra/" . $all_hotel->hotel_code . '-' . date('Y-m-d') . ".txt","a");
                                              $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d') . ".txt", "a");
                                              $wrote = fwrite($fWrite, $dataString . "\n \n");
                                              fclose($fWrite); */
                                            //echo $StartDate;
                                            if (isset($roomGuestvalnew[$StartDate])) {
                                                foreach ($roomGuestvalnew[$StartDate] as $roomGuestkey => $roomGuestval) {
                                                    //echo'<pre>';print_r($roomGuestval);die; 
                                                    if ($roomGuestval['Breakfast'] == 'yes') {
                                                        $check_dailyinventory_exists = Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->first();

                                                        if (isset($check_dailyinventory_exists)) {
                                                            $room_dailyinventory_id = $check_dailyinventory_exists->id;
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['rooms_cp' => $roomGuestval['roomAvailableQuantity'], 'contractual_room' => $roomGuestval['roomAvailableQuantity']]);
                                                        } else {
                                                            $dailyInventory = new Dailyinventory;

                                                            $dailyInventory->hotel_id = $all_hotel->hotel_id;
                                                            $dailyInventory->date = $StartDate;
                                                            $dailyInventory->rooms_cp = $roomGuestval['roomAvailableQuantity'];
                                                            $dailyInventory->contractual_room = $roomGuestval['roomAvailableQuantity'];
                                                            $dailyInventory->category_id = $all_hotel->hoteldetail_id;
                                                            $dailyInventory->flag = 1;
                                                            $dailyInventory->save();
                                                            $room_dailyinventory_id = $dailyInventory->id;
                                                        }
                                                        if ($roomGuestkey == 1) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['single_occupancy_price_cp' => $roomGuestval['roomRatePrice'], 'single_occupancy_discount' => $roomGuestval['roomDiscountPrice']]);
                                                            Hoteldetail::where(['hotel_id' => $all_hotel->hotel_id, 'id' => $all_hotel->hoteldetail_id])->update(['single_occupancy_price' => $roomGuestval['roomRatePrice']]);
                                                        } elseif ($roomGuestkey == 2) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['double_occupancy_price_cp' => $roomGuestval['roomRatePrice'], 'double_occupancy_discount' => $roomGuestval['roomDiscountPrice']]);
                                                            Hoteldetail::where(['hotel_id' => $all_hotel->hotel_id, 'id' => $all_hotel->hoteldetail_id])->update(['double_occupancy_price' => $roomGuestval['roomRatePrice']]);
                                                        } elseif ($roomGuestkey == 3) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['extra_adult_cp' => $roomGuestval['AdditionalGuestAmounts'], 'extra_adult_discount' => $roomGuestval['roomDiscountPrice']]);
                                                        }
                                                    }
                                                    else if($roomGuestval['Breakfast'] == 'no')
                                                    {
                                                        $check_dailyinventory_exists = Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->first();

                                                        if (isset($check_dailyinventory_exists)) {
                                                            $room_dailyinventory_id = $check_dailyinventory_exists->id;
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['rooms_ep' => $roomGuestval['roomAvailableQuantity'], 'contractual_room' => $roomGuestval['roomAvailableQuantity']]);
                                                        } else {
                                                            $dailyInventory = new Dailyinventory;

                                                            $dailyInventory->hotel_id = $all_hotel->hotel_id;
                                                            $dailyInventory->date = $StartDate;
                                                            $dailyInventory->rooms_ep = $roomGuestval['roomAvailableQuantity'];
                                                            $dailyInventory->contractual_room = $roomGuestval['roomAvailableQuantity'];
                                                            $dailyInventory->category_id = $all_hotel->hoteldetail_id;
                                                            $dailyInventory->flag = 1;
                                                            $dailyInventory->save();
                                                            $room_dailyinventory_id = $dailyInventory->id;
                                                        }//this is for single occupancy and singly discount
                                                        if ($roomGuestkey == 1) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['single_occupancy_price_ep' => $roomGuestval['roomRatePrice'], 'single_occupancy_discount' => $roomGuestval['roomDiscountPrice']]);
                                                            Hoteldetail::where(['hotel_id' => $all_hotel->hotel_id, 'id' => $all_hotel->hoteldetail_id])->update(['single_occupancy_price' => $roomGuestval['roomRatePrice']]);
                                                        } //this is for double 
                                                        elseif ($roomGuestkey == 2) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['double_occupancy_price_ep' => $roomGuestval['roomRatePrice'], 'double_occupancy_discount' => $roomGuestval['roomDiscountPrice']]);
                                                            Hoteldetail::where(['hotel_id' => $all_hotel->hotel_id, 'id' => $all_hotel->hoteldetail_id])->update(['double_occupancy_price' => $roomGuestval['roomRatePrice']]);
                                                        } //this is for extra
                                                        elseif ($roomGuestkey == 3) {
                                                            Dailyinventory::where(['hotel_id' => $all_hotel->hotel_id, 'category_id' => $all_hotel->hoteldetail_id, 'date' => $StartDate])->update(['extra_adult_ep' => $roomGuestval['AdditionalGuestAmounts'], 'extra_adult_discount' => $roomGuestval['roomDiscountPrice']]);
                                                        }

                                                    }
                                                    // Update/create master inventory
                                                    $check_masterinventory_exists = Masterinventory::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->first();
                                                    //$StartDate = date('Y-m-d');
                                                    $EndDate = date('Y-m-d', strtotime($StartDate . ' +365' . ' day'));
                                                    if (isset($check_masterinventory_exists)) {
                                                        $room_masterinventory_id = $check_masterinventory_exists->id;
                                                        Masterinventory::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->update(['no_of_rooms' => $roomGuestval['roomAvailableQuantity'], 'start_date' => $StartDate, 'end_date' => $EndDate]);
                                                    } else {
                                                        $masterInventory = new Masterinventory;
                                                        $masterInventory->hotel_id = $all_hotel->hotel_id;
                                                        $masterInventory->hoteldetail_id = $all_hotel->hoteldetail_id;
                                                        $masterInventory->user_id = '1';
                                                        $masterInventory->form_type = 'master_inventory';
                                                        $masterInventory->start_date = $StartDate;
                                                        $masterInventory->end_date = $EndDate;
                                                        $masterInventory->no_of_day = 0;
                                                        $masterInventory->monday = 1;
                                                        $masterInventory->tuesday = 1;
                                                        $masterInventory->wednesday = 1;
                                                        $masterInventory->thirsday = 1;
                                                        $masterInventory->friday = 1;
                                                        $masterInventory->saturday = 1;
                                                        $masterInventory->sunday = 1;
                                                        $masterInventory->no_of_rooms = $roomGuestval['roomAvailableQuantity'];
                                                        $masterInventory->category_id = 0;
                                                        $masterInventory->status = 1;
                                                        $masterInventory->save();
                                                        $room_masterinventory_id = $masterInventory->id;
                                                        Hotel::where('id', $all_hotel->hotel_id)->update(['status' => 1]);
                                                    }
                                                    if ($roomGuestval['Breakfast'] == 'no') {
                                                        DB::table('hoteldetails')->where(['id' => $all_hotel->hoteldetail_id])->update(['cp_include' => 'EP', 'custom_category' => $roomGuestval['roomRatePlanName']]);
                                                        DB::table('yatrainteractions')->where(['hotel_code' => $all_hotel->hotel_code, 'hoteldetail_id' => $all_hotel->hoteldetail_id])->update(['room_id' => $roomGuestval['roomPlanID'], 'rate_id' => $roomGuestval['roomTypeCode']]);
                                                    } else {
                                                        DB::table('hoteldetails')->where(['id' => $all_hotel->hoteldetail_id])->update(['cp_include' => 'CP', 'custom_category' => $roomGuestval['roomRatePlanName']]);
                                                        DB::table('yatrainteractions')->where(['hotel_code' => $all_hotel->hotel_code, 'hoteldetail_id' => $all_hotel->hoteldetail_id])->update(['room_id' => $roomGuestval['roomPlanID'], 'ratecp_id' => $roomGuestval['roomTypeCode']]);
                                                    }
                                                    if ($roomGuestval['roomRatePrice'] > 0 && $roomGuestval['roomRatePrice'] < 1000) {
                                                        $tax_str = 1;
                                                        $gst_bar = '0-999';
                                                    } elseif ($roomGuestval['roomRatePrice'] >= 1000 && $roomGuestval['roomRatePrice'] < 2500) {
                                                        $tax_str = 2;
                                                        $gst_bar = '1000-2499';
                                                    } elseif ($roomGuestval['roomRatePrice'] >= 2500 && $roomGuestval['roomRatePrice'] < 7500) {
                                                        $tax_str = 3;
                                                        $gst_bar = '2500-7499';
                                                    } else {
                                                        $tax_str = 4;
                                                        $gst_bar = 'Above 7500';
                                                    }
                                                    $check_roomtaxes_exists = Tax::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->first();

                                                    if (isset($check_roomtaxes_exists)) {
                                                        $room_roomtaxes_id = $check_roomtaxes_exists->id;
                                                        if ($roomGuestkey == 1) {
                                                            Tax::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->update(['luxary_text_title' => 'Sell Rate', 'luxary_text_value' => 0, 'service_text_title' => 'Sell Rate', 'service_text_value' => 0, 'other_text_title' => 'Flat Tax', 'other_tax_value' => $roomGuestval['roomTaxes'], 'gst_bar' => $gst_bar, 'gsttaxes_id' => $tax_str, 'status' => 1]);
                                                        } else if ($roomGuestkey == 2) {
                                                            Tax::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->update(['luxary_text_title' => 'Sell Rate', 'luxary_text_value' => 0, 'service_text_title' => 'Sell Rate', 'service_text_value' => 0, 'other_text_title' => 'Flat Tax', 'other_tax_value_2' => $roomGuestval['roomTaxes'], 'gst_bar' => $gst_bar, 'gsttaxes_id' => $tax_str, 'status' => 1]);
                                                        } else if ($roomGuestkey == 3) {
                                                            Tax::where(['hotel_id' => $all_hotel->hotel_id, 'hoteldetail_id' => $all_hotel->hoteldetail_id, 'start_date' => $StartDate])->update(['luxary_text_title' => 'Sell Rate', 'luxary_text_value' => 0, 'service_text_title' => 'Sell Rate', 'service_text_value' => 0, 'other_text_title' => 'Flat Tax', 'other_tax_value_3' => $roomGuestval['roomTaxes'], 'gst_bar' => $gst_bar, 'gsttaxes_id' => $tax_str, 'status' => 1]);
                                                        }
                                                    } else {
                                                        //$deletedRows = Dailyinventory::where('hotel_id' , $all_hotel)->where('date', $StartDate)->delete();
                                                        $Taxes = new Tax;

                                                        $Taxes->user_id = '1';
                                                        $Taxes->hotel_id = $all_hotel->hotel_id;
                                                        $Taxes->hoteldetail_id = $all_hotel->hoteldetail_id;
                                                        $Taxes->start_date = $StartDate;
                                                        $Taxes->end_date = date('Y-m-d', strtotime($StartDate . ' +365' . ' day'));
                                                        $Taxes->luxary_text_title = 'Sell Rate';
                                                        $Taxes->luxary_text_value = 0;
                                                        $Taxes->service_text_title = 'Sell Rate';
                                                        $Taxes->service_text_value = 0;
                                                        $Taxes->other_text_title = 'Sell Rate';
                                                        if ($roomGuestkey == 1) {
                                                            $Taxes->other_tax_value = $roomGuestval['roomTaxes'];
                                                        } else if ($roomGuestkey == 2) {
                                                            $Taxes->other_tax_value_2 = $roomGuestval['roomTaxes'];
                                                        } else if ($roomGuestkey == 3) {
                                                            $Taxes->other_tax_value_3 = $roomGuestval['roomTaxes'];
                                                        }
                                                        $Taxes->magicspree_fee = '100';
                                                        $Taxes->gst_bar = $gst_bar;
                                                        $Taxes->gsttaxes_id = $tax_str;
                                                        $Taxes->status = 1;
                                                        $Taxes->save();
                                                        $room_taxes_id = $Taxes->id;
                                                    }
                                                    $checkaffiliateCommission = Ratemapping::where(['hotel_id' => $all_hotel->hotel_id])->first();
                                                    if (isset($checkaffiliateCommission)) {
                                                        Ratemapping::where(['hotel_id' => $all_hotel->hotel_id])->update(['rate_id' => '3', 'rate_up' => '3', 'discount_on' => '2', 'weakday_all' => $roomGuestval['roomAffiliateCommission'], 'weakend_all' => $roomGuestval['roomAffiliateCommission'], 'Mon' => '1', 'Tue' => '1', 'Wed' => '1', 'Thu' => '1', 'Fri' => '1', 'Sat' => '1', 'Sun' => '1', 'today_12_6_sdis' => $roomGuestval['roomAffiliateCommission'],
                                                            'today_6_9_sdis' => $roomGuestval['roomAffiliateCommission'], 'today_9_12_sdis' => $roomGuestval['roomAffiliateCommission'], 'today_12_3_sdis' => $roomGuestval['roomAffiliateCommission'], 'today_3_sdis' => $roomGuestval['roomAffiliateCommission'],
                                                            'today_12_6_edis' => $roomGuestval['roomAffiliateCommission'], 'today_6_9_edis' => $roomGuestval['roomAffiliateCommission'], 'today_9_12_edis' => $roomGuestval['roomAffiliateCommission'], 'today_12_3_edis' => $roomGuestval['roomAffiliateCommission'], 'today_3_edis' => $roomGuestval['roomAffiliateCommission'],
                                                            'tom_2_sdis' => $roomGuestval['roomAffiliateCommission'], 'tom_5_sdis' => $roomGuestval['roomAffiliateCommission'], 'tom_8_sdis' => $roomGuestval['roomAffiliateCommission'], 'tom_10_sdis' => $roomGuestval['roomAffiliateCommission'], 'tom_11_sdis' => $roomGuestval['roomAffiliateCommission'],
                                                            'tom_2_edis' => $roomGuestval['roomAffiliateCommission'], 'tom_5_edis' => $roomGuestval['roomAffiliateCommission'], 'tom_8_edis' => $roomGuestval['roomAffiliateCommission'], 'tom_10_edis' => $roomGuestval['roomAffiliateCommission'], 'tom_11_edis' => $roomGuestval['roomAffiliateCommission'],
                                                        ]);
                                                    } else {
                                                        $Ratemapping = new Ratemapping;
                                                        $Ratemapping->hotel_id = $all_hotel->hotel_id;
                                                        $Ratemapping->rate_id = '3';
                                                        $Ratemapping->rate_up = '3';
                                                        $Ratemapping->discount_on = '2';
                                                        $Ratemapping->weakday_all = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->weakend_all = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->Mon = '1';
                                                        $Ratemapping->Tue = '1';
                                                        $Ratemapping->Wed = '1';
                                                        $Ratemapping->Thu = '1';
                                                        $Ratemapping->Fri = '1';
                                                        $Ratemapping->Sat = '1';
                                                        $Ratemapping->Sun = '1';
                                                        $Ratemapping->today_12_6_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_6_9_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_9_12_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_12_3_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_3_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_12_6_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_6_9_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_9_12_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_12_3_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->today_3_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_2_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_5_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_8_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_10_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_11_sdis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_2_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_5_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_8_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_10_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->tom_11_edis = $roomGuestval['roomAffiliateCommission'];
                                                        $Ratemapping->save();
                                                        $Ratemapping_id = $Ratemapping->id;
                                                    }
                                                    // echo "Hotel Code Updated - " . $all_hotel->hotel_code . '<br/>';
                                                }
                                            }
                                            /* $dataString = " ------------ End Write EP Data ------------" . "\n \n";
                                              //$fWrite = fopen(base_path() . "/yatra/" . $all_hotel->hotel_code . '-' . date('Y-m-d') .".txt","a");
                                              $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d') . ".txt", "a");
                                              $wrote = fwrite($fWrite, $dataString . "\n \n");
                                              fclose($fWrite); */
                                        }
                                    }
                                }
                                $found_count++;
                            } else {
                                $not_found_count++;
                            }
                        }
                        echo "Not found hotels - " . $not_found_count . "<br/>";
                        echo "Hotels updated- " . $found_count;
                    }
                    else {
                        //echo "No data Found For this City";
                    }
                } catch (\Exception $e) {
                     $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                    //$fWrite = fopen('php://stderr', 'w');
                    $wrote = fwrite($fWrite, $e->getMessage() . "7 \n \n");
                    fclose($fWrite);
                }
                
                $dataStringreq .=  "\n ".'------------ End Cron For City ' . $city . "-" . date('Y-m-d H:i:s') . ' ------------' . "\n ";
                $dataStringreq .= "\n ".'------------ Stopped Cron Job Successfully ' . date('Y-m-d H:i:s') . ' ------------' . "\n ";
        
                $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
                //$fWrite = fopen('php://stderr', 'w');
                $wrote = fwrite($fWrite, $dataStringreq . " \n");
                fclose($fWrite);
            }
            /* $indentifycronstatus = '';
              $indentifycronstatus .= ' ------------ End Cron Job Successfully ' . date('Y-m-d H:i:s') . '-----------------------' . "\n \n";
              $indentifycronstatus .= $city;
              $fWrite = fopen(base_path() . "/yatra/" . $city . "-cronstatus-" . ".txt", "a");
              $wrote = fwrite($fWrite, $indentifycronstatus . "\n \n");
              fclose($fWrite); */
        } catch (\Exception $e) {
             $fWrite = fopen(base_path() . "/yatra/" . $city . "-" . date('Y-m-d')  . ".txt", "a");
            //$fWrite = fopen('php://stderr', 'w');
             $wrote = fwrite($fWrite, $e->getMessage() . "8 \n \n");
            fclose($fWrite);
        }


    }

}
