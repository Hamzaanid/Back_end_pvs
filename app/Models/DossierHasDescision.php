<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierHasDescision extends Model
{
    use HasFactory;
    protected $fillable =['dossiersID','lien'];

    public function dossier(){
        return $this->belongsTo(Plaints::class,'dossiersID');
    }
}
