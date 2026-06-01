<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|between:0,2',
            'ordered_qty' => 'nullable|integer',
            'ordered_date' => 'nullable|date',
            'vendor_id' => 'integer|exists:vendors,id',
            'received_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'lot_number' => 'nullable|string',
        ];
    }
}
