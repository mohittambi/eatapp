<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
	//protected $table = 'verify_users';
    protected $guarded = [];
 
    public function userV()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
