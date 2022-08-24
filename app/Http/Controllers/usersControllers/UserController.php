<?php

namespace App\Http\Controllers\UsersControllers;

use Illuminate\Http\Request;
use App\Http\services\usersdo;
use App\Models\users;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\services\fichierdo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    { //return usersdo::create($request);
        $request->users = json_decode($request->users, true);
        $newUser = new users();
            $newUser->nom = $request->users['nom'];
            $newUser->numUser =  $request->users['numUser'];
            $newUser->active =  $request->users['active'];
            $newUser->email = $request->users['email'];
            $newUser->password = $request->users['password'];
            $newUser->idRole = $request->users['idRole'];

            DB::transaction(function () use ($newUser){
                global $request;
                 $id = $newUser->save();

                 if($request->file('img')){
                    fichierdo::image_signature($request,$newUser->id);
                 }

            });
    }

    public function img_sign(Request $request){
        fichierdo::image_signature($request, (int)$request->iduser);
    }


    public function update(Request $request, $id)
    {
      // return usersdo::update($request,$id);
      $request->users = json_decode($request->users, true);

      $userUpdate = users::find($id);
      if($request->users['password']){
          $userUpdate->password = $request->users['password'];
      }
          $userUpdate->nom = $request->users['nom'];
          $userUpdate->numUser =  $request->users['numUser'];
          $userUpdate->active =  $request->users['active'];
          $userUpdate->email = $request->users['email'];
          $userUpdate->idRole = $request->users['idRole'];

          DB::transaction(function () use ($userUpdate){
              global $request;
              if($request->file('img')){
                  fichierdo::image_signature($request,$userUpdate->id);
               }
              $userUpdate->update();
          });

    }


    public function destroy(Request $request ,$id)
    {
       // return usersdo::delete($request,$id);
       if($id != $request->user['id']){
        $users = users::find($id);
    if($users){
        Storage::delete("public/img_signature/user".$id.".jpeg");
        $users->delete();
        return $id;
    }
}else{
    return response()->json(["error"=>"operation impossible"],501);
}
    }

}
