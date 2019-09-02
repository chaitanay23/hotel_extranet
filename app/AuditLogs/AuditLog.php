<?php 

namespace App\AuditLogs;
use Auth;
use Route;
use DB;

class AuditLog
{
    public static function auditLogs( $request, $id,$linked_id=null)
    {  
        $logged_in_user = Auth::user();
        $user_id=$logged_in_user->id;
        $user_name=$logged_in_user->email;

        $routeArray = app('request')->route()->getAction();

        $controllerAction = class_basename($routeArray['controller']);

        list($controller, $action) = explode('@', $controllerAction);


       
        
        $ip=$request->ip();
        

          if($action=="store")
          {
             $message="new entry created at id :".$id;
          }

          else if( $action=="update")
          {
              $message="entry updated at id :".$id;
          }

         else 
          $message="entry deleted with id:".$id;


          
          DB::table('adminactivities')->insert([
            'admin_id' => $user_id,
            'admin_user' => $user_name,
            'ip' => $ip,
            'section' => $controller,
            'subsection' => $action,
            'hotel_id' => $linked_id,
            'message' => $message
          ]);
          
           
        //   echo $ip,$user_id,$user_name,$controller,$action,$message,$linked_id;
        //   dd();
    }

    public static function inventoryLog($request,$value)
    {
        $logged_in_user = Auth::user();
        $user_id=$logged_in_user->id;
        $user_name=$logged_in_user->email;
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $ip=$request->ip();
        $message = 'Date from '.$value['start_date'].' to '.$value['end_date'];

        if($action=="store")
        {
            if(isset($value['rooms_ep']))
            {
                $message = $message.' Number of rooms in EP updated to '.$value['rooms_ep'];
            }
            if(isset($value['rooms_cp']))
            {
                $message = $message.' Number of rooms in CP updated to '.$value['rooms_cp'];
            }
            if(isset($value['ep_booked']))
            {
                $message = $message.' EP Blocked for sale';
            }
            if(isset($value['cp_booked']))
            {
                $message = $message.' CP Blocked for sale';
            }

            // echo $ip,$value['hotel_id'],$value['category_id'],$controller,$action,$message;
            // dd();
        }
        elseif($action="update_price")
        {
            if(isset($value['single_occupancy_price_ep']))
            {
                $message = $message.' Single price for EP update to '.$value['single_occupancy_price_ep'];
            }
            if(isset($value['double_occupancy_price_ep']))
            {
                $message = $message.' Double price for EP update to '.$value['double_occupancy_price_ep'];
            }
            if(isset($value['extra_adult_ep']))
            {
                $message = $message.' Extra adult price for EP update to '.$value['extra_adult_ep'];
            }
            if(isset($value['child_price_ep']))
            {
                $message = $message.' Child price for EP update to '.$value['child_price_ep'];
            }
            
            if(isset($value['single_occupancy_price_cp']))
            {
                $message = $message.' Single price for CP update to '.$value['single_occupancy_price_cp'];
            }
            if(isset($value['double_occupancy_price_cp']))
            {
                $message = $message.' Double price for CP update to '.$value['double_occupancy_price_cp'];
            }
            if(isset($value['extra_adult_cp']))
            {
                $message = $message.' Extra adult price for CP update to '.$value['extra_adult_cp'];
            }
            if(isset($value['child_price_cp']))
            {
                $message = $message.' Child price for CP update to '.$value['child_price_cp'];
            }


        }
        // elseif($action="update_inventory_data")
        // {
            
        // }

        DB::table('adminactivities')->insert([
            'admin_id' => $user_id,
            'admin_user' => $user_name,
            'ip' => $ip,
            'section' => $controller,
            'subsection' => $action,
            'hotel_id' => $value['hotel_id'],
            'hoteldetail_id' => $value['category_id'],
            'message' => $message
          ]);
        
    }


}