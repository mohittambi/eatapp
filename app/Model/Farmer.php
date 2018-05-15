<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Farmer extends Model
{
    protected $table = 'farmers';
    use \App\Lib\LanguageConvertor\Translatable;
    public static $translatableColumns=["description"];
    
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
