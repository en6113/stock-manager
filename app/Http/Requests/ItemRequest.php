<?php

namespace App\Http\Requests;

use App\Enums\ItemPurchaseUnit;
use App\Enums\StorageLocation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'proper_inventory' => 'nullable|integer',
            'purchase_unit' => ['nullable', Rule::enum(ItemPurchaseUnit::class)],
            'unit_to_gram' => 'nullable|integer',
            'storage_location' => ['required', Rule::enum(StorageLocation::class)],
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
            'category.required' => 'カテゴリーを選択してください。',
            'purchase_unit.' . Enum::class => '単位は選択肢から選択してください。',
            'storage_location.required' => '保管場所は必須です。',
            'storage_location.' . Enum::class => '保管場所は選択肢から選択してください。',
            'vendor_id.exists' => '選択された業者は存在しません。',
            'allergen_ids.exists' => '選択されたアレルギー物質は存在しません。',
        ];
    }
}
