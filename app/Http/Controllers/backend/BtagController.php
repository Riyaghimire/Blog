<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Btag\BtagCollection;
use App\Http\Resources\Btag\BtagResource;
use App\Models\Btag;
use Illuminate\Http\Request;

class BtagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BtagCollection(Btag::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
       $request->validate([
            'title' => 'required|unique:btags,title',
        ]);

        $btag = new Btag([
            'title' => $request->get('title'),
            
        ]);
        $btag->save();
        return response()->json([
            'success' => 'Tags saved!',
            "btag" => new BtagResource($btag)
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new BtagResource(Btag::FindOrFail($id));
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
        $request->validate([
            'title' => 'required|max:255|unique:btags,title,'.$id,
        ]);

       $btag = Btag::findOrFail($id);
       $btag->title =  $request->get('title');
       

       $btag->save();
        return response()->json([
           'success' => 'Updated !',
           "btag" => new BtagResource($btag)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $btag = Btag::findorFail($id);
        $btag->delete();
        return response()->json(['danger' => 'Removed']);
    }
}
