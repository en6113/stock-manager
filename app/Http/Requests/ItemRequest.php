<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'name')->ignore($this->item),
            ],
            'item_category_id' => 'required|integer',
            'target_stock_qty' => 'required|integer|min:1',
            'unit' => 'required|string|max:30',
            'capacity' => 'nullable|string|max:30',
            'storage_location' => 'required|string|max:30',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'allergen_ids' => 'nullable|array',
            'allergen_ids.*' => 'integer|exists:allergens,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '食材名は必須です。',
            'name.max' => '食材名は255文字以内で入力してください。',
            'name.unique' => '食材名は既に使われています。別の名前に変更してください。',
            'target_stock_qty.required' => '適正在庫数は必須です。',
            'target_stock_qty.min' => '適正在庫数は1以上を入力してください。',
            'unit.required' => '単位は必須です。',
            'unit.max' => '単位は30文字以内で入力してください。',
            'capacity.max' => '規格容量は30文字以内で入力してください。',
            'storage_location.required' => '保管場所は必須です。',
            'storage_location.max' => '保管場所は30文字以内で入力してください。',
            'vendor_id.exists' => '選択された業者は存在しません。',
            'allergen_ids.exists' => '選択されたアレルギー物質は存在しません。',
        ];
    }
}
