<?php

namespace App\Http\Controllers\UsersControllers;

use Illuminate\Http\Request;
use App\Http\services\usersdo;
use App\Models\users;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\services\fichierdo;

class UserController extends Controller
{

    public function index(Request $request)
    {
       return users::with('Role:id,nom')
         ->select('id','nom','email','idRole','numUser','active')
        ->get();
        // ->where('id','<>',$request->user->id)
    }

    public function index_viceProc(){
        $id_proc = Role::select('id')->whereIn('nom',['vice_proc','proc'])->get();
       $id=[];
       foreach($id_proc as $role){
        array_push($id,$role->id);
       }
        $id;
        return users::select('id','nom')
                      ->whereIn('idRole',$id)->get();
    }

    public function store(Request $request)
    {
       return usersdo::create($request);
    }
    public function img_sign(Request $request){
        fichierdo::image_signature($request, (int)$request->iduser);
    }


    public function update(Request $request, $id)
    {
        usersdo::update($request,$id);
    }


    public function destroy(Request $request ,$id)
    {
        return usersdo::delete($request,$id);
    }

}
