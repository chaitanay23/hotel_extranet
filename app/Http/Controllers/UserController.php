<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Auth;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\HotelType;
use Session;
use AuditLog;
class UserController extends Controller

{

    function __construct()

    {
         $this->middleware('permission:user_list');
         $this->middleware('permission:user_create', ['only' => ['create','store']]);
         $this->middleware('permission:user_edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search_user(Request $request)
    {
        $search_string = $request->search_text;

        
        return $search_string;
    }

    public function index(Request $request)

    {
        $search = $request->get('search');
        $logged_in_user = Auth::user();
        //user_role_define is magicspree or hotel or rm or cm
        //user_level is super or local
        //check for logged in user is magicspree super user or local user if role define is 1 means magicspree and level is 1 means its super user
        if($logged_in_user->user_role_define == 1 and $logged_in_user->user_level == 1)
        {
            $data = Admin::where(function($search_query) use ($search){return $search_query->where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->orwhere('primary_email','like','%'.$search.'%');})->orderBy('id','DESC')->paginate(10);
        }
        elseif ($logged_in_user->user_role_define == 1 and $logged_in_user->user_level != 1)
        {
            $data = Admin::where(function($search_query) use ($search){return $search_query->where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->orwhere('primary_email','like','%'.$search.'%');})->where(function($query) use ($logged_in_user){return $query->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id);})->orderBy('id','DESC')->paginate(10);
        }
        //check for logged in user is from hotel or revenue manager if role define is not 1 means hotel or revenue manager and level is 1 means super user
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level == 1)
        {
            $data = Admin::where(function($search_query) use ($search){return $search_query->where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->orwhere('primary_email','like','%'.$search.'%');})->where(function($query) use ($logged_in_user){return $query->where('id','=',$logged_in_user->id)->orwhere('super_user_id','=',$logged_in_user->id);})->orderBy('id','DESC')->paginate(10); 
        }
        elseif($logged_in_user->user_role_define == 2 and $logged_in_user->user_level != 1)
        {
            $data = Admin::where(function($search_query) use ($search){return $search_query->where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->orwhere('primary_email','like','%'.$search.'%');})->where('id','=',$logged_in_user->id)->orderBy('id','DESC')->paginate(10);
        }    
        else
            $data = Admin::where(function($search_query) use ($search){return $search_query->where('name','like','%'.$search.'%')->orwhere('email','like','%'.$search.'%')->orwhere('primary_email','like','%'.$search.'%');})->where('id','=',$logged_in_user->id)->orderBy('id','DESC')->paginate(10);

        return view('users.index',compact('data','search'))
                    ->with('i', ($request->input('page', 1) - 1) * 10);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        
        return view('users.create',compact('roles'));

    }


    public function fetch(Request $request)
    {
        if($request->ajax()){
        $user_type = $request->type;
        
        //check user type is from magicspree
        if($user_type == 1)
        {
            $super_user = Admin::select('id','name')->where('user_role_define','=',$user_type)->where('status','=','1')->orderBy('id','DESC')->limit(500)->get();
        }
        else
            $super_user = Admin::select('id','name')->where('user_role_define','=',$user_type)->where('status','=','1')->where('user_level', '=', '1')->orderBy('id','DESC')->limit(500)->get();
        
        return $super_user;
        }
    }
    
    public function edit_fetch(Request $request)
    {
        if($request->ajax()){
            $user_type = $request->type;
            $super_user_id = $request->super_user_id;
            $super_user = Admin::select('id','name')->where('id','=',$super_user_id)->get();

            return $super_user;
        }
    }

    public function super_fetch(Request $request)
    {
        if($request->ajax()){
            $super_search = $request->search_super;
            $user_type = $request->type;
            if($user_type == 1)
            {
                $super_user_search = Admin::select('id','name')->where('user_role_define','=',$user_type)->where('name','like','%'.$super_search.'%')->get();
            }
            else
                $super_user_search = Admin::select('id','name')->where('user_role_define','=',$user_type)->where('user_level', '=', '1')->where('name','like','%'.$super_search.'%')->limit(50)->get();

            return $super_user_search;
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
        $valid_message =[
            'name.required' => 'Please enter username name of user',
            'email.required' => 'Please enter username of user',
            'email.unique' => 'This username is already registered',
            'primary_email.required' => 'Please enter primary email id of user',
            'primary_email.unique' => 'This email id is already registered',
            'password.same' => 'Password miss-match',
            'user_role_define.required' => 'Please select user type',
            'user_level.required' => 'Please select user level',
            'roles.required' => 'Please select user role',
            'mou.mimes' => 'Please select MOU file of valid extention',
            'mou.max' => 'MOU file is too large',
            ];
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required|same:confirm-password',
            'primary_email' => 'required|email',
            'user_role_define' => 'required',
            'user_level' => 'required',
            'roles' => 'required',
            'mou' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:10000',
        ],$valid_message);

        $input = $request->all();
        
        $super_user_id = $request->input('super_user_id');
        $super_name = Admin::select('name')->where('id','=',$super_user_id)->first();
        $input['super_user_name'] = $super_name['name'];       
        $input['password'] = Hash::make($input['password']);
        if($request->hasFile('profile_pic'))
        {
            $name = $request->file('profile_pic');
            $file_name = $input['name'].'profile_pic'.$name->getClientOriginalExtension();
            $file = $name->move(public_path().'/files/uploads',$file_name);
            $input['profile_pic'] = '/files/uploads/'.$file_name;
        }
        if ($request->hasFile('mou'))
        {
            $name = $request->file('mou'); 
            $file_name = $input['name'].'_hotel_mou.'.$name->getClientOriginalExtension();
            $file = $name->move(public_path().'/files/uploads',$file_name);
            $input['mou'] = '/files/uploads/'.$file_name;
        }
        $user = Admin::create($input);
        $id=$user->id;
        $linked_id=$input['super_user_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        $user->assignRole($request->input('roles'));
        $submit_type = $input['submit'];
        if($submit_type=="submit")
            return redirect()->route('users.index')
                        ->with('success','User created successfully');
        else
        {
            Session::put('id', $user->id);
            return app('App\Http\Controllers\HotelController')->create();
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
        $user = Admin::find($id);
        $super_id = $user->super_user_id;
        $super = DB::table('admins')->select('name','mou')->where('id','=',$super_id)->first();
        $chain_property = Admin::select('mou')->where('super_user_id','=',$user->id)->get();
        if($user->mou)
            $mou = $user->mou;
        elseif($super)
            $mou = $super->mou;
        else
            $mou = null;
        
        return view('users.show',compact('user','super','chain_property','mou'));

    }   


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)

    {
        $user = Admin::find($id);      
        $id = $user->id;
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $super_id = DB::table('admins')->select('super_user_id')->where('id','=',$id)->first();
        $super = json_decode(json_encode($super_id), True);
        
        return view('users.edit',compact('user','roles','userRole','super'));

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
        $valid_message =[
            'name.required' => 'Please enter username name of user',
            'email.required' => 'Please enter username of user',
            'primary_email.required' => 'Please enter primary email id of user',
            'password.same' => 'Password miss-match',
            'user_role_define.required' => 'Please select user type',
            'user_level.required' => 'Please select user level',
            'mou.mimes' => 'Please select MOU file of valid extention',
            'mou.max' => 'MOU file is too large',
            ];
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'same:confirm-password',
            'primary_email' => 'required|email',
            'user_role_define' => 'required',
            'user_level' => 'required',
            'mou' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:10000',
        ]);


        $input = $request->all();
        $super_user_id = $request->input('super_user_id');      
        $super_name = Admin::select('name')->where('id','=',$super_user_id)->first();
        $input['super_user_name'] = $super_name['name'];
        $input['super_user_id'] = $super_user_id;

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }
        if($request->hasFile('profile_pic'))
        {
            $name = $request->file('profile_pic');
            $file_name = $input['name'].'profile_pic'.$name->getClientOriginalExtension();
            $file = $name->move(public_path().'/files/uploads',$file_name);
            $input['profile_pic'] = '/files/uploads/'.$file_name;
        }
        if ($request->hasFile('mou'))
        {
            $name = $request->file('mou'); 
            $file_name = $input['name'].'_hotel_mou.'.$name->getClientOriginalExtension();
            $file = $name->move(public_path().'/files/uploads',$file_name);
            $input['mou'] = '/files/uploads/'.$file_name;
        }
        $user = Admin::find($id);
        $user->update($input);

        $linked_id=$input['super_user_id'];
        AuditLog::auditLogs($request,$id,$linked_id);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

	$url = $input['url'];
        return redirect($url)
                        ->with('success','User updated successfully');
        //return redirect()->route('users.index')
        //                ->with('success','User updated successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request,$id)

    {
        Admin::find($id)->delete();
        AuditLog::auditLogs($request,$id);
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');

    }

}
