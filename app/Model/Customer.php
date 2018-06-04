<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    public function getRelatedCountry() {
        return $this->belongsTo('App\Model\Country', 'country_id');
    }

}
