<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Hotel;
use App\Admin;
use Auth;
use App\Room;
use DateTime;
use App\Inventory;
use AuditLog;

class UpdateInventoryController extends Controller
{
    const INVENTORYTAB='inventory_tab';
    const PRICETAB='price_tab';
    function __construct()
    {
        $this->middleware('permission:update_inventory');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('update_inventory.index');
    }

    public function hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $logged_in_user = Auth::user();
            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                //here we select only hotels user from admins table in db
                //if magicspree side super user logged in
                $hotel_user = Hotel::select('id', 'title')->orderBy('id', 'DESC')->limit(1000)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('rm_user_id', $user_id)->orderBy('id', 'DESC')->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('user_id', $user_id)->orderBy('id', 'DESC')->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', '=', $logged_in_user->id)->orderBy('id', 'DESC')->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if revenue manager logged in 
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->orderBy('id', 'DESC')->get();
            } elseif ($logged_in_user->user_role_define == 4) {
                //if channel manager logged in
                $hotel_user = DB::table('hotels')->select('hotels.id as id', 'hotels.title as title')->leftjoin('hotelapiinteractions', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->where('hotelapiinteractions.channel_partner', '=', $logged_in_user->id)->orderBy('id', 'DESC')->get();
            }

            return $hotel_user;
        }
    }

    public function search_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_search = $request->search_hotel;
            $logged_in_user = Auth::user();

            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                $hotel_user = Hotel::select('id', 'title')->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('rm_user_id', $user_id)->where('title', 'like', '%' . $hotel_search . '%')->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('user_id', $user_id)->where('title', 'like', '%' . $hotel_search . '%')->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if revenue manager logged in 
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->get();
            } elseif ($logged_in_user->user_role_define == 4) {
                //if channel manager logged in
                $hotel_user = DB::table('hotels')->select('hotels.id as id', 'hotels.title as title')->leftjoin('hotelapiinteractions', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->where('hotelapiinteractions.channel_partner', '=', $logged_in_user->id)->where('hotels.title', 'like', '%' . $hotel_search . '%')->get();
            }

            return $hotel_user;
        }
    }

    public function room_fetch(Request $request)
    {
        $hotel_name = $request->hotel_name;
        $room_name = Room::select('id', 'custom_category')->where('hotel_id', '=', $hotel_name)->get();

        return $room_name;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $valid_message = [
            'hotel_id.not_in' => 'Please select hotel name.',
            'category_id.not_in' => 'Please select room name.'
        ];

        $this->validate($request, [
            'hotel_id' => 'not_in:0',
            'category_id' => 'not_in:0',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $valid_message);

        $input = $request->all();
        $input = array_filter($input);
        $start_date = new DateTime($input['start_date']);
        $end_date = new DateTime($input['end_date']);
        $end_date->modify('+1 day')->format('Y-m-d');
        $start_date_range = $input['start_date'];
        $end_date_range = $input['end_date'];
        $range = [$start_date_range, $end_date_range];

        while ($start_date != $end_date) {
            $new_update_inventory = Inventory::select('id')->where('date', '=', $start_date->format('Y-m-d'))->where('hotel_id', '=', $input['hotel_id'])->where('category_id', '=', $input['category_id'])->first();
            if ($new_update_inventory != null) {

                $new_update_inventory->update($input);
            } else {
                $input['date'] = $start_date->format('Y-m-d');
                Inventory::create($input);
            }
            $start_date->modify('+1 day')->format('Y-m-d');
        }
        if (isset($input['ep_booked'])) {
            if ($input['ep_booked'] == 'on') {
                $booked_inventory = Inventory::where('category_id', '=', $input['category_id'])->where('hotel_id', '=', $input['hotel_id'])->whereBetween('date', $range)->update(['ep_status' => '0']);
                //$booked_inventory = Inventory::where('category_id','=',$input['category_id'])->where('hotel_id','=',$input['hotel_id'])->whereBetween('date',$range)->update(['rooms_ep' => '0','ep_status' => '0']);
            }
        }
        if (isset($input['cp_booked'])) {
            if ($input['cp_booked'] == 'on') {
                $booked_inventory = Inventory::where('category_id', '=', $input['category_id'])->where('hotel_id', '=', $input['hotel_id'])->whereBetween('date', $range)->update(['cp_status' => '0']);
                //$booked_inventory = Inventory::where('category_id','=',$input['category_id'])->where('hotel_id','=',$input['hotel_id'])->whereBetween('date',$range)->update(['rooms_cp' => '0','cp_status' => '0']);
            }
        }
        AuditLog::inventoryLog($request, $input);
        return redirect()->route('update_inventory.index')
            ->with('success', 'Inventory Updated Successfully')
            ->with('tab_val',self::INVENTORYTAB);
    }

    public function update_price(Request $request)
    {
        $valid_message = [
            'hotel_id.not_in' => 'Please select hotel name.',
            'category_id.not_in' => 'Please select room name.'
        ];

        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'category_id' => 'required|not_in:0',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $valid_message);
        $input = $request->all();
        $input = array_filter($input);
        $start_date = new DateTime($input['start_date']);
        $end_date = new DateTime($input['end_date']);
        $end_date->modify('+1 day')->format('Y-m-d');
        while ($start_date != $end_date) {
            $new_update_inventory = Inventory::select('id')->where('date', '=', $start_date->format('Y-m-d'))->where('hotel_id', '=', $input['hotel_id'])->where('category_id', '=', $input['category_id'])->first();
            if ($new_update_inventory != null) {
                $new_update_inventory->update($input);
                //AuditLog::auditLogs($request,$id);
            } else {
                $input['date'] = $start_date->format('Y-m-d');
                Inventory::create($input);
                //AuditLog::auditLogs($request,$id);
            }
            $start_date->modify('+1 day')->format('Y-m-d');
        }
        AuditLog::inventoryLog($request, $input);
        return redirect()->route('update_inventory.index')
            ->with('success', 'Price Updated Successfully')
            ->with('tab_val',self::PRICETAB);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
