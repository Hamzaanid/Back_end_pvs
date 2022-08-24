<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dossierEnquete extends Model
{
    use HasFactory;
    protected $fillable =['NumDossier','type_dossierID','chambre_enquete',
                           'juge_enqueteID','usrhaspvsID','lien'];
    protected $hidden=['created_at','updated_at'];

}
