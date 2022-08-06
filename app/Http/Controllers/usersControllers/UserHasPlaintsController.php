<?php

namespace App\Http\Controllers\UsersControllers;
use App\Http\Controllers\controller;
use App\Http\services\usrhasplaintdo;
use App\Models\UserHasPlaints;
use Illuminate\Http\Request;

class UserHasPlaintsController extends Controller
{
    public function index(Request $request)
    {
        $de = $request->userhasplaint['de'];
        $a = $request->userhasplaint['a'];
        return UserHasPlaints::with(['plaint.sourcePlaint','user:id,nom'])
                ->select('id','userID','plaintID','traitID','dateMission')
                ->whereBetween('dateMission',[$de,$a])
                ->get();
    }


    public function store(Request $request)
    {
        return usrhasplaintdo::create($request);
    }


    public function update(Request $request,$id)
    {
        usrhasplaintdo::update($request,$id);
    }


    public function destroy($id)
    {
        usrhasplaintdo::delete($id);
    }

    public function get_mes_plaintes(Request $request){
        return usrhasplaintdo::mesplaintes($request);
    }
}
