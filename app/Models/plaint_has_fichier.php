<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plaint_has_fichier extends Model
{
    use HasFactory;
    protected $fillable =['plaintID','name','lien'];
    protected $hidden=['created_at','updated_at'];

    public function plaint(){
        return $this->belongsTo(Plaints::class,'plaintID');
    }
}
