<?php
namespace App\Http\services;

use App\Models\plaint_has_fichier;
use App\Models\Plaints;
use App\Models\userHasPlaints;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
class plaintsdo{

    public static function create($request)
        { //ajouter plaints
            $plaint= Plaints::create([
                "contreInconnu"=>$request->plaint['contreInconnu'],
                "TypePlaintID"=> $request->plaint['TypePlaintID'],
                "SourcePlaintID" => $request->plaint['SourcePlaintID'],
                "referencePlaints" => $request ->plaint['referencePlaints'],
                "datePlaints"=> $request->plaint['datePlaints'],
                "dateEnregPlaints"=> $request->plaint['dateEnregPlaints'],
                "dateFaits"=> $request ->plaint['dateFaits'],
                "EmplaceFaits" => $request -> plaint['EmplaceFaits'],
                "sujetPlaints" => $request -> plaint['sujetPlaints']
            ]);
            $data[0]= $plaint->id;
            $data[1]= $request ->plaint['referencePlaints'];
            return $data;

            //lie les data partie avec plaint
        }

        public static function update($request,$id)
        {
            $plaint = Plaints::find($id);
            if($plaint){
                $plaint->update([
                    "contreInconnu" => $request -> plaint['contreInconnu'],
                    "TypePlaintID" => $request -> plaint['TypePlaintID'],
                    "SourcePlaintID" => $request -> plaint['SourcePlaintID'],
                    "referencePlaints" => $request -> plaint['referencePlaints'],
                    "datePlaints" => $request -> plaint['datePlaints'],

                    //"dateEnregPlaints" => $request -> plaint['dateEnregPlaints'],

                    "dateFaits" => $request -> plaint['dateFaits'],
                    "EmplaceFaits" => $request -> plaint['EmplaceFaits'],
                    "sujetPlaints" => $request -> plaint['sujetPlaints']

                ]);
                return $plaint->id." has been updated";
            }
            return $plaint->id." not found.";
    }

    public static function delete($id){
        $userhasplaint = userHasPlaints::where('plaintID',$id)->first();
        $plaint = Plaints::find($id);
        $plainthasfiche =  plaint_has_fichier::where('plaintID',$id)->first();

            $lien='';
        if($userhasplaint){
            DB::transaction(function () use ($plaint,$plainthasfiche,$lien,$userhasplaint){
                if($plaint){
                    $userhasplaint->delete();
                    $lien = $plainthasfiche->lien;
                    $plainthasfiche->delete();
                    $plaint->delete();

                    Storage::delete($lien);
                }
            });

            return response()->json(["succes"=>"bien"],200);
        }else{


            DB::transaction(function () use ($plaint,$plainthasfiche,$lien){
                if($plaint){
                    $lien = $plainthasfiche->lien;
                    $plainthasfiche->delete();
                    $plaint->delete();

                    Storage::delete($lien);
                }
            });

            return response()->json(["succes"=>"bien"],200);
        }

    }

    public static function getplaintBydateEnrg( $request ){
        return $pl = Plaints::with('sourcePlaint','typePlaint','hasfichier:plaintID,lien')
                     ->whereNotIn('id',function ($query) {
                        $query->select('plaintID')
                            ->from('user_has_plaints');
                        })->whereBetween('dateEnregPlaints',[$request->dateEnrg['de'],$request->dateEnrg['a']])
                        ->get();
        }

        public static function getPlaints_of_user($request){
            // les plaint affecter a un user
            return $plaints = DB::table('plaints')
            ->join('user_has_plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->join('plaint_has_fichiers', 'plaints.id', '=', 'plaint_has_fichiers.plaintID')
            ->select('plaints.id', 'plaints.referencePlaints', 'plaints.dateEnregPlaints',
                       'user_has_plaints.dateMission','user_has_plaints.descision',
                       'user_has_plaints.traitID','user_has_plaints.userID',
                       'plaint_has_fichiers.lien')
                       ->where('user_has_plaints.userID',$request->userID)
                       ->whereIn('user_has_plaints.traitID',[1,2])
                       ->get();
           }

    //statistique
    public static function stat($request){
        $cher = $request->cher;

        $plaint_non_traiter = DB::table('plaints')
        ->whereBetween('dateEnregPlaints',[$cher['de'], $cher['a']]) //!!!
        ->whereNotIn('id',function ($query) {
                    global $request;
                 $query->select('plaintID')
                     ->from('user_has_plaints');
                    })
        ->count();

        $plaint_traiter = DB::table('plaints')
        ->whereBetween('dateEnregPlaints',[$cher['de'], $cher['a']]) //!!!
        ->whereIn('id',function ($query) {
                    global $request;
                 $query->select('plaintID')
                     ->from('user_has_plaints')
                     ->where('user_has_plaints.traitID',2)
                     ->Orwhere('user_has_plaints.traitID',3);
                    })
        ->count();

        $plaint_cours_traiter =DB::table('plaints')
        ->whereBetween('dateEnregPlaints',[$cher['de'], $cher['a']]) //!!!
        ->whereIn('id',function ($query) {
                    global $request;
                 $query->select('plaintID')
                     ->from('user_has_plaints')
                     ->where('user_has_plaints.traitID',1);
                    })
        ->count();

        return response()->json([
            'Traiter' => $plaint_traiter,
            'NonTraiter' => $plaint_non_traiter,
            'enCours' => $plaint_cours_traiter

        ],200);
    }
}



?>
