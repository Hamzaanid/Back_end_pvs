<?php

namespace App\Http\Controllers\PvsControllers;

use App\Http\Controllers\Controller;
use App\Http\services\fichierdo;
use App\Models\plaint_has_fichier;
use App\Models\Plaints;
use App\Models\pvs;
use App\Models\pvs_has_fichier;
use App\Models\userHasPvs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class pvsReponseController extends Controller
{
    public function add_pvs_reponse(Request $request){
        $file =$request->file('file');
        $name = 'PDFTemp';
        try{
            $userhaspvs = userHasPvs::where("pvsID",$request->idpvs)->first();
            $path = Storage::putFileAs("public", $file, $name.'.pdf');
            $files=[storage_path('app/public/pvsPDF/'.$request->Numpvs.'.pdf'),storage_path('app/'.$path)];
            $pdf = new Fpdi();

            foreach ($files as $file) {
                $pageCount =  $pdf->setSourceFile($file);

             for ($i=0; $i < $pageCount; $i++) {
                    $pdf->AddPage();
                    $tplId = $pdf->importPage($i+1);
                    $pdf->useTemplate($tplId);
                }
            }

            $filename_path=storage_path('app/public/pvsPDF/'.$request->Numpvs.'.pdf');

               $pdf->Output($filename_path,'F');
             Storage::delete('public/PDFTemp.pdf');
             if($userhaspvs)  $userhaspvs->delete();

             return "public/pvsPDF/$request->Numpvs.pdf";
        }catch(Exception $e){
            Storage::delete('public/PDFTemp.pdf');
            return 0;
        }
    }

    public function pvsReponsePlaints(Request $request) {
        // return  pvsdo::create($request);
        $request->pv = json_decode($request->pv);

        $newpv = new pvs();
        $newpv->TypeSourcePvsID = $request->pv->TypeSourcePvsID;
        $newpv->typepvsID = $request->pv->typepvsID;
        $newpv->typePoliceJudicID = $request->pv->typePoliceJudicID;
        $newpv->sujetpvs = $request->pv->sujetpvs;
        $newpv->dateEnregPvs =  $request->pv->dateEnregPvs;;
        $newpv->Numpvs = $request->pv->Numpvs;
        $newpv->policeJudics = $request->pv->policeJudics;
        $plaint = Plaints::where("referencePlaints",$request->pv->referenceplaint)->first();
        if(!$plaint) return response()->json(["error"],501);

        $succes = 1;
            DB::transaction(function () use ($newpv,$succes,$plaint){
                global $request,$succes;
                $newpv->save();

                $file =$request->file('file');
                $name = $newpv->Numpvs;
                $path = Storage::putFileAs("public/pvsPDF", $file, $name.'.pdf');
                $files=[storage_path('app/public/plaintesPDF/'.$plaint->referencePlaints.'.pdf'),
                         storage_path('app/'.$path)];
                $pdf = new Fpdi();

                foreach ($files as $file) {
                    $pageCount =  $pdf->setSourceFile($file);

                 for ($i=0; $i < $pageCount; $i++) {
                        $pdf->AddPage();
                        $tplId = $pdf->importPage($i+1);
                        $pdf->useTemplate($tplId);
                    }
                }
                $filename_path=storage_path('app/public/pvsPDF/'.$newpv->Numpvs.'.pdf');

               $pdf->Output($filename_path,'F');

                    $insert['name'] = $name;
                    $insert['lien'] = $path;
                    $insert['pvsID'] = $newpv->id;
                    pvs_has_fichier::create($insert);
            });
            if($succes == -1){
                Storage::delete('public/pvsPDF/'.$newpv->Numpvs.'.pdf');
                return response()->json(["error"],501);
            }else{
                return response()->json(["succes"],200);
            }
    }
}
