<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Barryvdh\DomPDF\Facade\Pdf;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use setasign\Fpdi\Fpdi;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PDFController extends Controller
{
    public function index()
    {
        $data = [
            'descision' => 'kjskjksdhdsljskds',
            'id'=> 2
        ];

        $id =1;
        $pdf = PDF::loadView('user1', $data);
        Storage::disk('img_signature')->put('desc_sign.pdf', $pdf->output());
        return  $pdf->download('tutsmake.pdf');
    }

    public function index2(){
        $files = [storage_path('app/public/fileMerge.pdf'), storage_path('app/public/file2.pdf')];
        $pdf = new Fpdi();

        foreach ($files as $file) {
            // set the source file and get the number of pages in the document
            $pageCount =  $pdf->setSourceFile($file);

         for ($i=0; $i < $pageCount; $i++) {
                //create a page
                $pdf->AddPage();
                //import a page then get the id and will be used in the template
                $tplId = $pdf->importPage($i+1);
                //use the template of the imporated page
                $pdf->useTemplate($tplId);
            }
        }

        $filename=storage_path('app/public/fileMerge.pdf');
        $pdf->Output($filename,'F');

        return $pdf->Output();
}
public function index1(){
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
