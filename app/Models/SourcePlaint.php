<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcePlaint extends Model
{
    use HasFactory;
    protected $fillable =['nom'];
    protected $hidden=['created_at','updated_at']; 
    
    public function plaints(){
        return $this->hasMany('App\Models\Plaints');
    }
}
