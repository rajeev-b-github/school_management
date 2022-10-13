<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profile_picture' => 'required',
            'current_school' => 'required',
            'previous_school' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pin_code' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'father_occupation' => 'required',
            'mother_occupation' => 'required',
            'father_contact_no' => 'required',
            'mother_contact_no' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'profile_picture.reuired' => 'Profile picture is required',
            'current_school.required' => 'Current School is required',
            'previous_school.required' => 'Previous School is required',
            'address_1.required' => 'Address 1 field is required',
            'address_2.required' => 'Address 2 field is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'country.required' => 'Country is required',
            'pin_code.required' => 'Pin Code is required',
            'father_name.required' => 'Father name is required',
            'mother_name.required' => 'Mother Name is required',
            'father_occupation.required' => 'Father occupation required',
            'mother_occupation.required' => 'Mother occupation required',
            'father_contact_no.required' => 'Father contact required',
            'mother_contact_no.required' => 'Mother contact required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()->first(),
            'Status'    => '422'
        ]));
    }
}
