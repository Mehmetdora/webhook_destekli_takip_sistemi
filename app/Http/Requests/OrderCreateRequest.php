<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // auth kontrolü yok
        return true;
    }
    public function rules(): array
    {
        return [
            'order_no'      => 'required|string',
            'customer_name' => 'required|string',
            'total_price'   => 'required|numeric',
            'status'        => 'required|in:pending,paid,shipped,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'order_no.required'      => 'order_no is required!',
            'customer_name.required' => 'customer_name is required!',
            'total_price.required'   => 'total_price is required!',
            'total_price.numeric'    => 'total_price need to be numeric!',
            'status.required'        => 'status is required!',
            'status.in'              => 'status need to be one of these: pending,paid,shipped,cancelled',
        ];
    }
}
