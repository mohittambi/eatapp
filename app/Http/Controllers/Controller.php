<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use File;
use App\Model\User;
use App\Model\Country;
use App\Model\State;
use App\Model\TruckCategory;
use App\Model\Category;
use App\Model\Setting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function validationHandle($validation)
    {
        foreach ($validation->getMessages() as $field_name => $messages){
            if(!isset($firstError)){
                $firstError        =$messages[0];
            }
        }
        return $firstError;
    }

    function randomOtp($length = 30)
    {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }


    function getCountryList()
    {
        $all_country = Country::orderBy('name','asc')->get();
        $list = [];
        $i=0;
        foreach($all_country as $country)
        {
            $list[$i]['name'] = $country->name;
            $list[$i]['id'] = $country->id;
            $list[$i]['phonecode'] = $country->phonecode;
            $i++;
        }
        return $list;
    }

    function getCountryListForSelectBox()
    {
        return Country::pluck('name','id')->toArray();
        
    }

     function getStateListForSelectBox($country_id)
    {
        return State::where('country_id',$country_id)->pluck('name','id')->toArray();
        
    }

    function getTruckCategoryListForSelectBox()
    {
        return TruckCategory::pluck('title','id')->toArray();
        
    }

    function getCompanyDriverListForSelectBox($companyId)
    {
        return User::where('company_id',$companyId)
            ->where('role','DR')
            ->doesnthave('getDriverAssociatedVehicleRelation')
            ->pluck('full_name','id')
            ->toArray();
    }

   
    function getCategoryList()
    {
        $all_category = Category::orderBy('name','asc')->get();
        $list = [];
        $i=0;
        foreach($all_category as $category)
        {
            $list[$i]['name'] = $category->name;
            $list[$i]['id'] = $category->id;
            $list[$i]['description'] = $category->description;
            $i++;
        }
        return $list;
    }
    
   

}
