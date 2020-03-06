<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Commission;
use App\Hotel;
use Auth;
use App\Admin;
use DB;
use Session;
use AuditLog;

class CommissionsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:commission_list');
        $this->middleware('permission:commission_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:commission_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:commission_delete', ['only' => ['destroy']]);
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
            $commission = DB::table('commissions')->select('commissions.id as id', 'commissions.commission as commission', 'hotels.title as hotel_name', 'commissions.pbc as pbc', 'commissions.magicspree_fee as magicspree_fee')->leftjoin('hotels', 'hotels.id', '=', 'commissions.hotel_id')->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('commissions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $commission = DB::table('commissions')->select('commissions.id as id', 'commissions.commission as commission', 'hotels.title as hotel_name', 'commissions.pbc as pbc', 'commissions.magicspree_fee as magicspree_fee')->leftjoin('hotels', 'hotels.id', '=', 'commissions.hotel_id')->whereIn('rm_user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('commissions.id', 'DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
            //if hotel side super user logged in
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $commission = DB::table('commissions')->select('commissions.id as id', 'commissions.commission as commission', 'hotels.title as hotel_name', 'commissions.pbc as pbc', 'commissions.magicspree_fee as magicspree_fee')->leftjoin('hotels', 'hotels.id', '=', 'commissions.hotel_id')->whereIn('hotels.user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('commissions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
            //if hotel side logged in
            $commission = DB::table('commissions')->select('commissions.id as id', 'commissions.commission as commission', 'hotels.title as hotel_name', 'commissions.pbc as pbc', 'commissions.magicspree_fee as magicspree_fee')->leftjoin('hotels', 'hotels.id', '=', 'commissions.hotel_id')->where('hotels.user_id', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('commissions.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 3) {
            //if revenue manager logged in to the system
            $commission = DB::table('commissions')->select('commissions.id as id', 'commissions.commission as commission', 'hotels.title as hotel_name', 'commissions.pbc as pbc', 'commissions.magicspree_fee as magicspree_fee')->leftjoin('hotels', 'hotels.id', '=', 'commissions.hotel_id')->where('hotels.revenue_user', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%');
            })->orderBy('commissions.id', 'DESC')->paginate(10);
        }

        return view('commission.index', compact('commission', 'search'))->with('i', ($request->input('page', 1) - 1) * 10);
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

        return view('commission.create', compact('hotel_id'));
    }

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
                $hotel_user = Hotel::select('id', 'title')->where('user_id', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 3) {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id', 'title')->where('revenue_user', '=', $logged_in_user->id)->where('title', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            }

            return $hotel_user;
        }
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
            'commission.required' => 'Please enter commission details.',
            'tds.required' => 'Please enter TDS details.'
        ];
        $this->validate($request, [
            'hotel_id' => 'required|unique:commissions|not_in:0',
            'commission' => 'required',
            'tds' => 'required',
        ], $valid_message);

        $input = $request->all();
        $commission_store = Commission::create($input);
        $id = $commission_store->id;
        $linked_id = $input['hotel_id'];
        AuditLog::auditLogs($request, $id, $linked_id);
        $submit_type = $input['submit'];
        if ($submit_type == "submit") {
            return redirect()->route('commission.index')
                ->with('success', 'Commission created successfully');
        } else {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\BankController')->create();
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
        $commission = Commission::find($id);
        $hotel = Hotel::select('title')->where('id', '=', $commission->hotel_id)->first();


        return view('commission.show', compact('commission', 'hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commission = Commission::find($id);

        return view('commission.edit', compact('commission'));
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
            'commission.required' => 'Please enter commission details.',
            'tds.required' => 'Please enter TDS details.'
        ];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'commission' => 'required',
            'tds' => 'required',
        ], $valid_message);
        $input = $request->all();
        $commission = Commission::find($id);
        $commission->update($input);
        $linked_id = $input['hotel_id'];
        AuditLog::auditLogs($request, $id, $linked_id);

        $url = $input['url'];
        return redirect($url)
            ->with('success', 'Commission updated successfully');
        // return redirect()->route('commission.index')
        //                 ->with('success','Commission updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Commission::find($id)->delete();
        AuditLog::auditLogs($request, $id);
        return redirect()->back()
            ->with('success', 'Commission deleted successfully');
    }
}
