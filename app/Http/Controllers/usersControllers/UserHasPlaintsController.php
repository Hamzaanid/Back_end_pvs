<?php

namespace App\Http\Controllers\UsersControllers;

use App\Http\Controllers\controller;
use App\Http\services\fichierdo;
use App\Http\services\usrhasplaintdo;
use App\Models\UserHasPlaints;
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
    {  //return usrhasplaintdo::create($request);
        $ids = $request->userhasplaint['plaintID'];
        if($ids == null || $request->userhasplaint['userID'] == null){
             return response()->json(['err'=>'connot read array'],500);
            }
        foreach($ids as $id_plaint){
        userHasPlaints::create([
            'userID'=>$request->userhasplaint['userID'],
            'plaintID' => $id_plaint,
            'traitID' => $request->userhasplaint['traitID'],
            'dateMission'=> $request->userhasplaint['dateMission'],
            'descision'=> $request->userhasplaint['descision']

        ]);
       }
    }


    public function updateTrait(Request $request, $id)
    {
        $usershasplaint = userHasPlaints::where('plaintID',$id)->first();
        $usershasplaint->update([
            'traitID' => $request->traitID
        ]);
    }

    public function getArchivePlaint(Request $request){
        $cher = $request->cherArch;
        return UserHasPlaints::with('user:id,nom',
                    'plaint:id,dateEnregPlaints,sujetPlaints,referencePlaints',
                    'plaint.hasfichier:plaintID,lien as lien')
                    ->join('plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
                    ->select('user_has_plaints.userID','plaints.id as plaintID', 'user_has_plaints.traitID','user_has_plaints.descision')
                    ->where('traitID',3)
                    ->whereBetween('plaints.dateEnregPlaints',[$cher['de'], $cher['a']])
                    ->paginate(20);
    }


    public function get_mes_plaintes(Request $request)
    { // return usrhasplaintdo::mesplaintes($request);
        return $plaints = DB::table('plaints')
            ->join('user_has_plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->join('plaint_has_fichiers', 'plaints.id','=', 'plaint_has_fichiers.plaintID')
            ->select( 'plaints.id', 'plaints.referencePlaints', 'plaints.dateEnregPlaints',
                       'user_has_plaints.dateMission','user_has_plaints.traitID','user_has_plaints.userID',
                       'user_has_plaints.descision',
                       'plaint_has_fichiers.lien')
                       ->where('user_has_plaints.userID',$request->user->id)
                       ->whereIn('user_has_plaints.traitID',[1,2])
                       ->get();
    }

    public function signer_plainte(Request $request, $id_plainte)
    {
        $descision = $request->userhasplaint['descision'];
        $lien = $request->userhasplaint['lien'];
        if ($descision != '') {
           // usrhasplaintdo::update($request, $id_plainte);
           $usershasplaint = userHasPlaints::where('plaintID',$id_plainte)->first();
           $usershasplaint->update([
               'traitID' => $request->userhasplaint['traitID'],
               'descision'=>$request->userhasplaint['descision']
           ]);
            return fichierdo::signerPDF($request, $descision, $lien);
        } else {
            return response()->json(["error" => "vide"], 501);
        }
    }

    public function update_descision_plainte(Request $request, $id_plainte)
    {
        $descision = $request->userhasplaint['descision'];
        $lien = $request->userhasplaint['lien'];
        $userID = $request->user['id'];

        if ($descision != '' && $userID != '') {
            $usershasplaint = userHasPlaints::where('plaintID',$id_plainte)->first();
            $usershasplaint->update([
            'traitID' => $request->userhasplaint['traitID'],
            'descision'=>$request->userhasplaint['descision']
        ]);
            return fichierdo::update_descision_pdf($userID, $descision, $lien);
        } else {
            return response()->json(["error" => "vide"], 501);
        }
    }

    public function destroy($id)
    { //usrhasplaintdo::delete($id);
        $userhasplaint = userHasPlaints::find($id);
        $userhasplaint->delete();
    }

    public function change_user(Request $request,$id_plaint){
        $itemupdate = UserHasPlaints::where('plaintID',$id_plaint)->first();
        $itemupdate->userID = $request->userID;

        DB::transaction(function () use ($itemupdate){
            $itemupdate->update();
        });

      }
    public function statistic_par_vice(Request $request ,$iduser){
        if($iduser == 0){
            $iduser = $request->user->id;// l'utilisateur qui envoi la requete
        }
       $traiter = UserHasPlaints::where('userID',$iduser)
                        ->whereIn('traitID',[2,3])
                        ->count();

       $enCours = UserHasPlaints::where('userID',$iduser)
                        ->where('traitID',1)
                        ->count();
            return response()->json(["plaintsenCours"=>$enCours,
                                      "plaintstraiter"=>$traiter],200);
    }

    public function affiche_plainte_statistic(Request $request)
    {
        return $pl = userHasPlaints::with('plaint:id,referencePlaints,dateEnregPlaints,sujetPlaints',
                 'plaint.hasfichier:plaintID,lien as lien')
            ->select('user_has_plaints.plaintID','user_has_plaints.traitID','user_has_plaints.dateMission',
                         'user_has_plaints.userID')
            ->where('userID', $request->userID)
            ->where('traitID', 1)
            ->get();
    }

    // les plaints a confirmer
    public function plaint_a_confirmer(Request $request,$numTrait)
    { // return usrhasplaintdo::mesplaintes($request);
        return $plaints = DB::table('plaints')
            ->join('user_has_plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->join('plaint_has_fichiers', 'plaints.id','=', 'plaint_has_fichiers.plaintID')
            ->join('users','users.id','=','user_has_plaints.userID')
            ->select( 'plaints.id', 'plaints.referencePlaints', 'plaints.dateEnregPlaints',
                       'user_has_plaints.dateMission','user_has_plaints.traitID','user_has_plaints.userID',
                       'user_has_plaints.descision','users.nom as nameUser',
                       'plaint_has_fichiers.lien')
                       ->where('user_has_plaints.traitID',$numTrait)
                       ->get();
    }
}
