<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Hotel;
use App\Admin;
use Auth;
use DB;
use Session;
use Carbon\Carbon;
use App\Inventory;
use AuditLog;

class RoomController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:room_list');
         $this->middleware('permission:room_create', ['only' => ['create','store']]);
         $this->middleware('permission:room_edit', ['only' => ['edit','update']]);
         $this->middleware('permission:room_delete', ['only' => ['destroy']]);
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
        //user_role_define is magicspree or hotel or rm or cm
        //user_level is super or local
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {
            $room = DB::table('hoteldetails')->select('hoteldetails.id as id', 'hoteldetails.picture as room_picture','hotels.title as hotel_name','hoteldetails.custom_category as room_name','hoteldetails.id as id','hoteldetails.status')->leftjoin('hotels', 'hotels.id', '=', 'hoteldetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%')->orwhere('custom_category','like','%'.$search.'%');})->orderBy('hoteldetails.id','DESC')->paginate(10);
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $room = DB::table('hoteldetails')->select('hoteldetails.id as id', 'hoteldetails.picture as room_picture','hotels.title as hotel_name','hoteldetails.custom_category as room_name','hoteldetails.id as id','hoteldetails.status')->leftjoin('hotels', 'hotels.id', '=', 'hoteldetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%')->orwhere('custom_category','like','%'.$search.'%');})->whereIn('hotels.rm_user_id',$user_id)->orderBy('hoteldetails.id','DESC')->paginate(10);   
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $room = DB::table('hoteldetails')->select('hoteldetails.id as id', 'hoteldetails.picture as room_picture','hotels.title as hotel_name','hoteldetails.custom_category as room_name','hoteldetails.id as id','hoteldetails.status')->leftjoin('hotels', 'hotels.id', '=', 'hoteldetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%')->orwhere('custom_category','like','%'.$search.'%');})->whereIn('hotels.user_id',$user_id)->orderBy('hoteldetails.id','DESC')->paginate(10);
        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            //if hotel local user logged in to the system 
            $room = DB::table('hoteldetails')->select('hoteldetails.id as id', 'hoteldetails.picture as room_picture','hotels.title as hotel_name','hoteldetails.custom_category as room_name','hoteldetails.id as id','hoteldetails.status')->leftjoin('hotels', 'hotels.id', '=', 'hoteldetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%')->orwhere('custom_category','like','%'.$search.'%');})->where('hotels.user_id','=',$logged_in_user->id)->orderBy('hoteldetails.id','DESC')->paginate(10);
        }
        elseif ($logged_in_user->user_role_define == 3)
        {
            //if revenue manager logged in to the system he will get list of rooms releted to him
            $room = DB::table('hoteldetails')->select('hoteldetails.id as id', 'hoteldetails.picture as room_picture','hotels.title as hotel_name','hoteldetails.custom_category as room_name','hoteldetails.id as id','hoteldetails.status')->leftjoin('hotels', 'hotels.id', '=', 'hoteldetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%')->orwhere('custom_category','like','%'.$search.'%');})->where('hotels.revenue_user','=',$logged_in_user->id)->orderBy('hoteldetails.id','DESC')->paginate(10);
        }

        /**
         * undocumented constant
         **/
        
        return view('room.index',compact('room','search'))
                    ->with('i', ($request->input('page', 1) - 1) * 10);
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
                $hotel_user = Hotel::select('id','title')->whereIn('user_id',$user_id)->limit(500)->get();
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
            
        return $hotel_user;
        }
    }

    public function edit_hotel_fetch(Request $request)
    {
        if($request->ajax())
        {
            $hotel_id = $request->hotel_user;
            $old_hotel_user = Hotel::select('id','title')->where('id','=',$hotel_id)->get();

            return $old_hotel_user;
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

            return $hotel_user;
        }
    }
    public function user_fetch(Request $request)
    {
        if($request->ajax()){
            $hotel_get = $request->hotel_get;
            //get user id from hotel table according to hotel select 
            $user_id = DB::table('hotels')->where('id','=',$hotel_get)->pluck('user_id');
            //here we select only hotels user from admins table in db
            $user = Admin::select('id','name')->where('user_role_define','=','2')->where('id','=',$user_id)->get();

            return $user;
        }
    }

    public function status_change_on(Request $request)
    {
        if($request->ajax()){
            $room_id = $request->room_id;
            $room_status = Room::where('id','=',$room_id)->update(['status'=>'1']);

            return $room_id;
        }
    }

    public function status_change_off(Request $request)
    {
        if($request->ajax()){
            $room_id = $request->room_id;
            $room_status = Room::where('id','=',$room_id)->update(['status'=>'0']);
            $inventory = Inventory::where('category_id','=',$room_id)->update(['cp_status' => '0','ep_status' => '0']);

            return $room_id;
        }
    }


    public function create()
    {
        $hotel_id = Session::get('hotel_id');
        if(!$hotel_id)
            $hotel_id = null;
        Session::forget('hotel_id');
        $facility_id = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('id')->all();
        $facility_name = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('name')->all();
        $facility = array_combine($facility_id, $facility_name);
        $inclusion_id = DB::table('roominclusions')->where('status','=','1')->pluck('id')->all();
        $inclusion_name = DB::table('roominclusions')->where('status','=','1')->pluck('name')->all();
        $inclusion = array_combine($inclusion_id, $inclusion_name);
        //as per request to keep default values in field
        $default_facility = [1,2,3,6,7,8,10,11,13,15,18,21];
        $default_inclusion = [1,3,4,5,6,7,8,9];

        return view('room.create',compact('hotel_id','facility','inclusion','default_facility','default_inclusion'));    
    }

    public function create_type(Request $request){
        $id = $request->id;
        $room = Room::find($id);
        $facility_id = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('id')->all();
        $facility_name = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('name')->all();
        $facility = array_combine($facility_id, $facility_name);
        $inclusion_id = DB::table('roominclusions')->where('status','=','1')->pluck('id')->all();
        $inclusion_name = DB::table('roominclusions')->where('status','=','1')->pluck('name')->all();
        $inclusion = array_combine($inclusion_id, $inclusion_name);
        //as per request to keep default values in field
        $default_facility = [1,2,3,6,7,8,10,11,13,15,18,21];
        $default_inclusion = [1,3,4,5,6,7,8,9];
        
        return view('room.create_type',compact('facility','inclusion','room','default_facility','default_inclusion'));
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
            'custom_category.required' => 'Please enter room name',
            'facility_data.required' => 'Please select hotel facilities',
            'inclusion_data.required' => 'Please select hotel inclusion',
            'max_guest_allow.required' => 'Please select maximum guest allowed',
            'max_child_allow.required' => 'Please select maximum child allowed',
            'max_adult_allow.required' => 'Please select maximum adult allowed',
            'max_infant_allow.required' => 'Please select maximum infants allowed',
            'picture.required' => 'Please uploads hotel facade picture',
            'picture.image' => 'Please select image file for facade picture',
            'picture.mimes' => 'Please select image file for facade picture',
            ];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'custom_category' => 'required',
            'facility_data' => 'required',
            'inclusion_data' => 'required',
            'max_guest_allow' => 'required',
            'max_adult_allow' => 'required',
            'max_child_allow' => 'required',
            'max_infant_allow' => 'required',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ],$valid_message);
        $input = $request->all();
        
        if ($request->hasFile('picture')){
            $name = $request->file('picture'); 
            $image_name = $input['hotel_id'].'_'.$input['custom_category'].'_room.'.$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['picture'] = '/images/uploads/'.$image_name;
            }

        $room_new = Room::create($input);
        $id=$room_new->id;
        $linked_id=$input['hotel_id'];

        AuditLog::auditLogs($request,$id,$linked_id);
        $room_id = $room_new->id;
        //inventory creation for 2 years 
        $start_date=Carbon::now();
        $end_date=Carbon::now()->addMonth(24);
        $inventory_input = [];
        while($start_date<=$end_date){
            array_push($inventory_input,$arrayName=array('hotel_id'=>$input['hotel_id'],'category_id'=>$room_id,'date'=>$start_date->toDateString()));
            $start_date=$start_date->addDays(1);
        }
        $inventory_new = Inventory::insert($inventory_input);

        foreach ($input['facility_data'] as $key => $value) {
            $room_facility = DB::insert('insert into roomcategoryfacilitie_hoteldetail (roomcategoryfacilitie_id,hoteldetail_id) values (?,?)',[$value,$room_id]);
            }
        foreach ($input['inclusion_data'] as $key => $value) {
            $room_facility = DB::insert('insert into roominclusion_hoteldetail (roominclusion_id,hoteldetail_id) values (?,?)',[$value,$room_id]);
            }
        $submit_type = $input['submit'];
        if($submit_type=="submit")
        {
        return redirect()->route('room.index')
                        ->with('success','Room created successfully');
        }
        else
        {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\ContactsController')->create();
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
        $room_show = Room::find($id);

        return view('room.show',compact('room_show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::find($id);
        $facility_id = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('id')->all();
        $facility_name = DB::table('roomcategoryfacilities')->where('status','=','1')->pluck('name')->all();
        $facility = array_combine($facility_id, $facility_name);
        $inclusion_id = DB::table('roominclusions')->where('status','=','1')->pluck('id')->all();
        $inclusion_name = DB::table('roominclusions')->where('status','=','1')->pluck('name')->all();
        $inclusion = array_combine($inclusion_id, $inclusion_name);
        $pre_facility = DB::table('roomcategoryfacilitie_hoteldetail')->where('hoteldetail_id','=',$id)->pluck('roomcategoryfacilitie_id')->all();
        $pre_inclusion = DB::table('roominclusion_hoteldetail')->where('hoteldetail_id','=',$id)->pluck('roominclusion_id')->all();

        return view('room.edit',compact('room','facility','inclusion','pre_facility','pre_inclusion'));
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
            'custom_category.required' => 'Please enter room name',
            'facility_data.required' => 'Please select hotel facilities',
            'inclusion_data.required' => 'Please select hotel inclusion',
            'max_guest_allow.required' => 'Please select maximum guest allowed',
            'max_child_allow.required' => 'Please select maximum child allowed',
            'max_adult_allow.required' => 'Please select maximum adult allowed',
            'max_infant_allow.required' => 'Please select maximum infants allowed',
            'picture.image' => 'Please select image file for facade picture',
            'picture.mimes' => 'Please select image file for facade picture',
            'picture.max' => 'Hotel facade picture is more than 2 MB',
            ];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'custom_category' => 'required',
            'facility_data' => 'required',
            'inclusion_data' => 'required',
            'max_adult_allow' => 'required',
            'max_child_allow' => 'required',
            'max_infant_allow' => 'required',
            'max_guest_allow' => 'required',
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        if ($request->hasFile('picture')){
            $name = $request->file('picture');
            $image_name = $input['hotel_id'].'_'.$input['custom_category'].'_room.'.$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['picture'] = '/images/uploads/'.$image_name;

            }
        $room_update = Room::find($id);
        $room_update->update($input);

        $linked_id=$input['hotel_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        if($request->input('facility_data') != '')
        {
            DB::table('roomcategoryfacilitie_hoteldetail')->where('hoteldetail_id','=',$room_update->id)->delete();
            foreach ($input['facility_data'] as $key => $value) {
                $facility_room = DB::insert('insert into roomcategoryfacilitie_hoteldetail (roomcategoryfacilitie_id,hoteldetail_id) value(?,?)',[$value,$room_update->id]);
            }
        }
        if($request->input('inclusion_data') != '')
        {
            DB::table('roominclusion_hoteldetail')->where('hoteldetail_id','=',$room_update->id)->delete();
            foreach ($input['inclusion_data'] as $key => $value) {
            $inclusion_room = DB::insert('insert into roominclusion_hoteldetail (roominclusion_id,hoteldetail_id) value(?,?)',[$value,$room_update->id]);
            }
        }
        $url = $input['url'];
        return redirect($url)
                        ->with('success','Room updated successfully');
        // return redirect()->route('room.index')
        //                 ->with('success','Room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        DB::table('roominclusion_hoteldetail')->where('hoteldetail_id','=',$id)->delete();
        DB::table('roomcategoryfacilitie_hoteldetail')->where('hoteldetail_id','=',$id)->delete();
        Room::find($id)->delete();
        AuditLog::auditLogs($request,$id);

        return redirect()->route('room.index')
                            ->with('success','Room deleted successfully');
    }
}
