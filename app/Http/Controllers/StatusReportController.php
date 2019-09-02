<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Hotel;
use Carbon\Carbon;



class StatusReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $search = $request->get('search');
        $today = Carbon::now();    
        $today = $today->format('Y-m-d');

        
        $hotel_status = DB::table('hotels')
        ->select('hotels.id as id',
        'hotels.title as title',
        'hotels.own_property as own_property',
        'cities.name as city_name',
        'hoteldetails.hotel_id as room_status',
        'contacts.hotel_id as contacts_status',
        'commissions.hotel_id as commissions_status',
        'ratemappings.hotel_id as discount_map_hotel',
        'daily.hotel_id as inventory_status',
        'launches.status as launch_status')
        ->leftjoin('cities','hotels.location_id','=','cities.id')
        ->leftjoin('hoteldetails','hotels.id','=','hoteldetails.hotel_id')
        ->leftjoin('contacts','hotels.id','=','contacts.hotel_id')
        ->leftjoin('commissions','hotels.id','=','commissions.hotel_id')
        ->leftjoin('ratemappings','hotels.id','=','ratemappings.hotel_id')
        ->leftjoin('launches','hotels.id','=','launches.hotel_id')
        ->leftjoin('dailyinventories as daily',function($join) use($today){
            $join->on('hotels.id','=','daily.hotel_id')
            ->on('daily.date','=',DB::raw('curdate()'));
        })    
        ->where(function($query) use($search)
        {
            return $query->where('hotels.title','like','%'.$search.'%')
            ->orwhere('cities.name','like','%'.$search.'%');
        })
        ->distinct()
        ->orderBy('id','DESC')
        ->paginate(10);
        
        return view('status_report.index',compact('hotel_status','search'))
                    ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function status_change_on(Request $request){
        if($request->ajax()){
            $hotel_id = $request->hotel_id;
            $launch_data = DB::table('launches')->where('hotel_id','=',$hotel_id)->update(['status'=>'1']);

            return $launch_data;
        }
    }
    public function status_change_off(Request $request){
        if($request->ajax()){
            $hotel_id = $request->hotel_id;
            $launch_data = DB::table('launches')->where('hotel_id','=',$hotel_id)->update(['status'=>'0']);

            return $launch_data;
        }
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
