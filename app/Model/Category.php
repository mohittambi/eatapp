<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \App\Lib\LanguageConvertor\Translatable;
    public static $translatableColumns=["name","description"];

    // public function farmerCategory() {
    //     return $this->belongsToMany('FarmerCategory');
    // }
    
}
