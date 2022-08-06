<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userHasPvs extends Model
{
    use HasFactory;
    protected $fillable = ['userID','pvsID','traitID','dateMission','descision'];
    protected $hidden=['created_at','updated_at'];

    public function user(){
        return $this->belongsTo(users::class,'userID');
    }

    public function traited(){
        return $this->belongsTo(traited::class,'traitID');
    }

    public function pvs(){
        return $this->belongsTo(pvs::class,'pvsID');
    }


}
