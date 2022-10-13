<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
            'user_type' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Name should not be left blank',
            'email.required' => 'Email should not be left blank',
            'email.email' => 'Email should be valid email id',
            'email.unique' => 'Email id already exist',
            'password.required' => 'Password should not be left blank',
            'password.min' => 'Password should be minimum 8 character long',
            'c_password.required' => 'Please confirm the password',
            'c_password.same' => 'Password and confirm password should be same',
            'user_type.required' => 'Please select your role',

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
