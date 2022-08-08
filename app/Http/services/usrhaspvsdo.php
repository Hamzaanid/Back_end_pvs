<?php
namespace App\Http\services;
use App\Models\userHasPvs;
use Illuminate\Support\Facades\DB;


class usrhaspvsdo{

    public static function create($request){

        $ids = $request->userhaspvs['pvsID'];
        if($ids == null || $request->userhaspvs['userID'] == null){
             return response()->json(['err'=>'connot read array'],500);
            }
        foreach($ids as $id_pv){
            userHasPvs::create([
            'userID'=>$request->userhaspvs['userID'],
            'pvsID' => $id_pv,
            'traitID' => $request->userhaspvs['traitID'],
            'descision'=> $request->userhaspvs['descision'],
            'dateMission'=> $request->userhaspvs['dateMission']
        ]);
       }


    }

    public static function update($request,$id){
        $userhaspvs = userHasPvs::where('pvsID',$id)->first();
        $userhaspvs->update([
            'traitID' => $request->userhaspvs['traitID'],
            'descision'=> $request->userhaspvs['descision']
        ]);
    }

     public static function delete($id){
        $userhaspvs = userHasPvs::find($id);
        $userhaspvs->delete();
    }

    public static function mespvs($request){

    return $pvs = DB::table('pvs')
    ->join('user_has_pvs', 'pvs.id', '=', 'user_has_pvs.pvsID')
    ->join('pvs_has_fichiers', 'pvs.id', '=', 'pvs_has_fichiers.pvsID')
    ->select( 'pvs.id', 'pvs.Numpvs', 'pvs.dateEnregPvs',
               'user_has_pvs.dateMission','user_has_pvs.traitID','user_has_pvs.userID',
               'pvs_has_fichiers.lien')
               ->where('user_has_pvs.userID',$request->user->id)
               ->whereIn('user_has_pvs.traitID',[1,2])
               ->get();
    }
}
