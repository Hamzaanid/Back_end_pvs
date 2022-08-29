<?php

namespace App\Http\Controllers\PvsControllers;
use App\Http\services\pvsdo;
use App\Http\services\fichierdo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pvs;
use App\Models\pvs_has_fichier;
use App\Models\userHasPvs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PvsController extends Controller
{
    public function index()
    {
        return pvs::with('typepvs','typesourcepvs','typepolicejudiciaire')
                   ->paginate(10);
    }

    public function cherche_byNumpvs(Request $request){

                    return $pv = userHasPvs:: with('pvs','pvs.hasfichier:pvsID,lien','pvs.typepvs','user:id,nom')
                            ->select('pvs.id as pvsID','user_has_pvs.traitID','user_has_pvs.descision','userID')
                            ->rightJoin('pvs','pvs.id','=','user_has_pvs.pvsID')
                            ->where('Numpvs',$request->Numpvs)
                            ->get();
    }

    public function getpvsBydateEnrg(Request $request){
        return  pvs::with('typepvs','typesourcepvs','hasfichier:pvsID,lien')
                    ->whereBetween('dateEnregPvs',[$request->dateEnrg['de'],$request->dateEnrg['a']])
                    ->whereNotIn('id',function ($query) {
                        $query->select('pvsID')
                            ->from('user_has_pvs');
                        })->paginate(10);


    }

    public function getPvs_of_user(Request $request)
    { //return pvsdo::getPvs_of_user($request);
        return $pvs = DB::table('pvs')
         ->join('user_has_pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
         ->join('pvs_has_fichiers', 'pvs.id', '=', 'pvs_has_fichiers.pvsID')
         ->select( 'user_has_pvs.id as userhaspvsID', 'pvs.id', 'pvs.Numpvs', 'pvs.dateEnregPvs',
                    'user_has_pvs.dateMission','user_has_pvs.descision',
                    'user_has_pvs.traitID','user_has_pvs.userID',
                    'pvs_has_fichiers.lien')
                    ->where('user_has_pvs.userID',$request->userID)
                    ->whereIn('user_has_pvs.traitID',[1,2])
                    ->get();
    }


    public function store(Request $request) {
        // return  pvsdo::create($request);
        $request->pv = json_decode($request->pv, true);
        $newpv = new pvs();
        $newpv->TypeSourcePvsID = $request->pv['TypeSourcePvsID'];
        $newpv->typepvsID = $request->pv['typepvsID'];
        $newpv->typePoliceJudicID = $request->pv['typePoliceJudicID'];
        $newpv->sujetpvs = $request->pv['sujetpvs'];
        $newpv->dateEnregPvs = $request->pv['dateEnregPvs'];
        $newpv->Numpvs = $request->pv['Numpvs'];
        $newpv->policeJudics = $request->pv['policeJudics'];

        //$newpv->datePvs = $request->pv['datePvs'];
        //$newpv->contreInnconue = $request->pv['contreInnconue'];
        $succes = 1;
            DB::transaction(function () use ($newpv,$succes){
                global $request,$succes;
                $newpv->save();

                 $succes = fichierdo::store_pdf_pvs($request,$newpv->id,$newpv->Numpvs);
            });
            if($succes == -1){
                return response()->json(["error"],501);
            }else{
                return response()->json(["succes"],200);
            }
    }


    public function PDF_pvs(Request $request,$idpvs){
        $result = explode(".",$idpvs);
        fichierdo::store_pdf_pvs($request,(int)$result[0],$result[1]);
    }


    public function update(Request $request, $id) {
        //pvsdo::update($request,$id);
        $pv =pvs::find($id);
        $pv->update([
            'TypeSourcePvsID'=> $request->pv['TypeSourcePvsID'],
            'typepvsID'=> $request->pv['typepvsID'],
            'sujetpvs'=> $request->pv['sujetpvs'],
            'dateEnregPvs'=> $request->pv['dateEnregPvs'],
            'policeJudics'=> $request->pv['policeJudics'],
            'typePoliceJudicID' =>$request->pv['typePoliceJudicID'],
            'Numpvs' => $request->pv['Numpvs'],
            'datePvs' =>  $request->pv['datePvs'],
        ]);
    }


    public function destroy($id){
        //return pvsdo::delete($id);
        $pv = pvs::find($id);
        $pvhasfiche =  pvs_has_fichier::where('pvsID',$id)->first();
        $userhaspv = userHasPvs::where('pvsID',$id)->first();
        $lien='';
    if($userhaspv){
        DB::transaction(function () use ($pv,$pvhasfiche,$lien,$userhaspv){
            if($pv){
                $lien = $pvhasfiche->lien;
                $userhaspv->delete();
                $pvhasfiche->delete();
                $pv->delete();

                Storage::delete($lien);

            }
        });
        return response()->json(["succes"=>"bien"],200);
    }else{
        DB::transaction(function () use ($pv,$pvhasfiche,$lien){
            if($pv){
                $lien = $pvhasfiche->lien;
                $pvhasfiche->delete();
                $pv->delete();

                Storage::delete($lien);

            }
        });
    }
    }


    public function statistique(Request $request){
        //return  pvsdo::stat($request);
        $cher = $request->cher;

        $pvs_non_traiter = DB::table('pvs')
        ->whereBetween('dateEnregPvs',[$cher['de'], $cher['a']]) //!!!
        ->whereNotIn('id',function ($query) {
                    global $request;
                 $query->select('pvsID')
                     ->from('user_has_pvs');
                    })
        ->count();

        $pvs_traiter = DB::table('pvs')
        ->whereBetween('dateEnregPvs',[$cher['de'], $cher['a']]) //!!!
        ->whereIn('id',function ($query) {
                    global $request;
                 $query->select('pvsID')
                     ->from('user_has_pvs')
                     ->where('user_has_pvs.traitID','>=',2);
                    })
        ->count();

        $pvs_cours_traiter =DB::table('pvs')
        ->whereBetween('dateEnregPvs',[$cher['de'], $cher['a']]) //!!!
        ->whereIn('id',function ($query) {
                    global $request;
                 $query->select('pvsID')
                     ->from('user_has_pvs')
                     ->where('user_has_pvs.traitID',1);
                    })
        ->count();

        $total_pvs =DB::table('pvs')
        ->whereBetween('dateEnregPvs',[$cher['de'], $cher['a']])
        ->count();

        return response()->json([
            'Traiter' => $pvs_traiter,
            'NonTraiter' => $pvs_non_traiter,
            'enCours' => $pvs_cours_traiter,
            'total' => $total_pvs

        ],200);
    }
}
