<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'ordered_qty' => 'nullable|integer',
            'ordered_date' => 'nullable|date',
            'vendor_id' => 'integer|exists:vendors,id',
            'received_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'lot_number' => 'nullable|string',
        ];
    }
}
