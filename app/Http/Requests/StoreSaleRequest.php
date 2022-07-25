<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSaleRequest extends ApiRequest {
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
    public function rules() {
        return [
            'customer_name' => 'required|string|max:50',
            'customer_mobile' => 'required',
            'customer_email' => 'required|email',
            'customer_gender' => 'required|string|max:1',
            // 'customer_age' => 'required|integer|min:1|max:150',
            // 'customer_job' => 'required|integer|max:1',
            // 'identity_card_photo' => 'required|file|max:5120', tetap di gunakan
            // 'product_id' => 'required|exists:products,id',
            // 'imei_photo' => 'required|file|max:5120',
            // 'quantity' => 'required|integer|min:1|max:100',
        ];
    }
}
