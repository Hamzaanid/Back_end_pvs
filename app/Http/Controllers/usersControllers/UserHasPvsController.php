<?php

namespace App\Http\Controllers\UsersControllers;
use App\Http\Controllers\controller;
use App\Http\services\fichierdo;
use App\Http\services\usrhaspvsdo;

use App\Models\userHasPvs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $cher = $request->cherArch;
        return userHasPvs::with('user:id,nom',
                    'pvs:id,dateEnregPvs,sujetpvs,Numpvs',
                    'pvs.hasfichier:pvsID,lien as lien')
                    ->join('pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
                    ->select('user_has_pvs.userID','pvs.id as pvsID', 'user_has_pvs.traitID','user_has_pvs.descision')
                    ->where('traitID',3)
                    ->whereBetween('pvs.dateEnregPvs',[$cher['de'],$cher['a']])
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

   public function change_user(Request $request,$id_pvs){
     $itemupdate = userHasPvs::where('pvsID',$id_pvs)->first();
     $itemupdate->userID = $request->userID;

     DB::transaction(function () use ($itemupdate){
         $itemupdate->update();
     });

   }

   public function statistic_par_vice(Request $request,$iduser){
    if($iduser == 0){
        $iduser = $request->user->id;// l'utilisateur qui envoi la requete
    }
    $traiter = userHasPvs::where('userID',$iduser)
                     ->whereIn('traitID',[2,3])
                     ->count();

    $enCours = userHasPvs::where('userID',$iduser)
                     ->where('traitID',1)
                     ->count();
         return response()->json(["pvsenCours"=>$enCours,
                                   "pvstraiter"=>$traiter],200);
     }

     public function affiche_plainte_statistic(Request $request)
     {
         return $pl = userHasPvs::with('pvs:id,Numpvs,dateEnregPvs,sujetpvs',
                  'pvs.hasfichier:pvsID,lien as lien')
             ->select('user_has_pvs.pvsID','user_has_pvs.traitID','user_has_pvs.dateMission',
                          'user_has_pvs.userID')
             ->where('userID', $request->userID)
             ->where('traitID', 1)
             ->get();
     }

}
