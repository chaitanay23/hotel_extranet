<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hotel;
use App\Admin;
use App\HotelType;
use Auth;
use Session;
use Image;
use DB;
use Carbon\Carbon;
use AuditLog;

class HotelController extends Controller
{

    function __construct()

    {

        $this->middleware('permission:hotel_list');
        $this->middleware('permission:hotel_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:hotel_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hotel_delete', ['only' => ['destroy']]);
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
        if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->whereIn('rm_user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
            $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->whereIn('user_id', $user_id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);
        } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->where('user_id', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);
        }
        //if revenue manager logged in he will able to see hotels releted to that user
        elseif ($logged_in_user->user_role_define == 3) {
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->where('revenue_user', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);
        }
        //default case
        else
            $hotel = DB::table('hotels')->select('hotels.title as title', 'hotels.picture as picture', 'cities.name as city_name', 'areas.name as area_name', 'regions.name as region_name', 'hotels.id as id')->leftjoin('cities', 'hotels.location_id', '=', 'cities.id')->leftjoin('areas', 'hotels.area_id', '=', 'areas.id')->leftjoin('regions', 'regions.id', '=', 'hotels.region_id')->where('user_id', '=', $logged_in_user->id)->where(function ($query) use ($search) {
                return $query->where('hotels.title', 'like', '%' . $search . '%')->orwhere('cities.name', 'like', '%' . $search . '%')->orwhere('areas.name', 'like', '%' . $search . '%')->orwhere('regions.name', 'like', '%' . $search . '%');
            })->orderBy('hotels.id', 'DESC')->paginate(10);

        return view('hotel.index', compact('hotel', 'search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Session::get('id');
        if (!$user_id)
            $user_id = null;
        $hotel_type_id = HotelType::pluck('id')->all();
        $hotel_type_name = HotelType::pluck('name')->all();
        $hotel_type = array_combine($hotel_type_id, $hotel_type_name);
        $cities = DB::table('cities')->select('id', 'name')->where('status', '=', '1')->get();
        $facility = DB::table('attributes')->where('status', '=', '1')->pluck('id')->all();
        $facility_name = DB::table('attributes')->where('status', '=', '1')->pluck('value')->all();
        $fac = array_combine($facility, $facility_name);
        //default attributes as per request 
        $default_attributes = [1, 2, 13, 14, 15, 16, 17, 19, 21, 22, 25, 27, 28, 34, 36, 61, 77, 78, 81, 88, 89, 153, 154, 158];
        Session::forget('id');

        return view('hotel.create', compact('user_id', 'hotel_type', 'cities', 'fac', 'default_attributes'));
    }

    public function city_fetch(Request $request)
    {
        if ($request->ajax()) {
            $cities = DB::table('cities')->select('id', 'name')->where('status', '=', '1')->get();

            return $cities;
        }
    }


    public function area_fetch(Request $request)
    {
        if ($request->ajax()) {
            $regionsec = $request->region;
            $area = DB::table('areas')->select('id', 'name')->where('region_id', '=', $regionsec)->where('status', '=', '1')->get();

            return $area;
        }
    }
    public function region_fetch(Request $request)
    {
        if ($request->ajax()) {
            $citsec = $request->city_id;
            $region = DB::table('regions')->select('id', 'name')->where('city_id', '=', $citsec)->where('status1', '=', '1')->get();

            return $region;
        }
    }

    public function hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            //check logged in user and show data in dropdown accordingly
            $logged_in_user = Auth::user();
            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                //here we select only hotels local user only from admins table in db
                //if logged in user is super user of magicspree
                $local_user = Admin::select('id', 'name')->where('user_role_define', '=', '2')->where('user_level', '=', '2')->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if logged in user is local user of magicspree
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('user_id')->whereIn('rm_user_id', $user_id)->get()->toArray();
                $local_user = Admin::select('id', 'name')->whereIn('id', $hotel_user)->where('user_role_define', '=', '2')->orderBy('id', 'DESC')->limit(500)->where('user_level', '=', '2')->get();
            }
            //check for hotel users super and local
            elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if super user of hotel
                $local_user = Admin::select('id', 'name')->where('super_user_id', '=', $logged_in_user->id)->where('user_role_define', '=', '2')->where('user_level', '=', '2')->orderBy('id', 'DESC')->limit(500)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if local user of hotel
                $local_user = Admin::select('id', 'name')->where('id', '=', $logged_in_user->id)->where('user_role_define', '=', '2')->where('user_level', '=', '2')->orderBy('id', 'DESC')->limit(500)->get();
            }

            return $local_user;
        }
    }

    public function search_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_search = $request->search_hotel;
            $logged_in_user = Auth::user();
            //check logged in user and show data in dropdown accordingly
            if ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1) {
                //here we select only hotels local user only from admins table in db
                //if logged in user is super user of magicspree
                $local_user = Admin::select('id', 'name')->where('user_role_define', '=', '2')->where('user_level', '=', '2')->where('name', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1) {
                //if logged in user is local user of magicspree
                $user_id = Admin::select('id')->where('id', '=', $logged_in_user->id)->orwhere('super_user_id', '=', $logged_in_user->id)->get()->toArray();
                $hotel_user = Hotel::select('user_id')->whereIn('rm_user_id', $user_id)->get()->toArray();
                $local_user = Admin::select('id', 'name')->whereIn('id', $hotel_user)->where('user_role_define', '=', '2')->where('user_level', '=', '2')->where('name', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            }
            //check for hotel users super and local
            elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1) {
                //if super user of hotel
                $local_user = Admin::select('id', 'name')->where('super_user_id', '=', $logged_in_user->id)->where('user_role_define', '=', '2')->where('user_level', '=', '2')->where('name', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            } elseif ($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1) {
                //if local user of hotel
                $local_user = Admin::select('id', 'name')->where('id', '=', $logged_in_user->id)->where('user_role_define', '=', '2')->where('user_level', '=', '2')->where('name', 'like', '%' . $hotel_search . '%')->limit(50)->get();
            }

            return $local_user;
        }
    }

    public function edit_hotel_fetch(Request $request)
    {
        if ($request->ajax()) {
            $hotel_user = $request->hotel_user;
            $local_user =  Admin::select('id', 'name')->where('id', '=', $hotel_user)->get();

            return $local_user;
        }
    }

    public function rm_user_fetch(Request $request)
    {
        if ($request->ajax()) {
            //here we select only magicspree users from admins table in db
            $rm = Admin::select('id', 'name')->where('user_role_define', '=', '1')->get();

            return $rm;
        }
    }

    public function revenue_user_fetch(Request $request)
    {
        if ($request->ajax()) {
            $revenue = Admin::select('id', 'name')->where('user_role_define', '=', '3')->get();

            return $revenue;
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
            'user_id.not_in' => 'Please select user name.',
            'user_id.unique' => 'Details for selected user already created',
            'user_id.required' => 'Select valid branch name',
            'rm_user_id.not_in' => 'Please select relationship manager',
            'title.required' => 'Please enter hotel name',
            'hotel_type.required' => 'Please select hotel type',
            'rating.required' => 'Please select hotel rating',
            'check_in_time.required' => 'Please select check in time',
            'check_out_time.required' => 'Please select check out time',
            'hote_description.required' => 'Please enter hotel description',
            'location_id.not_in' => 'Please select valid city',
            'area_id.not_in' => 'Please select valid area',
            'region_id.not_in' => 'Please select valid region',
            'latitude.required' => 'Please enter latitude details',
            'longitude.required' => 'Please enter longitude details',
            'picture.required' => 'Please uploads hotel facade picture',
            'picture.image' => 'Please select image file for facade picture',
            'picture.mimes' => 'Please select image file for facade picture',
            'picture.max' => 'Hotel facade picture is more than 2 MB',
            'pictures.required' => 'Please uploads hotel image',
            'pictures.image' => 'Please select image file for hotel image',
            'pictures.mimes' => 'Please select image file for hotel image',
            'pictures.max' => 'Hotel images is more than 7 MB',
        ];
        $this->validate($request, [
            'user_id' => 'required|unique:hotels|not_in:0',
            'rm_user_id' => 'required|not_in:0',
            'title' => 'required',
            'hoteltype_id' => 'required',
            'rating' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'hote_description' => 'required',
            'location_id' => 'required|not_in:0',
            'area_id' => 'required|not_in:0',
            'region_id' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pictures' => 'required',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:7168',
        ], $valid_message);

        $input = $request->all();
        $no_room = $request->input('no_of_rooms');
        $input['no_of_rooms'] = (int) $no_room;
        $input['build_year'] = 1990;
        $displayName = $request->input('result');
        $hotel_user_id = $request->input('user_id');
        $submit_type = $input['submit'];
        if ($displayName == 'same') {

            $input['display_name'] = $input['title'];
        }

        //below code is used to set branch name of hotel as the user selected from the drop down
        $branch_name = DB::table('admins')->select('name')->where('id', '=', $hotel_user_id)->first();

        $input['hotel_branch_name'] = $branch_name->name;

        if ($request->hasFile('picture')) {

            $name = $request->file('picture');

            $image_name = $input['user_id'] . '_' . $input['title'] . '_front.' . $name->getClientOriginalExtension();

            // $img = Image::make($name->getRealPath());
            // $img->resize(300, 300,function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save();

            $img = $name->move(public_path() . '/images/uploads', $image_name);

            $input['picture'] = '/images/uploads/' . $image_name;
        }

        if ($request->hasFile('pictures')) {

            foreach ($request->file('pictures') as $key => $image) {
                $hotel_name = str_replace(",", "_", $input['title']);
                $image_name = $input['user_id'] . '_' . $hotel_name . '_inner_' . $key . '.' . $image->getClientOriginalExtension();

                $img = $image->move(public_path() . '/images/uploads', $image_name);

                $data[] = '/images/uploads/' . $image_name;
            }

            $input['pictures'] = json_encode($data);
            $input['pictures'] = rtrim(ltrim($input['pictures'], '["'), '"]');
            $input['pictures'] = explode('","', $input['pictures']);
            $input['pictures'] = str_replace("\\", '', $input['pictures']);
            $input['pictures'] = implode(',', $input['pictures']);
        }

        $hotel_new = Hotel::create($input);
        $id = $hotel_new->id;

        $linked_id = $input['user_id'];
        AuditLog::auditLogs($request, $id, $linked_id);

        $hotel_id = $hotel_new->id;
        $launch_table = DB::table('launches')->insert(['hotel_id' => $hotel_id]);
        foreach ($input['attribute_id'] as $key => $value) {
            $attrib_hotel = DB::insert('insert into attribute_hotel (attribute_id,hotel_id) values (?,?)', [$value, $hotel_id]);
        }


        if ($submit_type == "submit")
            return redirect()->route('hotel.index')
                ->with('success', 'Hotel created successfully');
        else {
            Session::put('hotel_id', $hotel_id);
            return app('App\Http\Controllers\RoomController')->create();
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
        $hotel_show = Hotel::find($id);
        $user = DB::table('admins')->select('name')->where('id', '=', $hotel_show->user_id)->first();
        $rm = DB::table('admins')->select('name')->where('id', '=', $hotel_show->rm_user_id)->first();
        $type = DB::table('hoteltypes')->select('name')->where('id', '=', $hotel_show->hoteltype_id)->first();
        $city = DB::table('cities')->select('name')->where('id', '=', $hotel_show->location_id)->first();
        $region_name = DB::table('regions')->select('name')->where('id', '=', $hotel_show->region_id)->first();
        $area_name = DB::table('areas')->select('name')->where('id', '=', $hotel_show->area_id)->first();
        $room_details = DB::table('hoteldetails')->select('hoteldetails.custom_category as custom_category', 'dailyinventories.rooms_cp as rooms_cp', 'dailyinventories.rooms_ep as rooms_ep')->leftjoin('dailyinventories', 'dailyinventories.category_id', '=', 'hoteldetails.id')->where('hoteldetails.hotel_id', '=', $hotel_show->id)->where('dailyinventories.date', '=', Carbon::now()->format('Y-m-d'))->get();

        return view('hotel.show', compact('hotel_show', 'user', 'rm', 'type', 'city', 'region_name', 'area_name', 'room_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hotel = Hotel::find($id);
        $hotel_type_id = HotelType::pluck('id')->all();
        $hotel_type_name = HotelType::pluck('name')->all();
        $hotel_type = array_combine($hotel_type_id, $hotel_type_name);
        $cities = DB::table('cities')->select('id', 'name')->where('status', '=', '1')->where('name', '!=', '')->get();
        $facility = DB::table('attributes')->where('status', '=', '1')->pluck('id')->all();
        $facility_name = DB::table('attributes')->where('status', '=', '1')->pluck('value')->all();
        $fac = array_combine($facility, $facility_name);
        $pre_facility = DB::table('attribute_hotel')->where('hotel_id', '=', $id)->pluck('attribute_id')->all();
        $rm = Admin::select('id', 'name')->where('user_role_define', '=', '1')->pluck('name', 'id')->all();
        $revenue = Admin::select('id', 'name')->where('user_role_define', '=', '3')->pluck('name', 'id')->all();
        array_push($rm, [0 => "Select relationship manager"], [null => "Select relationship manager"]);
        array_push($revenue, [0 => "Select revenue manager"], [null => "Select revenue manager"]);

        $hotel_revenue =  in_array($hotel->revenue_user, array_keys($revenue)) ? $hotel->revenue_user : null;
        $hotel_rm = in_array($hotel->rm_user_id, array_keys($rm)) ? $hotel->rm_user_id : null;

        if ($hotel_revenue == null) {
            Hotel::where('id', $id)->update(['revenue_user' => null]);
        }
        if ($hotel_rm == null) {
            Hotel::where('id', $id)->update(['rm_user_id' => null]);
        }

        $city_id = DB::table('cities')->where('status', '=', '1')->where('name', '!=', '')->pluck('id')->all();
        $city_name = DB::table('cities')->where('status', '=', '1')->where('name', '!=', '')->pluck('name')->all();
        $city_list = array_combine($city_id, $city_name);

        return view('hotel.edit', compact('hotel', 'hotel_type', 'cities', 'city_list', 'fac', 'pre_facility', 'rm', 'revenue'));
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
            'user_id.not_in' => 'Please select user name.',
            'user_id.required' => 'Select valid branch name',
            'rm_user_id.not_in' => 'Please select relationship manager',
            'title.required' => 'Please enter hotel name',
            'hotel_type.required' => 'Please select hotel type',
            'rating.required' => 'Please select hotel rating',
            'check_in_time.required' => 'Please select check in time',
            'check_out_time.required' => 'Please select check out time',
            'hote_description.required' => 'Please enter hotel description',
            'location_id.not_in' => 'Please select valid city',
            'area_id.not_in' => 'Please select valid area',
            'region_id.not_in' => 'Please select valid region',
            'latitude.required' => 'Please enter latitude details',
            'longitude.required' => 'Please enter longitude details',
            'picture.image' => 'Please select image file for facade picture',
            'picture.mimes' => 'Please select image file for facade picture',
            'picture.max' => 'Hotel facade picture is more than 2 MB',
            'pictures.image' => 'Please select image file for hotel image',
            'pictures.mimes' => 'Please select image file for hotel image',
            'pictures.max' => 'Hotel images is more than 7 MB',
        ];
        $this->validate($request, [
            'user_id' => 'required| not_in:0',
            'title' => 'required',
            'rm_user_id' => 'required|not_in:0',
            'hoteltype_id' => 'required',
            'rating' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'hote_description' => 'required',
            'location_id' => 'required|not_in:0',
            'area_id' => 'required|not_in:0',
            'region_id' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:7168',
        ], $valid_message);

        $input = $request->all();

        $no_room = $request->input('no_of_rooms');

        $input['no_of_rooms'] = (int) $no_room;

        $input['build_year'] = 1990;

        $displayName = $request->input('result');

        $hotel_user_id = $request->input('user_id');

        if ($displayName == 'same') {

            $input['display_name'] = $input['title'];
        }
        //below code is used to set branch name of hotel as the user selected from the drop down
        $branch_name = DB::table('admins')->select('name')->where('id', '=', $hotel_user_id)->first();

        $input['hotel_branch_name'] = $branch_name->name;

        if ($request->hasFile('picture')) {

            $name = $request->file('picture');

            $image_name = $input['user_id'] . '_' . $input['title'] . '_front.' . $name->getClientOriginalExtension();

            $img = $name->move(public_path() . '/images/uploads', $image_name);

            $input['picture'] = '/images/uploads/' . $image_name;
        }

        if ($request->hasFile('pictures')) {

            foreach ($request->file('pictures') as $key => $image) {
                $hotel_name = str_replace(",", "_", $input['title']);
                $image_name = $input['user_id'] . '_' . $hotel_name . '_inner_' . $key . '.' . $image->getClientOriginalExtension();
                $img = $image->move(public_path() . '/images/uploads', $image_name);
                $data[] = '/images/uploads/' . $image_name;
            }

            $input['pictures'] = json_encode($data);
            $input['pictures'] = rtrim(ltrim($input['pictures'], '["'), '"]');
            $input['pictures'] = explode('","', $input['pictures']);
            $input['pictures'] = str_replace("\\", '', $input['pictures']);
            $input['pictures'] = implode(',', $input['pictures']);
        }
        //dd($input);
        $hotel_update = Hotel::find($id);

        $hotel_update->update($input);
        $linked_id = $input['user_id'];
        AuditLog::auditLogs($request, $id, $linked_id);

        $hotel_update_id = $hotel_update->id;

        if ($request->input('attribute_id') != '') {

            //print_r($input['attribute_id']);die();

            DB::table('attribute_hotel')->where('hotel_id', '=', $hotel_update_id)->delete();

            foreach ($input['attribute_id'] as $key => $value) {

                $attrib_hotel = DB::insert('insert into attribute_hotel (attribute_id,hotel_id) values (?,?)', [$value, $hotel_update_id]);
            }
        }

        $url = $input['url'];
        return redirect($url)
            ->with('success', 'Hotel updated successfully');
        //return redirect()->route('hotel.index')

        //              ->with('success','Hotel updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::table('attribute_hotel')->where('hotel_id', '=', $id)->delete();
        Hotel::find($id)->delete();
        AuditLog::auditLogs($request, $id);

        return redirect()->back()
            ->with('success', 'Hotel deleted successfully');
    }
}
