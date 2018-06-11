<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $table = 'amenities';
    use \App\Lib\LanguageConvertor\Translatable;
    public static $translatableColumns=["name","description"];
    

    

    
}
