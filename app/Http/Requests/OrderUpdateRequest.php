<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'        => 'status is required!',
            'status.in'              => 'status need to be one of these: pending,paid,shipped,cancelled',
        ];
    }
}
