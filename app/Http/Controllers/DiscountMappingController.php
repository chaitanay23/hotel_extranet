<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Admin;
use App\Channel;
use Auth;
use App\Hotel;
use App\DiscountMapping;
use Session;
use AuditLog;

class DiscountMappingController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:discount_mapping_list');
        $this->middleware('permission:discount_mapping_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:discount_mapping_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:discount_mapping_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $logged_in_user = Auth::user();
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name')->leftjoin('hotels', 'hotels.id', '=', 'ratemappings.hotel_id')->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name')->leftjoin('hotels', 'hotels.id', '=', 'ratemappings.hotel_id')->whereIn('rm_user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
            //if hotel side super user logged in
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name')->leftjoin('hotels', 'hotels.id', '=', 'ratemappings.hotel_id')->whereIn('hotels.user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
            //if hotel side local user logged in
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name')->leftjoin('hotels', 'hotels.id', '=', 'ratemappings.hotel_id')->where('hotels.user_id', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 3) {
            //if hotel side local user logged in
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name')->leftjoin('hotels', 'hotels.id', '=', 'ratemappings.hotel_id')->where('hotels.revenue_user', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 4) {
            $discount = DB::table('ratemappings')->select('ratemappings.id as id', 'hotels.title as hotel_name', 'hotelapiinteractions.channel_partner')->leftjoin('hotelapiinteractions', 'hotelapiinteractions.hotel_id', '=', 'ratemappings.hotel_id')->leftjoin('hotels', 'hotels.id', '=', 'hotelapiinteractions.hotel_id')->where('hotelapiinteractions.channel_partner', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('ratemappings.id', 'DESC')->paginate(10);
        }

        return view('discount_mapping.index', compact('discount', 'search'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
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
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', $logged_in_user->id)->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 4) {
                $channel_hotel = Channel::select('hotel_id')->where('channel_partner', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('id', $channel_hotel)->orderBy('id', 'DESC')->limit(500)->get();
            }

            return $hotel_user;
        }
    }

    public function edit_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_id = $request->hotel_user;
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
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('user_id', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 4) {
                $channel_hotel = Channel::select('hotel_id')->where('channel_partner', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('id', 'title')->whereIn('id', $channel_hotel)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            }

            return $hotel_user;
        }
    }

    public function create()
    {
        $hotel_id = Session::get('hotel_id');
        if (!$hotel_id)
            $hotel_id = null;
        Session::forget('hotel_id');
        $percentage = array();
        $logged_in_user = Auth::user();
        if ($logged_in_user->user_role_define == 1) {
            for ($i = 0; $i < 100; $i++) {
                $percentage[$i] = $i . '%';
            }
        } else {
            for ($i = 15; $i < 96; $i++) {
                $percentage[$i] = $i . '%';
            }
        }

        return view('discount_mapping.create', compact('percentage', 'hotel_id'));
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
            'hotel_id.unique' => 'Discount mapping for this hotel is already available',
            'today_12_6_sdis.not_in' => 'Please select weekday discount for 12am - 6am.',
            'today_12_6_edis.not_in' => 'Please select weekend discount for 12am - 6am.',
            'today_6_9_sdis.not_in' => 'Please select weekday discount for 6am - 9am.',
            'today_6_9_edis.not_in' => 'Please select weekend discount for 6am - 9am.',
            'today_9_12_sdis.not_in' => 'Please select weekday discount for 9am - 12pm.',
            'today_9_12_edis.not_in' => 'Please select weekend discount for 9am - 12pm.',
            'today_12_3_sdis.not_in' => 'Please select weekday discount for 12pm - 3pm.',
            'today_12_3_edis.not_in' => 'Please select weekend discount for 12pm - 3pm.',
            'today_3_sdis.not_in' => 'Please select weekday discount for 3pm ownwards.',
            'today_3_edis.not_in' => 'Please select weekend discount for 3pm ownwards.',
            'tom_2_sdis.not_in' => 'Please select weekday discount for 0-2 rooms.',
            'tom_2_edis.not_in' => 'Please select weekend discount for 0-2 rooms.',
            'tom_5_sdis.not_in' => 'Please select weekday discount for 3-5 rooms.',
            'tom_5_edis.not_in' => 'Please select weekend discount for 3-5 rooms.',
            'tom_8_sdis.not_in' => 'Please select weekday discount for 6-8 rooms.',
            'tom_8_edis.not_in' => 'Please select weekend discount for 6-8 rooms.',
            'tom_10_sdis.not_in' => 'Please select weekday discount for 9-10 rooms.',
            'tom_10_edis.not_in' => 'Please select weekend discount for 9-10 rooms.',
            'tom_11_sdis.not_in' => 'Please select weekday discount for more than 10 rooms.',
            'tom_11_edis.not_in' => 'Please select weekend discount for more than 10 rooms.',
        ];
        $this->validate($request, [
            'hotel_id' => 'required|unique:ratemappings|not_in:0',
            'today_12_6_sdis' => 'not_in:0',
            'today_12_6_edis' => 'not_in:0',
            'today_6_9_sdis' => 'not_in:0',
            'today_6_9_edis' => 'not_in:0',
            'today_9_12_sdis' => 'not_in:0',
            'today_9_12_edis' => 'not_in:0',
            'today_12_3_sdis' => 'not_in:0',
            'today_12_3_edis' => 'not_in:0',
            'today_3_sdis' => 'not_in:0',
            'today_3_edis' => 'not_in:0',
            'tom_2_sdis' => 'not_in:0',
            'tom_2_edis' => 'not_in:0',
            'tom_5_sdis' => 'not_in:0',
            'tom_5_edis' => 'not_in:0',
            'tom_8_sdis' => 'not_in:0',
            'tom_8_edis' => 'not_in:0',
            'tom_10_sdis' => 'not_in:0',
            'tom_10_edis' => 'not_in:0',
            'tom_11_sdis' => 'not_in:0',
            'tom_11_edis' => 'not_in:0',
        ], $valid_message);
        $input = $request->all();
        $discount_info = DiscountMapping::create($input);
        $id = $discount_info->id;
        $linked_id = $input['hotel_id'];
        AuditLog::auditLogs($request, $id);



        $submit_type = $input['submit'];
        if ($submit_type == "submit") {
            return redirect()->route('discount_mapping.index')->with('success', 'Discount Mapping created sucessfully');
        } else {
            $hotel_id = $input['hotel_id'];

            return app('App\Http\Controllers\AddonController')->show_addon($request);
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
        $discount = DiscountMapping::find($id);
        $hotel = DB::table('hotels')->select('title')->where('id', '=', $discount->hotel_id)->first();

        return view('discount_mapping.show', compact('discount', 'hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = DiscountMapping::find($id);
        $percentage = array();
        $logged_in_user = Auth::user();
        if ($logged_in_user->user_role_define == 1) {
            for ($i = 0; $i < 100; $i++) {
                $percentage[$i] = $i . '%';
            }
        } else {
            for ($i = 15; $i < 96; $i++) {
                $percentage[$i] = $i . '%';
            }
        }

        return view('discount_mapping.edit', compact('discount', 'percentage'));
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
            'today_12_6_sdis.not_in' => 'Please select weekday discount for 12am - 6am.',
            'today_12_6_edis.not_in' => 'Please select weekend discount for 12am - 6am.',
            'today_6_9_sdis.not_in' => 'Please select weekday discount for 6am - 9am.',
            'today_6_9_edis.not_in' => 'Please select weekend discount for 6am - 9am.',
            'today_9_12_sdis.not_in' => 'Please select weekday discount for 9am - 12pm.',
            'today_9_12_edis.not_in' => 'Please select weekend discount for 9am - 12pm.',
            'today_12_3_sdis.not_in' => 'Please select weekday discount for 12pm - 3pm.',
            'today_12_3_edis.not_in' => 'Please select weekend discount for 12pm - 3pm.',
            'today_3_sdis.not_in' => 'Please select weekday discount for 3pm ownwards.',
            'today_3_edis.not_in' => 'Please select weekend discount for 3pm ownwards.',
            'tom_2_sdis.not_in' => 'Please select weekday discount for 0-2 rooms.',
            'tom_2_edis.not_in' => 'Please select weekend discount for 0-2 rooms.',
            'tom_5_sdis.not_in' => 'Please select weekday discount for 3-5 rooms.',
            'tom_5_edis.not_in' => 'Please select weekend discount for 3-5 rooms.',
            'tom_8_sdis.not_in' => 'Please select weekday discount for 6-8 rooms.',
            'tom_8_edis.not_in' => 'Please select weekend discount for 6-8 rooms.',
            'tom_10_sdis.not_in' => 'Please select weekday discount for 9-10 rooms.',
            'tom_10_edis.not_in' => 'Please select weekend discount for 9-10 rooms.',
            'tom_11_sdis.not_in' => 'Please select weekday discount for more than 10 rooms.',
            'tom_11_edis.not_in' => 'Please select weekend discount for more than 10 rooms.',
        ];

        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'today_12_6_sdis' => 'not_in:0',
            'today_12_6_edis' => 'not_in:0',
            'today_6_9_sdis' => 'not_in:0',
            'today_6_9_edis' => 'not_in:0',
            'today_9_12_sdis' => 'not_in:0',
            'today_9_12_edis' => 'not_in:0',
            'today_12_3_sdis' => 'not_in:0',
            'today_12_3_edis' => 'not_in:0',
            'today_3_sdis' => 'not_in:0',
            'today_3_edis' => 'not_in:0',
            'tom_2_sdis' => 'not_in:0',
            'tom_2_edis' => 'not_in:0',
            'tom_5_sdis' => 'not_in:0',
            'tom_5_edis' => 'not_in:0',
            'tom_8_sdis' => 'not_in:0',
            'tom_8_edis' => 'not_in:0',
            'tom_10_sdis' => 'not_in:0',
            'tom_10_edis' => 'not_in:0',
            'tom_11_sdis' => 'not_in:0',
            'tom_11_edis' => 'not_in:0',
        ], $valid_message);
        $input = $request->all();
        $discount_update = DiscountMapping::find($id);
        $discount_update->update($input);

        $linked_id = $input['hotel_id'];
        AuditLog::auditLogs($request, $id, $linked_id);

        return redirect()->route('discount_mapping.index')
            ->with('success', 'Discount mapping updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DiscountMapping::find($id)->delete();
        AuditLog::auditLogs($request, $id);
        return redirect()->back()->with('success', 'Discount mapping deleted sucessfully');
    }
}
