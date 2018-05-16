<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class AddDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        $rules = [
                    'image' => 'required|mimes:jpeg,jpg,png',
                    'full_name' => 'required|max:255',
                    
                    'email' => 'required|email|max:255|unique:users',
                    'phone_number' => 'required|numeric|digits_between:7,15|unique:users',
                    'password' => 'required|max:50|min:8',
                    'address_1' => 'required|max:255',
                    'address_2' => 'max:255',
                    'country_id' => 'required',
                    'state_id' => 'required',
                    'city' => 'required|max:255',
                    'zipcode' => 'required|max:10|min:5',
                    'type_driving_licence' => 'required|max:255',
                    'id_proof_detail' => 'required|max:255',
                    'id_proof_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'type_driving_licence_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',

                    
                ];      
        return $rules;
    }
}
