<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Booking;
use App\Tax;
use App\Userlog;
use App\Adminlog;
use App\Adminactivity;
use App\Successfullbid;
use App\Bidcounter;
use App\Mstax;
use App\Payment;
use App\Creditcard;
use App\Negotiable;
use Auth;
use App\Admin;
use DB;

class ReportController extends Controller
{     
    public function newUsers(Request $request)
    {
       
        $fromdate = $request->fromdate;
        $todate= $request->todate;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");

       

        if($fromdate !='' || $todate !='') {
        $booking = User::whereBetween('created_at',[$fromdate,$todate])->paginate(10);
       

        }  else {
        $booking = User::paginate(10);
        }
        $counter =count($booking);
        
       

        return view('Reports.newusers',compact('booking','counter'));
    }

    public function showBookings(Request $request)
    {
        $search=$request->search;
        
        $id=$request->booking_id;
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");

        $logged_in_user = Auth::user();
        
        // dd($user_id);
        //user_role_define is magicspree or hotel or rm or cm
        //user_level is super or local
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {

            if($search==1)
            {

                if($fromdate !='' || $todate !='') {
                $booking = Booking::whereBetween('created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
        

                }else   $booking =Booking::orderBy('id','DESC')->paginate(15);

        
            }

            elseif($search==2)
            {
                echo $search;
                if($fromdate !='' || $todate !='') {
                    $booking = Booking::whereBetween('check_in_time',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
            
        
                    }else   $booking =Booking::orderBy('id','DESC')->paginate(15);

            }

            elseif($search==3)
            {

                if($fromdate !='' || $todate !='') {
                    $booking = Booking::whereBetween('check_out_time',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
            
        
                    }else   $booking =Booking::orderBy('id','DESC')->paginate(15);  echo $search;

            }

            else{
            
                if($id!='')
                {
                    $booking = Booking::where('booking_code',$id)->orderBy('id','DESC')->paginate(15);
                }
                else 
                $booking =Booking::orderBy('id','DESC')->paginate(15);


            }

           
            
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {   
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
           //check this i think its wrong

            if($search==1)
            {

                if($fromdate !='' || $todate !='') {
                
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);

                }else                
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);
        
            }

            elseif($search==2)
            {
                echo $search;
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->whereBetween('bookings.check_out_time',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);            
        
                }else                          
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);

            }

                elseif($search==3)
                {

                    if($fromdate !='' || $todate !='') {
                        $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->whereBetween('bookings.check_in_time',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);

                
            
                        }else                 $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);




                }

            else{
            
                if($id!='')
                {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->where('bookings.booking_code',$id)->orderBy('id','DESC')->paginate(15);

                }
                else 
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);


            }
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            


        //again wrong code for $search


                if($search==1)
            {
                
                if($fromdate !='' || $todate !='') {
                    
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->paginate(15);

           
    
                }else               $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->paginate(15);

    
           
            }
    
            elseif($search==2)
            {
                echo $search;
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->whereBetween('bookings.check_in_time',[$fromdate,$todate])->paginate(15);
               
        
                    }else                       $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->paginate(15);

            }
    
            elseif($search==3)
            {
    
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->whereBetween('bookings.check_out_time',[$fromdate,$todate])->paginate(15);
               
        
                    }else                       $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->paginate(15);

    
            }
    
            else{
            
                if($id!='')
                {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->where('bookings.booking_code','=',$id)->paginate(15);

                }
                else 
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->paginate(15);

            }
        }
               
                
            
            
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            
            //if hotel local user logged in to the system
            $booking = DB::table('bookings')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
            // $booking = Booking::whereBetween('created_at',[$fromdate,$todate])->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
            
            if($search==1)
            {
    
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->wherebetween('bookings.created_at',[$fromdate,$todate])->paginate(15);
           
    
                }else   $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
    
           
            }
    
            elseif($search==2)
            {
                echo $search;
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->wherebetween('bookings.check_in_time',[$fromdate,$todate])->paginate(15);
               
        
                    }else   $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
    
            }
    
            elseif($search==3)
            {
    
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->wherebetween('bookings.check_out_time',[$fromdate,$todate])->paginate(15);
               
        
                    }else   $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
            }
    
            else{
            
                if($id!='')
                {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->where('bookings.booking_code','=',$id)->paginate(15);
                }
                else 
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->paginate(15);
    
    
            }
            
                   }
        elseif ($logged_in_user->user_role_define == 3)
        {
            //if revenue manager logged in to the system he will get list of rooms releted to him



            if($search==1)
            {
    
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->whereBetween('bookings.created_at',[$fromdate,$todate])->paginate(15);
           
    
                }else              $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->paginate(15);

    
           
            }
    
            elseif($search==2)
            {
                echo $search;
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->whereBetween('bookings.created_at',[$fromdate,$todate])->paginate(15);

               
        
                    }else               $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->paginate(15);

    
            }
    
            elseif($search==2)
            {
    
                if($fromdate !='' || $todate !='') {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->whereBetween('bookings.created_at',[$fromdate,$todate])->paginate(15);

               
        
                    }else              $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->paginate(15);

            }
    
            else{
            
                if($id!='')
                {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->where('bookings.booking_code','=',$id)->paginate(15);

                }
                else 
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->paginate(15);

    
            }
            
        }



                  
        $counter=count($booking);
       
       
  
        return view('Reports.bookings',compact('booking','counter','fromdate','todate','id','search'));
    }

    public function UserLogs(Request $request)
    {
       
        $fromdate = $request->fromdate;
        $todate= $request->todate;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Userlog::whereBetween('created_at',[$fromdate,$todate])->paginate(15);
       

        }  else {
        $booking = Userlog::paginate(15);
        }
        $counter =count($booking);
        
       

        return view('Reports.userlogs',compact('booking','counter'));
    }
        
    public function AdminLogs(Request $request)
    {
       
        $fromdate = $request->fromdate;
        $todate= $request->todate;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Adminlog::whereBetween('created_at',[$fromdate,$todate])->paginate(15);
       

        }  else {
        $booking = Adminlog::paginate(15);
        }
        $counter =count($booking);
        
       

        return view('Reports.adminlogs',compact('booking','counter'));
    }    

    public function AdminActivities(Request $request)
    {
       
        $fromdate = $request->fromdate;
        $todate= $request->todate;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Adminactivity::whereBetween('created_at',[$fromdate,$todate])->paginate(15);
       

        }  else {
        $booking = Adminactivity::paginate(15);
        }
        $counter =count($booking);
        
       

        return view('Reports.adminactivities',compact('booking','counter'));
    }

    public function NotBooked(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $fromdate_old= $request->start_date_old;
        $todate_old= $request->end_date_old;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Successfullbid::whereBetween('created_at',[$fromdate,$todate])
        ->where('booking_status',0)
        ->orderBy('id','DESC')
        ->paginate(15);
       

        }  else {
        $booking = Successfullbid::orderBy('id','DESC')->paginate(15);
        }
        $counter =count($booking);
        
       
        return view('Reports.notbooked',compact('booking','counter','fromdate','todate','fromdate_old','todate_old'));
    }

    public function Booked(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Successfullbid::whereBetween('created_at',[$fromdate,$todate])
        ->where('booking_status',1)
        ->orderBy('id','DESC')
        ->paginate(15);
       
            
        }  else {
        $booking = Successfullbid::orderBy('id','DESC')->paginate(15);
        }
        $counter =count($booking);
        
       

        return view('Reports.booked',compact('booking','counter','fromdate','todate'));
    }

    public function Unsuccessful(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Bidcounter::whereBetween('created_at',[$fromdate,$todate])
        ->orderBy('id','DESC')
        ->paginate(15);
       

        }  else {
        $booking = Bidcounter::orderBy('id','DESC')->paginate(15);
        }
        $counter =count($booking);
        
        
        
        return view('Reports.unsuccessful',compact('booking','counter','fromdate','todate'));
    }    

    public function showSales(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
      
        
        $mstax =  Mstax::where('status',1)->first();
       
        $logged_in_user = Auth::user();
        //user_role_define is magicspree or hotel or rm or cm
        //user_level is super or local
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {
            
            
            if($fromdate !='' || $todate !='') {
                
                $booking = Booking::whereBetween('created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
               
        
                }  else {
                $booking = Booking::orderBy('id','DESC')->paginate(15);
                
                }
                $counter =count($booking);
                
                
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            if($fromdate !='' || $todate !='') {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);

               
        
                }  else {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);

                }
                $counter =count($booking);
                
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();

            if($fromdate !='' || $todate !='') {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
        
                }  else {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->orderBy('id','DESC')->paginate(15);
                }
                $counter =count($booking);
                

        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            //if hotel local user logged in to the system 

            if($fromdate !='' || $todate !='') {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$user_id)->wherebetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
        
                }  else {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);
                }
                $counter =count($booking);
                
        }
        elseif ($logged_in_user->user_role_define == 3)
        {
            //if revenue manager logged in to the system he will get list of rooms releted to him

            if($fromdate !='' || $todate !='') {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
        
                }  else {
                    $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->orderBy('id','DESC')->paginate(15);
                }
                $counter =count($booking);
                
        }


        

        return view('Reports.sales',compact('booking','counter','mstax','fromdate','todate'));
    }

    public function Negotiable(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");
        if($fromdate !='' || $todate !='') {
        $booking = Negotiable::whereBetween('created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
       

        }  else {
        $booking = Negotiable::orderBy('id','DESC')->paginate(15);
        }
        $counter =count($booking);
        
       

        return view('Reports.negotiable',compact('booking','counter','fromdate','todate'));
    }

    public function showPayment(Request $request)
    {
       
        $fromdate = $request->start_date;
        $todate= $request->end_date;
        $date1 = date_create($fromdate);
        $date2 = date_create($todate);
        $fromdate1 = date_format($date1,"Y-m-d");
        $todate1 = date_format($date2,"Y-m-d");          
        $mstax =  Mstax::where('status',1)->first();
        $logged_in_user = Auth::user();
        //user_role_define is magicspree or hotel or rm or cm
        //user_level is super or local
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {

        if($fromdate !='' || $todate !='') {
            $booking = Booking::whereBetween('created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
    
            }  else {
            $booking = Booking::orderBy('id','DESC')->paginate(15);
            }
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            
        if($fromdate !='' || $todate !='') {
            $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
    
            }  else {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.rm_user_id','=',$user_id)->orderBy('id','DESC')->paginate(15);               
            }  
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();

        if($fromdate !='' || $todate !='') {
            $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
    
            }  else {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->whereIn('hoteldetails.user_id',$user_id)->orderBy('id','DESC')->paginate(15);
            }
        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            //if hotel local user logged in to the system 

        if($fromdate !='' || $todate !='') {
            $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->wherebetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
    
            }  else {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hoteldetails.user_id','=',$logged_in_user->id)->orderBy('id','DESC')->paginate(15);
            }
        }
        elseif ($logged_in_user->user_role_define == 3)
        {
            //if revenue manager logged in to the system he will get list of rooms releted to him

        if($fromdate !='' || $todate !='') {
            $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->whereBetween('bookings.created_at',[$fromdate,$todate])->orderBy('id','DESC')->paginate(15);
    
            }  else {
                $booking= DB::table('bookings')->leftjoin('hotels', 'hotels.id', '=', 'bookings.hotel_id')->leftjoin('hoteldetails', 'hoteldetails.id', '=', 'bookings.hoteldetail_id')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->where('hotels.revenue_user','=',$logged_in_user->id)->orderBy('id','DESC')->paginate(15);
            }
        }

        
        $counter =count($booking);                

        return view('Reports.payment',compact('booking','counter','mstax','fromdate','todate'));
    }  

    //Get Reason Dealy Payment
	public function GetReasonForPayment(Request $request)
	{
		if($request->ajax())
		{
		    
			$booking_id = $request->id;
			$reason_id = $request->reason_id;
						
			$counter = Payment::where('booking_id',$booking_id)->first();

			if($counter)
			{
				Payment::where('booking_id',$booking_id)->update(['reason'=>$reason_id]);
			}
			else
			{
				$pay = New Payment;
				$pay->booking_id = $booking_id;
				$pay->reason = $reason_id;

				$pay->save();
			}
		    

	       	return response()->json( array('ret' => 1,'con'=>'') ); 
			       			
		}
	}
	//End


	//Get Reason Dealy Other Text
	public function GetOtherReason(Request $request)
	{
		if($request->ajax())
		{
		    
			$booking_id = $request->id;
			$reason = $request->reason;
						
			$counter = Payment::where('booking_id',$booking_id)->count();

			if($counter)
			{
				Payment::where('booking_id',$booking_id)->update(['r_txt'=>$reason]);
			}
			
	       	return response()->json( array('ret' => 1,'con'=>'') ); 
			       			
		}
	}
    //End
    
    //Fetch Credit Card Details
	public function GetCCDetails(Request $request)
	{
		if($request->ajax())
		{
		    
			$bank_id = $request->id;
		    
		    $get_detail = Creditcard::find($bank_id);

	       	return response()->json( array('ret' => 1,'con'=>$get_detail->cardnumber) ); 
			       			
		}
	}
    //End
    
    //Fetch Credit Card Details
	public function GetPaymentDetails(Request $request)
	{
		if($request->ajax())
		{
		    
			$booking_id = $request->booking_id;
			$status = $request->status;
			$date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
			$mode = $request->mode;
			$t_id = $request->t_id;
			$ac_number = $request->ac_number;
			$msbank = ($request->msbank=='null')?0:$request->msbank;
			$cc_info = ($request->cc_info=='null')?0:$request->cc_info;
			
			$counter = Payment::where('booking_id',$booking_id)->count();

			if($counter)
			{
				Payment::where('booking_id',$booking_id)->update(['status'=>$status,'date'=>$date,'mode'=>$mode,'t_id'=>$t_id,'number'=>$ac_number,'bank_id'=>($msbank=='null')?0:$msbank,'creditcard_id'=>($cc_info=='null')?0:$cc_info]);
			}
			else
			{
				$pay = New Payment;
				$pay->booking_id = $booking_id;
				$pay->status = $status;
				$pay->date = $date;
				$pay->mode = $mode;
				$pay->t_id = $t_id;
				$pay->number = $ac_number;
				$pay->bank_id = $msbank;
				$pay->creditcard_id = $cc_info;

				$pay->save();
			}
		    

	       	return response()->json( array('ret' => 1,'con'=>'') ); 
			       			
		}
	}
    //End
    
    //Fetch Bank Details
	public function GetBankDetails(Request $request)
	{
		if($request->ajax())
		{
		    
			$bank_id = $request->id;
		    
		    $get_detail = \App\Model\Msbank::find($bank_id);

	       	return response()->json( array('ret' => 1,'con'=>$get_detail->account_no) ); 
			       			
		}
	}
	//End
    
}