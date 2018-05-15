<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // //
    // protected $table = 'users';
    // use \App\Lib\LanguageConvertor\Translatable;
    // public static $translatableColumns=["first_name","last_name"];
    protected $hidden = [
        'password', 'remember_token',
    ];

    use Sluggable;
     public function sluggable() {
        return ['slug'=>[
            'source'=>'full_name',
            'onUpdate'=>true
        ]];
    }

    public function getRelatedCountry() {
        return $this->belongsTo('App\Model\Country', 'country_id');
    }

    public function farmerDetails()
    {
        return $this->hasOne('App\Model\Farmer');
    }
    
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    } 

     


   
}
