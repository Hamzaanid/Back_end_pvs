<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userHasPlaints extends Model
{
    use HasFactory;

    protected $fillable=['userID','plaintID','traitID','dateMission','descision'];
    protected $hidden=['created_at','updated_at'];

    public function user(){
        return $this->belongsTo(users::class,'userID');
    }
    public function plaint(){
        return $this->belongsTo(Plaints::class,'plaintID');
    }
   /* public function traited(){
        return $this->belongsTo(traited::class,'traitID');
    } */
}
