<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class AddVehicleRequest extends FormRequest
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
                    'truck_category_id' => 'required|max:255',
                    'truck_number' => 'required|max:255',
                    'manufacturing_company' => 'required|max:255',
                    'manufacturing_year' => 'required',
                     'manufacturing_month' => 'required',
                    'truck_registration_detail' => 'required|max:255',
                    'truck_us_dot_number' => 'required|max:255',
                    'truck_mc_number' => 'required|max:255',
                    'truck_insurance_details' => 'required|max:255',
                    'driver_id' => 'required',
                    'truck_registration_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'truck_us_dot_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'truck_mc_number_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',
                    'truck_insurance_doc' => 'required|mimes:jpeg,jpg,png,pdf,odt,doc',

                                        
                ];      
        return $rules;
    }
}
