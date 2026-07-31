<?php

namespace App\Http\Requests;

use App\Enums\DishCategory;
use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'dish_category' => ['required', Rule::enum(DishCategory::class)],
            'servings' => 'required|integer',
            'calories' => 'nullable|integer',
            'item_names.*' => 'nullable|string|exists:items,name',
            'required_amounts.*' => 'nullable|numeric|min:0.1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'メニュー名を入力してください',
            'dish_category.required' => 'カテゴリーを選択してください',
            'dish_category.'.Enum::class => 'カテゴリーを選択肢から選択してください',
            'servings.required' => '提供人数を入力してください',
            'item_name.exists' => 'マスターに存在する食材名を入力してください',
        ];
    }

    /**
     * 中間テーブルに保存するためのデータを成型
     */
    public function getSyncData(): array
    {
        // 送られてきたアイテム名の一覧をコレクション化し、空の要素を除外
        $itemNames = collect($this->input('item_ids', []))->filter();

        if ($itemNames->isEmpty()) {
            return [];
        }

        // DBへのクエリを発行し、名前をキーにした連想配列にする
        $items = Item::whereIn('name', $itemNames)->get()->keyBy('name');

        $requiredAmounts = $this->input('required_amounts', []);
        $servings = $this->input('servings');

        $syncData = [];

        foreach ($this->input('item_ids', []) as $key => $itemName) {
            // 空白の入力枠は無視するロジック（エラー防止）
            if (empty($itemName) || ! $items->has($itemName)) {
                continue;
            }

            $item = $items->get($itemName);

            $syncData[$item->id] = [
                'required_amount' => $requiredAmounts[$key] ?? 0,
                'servings' => $servings,
            ];
        }

        return $syncData;
    }
}
