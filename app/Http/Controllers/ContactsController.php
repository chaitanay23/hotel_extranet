<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Admin;
use Auth;
use App\Hotel;
use App\Contact;
use Session;
use AuditLog;

class ContactsController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:contact_detail_list');
         $this->middleware('permission:contact_detail_create', ['only' => ['create','store']]);
         $this->middleware('permission:contact_detail_edit', ['only' => ['edit','update']]);
         $this->middleware('permission:contact_detail_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $logged_in_user = Auth::user();
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {
            $contact=DB::table('contacts')->select('contacts.id as id','hotels.title as hotel_name','contacts.pemail as email','contacts.pmobile as mobile')->leftjoin('hotels','hotels.id','=','contacts.hotel_id')->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('contacts.id','DESC')->paginate(10);
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $contact=DB::table('contacts')->select('contacts.id as id','hotels.title as hotel_name','contacts.pemail as email','contacts.pmobile as mobile')->leftjoin('hotels','hotels.id','=','contacts.hotel_id')->whereIn('rm_user_id',$user_id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('contacts.id','DESC')->paginate(10);
        }
        //check for logged in user is from hotel super user or local user if super user from hotel is able to see all the hotels of its local and local is able to see its own hotel
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            //if hotel side super user logged in
            $user_id = Admin::select('id')->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id)->get()->toArray();
            $contact=DB::table('contacts')->select('contacts.id as id','hotels.title as hotel_name','contacts.pemail as email','contacts.pmobile as mobile')->leftjoin('hotels','hotels.id','=','contacts.hotel_id')->whereIn('hotels.user_id',$user_id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('contacts.id','DESC')->paginate(10);
        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            $contact=DB::table('contacts')->select('contacts.id as id','hotels.title as hotel_name','contacts.pemail as email','contacts.pmobile as mobile')->leftjoin('hotels','hotels.id','=','contacts.hotel_id')->where('hotels.user_id','=',$logged_in_user->id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('contacts.id','DESC')->paginate(10);
        }
        elseif($logged_in_user->user_role_define == 3)
        {
            $contact=DB::table('contacts')->select('contacts.id as id','hotels.title as hotel_name','contacts.pemail as email','contacts.pmobile as mobile')->leftjoin('hotels','hotels.id','=','contacts.hotel_id')->where('hotels.revenue_user','=',$logged_in_user->id)->where(function($query) use($search){return $query->where('hotels.title','like','%'.$search.'%');})->orderBy('contacts.id','DESC')->paginate(10);
        }
        

        return view('contact.index',compact('contact','search'))->with('i',($request->input('page',1)-1)*10);
    }

    /**
     * Display a listing of the resource.
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
                $hotel_id = $request->hotel_id;
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
                $user = Admin::where('user_role_define','=','2')->where('id','=',$user_id)->get();

                return $user;
            }
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotel_id = Session::get('hotel_id');
        if(!$hotel_id)
            $hotel_id = null;
        Session::forget('hotel_id');

        return view('contact.create',compact('hotel_id'));
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
            'pdesignation.required' => 'Please enter designation of primary contact',
            'pemail.required' => 'Please enter primary email address.',
            'pemail.email' => 'Please enter valid primary email address.',
            'pmobile.required' => 'Please enter primary mobile number.',
            ];
        $this->validate($request,[
            'hotel_id' => 'required|unique:contacts|not_in:0',
            'pdesignation' => 'required',
            'user_id' => 'required|not_in:0',
            'pemail' => 'required|email',
            'pmobile' => 'required',
        ],$valid_message);
        $input=$request->all();
        $info=Contact::create($input);

        $id=$info->id;
        $linked_id=$input['hotel_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        $submit_type = $input['submit'];
        if($submit_type=="submit")
        {
            return redirect()->route('contact.index')->with('success','Contact created sucessfully');
        }
        else
        {
            Session::put('hotel_id', $input['hotel_id']);
            return app('App\Http\Controllers\ChannelManagerController')->create();
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
        $contact=Contact::find($id);
        $hotel=DB::table('hotels')->select('title')->where('id','=',$contact->hotel_id)->first();

        return view('contact.show',compact('contact','hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
       $contact=Contact::find($id);
       $hotel=DB::table('hotels')->select('title')->where('id','=',$contact->hotel_id)->first();

       return view('contact.edit',compact('contact','hotel'));
       //$contact_id=DB::table('contacts')->pluck('id')->all();

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
            'pemail.required' => 'Please enter primary email address.',
            'pemail.email' => 'Please enter valid primary email address.',
            'pmobile.required' => 'Please enter primary mobile number.',            
            ];
        $this->validate($request,[
            'hotel_id' => 'required|not_in:0',
            'user_id' => 'required|not_in:0',
            'pemail' => 'required|email',
            'pmobile' => 'required',
        ],$valid_message);

        $input=$request->all();
        $contact_id=Contact::find($id);
        $contact_id->update($input);
        $linked_id=$input['hotel_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        
        $url = $input['url'];
        return redirect($url)
                        ->with('success','Contact updated successfully');
        //return redirect()->route('contact.index')->with('success','Contact updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        Contact::find($id)->delete();
        AuditLog::auditLogs($request,$id);
        return redirect()->route('contact.index')->with('success','Contact deleted sucessfully');
    }
}
