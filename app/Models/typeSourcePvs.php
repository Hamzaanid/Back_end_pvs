<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeSourcePvs extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];
    protected $hidden=['created_at','updated_at']; 
    
    public function pvs(){
        return $this->hasMany(pvs::class);
    }
}
