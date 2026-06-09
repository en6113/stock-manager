<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ExportCsvRequest extends FormRequest
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
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    // バリデーションを通過した後に実行される処理（Carbonを使って確実に Y-m-d 形式の文字列に変換してリクエストデータを上書き）
    protected function passedValidation()
    {
        $this->merge([
            'start_date' => Carbon::parse($this->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($this->end_date)->format('Y-m-d'),
        ]);
    }
}
