<?php

namespace App\Http\Requests;

use App\Enums\AdjustmentReason;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StockAdjustmentRequest extends FormRequest
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
            'actual_qty' => 'required|numeric',
            'reason' => ['required', Rule::enum(AdjustmentReason::class)],
            'note' => 'nullable|string',
            'adjusted_on' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'actual_qty.required' => '実際の在庫数を入力してください',
            'actual_qty.numeric' => '実際の在庫数は数値で入力してください',
            'reason.' . Enum::class => '調整理由を選択肢から選択してください',
        ];
    }
}
