<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class users extends Authenticatable implements JWTSubject
{
    use Notifiable,HasFactory;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable=['nom','email','password','idRole','numUser','active'];
    protected $hidden=['created_at'	,'updated_at','remember_token'];

    public function role(){
        return $this->belongsTo(Role::class,'idRole');
    }

    public function userhasplaints(){
        return $this->hasMany(userHasPlaints::class,'palintID');
    }
    public function userhaspvs(){
        return $this->hasMany(userHasPvs::class,'pvsID');
    }



}
