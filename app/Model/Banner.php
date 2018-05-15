<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Banner extends Model
{
    protected $table = 'banners';
    use \App\Lib\LanguageConvertor\Translatable;
    public static $translatableColumns=["name","description"];
    //
    // use Sluggable;
    // public function sluggable() {
    //     return ['slug'=>[
    //         'source'=>'full_name',
    //         'onUpdate'=>true
    //     ]];
    // }

    // public function getRelatedCountry() {
    //     return $this->belongsTo('App\Model\Country', 'country_id');
    // }

    
}
