<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FrontPage extends Model
{
    //protected $table = 'front_pages';
    use \App\Lib\LanguageConvertor\Translatable;
    public static $translatableColumns=["title","content"];
    

    
}
