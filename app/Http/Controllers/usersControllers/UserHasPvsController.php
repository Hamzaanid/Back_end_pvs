<?php

namespace App\Http\Controllers\UsersControllers;
use App\Http\Controllers\controller;
use App\Http\services\fichierdo;
use App\Http\services\usrhaspvsdo;

use App\Models\userHasPvs;
use Illuminate\Http\Request;

class UserHasPvsController extends Controller
{
    public function index(Request $request)
    {
        $de = $request->userhaspvs['de'];
        $a = $request->userhaspvs['a'];
        return UserHasPvs::with(['pvs.typepvs','user:id,nom'])
                ->select('id','userID','pvsID','traitID','dateMission')
                ->whereBetween('dateMission',[$de,$a])
                ->get();
    }


    public function store(Request $request)
    {
        return usrhaspvsdo::create($request);
    }

    public function updateTrait(Request $request,$id)
    {
        $userhaspvs = userHasPvs::where('pvsID',$id)->first();
        $userhaspvs->update([
            'traitID' => $request->traitID
        ]);
    }

    public function getArchivePvs(Request $request){
        return userHasPvs::with('user:id,nom',
                    'pvs:id,dateEnregPvs,sujetpvs,Numpvs',
                    'pvs.hasfichier:pvsID,lien as lien')
                    ->join('pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
                    ->select('user_has_pvs.userID','pvs.id as pvsID', 'user_has_pvs.traitID')
                    ->where('traitID',3)
                    ->where('pvs.Numpvs',$request->Numpvs)
                    ->get();
    }

    public function destroy($id)
    {
        usrhaspvsdo::delete($id);
    }

    public function get_mes_pvs(Request $request){
        return usrhaspvsdo::mespvs($request);
    }

    public function signer_pvs(Request $request,$id_pvs){

        $descision = $request->userhaspvs['descision'];
        $lien = $request->userhaspvs['lien'];
        if($descision != ''){
            usrhaspvsdo::update($request,$id_pvs);
        return fichierdo::signerPDF($request,$descision,$lien);
        }else{
            return response()->json(["error"=>"vide"],501);
        }

  }

    public function update_descision_pvs(Request $request,$id_pvs){
        $descision = $request->userhaspvs['descision'];
        $lien = $request->userhaspvs['lien'];
        $userID = $request->userhaspvs['userID'];

        if($descision != '' && $userID != ''){
            usrhaspvsdo::update($request,$id_pvs);
            return fichierdo::update_descision_pdf($userID,$descision,$lien);
        }else{
            return response()->json(["error"=>"vide"],501);
        }
   }
}
