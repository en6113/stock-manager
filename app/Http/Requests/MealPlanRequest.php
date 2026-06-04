<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealPlanRequest extends FormRequest
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
            'month' => 'nullable|date_format:Y-m',
            'date' => 'nullable|date',
        ];
    }

    // 中間テーブルにデータを保存するためにデータを成型(@storeと@updateで使用)
    public function getFormattedMenuData(): array
    {
        $formatted = [];

        foreach ($this->input('menus', []) as $categoryId => $menuData) {
            // メニューが選択されていないカテゴリは無視
            if (empty($menuData['menu_id'])) {
                continue;
            }

            $formatted[] = [
                'menu_id' => $menuData['menu_id'],
                'ingredients' => $menuData['ingredients'] ?? [] // 調整後の食材の配列
            ];
        }

        return $formatted;
    }
}
