<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pvs_has_fichier extends Model
{
    use HasFactory;

    protected $fillable =['pvsID','name','lien'];
    protected $hidden=['created_at','updated_at'];


    public function pvs(){
        return $this->belongsTo(pvs::class,'pvsID');
    }
}
