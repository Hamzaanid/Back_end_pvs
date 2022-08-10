<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pvs extends Model
{
    use HasFactory;
    protected $fillable=['TypeSourcePvsID','typepvsID',
      'sujetpvs','dateEnregPvs','policeJudics',
      'typePoliceJudicID','Numpvs','datePvs','heureRealisation',
      'contreInnconue'];
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

    public function userhaspvs(){
        return $this->hasOne(userhaspvs::class);
    }

    public function hasfichier(){
        return $this->hasOne(pvs_has_fichier::class,'pvsID');
    }

}
