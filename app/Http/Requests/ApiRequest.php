<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest {
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(Controller::fail(
            $validator->getMessageBag()->first(),
            [
                'data' => $validator->errors()
            ]
        ));
    }
}
