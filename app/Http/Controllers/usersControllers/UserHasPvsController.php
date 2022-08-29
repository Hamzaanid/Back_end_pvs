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
       // return usrhaspvsdo::create($request);
       $ids = $request->userhaspvs['pvsID'];
       if($ids == null || $request->userhaspvs['userID'] == null){
            return response()->json(['err'=>'connot read array'],500);
           }
       foreach($ids as $id_pv){
           userHasPvs::create([
           'userID'=>$request->userhaspvs['userID'],
           'pvsID' => $id_pv,
           'traitID' => $request->userhaspvs['traitID'],
           'descision'=> $request->userhaspvs['descision'],
           'dateMission'=> $request->userhaspvs['dateMission']
       ]);
      }
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
                    ->select('user_has_pvs.userID','pvs.id as pvsID', 'user_has_pvs.traitID',
                               'user_has_pvs.descision')
                    ->where('traitID','>=',5)
                    ->Orwhere('traitID',3)
                    ->whereBetween('pvs.dateEnregPvs',[$cher['de'],$cher['a']])
                    ->paginate(20);
    }

    public function destroy($id)
    {
       // usrhaspvsdo::delete($id);
       $userhaspvs = userHasPvs::find($id);
       $userhaspvs->delete();
    }

    public function get_mes_pvs(Request $request){
       // return usrhaspvsdo::mespvs($request);
       return $pvs = DB::table('pvs')
       ->join('user_has_pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
       ->join('pvs_has_fichiers', 'pvs.id', '=', 'pvs_has_fichiers.pvsID')
       ->select( 'pvs.id', 'pvs.Numpvs', 'pvs.dateEnregPvs',
                  'user_has_pvs.dateMission','user_has_pvs.traitID','user_has_pvs.userID',
                  'user_has_pvs.descision',
                  'pvs_has_fichiers.lien')
                  ->where('user_has_pvs.userID',$request->user->id)
                  ->whereIn('user_has_pvs.traitID',[1,2,4]) // 4 Enquete pas valider
                  ->get();
    }

    public function signer_pvs(Request $request,$id_pvs){

        $descision = $request->userhaspvs['descision'];
        $lien = $request->userhaspvs['lien'];
        if($descision != ''){
           // usrhaspvsdo::update($request,$id_pvs);
            $userhaspvs = userHasPvs::where('pvsID',$id_pvs)->first();
            $userhaspvs->update([
            'traitID' => $request->userhaspvs['traitID'],
            'descision'=> $request->userhaspvs['descision']
            ]);
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
           // usrhaspvsdo::update($request,$id_pvs);
           $userhaspvs = userHasPvs::where('pvsID',$id_pvs)->first();
           $userhaspvs->update([
               'traitID' => $request->userhaspvs['traitID'],
               'descision'=> $request->userhaspvs['descision']
           ]);
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
                     ->where('traitID','>=',2)
                     ->count();

    $enCours = userHasPvs::where('userID',$iduser)
                     ->where('traitID',1)
                     ->count();
         return response()->json(["pvsenCours"=>$enCours,
                                   "pvstraiter"=>$traiter],200);
     }

     public function affiche_pvs_statistic(Request $request)
     {
         return $pl = userHasPvs::with('pvs:id,Numpvs,dateEnregPvs,sujetpvs',
                  'pvs.hasfichier:pvsID,lien as lien')
             ->select('user_has_pvs.pvsID','user_has_pvs.traitID','user_has_pvs.dateMission',
                          'user_has_pvs.userID')
             ->where('userID', $request->userID)
             ->where('traitID', 1)
             ->get();
     }

     public function pvs_enquete_vice(Request $request){
        return $pvs = DB::table('pvs')
         ->join('user_has_pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
         ->join('pvs_has_fichiers', 'pvs.id', '=', 'pvs_has_fichiers.pvsID')
         ->select( 'user_has_pvs.id as userhaspvsID', 'pvs.id', 'pvs.Numpvs', 'pvs.dateEnregPvs',
                    'user_has_pvs.dateMission','user_has_pvs.descision',
                    'user_has_pvs.traitID','user_has_pvs.userID',
                    'pvs_has_fichiers.lien')
                    ->where('user_has_pvs.userID',$request->userID)
                    ->where('user_has_pvs.traitID',4)
                    ->get();
     }

}
