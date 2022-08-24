<?php

namespace App\Http\Controllers\PvsControllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\TypePoliceJudic;

class TypePoliceJudicController extends Controller
{
    public function index()
    {
        return TypePoliceJudic::select('id','nom')->get();
    }


    public function store(Request $request)
    {
        TypePoliceJudic::create([
            'nom' => $request->typepolice['nom']
        ]);
    }

    public function update(Request $request, $id)
    {
        $typePolice = TypePoliceJudic::find($id);
        if($typePolice)
        {
            $typePolice->update([
                'nom' =>$request->typepolice['nom']
            ]);
            return $typePolice->id." updated";
        }
        return "Not found.";
    }

    public function destroy($id)
    {
        $typePolice = TypePoliceJudic::find($id);
        if($typePolice)
        {
            $typePolice->delete();
            return $typePolice->id." deleted";
        }
        return "Not found.";
    }
}
