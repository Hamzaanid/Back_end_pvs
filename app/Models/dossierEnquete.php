<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dossierEnquete extends Model
{
    use HasFactory;
    protected $fillable =['NumDossier','type_dossierID','chambre_enquete','dateEnreg',
                           'juge_enqueteID','userID','pvsID','lienDescision','lien'];

    protected $hidden=['created_at','updated_at'];

    public function user(){
        return $this->belongsTo(users::class,'userID');
    }
    public function pvs(){
        return $this->belongsTo(pvs::class,'pvsID');
    }

    public function juge_enquete(){
        return $this->belongsTo(users::class,'juge_enqueteID');
    }

}
