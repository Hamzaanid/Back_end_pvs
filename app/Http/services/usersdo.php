<?php
namespace App\Http\services;

//use App\Models\usersHasPlaints;
use App\Models\users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class usersdo{

    public static function create($request){

        $request->users = json_decode($request->users, true);
        $newUser = new users;

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

    public static function update($request,$id){
        //'password' => $request->users['password'],
        $users = users::find($id);
        $users->update([
            'nom' => $request->users['nom'],
            'email' => $request->users['email'],
            'numUser'=>$request->users['numUser'],
            'active' => $request->users['active'],
            'idRole' => $request->users['idRole']
        ]);
    }
    public static function delete($request,$id){
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
