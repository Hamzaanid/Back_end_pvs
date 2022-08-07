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
    $pdf = new Fpdi();
    $pageCount =  $pdf->setSourceFile(storage_path('app/public/file1.pdf'));

         for ($i=0; $i < $pageCount-1; $i++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($i+1);
                $pdf->useTemplate($tplId);
            }
            return $pdf->Output();
}
}
