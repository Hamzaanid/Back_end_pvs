<?php

namespace App\Http\Controllers\PlaintsControllers;

use App\Http\Controllers\Controller;
use App\Models\Plaints;
use App\Http\services\plaintsdo;
use App\Http\services\fichierdo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\userHasPlaints;


class PlaintsController extends Controller
{

    // return tous les plaint avec pagination
    public function index()
    {
        return $pl = Plaints::with('sourcePlaint', 'typePlaint')
            ->orderBy('created_at', 'desc')->paginate(15);
    }

    // chercher sur un plaint par reference
    public function getplaintByref(Request $request)
    {
        return $pl = userHasPlaints::with('plaint','plaint.hasfichier:plaintID,lien as lien')
            ->select('plaints.id as plaintID', 'user_has_plaints.traitID as traitID')
            ->rightJoin('plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->where('referencePlaints', $request->reference)
            ->get();
    }

    public function getplaintBydateEnrg(Request $request)
    {
        return plaintsdo::getplaintBydateEnrg($request);
    }

    public function getPlaints_of_user(Request $request)
    {
        return plaintsdo::getPlaints_of_user($request);
    }

    public function store(Request $request)
    {
        return   $id_plaint = plaintsdo::create($request);
    }

    public function PDF_plaint(Request $request, $idplaint)
    {
        $result = explode(".", $idplaint);
        fichierdo::store_pdf_plaints($request, (int)$result[0], $result[1]);
    }


    public function update(Request $request, $id)
    {
        plaintsdo::update($request, $id);
    }


    public function destroy($id)
    {
        //DB::table('plaints')->where('id',$id)->delete();
        return plaintsdo::delete($id);
    }

    public function statistique(Request $request)
    {
        return  plaintsdo::stat($request);
    }
}
