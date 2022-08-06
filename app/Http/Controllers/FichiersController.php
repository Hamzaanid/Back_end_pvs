<?php

namespace App\Http\Controllers;
use App\Http\services\upload_fichier;

use Illuminate\Http\Request;
use App\Models\pvs_has_fichier;

class FichiersController extends Controller
{

    public function index($id)
    {
        //les fichiers correspond le pv de l'id $id
        return pvs_has_fichier::where('pvsID',$id)->get();
    }


    public function store(Request $request,$id)
    {
        {
            $validatedData = $request->validate([
            'file' => 'required',
            'file.*' => 'mimes:pdf'
            ]);

            $files =$request->file('files');

            foreach($files as $file)
             {

                 $path = $file->store('public/pvsPDF');
                 $name = $file->getClientOriginalName();

                 $insert['name'] = $name;
                 $insert['lien'] = $path;
                 $insert['pvsID'] = $id;

                 pvs_has_fichier::create($insert);
             }
       }
    }


    public function download(Request $request)
    {
        return response()->download(storage_path('app/'.$request->lien));
    }
}
