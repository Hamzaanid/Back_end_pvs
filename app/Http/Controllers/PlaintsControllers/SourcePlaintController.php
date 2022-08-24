<?php

namespace App\Http\Controllers\PlaintsControllers;

use App\Http\Controllers\Controller;
use App\Models\SourcePlaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SourcePlaintController extends Controller
{
    public function index()
    {
        return sourcePlaint::select('id','nom')->get();
    }

    public function store(Request $request)
    {
        sourcePlaint::create([
            "nom" => $request -> sourceplaint['nom'],
        ]);
    }


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
