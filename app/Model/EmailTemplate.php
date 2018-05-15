<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class EmailTemplate extends Model
{
    
    use Sluggable;
    public function sluggable() {
        return ['slug'=>[
            'source'=>'title',
            'onUpdate'=>true
        ]];
    }

    
}
