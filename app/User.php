<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
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
}
