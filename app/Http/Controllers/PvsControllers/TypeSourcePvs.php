<?php

namespace App\Http\Controllers\PvsControllers;

use App\Http\Controllers\Controller;

use App\Models\typeSourcePvs as ModelsTypeSourcePvs;
use Illuminate\Http\Request;

class TypeSourcePvs extends Controller
{

    public function index()
    {
        return ModelsTypeSourcePvs::select('id','nom')->get();
    }

    public function store(Request $request)
    {
        ModelsTypeSourcePvs::create([
            'nom'=>$request->typesourcepvs['nom']
        ]);
    }


    public function update(Request $request, $id)
    {
        $typesource = ModelsTypeSourcePvs::find($id);
        $typesource->update([
            'nom'=>$request->typesourcepvs['nom']
        ]);
    }

    public function destroy($id)
    {
        $typesource = ModelsTypeSourcePvs::find($id);
        $typesource->delete();
    }
}
