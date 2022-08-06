<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataParties extends Model
{
    use HasFactory;
    protected $fillable = ['PersonneMoraleID','TypeCarteIdentsID','SituationFamillID','VilleDeNaissID',
     	'paysID','ProvinceID','nationaliteID','soi','morale','genre','nom','prenom','NumCarte',
         'NomDePere','FilsDe','NomDeMere','FilleDe','DataPartieCol',
    	'Profession','LieuDeTravail','NumFinan','DateNaiss','DataPersonnecol','genreID','minor','address','LieuNaiss'];

        protected $hidden=['created_at'	,'updated_at'];

    public function nationnalites()
    {
        return $this->belongsTo(Nationalite::class,'nationaliteID');
    }
    public function pays()
    {
        return $this->belongsTo(Pays::class,'paysID');
    }
    public function personneMorales()
    {
        return $this->belongsTo(personneMorales::class,'PersonneMoraleID');
    }
    public function provinces()
    {
        return $this->belongsTo(Province::class,'ProvinceID');
    }
    public function situationFamil()
    {
        return $this->belongsTo(SituationFamil::class,'SituationFamillID');
    }
    public function typeCarteIdent()
    {
        return $this->belongsTo(TypeCarteIdent::class,'TypeCarteIdentsID');
    }
    public function villeDeNaiss()
    {
        return $this->belongsTo(VilleDeNaiss::class,'VilleDeNaissID');
    }

    public function genre()
    {
        return $this->belongsTo(genre::class,'genreID');
    }
}
