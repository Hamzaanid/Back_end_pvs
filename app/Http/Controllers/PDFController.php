<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Barryvdh\DomPDF\Facade\Pdf;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use setasign\Fpdi\Fpdi;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\services\fichierdo;

class PDFController extends Controller
{
    public function get_URL_pvs($name){

        $file = storage_path('app/public/pvsPDF/'.$name);
        $pdf = new Fpdi();

            $pageCount =  $pdf->setSourceFile($file);
         for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }

            return $pdf->Output();

    }

    public function get_URL_plaintes($name){

        $file = storage_path('app/public/plaintesPDF/'.$name);
        $pdf = new Fpdi();

            $pageCount =  $pdf->setSourceFile($file);
         for ($i=0; $i < $pageCount; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }

            return $pdf->Output();

    }


  public function couper(){

    // autre methode
   /* $this->validate($request, [
        'filenames' => 'required',
        'filenames.*' => 'mimes:pdf'
    ]);
    if($request->hasFile('filenames')){
        $pdf = new \LynX39\LaraPdfMerger\PdfManage;

        foreach ($request->file('filenames') as $key => $value) {
            $pdf->addPDF($value->getPathName(), 'all');
        }

        $input['file_name'] = time().'.pdf';
        $pdf->merge('file', public_path($input['file_name']), 'P');
    }

    return response()->download(public_path($input['file_name']));
}*/
}
        public function update_descision_pdf($request){
            fichierdo::update_descision_pdf(1,"test manuelle","public/plaintesPDF/ddsdssd.pdf");
        }

}
