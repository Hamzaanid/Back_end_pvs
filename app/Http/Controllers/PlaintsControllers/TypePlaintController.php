<?php

namespace App\Http\Controllers\PlaintsControllers;
use App\Http\Controllers\Controller;
use App\Models\TypePlaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypePlaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TypePlaint::select('id','nom')->get();
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
        TypePlaint::create([
            "nom" => $request -> typeplaint['nom']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypePlaint  $typePlaint
     * @return \Illuminate\Http\Response
     */
    public function show(TypePlaint $typePlaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TypePlaint  $typePlaint
     * @return \Illuminate\Http\Response
     */
    public function edit(TypePlaint $typePlaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypePlaint  $typePlaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $typeplaint = TypePlaint::find($id);
        if($typeplaint){
            $typeplaint->update([
                "nom" => $request -> typeplaint['nom'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypePlaint  $typePlaint
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeplaint = TypePlaint::find($id);
        if($typeplaint)
        {
            $typeplaint->delete();
        return $typeplaint->id." deleted";
        }
        return $typeplaint->id." not found.";
    }
    }
