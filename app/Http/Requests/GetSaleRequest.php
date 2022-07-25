<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;

class GetSaleRequest extends ApiRequest
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
        if (Controller::isAPI()) {
            return [
                'from' => 'date|required_without:byMonth',
                'to' => 'date|required_without:byMonth',
                'dividedBy' => 'integer|required',
                'mean' => 'boolean|required',
                'byMonth' => 'date|required_without:from,to'
            ];
        }

        return [];
    }
}
