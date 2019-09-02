<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\HotelType;

class HotelTypeController extends Controller
{

    function __construct()

    {

         $this->middleware('permission:hotel_type_list');

         $this->middleware('permission:hotel_type_create', ['only' => ['create','store']]);

         $this->middleware('permission:hotel_type_edit', ['only' => ['edit','update']]);

         $this->middleware('permission:hotel_type_delete', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = HotelType::orderBy('id','DESC')->paginate(10);

        return view('hotel_type.index',compact('data'))

            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hotel_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'name' => 'required',
        ]);

        $input = $request->all();

        $type = HotelType::create($input);

        return redirect()->route('hotel_type.index')

                        ->with('success','User created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = HotelType::find($id);

        return view('hotel_type.edit',compact('type'));
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
       
        $this->validate($request, [

            'name' => 'required',
        ]);

        //Chetan code
        //$input = $request->all();

        //$type->update($input);
        //End code

        //Himanshu code
        
         $type = HotelType::find($id);

         $name = $request->name;
         $type->name= $name;
         $type->save();

        return redirect()->route('hotel_type.index')

                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HotelType::find($id)->delete();

        return redirect()->route('hotel_type.index')

                        ->with('success','User deleted successfully');
    }
}
