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

    public function update(Request $request,$id)
    {
        usrhaspvsdo::update($request,$id);
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
}
