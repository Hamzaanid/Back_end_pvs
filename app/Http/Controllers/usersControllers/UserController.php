<?php

namespace App\Http\Controllers\UsersControllers;

use Illuminate\Http\Request;
use App\Http\services\usersdo;
use App\Models\users;
use App\Models\Role;
use App\Http\Controllers\Controller;

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
        $id_proc = Role::select('id')->where('nom','vice_admin')->first();
        return users::select('id','nom')
                      ->where('idRole',$id_proc->id)->get();
    }

    public function store(Request $request)
    {
        usersdo::create($request);
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
