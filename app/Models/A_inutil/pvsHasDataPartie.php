<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pvsHasDataPartie extends Model
{
    use HasFactory;
    protected $fillable =['pvsID','datapartieID'];
    protected $hidden=['created_at','updated_at'];
    
    public function pvs(){
        return $this->belongsTo(pvs::class,'pvsID');
    }
    public function datapartie(){
        return $this->belongsTo(DataParties::class,'datapartieID');
    }
}
