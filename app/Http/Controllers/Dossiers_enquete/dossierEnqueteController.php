<?php

namespace App\Http\Controllers\Dossiers_enquete;

use App\Http\Controllers\Controller;
use App\Models\dossierEnquete;
use App\Models\userHasPvs;
use Illuminate\Http\Request;

class dossierEnqueteController extends Controller
{
    public function index_pvs_enquete(){
        return   userHasPvs::with('user:id,nom','pvs:id,Numpvs')
                  ->select('id','userID','pvsID')
                  ->where('traitID',4)
                  ->get();
     }
    public function get_pvs_enquete(Request $request){
      return   userHasPvs::with('user:id,nom','pvs:id,Numpvs')
                  ->select('id','userID','pvsID')
                  ->where('traitID',5)// 5 : pvs enquete confirmer
                  ->where('pvsID',function ($query) {
                    global $request;
                    $query->select('id')
                          ->from('pvs')
                          ->where('Numpvs',$request->Numpvs);
                })->get();
    }

    public function storeDossier(Request $request){
        $newDossier = new dossierEnquete();

        $newDossier->NumDossier = $request->dossierEnquete['NumDossier'];
        $newDossier->type_dossierID = $request->dossierEnquete['type_dossierID'];
        $newDossier->chambre_enquete = $request->dossierEnquete['chambre_enquete'];
        $newDossier->juge_enqueteID = $request->dossierEnquete['juge_enqueteID'];
        $newDossier->usrhaspvsID = $request->dossierEnquete['usrhaspvsID'];
        $newDossier->lien = $request->dossierEnquete['lien'];

        $newDossier->save();
    }
}
