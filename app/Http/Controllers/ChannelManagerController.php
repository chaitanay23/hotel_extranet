<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\Hotel;
use App\Admin;
use DB;
use Auth;
use Session;
use AuditLog;

class channelManagerController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:channel_manager_list');
        $this->middleware('permission:channel_manager_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:channel_manager_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:channel_manager_delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $logged_in_user = Auth::user();
            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                //here we select only hotels user from admins table in db
                //if magicspree side super user logged in
                $hotel_user = Hotel::select('id', 'title')->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('rm_user_id', $user_id)->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('user_id', $user_id)->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 2) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', '=', $logged_in_user->id)->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->orderBy('id', 'DESC')->limit(500)->get();
            }

            return $hotel_user;
        }
    }

    public function edit_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_id = $request->hotel_id;
            $hotel_user = Hotel::select('id', 'title')->where('id', '=', $hotel_id)->get();

            return $hotel_user;
        }
    }

    public function search_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_search = $request->search_hotel;
            $logged_in_user = Auth::user();
            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                //here we select only hotels user from admins table in db
                //if magicspree side super user logged in
                $hotel_user = Hotel::select('id', 'title')->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if magicspree side local user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('rm_user_id', $user_id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if hotel side super user logged in
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('user_id', $user_id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 2) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            }

            return $hotel_user;
        }
    }

    public function user_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_get = $request->hotel_get;
            //get user id from hotel table according to hotel select 
            $user_id = DB::table('hotels')->where('id', '=', $hotel_get)->pluck('user_id');
            //here we select only hotels user from admins table in db
            $user = Admin::select('id', 'email')->where('user_role_define', '=', '2')->where('id', '=', $user_id)->get();

            return $user;
        }
    }

    public function channel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $partner = Admin::select('id', 'name')->where('user_role_define', '=', '4')->get();

            return $partner;
        }
    }

    public function status_update_on(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $input = Channel::find($id);
            Channel::where('id', '=', $id)->update(['status' => '1']);
            $inp = Channel::find($id);

            return $inp;
        }
    }
    public function status_update_off(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $input = Channel::find($id);
            Channel::where('id', '=', $id)->update(['status' => '0']);
            $inp = Channel::find($id);

            return $inp;
        }
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $logged_in_user = Auth::user();
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('hotelapiinteractions.hotelkey', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->whereIn('rm_user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('hotelapiinteractions.hotelkey', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
            //if hotel side super user logged in
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->whereIn('hotels.user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
            //if hotel side local user logged in
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->where('hotels.user_id', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 3) {
            //if revenue manager user logged in
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->where('hotels.revenue_user', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 4) {
            //if channel manager logged in
            $channel = DB::table('hotelapiinteractions')->select('hotelapiinteractions.id as id', 'hotelapiinteractions.hotelkey as hotelkey', 'hotelapiinteractions.hotel_id as hotel_id', 'hotelapiinteractions.status as status', 'hotels.title as hotel_name', 'hotelapiinteractions.hotelpassword as password', 'hotelapiinteractions.channel_partner as channelPartner', 'admins.name as partner_name')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->leftjoin('admins', 'admins.id', '=', 'hotelapiinteractions.channel_partner')->where('hotelapiinteractions.channel_partner', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('hotelapiinteractions.id', 'DESC')->paginate(10);
        }

        return view('channel.index', compact('channel', 'search'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotel_id = Session::get('hotel_id');
        if (!$hotel_id)
            $hotel_id = null;
        Session::forget('hotel_id');
        return view('channel.create', compact('hotel_id'));
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
            'hotel_id.unique' => 'Details for selected hotel already created',
        ];
        $this->validate($request, [
            'hotel_id' => 'required|unique:hotelapiinteractions|not_in:0',
        ], $valid_message);
        $input = $request->all();
        $submit_type = $input['submit'];
        if ($submit_type == "skip") {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\CommissionsController')->create();
        }
        $info = Channel::create($input);
        $id = $info->id;
        AuditLog::auditLogs($request, $id);
        if ($submit_type == "submit") {
            return redirect()->route('channel.index')->with('success', 'Channel manager created successfully');
        } else {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\CommissionsController')->create();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = Channel::find($id);
        $hotel = DB::table('hotels')->select('title')->where('id', '=', $channel->hotel_id)->first();
        $partner_name = DB::table('admins')->select('name')->where('id', '=', $channel->channel_partner)->first();

        return view('channel.show', compact('channel', 'hotel', 'partner_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $channel = Channel::find($id);
        $hotel = DB::table('hotels')->select('title')->where('id', '=', $channel->hotel_id)->first();
        $partner = Admin::select('id', 'name')->where('user_role_define', '=', '4')->pluck('name', 'id')->all();
        return view('channel.edit', compact('channel', 'hotel', 'partner'));
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
        $valid_message = [
            'hotel_id.not_in' => 'Please select hotel name.',
            'channel_partner.not_in:0' => 'Please select valid channel partner',
        ];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'channel_partner' => 'required|not_in:0',
        ], $valid_message);
        $value = $request->all();
        $input = Channel::find($id);
        $input->update($value);
        AuditLog::auditLogs($request, $id);

        $url = $value['url'];
        return redirect($url)
            ->with('success', 'Channel manager details updated successfully');
        //return redirect()->route('channel.index')->with('success','Channel manager updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Channel::find($id)->delete();
        AuditLog::auditLogs($request, $id);
        return redirect()->back()->with('Success', 'Channel manager deleted successfully');
    }
}
