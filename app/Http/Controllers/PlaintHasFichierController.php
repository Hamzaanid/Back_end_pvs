<?php

namespace App\Http\Controllers;

use App\Models\plaint_has_fichier;
use Illuminate\Http\Request;

class PlaintHasFichierController extends Controller
{
    public function index($id)
    {
        //les fichiers correspond le pv de l'id $id
        return plaint_has_fichier::where('plaintID',$id)->get();
    }

    public function store(Request $request,$id)
    {
        $files =$request->file('files');

        foreach($files as $file)
         {

             $path = $file->store('public/files/plaint');
             $name = $file->getClientOriginalName();

             $insert['name'] = $name;
             $insert['lien'] = $path;
             $insert['plaintID'] = $id;

             plaint_has_fichier::create($insert);
         }
    }


    public function download(Request $request)
    {
        return response()->download(storage_path('app/'.$request->lien));
    }
}
