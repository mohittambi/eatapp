<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class SocialAccount extends Model
{    
    protected $fillable = array('user_id', 'social_id','social_type');

    public function getAssociateUserWithSocial() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
