<?php

namespace App\Http\Controllers\PvsControllers;
use App\Http\services\pvsdo;
use App\Http\services\fichierdo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pvs;
use App\Models\userHasPvs;
use Illuminate\Support\Facades\DB;


class PvsController extends Controller
{
    public function index()
    {
        return pvs::with('typepvs','typesourcepvs','typepolicejudiciaire')
                   ->paginate(10);
    }

    public function getPvs_betwen_dateEnrg(Request $request){
       return pvsdo::get_pvs_betwDateEnrg($request);
    }


    public function cherche_byNumpvs(Request $request){

                    return $pv = userHasPvs:: with('pvs','pvs.hasfichier:pvsID,lien','pvs.typepvs')
                            ->select('pvs.id as pvsID','user_has_pvs.traitID')
                            ->rightJoin('pvs','pvs.id','=','user_has_pvs.pvsID')
                            ->where('Numpvs',$request->Numpvs)
                            ->get();
    }

    public function getpvsBydateEnrg(Request $request){
        return  pvs::with('typepvs','typesourcepvs','hasfichier:pvsID,lien')
                    ->whereBetween('dateEnregPvs',[$request->dateEnrg['de'],$request->dateEnrg['a']])
                    ->whereNotIn('id',function ($query) {
                        $query->select('pvsID')
                            ->from('user_has_pvs');
                        })->get();


    }

    public function getPvs_of_user(Request $request)
    { return pvsdo::getPvs_of_user($request); }


    public function store(Request $request) {  return  pvsdo::create($request); }


    public function PDF_pvs(Request $request,$idpvs){
        $result = explode(".",$idpvs);
        fichierdo::store_pdf_pvs($request,(int)$result[0],$result[1]);
    }


    public function update(Request $request, $id) { pvsdo::update($request,$id);  }


    public function destroy($id){ return pvsdo::delete($id); }


    public function statistique(Request $request){ return  pvsdo::stat($request); }
}
