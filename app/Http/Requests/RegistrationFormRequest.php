<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class RegistrationFormRequest extends FormRequest
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
        return [
            'firstName' => 'required|regex:/^([a-zA-Z]{3,})+$/',
            'lastName' => 'required|regex:/^([a-zA-Z]{3,})+$/',
            'username' => 'required|regex:/^([a-zA-Z0-9@_.-]{3,})+$/|unique:users',
            'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|unique:users',
            'phoneNumber' => 'required|regex:/[0-9]/|unique:users',
            'idNumber' => 'required|regex:/[0-9]/|unique:users',
            'gender' => ['required', 'regex:/^Male$|^male$|^Female$|^female$/'],
            'password' => 'required|string|min:6|max:10',
            'role' => ['required', 'regex:/^admin$|^manager$/']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], 400));
    }
}
