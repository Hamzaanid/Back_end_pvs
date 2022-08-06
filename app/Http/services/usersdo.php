<?php
namespace App\Http\services;

//use App\Models\usersHasPlaints;
use App\Models\users;


class usersdo{

    public static function create($request){
        users::create([
            'nom' => $request->users['nom'],
            'numUser'=>  $request->users['numUser'],
            'active' =>  $request->users['active'],
            'email' => $request->users['email'],
            'password' => $request->users['password'],
            'idRole' => $request->users['idRole']
        ]);
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
            $users->delete();
            return $id;
        }
    }else{
        return response()->json(["error"=>"operation impossible"],501);
    }
    }

}
