<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddonType;

class AddonTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $addon_type = AddonType::orderBy('id','DESC')->paginate(10);
        
        return view('addon_type.index',compact('addon_type'))
                ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addon_type.create');
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
            'title' => 'required',
            'amount' => 'required',
            'tagline' => 'required',
            'list_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $input = $request->all();
        
        if ($request->hasFile('picture')){
            $name = $request->file('picture'); 
            $image_name = $input['title'].$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['picture'] = '/images/uploads/'.$image_name;
            }

        if ($request->hasFile('list_img')){
            $name = $request->file('list_img'); 
            $image_name = $input['title'].'_1.'.$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['list_img'] = '/images/uploads/'.$image_name;
            }

        $addon_type = AddonType::create($input);

        return redirect()->route('addon_type.index')
                        ->with('success','Addon type created successfully');
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
        $addon_type = AddonType::find($id);

        return view('addon_type.edit',compact('addon_type'));
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
            'title' => 'required',
            'amount' => 'required',
            'tagline' => 'required',
            'list_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        $input = array_filter($input);
        if ($request->hasFile('picture')){
            $name = $request->file('picture'); 
            $title = str_replace('/','_',$input['title']);
            $image_name = $title.'.'.$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['picture'] = '/images/uploads/'.$image_name;
            }

        if ($request->hasFile('list_img')){
            $name = $request->file('list_img'); 
            $title = str_replace('/','_',$input['title']);
            $image_name = $title.'_1.'.$name->getClientOriginalExtension();
            $img = $name->move(public_path().'/images/uploads',$image_name);
            $input['list_img'] = '/images/uploads/'.$image_name;
            }
        $addon_id = AddonType::find($id);
        $addon_id->update($input);

        return redirect()->route('addon_type.index')
                        ->with('success','Addon type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AddonType::find($id)->delete();
        
        return redirect()->route('addon_type.index')
                        ->with('success','Addon type deleted successfully');
    }
}
