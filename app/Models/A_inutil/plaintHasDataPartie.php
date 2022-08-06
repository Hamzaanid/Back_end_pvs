<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plaintHasDataPartie extends Model
{
    use HasFactory;
    protected $fillable=['plaintID','datapartieID'];
    protected $hidden=['created_at'	,'updated_at'];
    
    public function plaint(){
        return $this->belongsTo(Plaints::class,'plaintID');
    }
    public function datapartie(){
        return $this->belongsTo(DataParties::class,'datapartieID');
    }
}
