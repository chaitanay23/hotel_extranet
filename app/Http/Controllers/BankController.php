<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hotel;
use App\Bankdetail;
use App\Admin;
use Auth;
use DB;
use Session;
use AuditLog;

class BankController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:bank_detail_list');
         $this->middleware('permission:bank_detail_create', ['only' => ['create','store']]);
         $this->middleware('permission:bank_detail_edit', ['only' => ['edit','update']]);
         $this->middleware('permission:bank_detail_delete', ['only' => ['destroy']]);
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
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {
            $bank = DB::table('bankdetails')->select('bankdetails.id as id','hotels.title as hotel_name','bankdetails.account_no as account_no','bankdetails.account_holder as account_holder','bankdetails.ifsc_code as ifsc_code')->leftjoin('hotels','hotels.id','=','bankdetails.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('bankdetails.id','DESC')->paginate(10);
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $bank = DB::table('bankdetails')->select('bankdetails.id as id','hotels.title as hotel_name','bankdetails.account_no as account_no','bankdetails.account_holder as account_holder','bankdetails.ifsc_code as ifsc_code')->leftjoin('hotels','hotels.id','=','bankdetails.hotel_id')->whereIn('rm_user_id',$user_id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('bankdetails.id','DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            //if hotel side super user logged in
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $bank = DB::table('bankdetails')->select('bankdetails.id as id','hotels.title as hotel_name','bankdetails.account_no as account_no','bankdetails.account_holder as account_holder','bankdetails.ifsc_code as ifsc_code')->leftjoin('hotels','hotels.id','=','bankdetails.hotel_id')->whereIn('hotels.user_id',$user_id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('bankdetails.id','DESC')->paginate(10);
        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            //if hotel side local user logged in
            $bank = DB::table('bankdetails')->select('bankdetails.id as id','hotels.title as hotel_name','bankdetails.account_no as account_no','bankdetails.account_holder as account_holder','bankdetails.ifsc_code as ifsc_code')->leftjoin('hotels','hotels.id','=','bankdetails.hotel_id')->where('hotels.user_id','=',$logged_in_user->id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('bankdetails.id','DESC')->paginate(10);
        }
        elseif($logged_in_user->user_role_define == 3)
        {
            //if revenue manager user logged in 
            $bank = DB::table('bankdetails')->select('bankdetails.id as id','hotels.title as hotel_name','bankdetails.account_no as account_no','bankdetails.account_holder as account_holder','bankdetails.ifsc_code as ifsc_code')->leftjoin('hotels','hotels.id','=','bankdetails.hotel_id')->where('hotels.revenue_user','=',$logged_in_user->id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('bankdetails.id','DESC')->paginate(10);
        }
        
        return view('bank_detail.index',compact('bank','search'))->with('i', ($request->input('page', 1) - 1) * 10);
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
                //if hotel side revenue manager user is logged in
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
            $hotel_user = Hotel::select('id','title')->where('id','=',$hotel_id)->get();

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
                    //here we select only hotels user from admins table in db
                    //if magicspree side super user logged in
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
                    //if hotel side revenue manager user is logged in
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

    public function create()
    {
        $hotel_id = Session::get('hotel_id');
        if(!$hotel_id)
            $hotel_id = null;
        Session::forget('hotel_id');
        $bank_id = DB::table('banks')->pluck('id')->all();
        $bank_name = DB::table('banks')->pluck('name')->all();
        $bank_all = array_combine($bank_id, $bank_name);

        return view('bank_detail.create',compact('bank_all','hotel_id'));
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
            'account_no.required' => 'Please enter account number.',
            'account_holder.required' => 'Please enter account holder details.',
            'ifsc_code.required' => 'Please enter ifsc code of bank.',
            'bank_id.required' => 'Please select bank name.',
            'payment_id.required' => 'Please select payment option'];
        $this->validate($request, [
            'hotel_id' => 'required|unique:bankdetails|not_in:0',
            'user_id' => 'required|not_in:0',
            'account_no' => 'required',
            'account_holder' => 'required',
            'ifsc_code' => 'required',
            'bank_id' => 'required',
            'payment_id' => 'required',
        ],$valid_message);

        $input = $request->all();
        $bank_create = Bankdetail::create($input);

        $id=$bank_create->id;

        $linked_id=$input['hotel_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        $submit_type = $input['submit'];
        if($submit_type=="submit")
        {
            return redirect()->route('bank_detail.index')
                        ->with('success','Bank details created successfully');
        }
        else
        {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\DiscountMappingController')->create();
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
        $bank = Bankdetail::find($id);
        $hotel = Hotel::select('title')->where('id','=',$bank->hotel_id)->first();
        $bank_name = DB::table('banks')->where('id','=',$bank->bank_id)->first();

        return view('bank_detail.show',compact('bank','hotel','bank_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank = Bankdetail::find($id);
        $bank_id = DB::table('banks')->pluck('id')->all();
        $bank_name = DB::table('banks')->pluck('name')->all();
        $bank_all = array_combine($bank_id, $bank_name);

        return view('bank_detail.edit',compact('bank','bank_all'));
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
            'account_no.required' => 'Please enter account number.',
            'account_holder.required' => 'Please enter account holder details.',
            'ifsc_code.required' => 'Please enter ifsc code of bank.',
            'bank_id.required' => 'Please select bank name.',
            'payment_id.required' => 'Please select payment option'];
        $this->validate($request, [
            'hotel_id' => 'required|not_in:0',
            'user_id' => 'required|not_in:0',
            'account_no' => 'required',
            'account_holder' => 'required',
            'ifsc_code' => 'required',
            'bank_id' => 'required',
            'payment_id' => 'required',
        ],$valid_message);
        $input = $request->all();
        $bank = Bankdetail::find($id);
        $bank->update($input);
        $linked_id=$input['hotel_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        
        $url = $input['url'];
        return redirect($url)
                        ->with('success','Bank details updated successfully');
        // return redirect()->route('bank_detail.index')
        //                 ->with('success','Bank detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        Bankdetail::find($id)->delete();
        AuditLog::auditLogs($request,$id);
        
        return redirect()->route('bank_detail.index')
                        ->with('success','Bank detail deleted successfully');
    }
}
