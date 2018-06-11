<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FarmerBanner extends Model
{
    protected $table = 'farmers_banners';
    public $fillable =['name','user_id','description'];
    protected $guarded = array(); 
}
