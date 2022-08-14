<?php
namespace App\Http\services;
use App\Models\userHasPlaints;
use Illuminate\Support\Facades\DB;

class usrhasplaintdo{
               ####### affecter plaint a un user ########
    public static function create($request){
        $ids = $request->userhasplaint['plaintID'];
        if($ids == null || $request->userhasplaint['userID'] == null){
             return response()->json(['err'=>'connot read array'],500);
            }
        foreach($ids as $id_plaint){
        userHasPlaints::create([
            'userID'=>$request->userhasplaint['userID'],
            'plaintID' => $id_plaint,
            'traitID' => $request->userhasplaint['traitID'],
            'dateMission'=> $request->userhasplaint['dateMission'],
            'descision'=> $request->userhasplaint['descision']

        ]);
       }


    }

    public static function update($request,$id){

        $usershasplaint = userHasPlaints::where('plaintID',$id)->first();
        $usershasplaint->update([
            'traitID' => $request->userhasplaint['traitID'],
            'descision'=>$request->userhasplaint['descision']
        ]);



    }

     public static function delete($id){
        $userhasplaint = userHasPlaints::find($id);
        $userhasplaint->delete();
    }

    public static function mesplaintes($request){

        return $plaints = DB::table('plaints')
            ->join('user_has_plaints', 'plaints.id', '=', 'user_has_plaints.plaintID')
            ->join('plaint_has_fichiers', 'plaints.id','=', 'plaint_has_fichiers.plaintID')
            ->select( 'plaints.id', 'plaints.referencePlaints', 'plaints.dateEnregPlaints',
                       'user_has_plaints.dateMission','user_has_plaints.traitID','user_has_plaints.userID',
                       'plaint_has_fichiers.lien')
                       ->where('user_has_plaints.userID',$request->user->id)
                       ->whereIn('user_has_plaints.traitID',[1,2])
                       ->get();
    }

}
