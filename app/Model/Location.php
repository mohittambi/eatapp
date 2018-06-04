<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Location extends Model
{

 	use Sluggable;
     public function sluggable() {
        return ['slug'=>[
            'source'=>'address',
            'onUpdate'=>true
        ]];
    }
}
