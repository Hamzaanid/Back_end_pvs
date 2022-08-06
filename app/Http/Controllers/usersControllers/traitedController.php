<?php

namespace App\Http\Controllers\usersControllers;

use App\Http\Controllers\Controller;
use App\Models\traited;
use Illuminate\Http\Request;

class traitedController extends Controller
{
    public function index()
    {
        return traited::select('id','nom')->get();
    }


    public function store(Request $request)
    {
        traited::create([
            'nom' => $request->traited['nom']
        ]);
    }


    public function update(Request $request, $id)
    {
        $traite = traited::find($id);
        $traite->update([
            'nom'=>$request->traited['nom']
        ]);
    }

    
    public function destroy($id)
    {
        $traite = traited::find($id);
        $traite->delete();
    }
}
