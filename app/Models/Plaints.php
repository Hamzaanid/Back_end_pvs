<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaints extends Model
{
    use HasFactory;
    protected $fillable = ['contreInconnu','TypePlaintID','SourcePlaintID','referencePlaints',
     	'datePlaints','dateEnregPlaints','dateFaits','EmplaceFaits','sujetPlaints'];

         protected $hidden=['created_at','updated_at'];

    public function sourcePlaint()
    {
        return $this->belongsTo('App\Models\SourcePlaint','SourcePlaintID');
    }
    public function typePlaint()
    {
        return $this->belongsTo(TypePlaint::class, 'TypePlaintID');
    }

    public function userhasplaints(){
        return $this->hasMany(userhasplaints::class,'plaintID');
    }

    public function hasfichier(){
        return $this->hasOne(plaint_has_fichier::class,'plaintID');
    }




}
