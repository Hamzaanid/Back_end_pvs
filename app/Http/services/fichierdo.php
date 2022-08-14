<?php
namespace App\Http\services;

use App\Models\pvs_has_fichier;
use App\Models\plaint_has_fichier;
//use Barryvdh\DomPDF\Facade\Pdf;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Dompdf\Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use setasign\Fpdi\Fpdi;

class fichierdo{

    public static function fileDownload($request){

        return response()->download(storage_path('app/'.$request->lien));
    }

    public static function image_signature($request,$iduser){
        $file =$request->file('img');
        $path = Storage::putFileAs("public/img_signature",$file,'user'.$iduser.'.jpeg');
    }

    public static function store_pdf_pvs($request,$idpvs,$Numpvs)
    {
        /*$rules = ['file' => 'required', 'file.*' => 'mimes:pdf'];
        $validator = $request->validate($rules);

        if($validator->fails()){
            return response()->json(["type"=>"format"],503);
        }*/

        $file =$request->file('file');
         $name = $Numpvs;

         $path = Storage::putFileAs("public/pvsPDF", $file, $name.'.pdf');

             $insert['name'] = $name;
             $insert['lien'] = $path;
             $insert['pvsID'] = $idpvs;

             pvs_has_fichier::create($insert);

   }

   public static function store_pdf_plaints($request,$idplaint,$reference)
    {
       /* $rules = ['file' => 'required', 'file.*' => 'mimes:pdf'];
        $validator = $request->validate($rules);

        if($validator->fails()){
            return response()->json(["type"=>"format"],503);
        }*/

        $name = $reference;
         $file =$request->file('file');

         $path = Storage::putFileAs("public/plaintesPDF", $file, $name.'.pdf');

             $insert['name'] = $name;
             $insert['lien'] = $path;
             $insert['plaintID'] = $idplaint;

             plaint_has_fichier::create($insert);

   }


  public static function signerPDF($request,$descision,$lien){
    //
    $succes_generate_pdf1 = 0;
    try{
        $data = [
                'descision' => $descision,
                'id'=> $request->user->id,
                'date' => date('m/d/Y')
            ];

            $pdf = PDF::loadView('user1', $data);
            Storage::disk('img_signature')->put('desc_sign.pdf', $pdf->output());
            $succes_generate_pdf1 = 1;
    }catch(\Exception $e){
        Storage::delete('public/img_signature/desc_sign.pdf');
        return response()->json(["erreur"=>"generate "],500);
    }

    if($succes_generate_pdf1 == 1){
        $files = [storage_path('app/'.$lien), storage_path('app/public/img_signature/desc_sign.pdf')];
        $pdf = new Fpdi();

        foreach ($files as $file) {
            $pageCount =  $pdf->setSourceFile($file);

         for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
        }
        $filename_path=storage_path('app/'.$lien);
         $pdf->Output($filename_path,'F');

         Storage::delete('public/img_signature/desc_sign.pdf');
        return response()->json(["succes"=>"bien"],200);
    }else{
        Storage::delete('public/img_signature/desc_sign.pdf');
        return response()->json(["erreur"=>"generate "],500);
    }
  }


  public static function update_descision_pdf($userID,$descision,$lien){
       //couper la dernier page
    $pdf = new Fpdi();
    $pageCount =  $pdf->setSourceFile(storage_path('app/'.$lien));//nombre de page

         for ($i=0; $i < $pageCount-1; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
            $filename_path=storage_path('app/'.$lien);
            $pdf->Output($filename_path,'F');

      // generer new PAGE (pdf) de signature
            $succes_generate_pdf1 = 0;
            try{
                $data = [
                        'descision' => $descision,
                        'id'=> $userID,
                        'date' => date('m/d/Y')
                    ];

                    $pdf = PDF::loadView('user1', $data);
                    Storage::disk('img_signature')->put('desc_sign.pdf', $pdf->output());
                    $succes_generate_pdf1 = 1;
            }catch(Exception $e){
                Storage::delete('public/img_signature/desc_sign.pdf');
                return response()->json(["erreur"=>"generate "],501);
            }
        // on teste que les deux operations precedent bien validé

           if( $succes_generate_pdf1 == 1){ // bien generer la signature ???
                $files = [storage_path('app/'.$lien), storage_path('app/public/img_signature/desc_sign.pdf')];
                $pdf = new Fpdi();

                foreach ($files as $file) {
                    $pageCount1 =  $pdf->setSourceFile($file);

                 for ($i=0; $i < $pageCount1; $i++) {
                        $pdf->AddPage();
                        $tplId = $pdf->importPage($i+1);
                        $pdf->useTemplate($tplId);
                    }
                }
                $filename_path=storage_path('app/'.$lien);
                 $pdf->Output($filename_path,'F');
                 Storage::delete('public/img_signature/desc_sign.pdf');
                 /** File::delete(filename_path); */

                ######## return $pdf->Output(); ##################

                return response()->json(["success"=>"bien"],200);
            }else{
                Storage::delete('public/img_signature/desc_sign.pdf');
                return response()->json(["erreur"=>"generate "],500);

            }
  }

}
?>
