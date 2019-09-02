<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\AddonType;
use Auth;
use App\Hotel;
use App\Admin;
use App\Addon;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('addon.index');
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
            elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 2)
            {
                //if hotel side local user is logged in
                $hotel_user = Hotel::select('id','title')->where('user_id','=',$logged_in_user->id)->orderBy('id','DESC')->limit(500)->get();
            }
            elseif($logged_in_user->user_role_define == 3)
            {
                //if hotel side revenue manager user is logged in
                $hotel_user = Hotel::select('id','title')->where('revenue_user','=',$logged_in_user->id)->orderBy('id','DESC')->limit(500)->get();
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

            return $hotel_user;
        }
    }

    public function user_fetch(Request $request)
    {
        //
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
    public function show_addon(Request $request)
    {
        
        $valid_message = [
            'hotel_id.not_in' => 'Please select valid hotel name.',
        ];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',],$valid_message);
        $input = $request->all();
        $hotel_id = $input['hotel_id'];
        $addon_type = AddonType::select('id','title','picture')->get();
        $hotel_name = Hotel::select('title')->where('id',$hotel_id)->first();
        $addon_value = Addon::where('hotel_id',$hotel_id)->get();
        $discount = array();
        for($i=0;$i<100;$i+=5)
        {
            $discount[$i] = $i.'%';
        }
        
        return view('addon.create',compact('addon_type','hotel_id','hotel_name','discount','addon_value'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function toggle_on(Request $request)
    {
        if($request->ajax()){
            $hotel_id=$request->hotel_id;
            $addontype_id=$request->addontype_id;
            $addon_id=Addon::select('id')->where('hotel_id','=',$hotel_id)->where('couponcategory_id','=',$addontype_id)->first();
            if($addon_id){
                $addon_create =Addon::where('id',$addon_id->id)->first();
            }
            else{
                $addon_create = new Addon;
            }
            $addon_create->hotel_id=$hotel_id;
            $addon_create->couponcategory_id=$addontype_id;
            $addon_create->discount=$request->dis_percent;
            $addon_create->dis_type=$request->dis_type_value;
            $addon_create->flat_dis=$request->dis_flat;
            $addon_create->cost_for_two=$request->avg_cost;
            $addon_create->status='1';
            $addon_create->save();
           
            return $addon_create;
        }
    }

    public function toggle_off(Request $request)
    {
        if($request->ajax()){
            $hotel_id=$request->hotel_id;
            $addontype_id=$request->addontype_id;
            $addon_id=Addon::select('id')->where('hotel_id','=',$hotel_id)->where('couponcategory_id','=',$addontype_id)->first();
            $addon_update =Addon::where('id',$addon_id->id)->first();
            $addon_update->status='0';
            $addon_update->dis_type=$request->dis_type_value;
            $addon_update->discount=$request->dis_percent;
            $addon_update->flat_dis=$request->dis_flat;
            $addon_update->cost_for_two=$request->avg_cost;
            $addon_update->save();
            
            return $addon_update;
        }
    }

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
