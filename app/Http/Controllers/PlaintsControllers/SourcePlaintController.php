<?php

namespace App\Http\Controllers\PlaintsControllers;

use App\Http\Controllers\Controller;
use App\Models\SourcePlaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SourcePlaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return sourcePlaint::select('id','nom')->get();
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
        sourcePlaint::create([
            "nom" => $request -> sourceplaint['nom'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SourcePlaint  $sourcePlaint
     * @return \Illuminate\Http\Response
     */
    public function show(SourcePlaint $sourcePlaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SourcePlaint  $sourcePlaint
     * @return \Illuminate\Http\Response
     */
    public function edit(SourcePlaint $sourcePlaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SourcePlaint  $sourcePlaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $sourceplaint = sourcePlaint::find($id);
        if($sourceplaint){
            $sourceplaint->update([
                "nom" => $request -> sourceplaint['nom'],
            ]);
            return $sourceplaint->id." updated.";
        }
        return $sourceplaint->id." not found.";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SourcePlaint  $sourcePlaint
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sourceplaint = sourcePlaint::find($id);
        if($id)
        {
            $sourceplaint ->delete();
            return $sourceplaint->id." deleted.";
        }
        return $sourceplaint->id." not found.";
    }
}
