<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class AddCompanyRequest extends FormRequest
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
                    'company_name' => 'required|max:255',
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'phone_number' => 'required|numeric|digits_between:7,15|unique:users',
                    'country_id' => 'required',
                    'password' => 'required|max:50|min:8',
                    'address_1' => 'required|max:255',
                    'address_2' => 'max:255',
                    'state_id' => 'required',
                    'city' => 'required|max:255',
                    'zipcode' => 'required|max:10|min:5',
                    'id_proof_detail' => 'required|max:255',
                    'company_registration' => 'required|max:255',
                    'company_authentication' => 'required|max:255',
                    'mc_number' => 'required|max:255',
                    'dot_number' => 'required|max:255',
                    'type_of_insurance' => 'required|max:255',

                    
                    'id_proof_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'company_registration_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'company_authentication_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'mc_number_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'dot_number_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'type_of_insurance_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                ];      
        return $rules;
    }
}
