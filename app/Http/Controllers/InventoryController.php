<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Admin;
use Auth;
use App\Hotel;
use App\Room;
use App\Inventory;
use DateTime;
use AuditLog;

class InventoryController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:daily_inventory');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inventory = null;
        return view('inventory.index',compact('inventory'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function hotel_fetch(Request $request)
    {
        if($request->ajax())
        {
            $logged_in_user = Auth::user();
            if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
            {
                //here we select only hotels user from admins table in db
                //if magicspree side super user logged in
                $hotel_user = Hotel::select('id','title')->orderBy('id','DESC')->limit(500)->get();
            }
            elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
            {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id','title')->whereIn('rm_user_id',$user_id)->orderBy('id','DESC')->limit(500)->get();
            }
            elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
            {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id','title')->whereIn('user_id',$user_id)->orderBy('id','DESC')->limit(500)->get();
            }
            elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
            {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id','title')->where('user_id','=',$logged_in_user->id)->orderBy('id','DESC')->limit(500)->get();
            }
            elseif($logged_in_user->user_role_define == 3)
            {
                //if revenue manager logged in 
                $hotel_user = Hotel::select('id','title')->where('revenue_user','=',$logged_in_user->id)->orderBy('id','DESC')->limit(500)->get();
            }
            elseif($logged_in_user->user_role_define == 4)
            {
                //if channel manager logged in
                $hotel_user = DB::table('hotels')->select('hotels.id as id','hotels.title as title')->leftjoin('hotelapiinteractions','hotels.id','=','hotelapiinteractions.hotel_id')->where('hotelapiinteractions.channel_partner','=',$logged_in_user->id)->orderBy('id','DESC')->limit(500)->get();
            }
            
            return $hotel_user;
        }
    }
    public function search_hotel_fetch(Request $request)
    {
        if($request->ajax()){
            $hotel_search = $request->search_hotel;
            $logged_in_user = Auth::user();
            if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
            {
                $hotel_user = Hotel::select('id','title')->where('title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
            {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id','title')->whereIn('rm_user_id',$user_id)->where('title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
            {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id','title')->whereIn('user_id',$user_id)->where('title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
            {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id','title')->where('user_id','=',$logged_in_user->id)->where('title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            elseif($logged_in_user->user_role_define == 3)
            {
                //if revenue manager logged in 
                $hotel_user = Hotel::select('id','title')->where('revenue_user','=',$logged_in_user->id)->where('title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            elseif($logged_in_user->user_role_define == 4)
            {
                //if channel manager logged in
                $hotel_user = DB::table('hotels')->select('hotels.id as id','hotels.title as title')->leftjoin('hotelapiinteractions','hotels.id','=','hotelapiinteractions.hotel_id')->where('hotelapiinteractions.channel_partner','=',$logged_in_user->id)->where('hotels.title','like','%'.$hotel_search.'%')->limit(50)->get();
            }
            return $hotel_user;
        }
    }

    public function edit_hotel_fetch(Request $request)
    {
        if($request->ajax())
        {
            $hotel_id = $request->hotel_user;
            $hotel_user = Hotel::select('id','title')->where('id','=',$hotel_id)->get();

            return $hotel_user;
        }
    }
    
    public function room_fetch(Request $request)
    {
        $hotel_name = $request->hotel_name;
        $room_name = Room::select('id','custom_category')->where('hotel_id','=',$hotel_name)->get();
        return $room_name;
    }
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_inventory(Request $request)
    {
        $valid_message = [
            'hotel_id.not_in' => 'Please select hotel name.',
            'category_id.not_in' => 'Please select room name.'];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'category_id' => 'required|not_in:0',
        ],$valid_message);
        $input = $request->all();
        $start_date = $input['start_date'];
        $end_date= $input['end_date'];
        $range = [$start_date,$end_date];
        $inventory = Inventory::where('category_id',$input['category_id'])->whereBetween('date',$range)->get();
        $hotel_name = Hotel::select('id','title')->where('id','=',$input['hotel_id'])->first();
        $room_name = Room::select('id','custom_category')->where('id','=',$input['category_id'])->first();
        
        if(count($inventory) == 0)
        {
            // print_r($inventory);die();
            return view('inventory.index')->withErrors(['No Inventory Found  with given hotel please contact your adminstrator!!']);
        }
        else{
            return view('inventory.index',compact('inventory','hotel_name','room_name','start_date','end_date'));
        }
    }
    public function update_inventory_data(Request $request)
    {
        if($request->ajax()){
            $updated_value = $request->updated_value;
            $column = $request->column;
            $id = $request->id;
            $inventory_update = DB::table('dailyinventories')->where('id','=',$id)->update([$column => $updated_value]);

            return $id;
        }   
    }
    public function status_update_on(Request $request)
    {
        if($request->ajax()){
            $change = $request->change;
            $room_id = $request->room;
            if($change=='cp')
            {
                $status = DB::table('dailyinventories')->where('category_id','=',$room_id)->update(['cp_status' => '1']);
            }
            else
            {
                $status = DB::table('dailyinventories')->where('category_id','=',$room_id)->update(['ep_status' => '1']);   
            }
            $value = '1';
            return $value;
        }
    }
    public function status_update_off(Request $request)
    {
        if($request->ajax()){
            $change = $request->change;
            $room_id = $request->room;
            if($change=='cp')
            {
                $status = DB::table('dailyinventories')->where('category_id','=',$room_id)->update(['cp_status' => '0']);
            }
            else
            {
                $status = DB::table('dailyinventories')->where('category_id','=',$room_id)->update(['ep_status' => '0']);   
            }
            $value = '0';
            return $value;
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}