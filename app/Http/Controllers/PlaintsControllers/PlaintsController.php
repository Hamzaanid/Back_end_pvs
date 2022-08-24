<?php

namespace App\Http\Controllers\PlaintsControllers;

use App\Http\Controllers\Controller;
use App\Models\Plaints;
use App\Http\services\plaintsdo;
use App\Http\services\fichierdo;
use App\Models\plaint_has_fichier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\userHasPlaints;
use Illuminate\Support\Facades\Storage;

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
            ->select('plaints.id as plaintID', 'user_has_plaints.traitID','user_has_plaints.descision')
            ->rightJoin('plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->where('referencePlaints', $request->reference)
            ->get();
    }

    public function getplaintBydateEnrg(Request $request)
    {
       // return plaintsdo::getplaintBydateEnrg($request);
       return $pl = Plaints::with('sourcePlaint','typePlaint','hasfichier:plaintID,lien')
       ->whereNotIn('id',function ($query) {
          $query->select('plaintID')
              ->from('user_has_plaints');
          })->whereBetween('dateEnregPlaints',[$request->dateEnrg['de'],$request->dateEnrg['a']])
          ->paginate(10);
    }

    public function getPlaints_of_user(Request $request)
    {
      //  return plaintsdo::getPlaints_of_user($request);
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

    public function store(Request $request)
    {
       // return   $id_plaint = plaintsdo::create($request);
       $newPlaint = new Plaints();
       /* if($request->plaint['EmplaceFaits']){
            $newPlaint->EmplaceFaits = $request->EmplaceFaits;
        }*/
        $newPlaint->TypePlaintID = $request->TypePlaintID;
        $newPlaint->SourcePlaintID = $request->SourcePlaintID;
        $newPlaint->referencePlaints = $request->referencePlaints;
        $newPlaint->dateEnregPlaints = $request->dateEnregPlaints;
        $newPlaint->sujetPlaints = $request->sujetPlaints;

        $succes = 1;
        DB::transaction(function () use ($newPlaint,$succes){
            global $request,$succes;
            $newPlaint->save();
             $succes = fichierdo::store_pdf_plaints($request,$newPlaint->id,$newPlaint->referencePlaints);
        });
        if($succes == -1){
            return response()->json(["error"],501);
        }else{
            return response()->json(["succes"],200);
        }

    }

    public function PDF_plaint(Request $request, $idplaint)
    {
        $result = explode(".", $idplaint);
        fichierdo::store_pdf_plaints($request, (int)$result[0], $result[1]);
    }


    public function update(Request $request, $id)
    {
       // plaintsdo::update($request, $id);
       $plaint = Plaints::find($id);
       if($plaint){
           $plaint->update([
               "contreInconnu" => $request-> plaint['contreInconnu'],
               "TypePlaintID" => $request-> plaint['TypePlaintID'],
               "SourcePlaintID" => $request-> plaint['SourcePlaintID'],
              "referencePlaints" => $request-> plaint['referencePlaints'],
               "datePlaints" => $request-> plaint['datePlaints'],

              "dateEnregPlaints" => $request-> plaint['dateEnregPlaints'],
               "sujetPlaints" => $request-> plaint['sujetPlaints']

           ]);
           return $plaint->id." has been updated";
       }
       return $plaint->id." not found.";
    }


    public function destroy($id)
    {   //return plaintsdo::delete($id);
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

    public function statistique(Request $request)
    {
       // return  plaintsdo::stat($request);
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
        ->whereBetween('dateEnregPlaints',[$cher['de'], $cher['a']]) //
        ->whereIn('id',function ($query) {
                    global $request;
                 $query->select('plaintID')
                     ->from('user_has_plaints')
                     ->where('user_has_plaints.traitID',1);
                    })
        ->count();

        $total_plaints =DB::table('plaints')
        ->whereBetween('dateEnregPlaints',[$cher['de'], $cher['a']])
        ->count();

        return response()->json([
            'Traiter' => $plaint_traiter,
            'NonTraiter' => $plaint_non_traiter,
            'enCours' => $plaint_cours_traiter,
            'total' => $total_plaints

        ],200);
    }

}
