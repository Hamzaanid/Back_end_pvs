<?php
namespace App\Http\services;

//use Barryvdh\DomPDF\Facade\Pdf;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Dompdf\Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use setasign\Fpdi\Fpdi;

class DossierService{

   public static function storeDocumentWithJoin($request){

    $file =$request->file('file');
    $nameDossier = $request->NumDossier; // le nom du dossier d'enquete
    $name = 'moltamas';  // le fichier uploader par utilisateur
    try{
        $path = Storage::putFileAs("public/DossiersEnquetes", $file, $name.'.pdf');
        $files=[storage_path('app/'.$path),storage_path('app/public/pvsPDF/'.$request->Numpvs.'.pdf')];
        $pdf = new Fpdi();

        foreach ($files as $file) {
            $pageCount =  $pdf->setSourceFile($file);

         for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
        }

        $filename_path=storage_path('app/public/DossiersEnquetes/'.$nameDossier.'.pdf');

           $pdf->Output($filename_path,'F');
         Storage::delete('public/DossiersEnquetes/moltamas.pdf');

         return "public/DossiersEnquetes/$nameDossier.pdf";
    }catch(Exception $e){
        Storage::delete('public/DossiersEnquetes/moltamas.pdf');
        return 0;
    }


   }
}
?>
