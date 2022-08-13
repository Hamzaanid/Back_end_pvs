<?php

namespace App\Http\Controllers\UsersControllers;

use App\Http\Controllers\controller;
use App\Http\services\fichierdo;
use App\Http\services\usrhasplaintdo;
use App\Models\UserHasPlaints;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserHasPlaintsController extends Controller
{
    public function index(Request $request)
    {
        $de = $request->userhasplaint['de'];
        $a = $request->userhasplaint['a'];
        return UserHasPlaints::with(['plaint.sourcePlaint', 'user:id,nom'])
            ->select('id', 'userID', 'plaintID', 'traitID', 'dateMission')
            ->whereBetween('dateMission', [$de, $a])
            ->get();
    }


    public function store(Request $request)
    {
        return usrhasplaintdo::create($request);
    }


    public function updateTrait(Request $request, $id)
    {
        $usershasplaint = userHasPlaints::where('plaintID',$id)->first();
        $usershasplaint->update([
            'traitID' => $request->traitID
        ]);
    }

    public function getArchivePlaint(Request $request){
        return UserHasPlaints::with('user:id,nom',
                    'plaint:id,dateEnregPlaints,sujetPlaints,referencePlaints',
                    'plaint.hasfichier:plaintID,lien as lien')
                    ->join('plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
                    ->select('user_has_plaints.userID','plaints.id as plaintID', 'user_has_plaints.traitID')
                    ->where('traitID',3)
                    ->where('plaints.referencePlaints',$request->referenece)
                    ->get();
    }


    public function get_mes_plaintes(Request $request)
    {
        return usrhasplaintdo::mesplaintes($request);
    }

    public function signer_plainte(Request $request, $id_plainte)
    {
        $descision = $request->userhasplaint['descision'];
        $lien = $request->userhasplaint['lien'];
        if ($descision != '') {
            usrhasplaintdo::update($request, $id_plainte);
            return fichierdo::signerPDF($request, $descision, $lien);
        } else {
            return response()->json(["error" => "vide"], 501);
        }
    }

    public function update_descision_plainte(Request $request, $id_plainte)
    {
        $descision = $request->userhasplaint['descision'];
        $lien = $request->userhasplaint['lien'];
        $userID = $request->userhasplaint['userID'];

        if ($descision != '' && $userID != '') {
            usrhasplaintdo::update($request, $id_plainte);
            return fichierdo::update_descision_pdf($userID, $descision, $lien);
        } else {
            return response()->json(["error" => "vide"], 501);
        }
    }

    public function destroy($id)
    {
        usrhasplaintdo::delete($id);
    }
}
