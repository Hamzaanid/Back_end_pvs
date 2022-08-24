<?php

namespace App\Http\Controllers\PlaintsControllers;
use App\Http\Controllers\Controller;
use App\Models\TypePlaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypePlaintController extends Controller
{
    public function index()
    {
        return TypePlaint::select('id','nom')->get();
    }


    public function store(Request $request)
    {
        TypePlaint::create([
            "nom" => $request -> typeplaint['nom']
        ]);
    }


    public function update(Request $request,$id)
    {
        $typeplaint = TypePlaint::find($id);
        if($typeplaint){
            $typeplaint->update([
                "nom" => $request -> typeplaint['nom'],
            ]);
        }
    }

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
