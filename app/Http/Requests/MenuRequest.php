<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'name' => 'required|string',
            'dish_category' => 'required|integer',
            'calories' => 'nullable|integer',
            'item_name.*' => 'required|string',
            'servings' => 'required|integer',
            'required_amounts.*' => 'required_with:item_name.*|nullable|integer|min:0.1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'メニュー名を入力してください。',
            'dish_category.required' => 'カテゴリーを選択してください。',
            'item_id.required' => '食材名を入力してください。',
            'servings.required' => '何人分を想定しているかを入力してください。',
            'required_amount.required' => '必要量を入力してください',
        ];
    }
}
