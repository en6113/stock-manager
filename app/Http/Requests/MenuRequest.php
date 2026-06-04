<?php

namespace App\Http\Requests;

use App\Models\Item;
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
            'name' => 'required|string|max:255',
            'dish_category' => 'required|integer',
            'calories' => 'nullable|integer',
            'item_name.*' => 'required|string',
            'servings' => 'required|integer',
            'required_amounts.*' => 'required_with:item_name.*|nullable|numeric|min:0.1',
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
            if (empty($itemName) || !$items->has($itemName)) {
                continue;
            }

            $item = $items->get($itemName);
            $amount = $requiredAmounts[$key] ?? 0;

            $syncData[$item->id] = [
                'servings' => $servings,
                'required_amount' => $amount,
            ];
        }

        return $syncData;
    }
}
