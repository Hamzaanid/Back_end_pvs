<?php

namespace App\Http\Controllers\Dossiers_enquete;

use App\Http\Controllers\Controller;
use App\Http\services\DossierService;
use App\Models\dossierEnquete;
use App\Models\typeDossier;
use App\Models\userHasPvs;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dossierEnqueteController extends Controller
{
    public function index_juge(){
        return users::select("id","nom")
                    ->whereIn("idRole",function ($query) {
                        $query->select('id')
                              ->from('roles')
                              ->where("nom","j_enquête");
                    })->get();
     }

    public function index_type(){
       return typeDossier::select("id","nom")->get();
    }

    public function index_pvs_enquete(){ // pas utuliser et pas d'api
        return   userHasPvs::with('user:id,nom','pvs:id,Numpvs')
                  ->select('id','userID','pvsID')
                  ->where('traitID',4)
                  ->get();
     }

    public function get_pvs_enquete(Request $request){
      return   userHasPvs::with('user:id,nom','pvs:id,Numpvs','pvs.hasfichier:pvsID,lien')
                  ->select('id','userID','pvsID','traitID')
                  ->where('traitID','>=',5)// 5 : pvs enquete confirmer
                  ->where('pvsID',function ($query) {
                    global $request;
                    $query->select('id')
                          ->from('pvs')
                          ->where('Numpvs',$request->Numpvs);
                })->get();
    }

    public function all_pvs_enquete(Request $request){
        return   userHasPvs::with('user:id,nom','pvs:id,Numpvs','pvs.hasfichier:pvsID,lien')
                    ->select('id','userID','pvsID')
                    ->where('traitID',5)// 5 : pvs enquete confirmer
                    ->paginate(10);
      }
        ############# les dossiers d'enquete ############

    public function storeDossier(Request $request){
        $newDossier = new dossierEnquete();

        $pvsToDossier =userHasPvs::where('pvsID',$request->pvsID)
                     ->first();
        $pvsToDossier->traitID = 6; // apres ou 6 est un : dossier

        $newDossier->NumDossier = $request->NumDossier;
        $newDossier->dateEnreg = $request->dateEnreg;
        $newDossier->type_dossierID = $request->type_dossierID;
        $newDossier->chambre_enquete = $request->chambre_enquete;
        $newDossier->juge_enqueteID = $request->juge_enqueteID;
        $newDossier->pvsID = $request->pvsID;
        $newDossier->userID = $request->userID;
        $lien = DossierService::storeDocumentWithJoin($request);
        if($lien == 0){
            return response()->json(["error"=>"pdfdossier"],501);
        }
        $newDossier->lien = $lien;
        $succes = -1;
        DB::transaction(function () use ($newDossier,$succes,$pvsToDossier){
            global $succes;

            $newDossier->save();
            $pvsToDossier->save();
            $succes = 1;
            if($succes == -1){
                DB::rollBack();
            }
        });
    }

    public function paginateChambre1(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('chambre_enquete',"الغرفة 1")
                         ->where('traiter',true)
                         ->orderBy('updated_at','desc')
                         ->paginate(10);
      }
      public function paginateChambre2(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('chambre_enquete',"الغرفة 2")
                         ->where('traiter',true)
                         ->orderBy('updated_at','desc')
                         ->paginate(10);
      }

           // cherche en chambre 1 et 2
      public function EnqueteChambreCherche(Request $request , $chambre){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('chambre_enquete',$chambre)
                         ->where('NumDossier',$request->NumDossier)
                         ->get();
      }

       // cherche global sur les dossier ###################
       public function chercheDossier(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('NumDossier',$request->NumDossier)
                         ->get();
      }

      public function paginateTraiter(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('traiter',true)
                         ->orderBy('updated_at','desc')
                         ->paginate(10);
      }

      // les dossier d'un juge

      public function dossiersJuge(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('juge_enqueteID',$request->user['id'])
                         ->where('NumDossier',$request->NumDossier)
                         ->get();
      }

      public function paginate_mes_fileJuge(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                        // ->where('traiter',false)
                         ->where('juge_enqueteID',$request->user['id'])
                         ->orderBy('updated_at','desc')
                         ->orderBy('traiter','desc')
                         ->paginate(10);
      }

      public function addDescisionEnquete(Request $request,$ND){
          $updatedDossier = dossierEnquete::where('NumDossier',$request->NumDossier)
                                    ->find($ND);

          DB::transaction(function() use ($updatedDossier,$request){
            $succes = -1;
            $lienDescision = DossierService::addDescisionpdf($request);
            $updatedDossier->lienDescision = $lienDescision;
            $updatedDossier->traiter = true;
            $updatedDossier->update();
            $succes = 1;
            if($succes == -1 || $lienDescision == -1 ){
                DB::rollBack();
                return response()->json(["error"=>"ll"],501);
            }
        });
      }

      ######### les pvsEnquete d'un vice
      public function dossiersParVice(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                         ->where('userID',$request->user['id'])
                         ->where('NumDossier',$request->NumDossier)
                         ->get();
      }

      public function paginate_mes_fileVice(Request $request){
        return dossierEnquete::with('user:id,nom','pvs:id,Numpvs','juge_enquete:id,nom')
                        // ->where('traiter',false)
                         ->where('userID',$request->user['id'])
                         ->orderBy('traiter','desc')
                         ->orderBy('updated_at','desc')
                         ->paginate(10);
      }
}
