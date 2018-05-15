<?php
namespace App\Lib;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class customValidation
 {
    
    public static  function register($register){
        $register(['old_password'=>"Old password not match",
                   "valid_user"=>"User not exist",
                    "valid_time"=>"You can't change this field now.",
                     
            ]);
    }
     public static function old_password($attribute, $value, $parameters, $validator){
         
         return Hash::check($value,$parameters[0]);
     }
     public static function valid_user($attribute, $value, $parameters, $validator){
          return User::where("userid","=",$value)->count()?true:false;
          
     }
     
     public static function valid_time($attribute, $value, $parameters, $validator){
         $userid=$parameters[0];
         $field=$parameters[1];
         $type=$parameters[2];
         $diff=$type=="week"?7:1;
          $time=User::where("userid","=",$userid)->select($field)->first()->{$field};
         $now = strtotime(date("Y-m-d H:i:s")); // or your date as well
         if($time!="0000-00-00 00:00:00"){
         $your_date = strtotime($time);
            $datediff = $now - $your_date;
 
          if(floor($datediff / (60 * 60 * 24))>=$diff){
              return true;
          }
          else
              return false;
         }
         else{
             return true;
         }
    }
 }

