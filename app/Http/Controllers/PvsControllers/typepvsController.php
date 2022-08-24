<?php
namespace App\Http\Controllers\PvsControllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\typepvs;

class typepvsController extends Controller
{
    public function index()
    {
        return typepvs::select('id','nom')->get();
    }



    public function store(Request $request)
    {
        typepvs::create([
            'nom'=>$request->typepvs['nom']
        ]);
    }


    public function update(Request $request, $id)
    {
        $typepvs = typepvs::find($id);
        $typepvs->update([
            'nom'=>$request->typepvs['nom']
        ]);
    }

    public function destroy($id)
    {
        $typepvs = typepvs::find($id);
        $typepvs->delete();
    }
}
