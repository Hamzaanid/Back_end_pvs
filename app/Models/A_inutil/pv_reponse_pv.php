<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pv_reponse_pv extends Model
{
    use HasFactory;
    protected $fillable=['pvsID','TypeSourcePvsID','typepvsID','sujetpvs','dateEnregPvs','policeJudics','typePoliceJudicID','numEnvoi','datePvs','heureRealisation','contreInnconue'];
    protected $hidden=['created_at','updated_at'];

    public function typepvs(){
        return $this->belongsTo(typepvs::class,'typepvsID');
    }
    public function typesourcepvs(){
        return $this->belongsTo(typeSourcePvs::class,'TypeSourcePvsID');
    }
    public function typepolicejudiciaire(){
        return $this->belongsTo(typePoliceJudic::class,'typePoliceJudicID');
    }
}
