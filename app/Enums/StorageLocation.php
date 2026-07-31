<?php

namespace App\Enums;

enum StorageLocation: string
{
    case PANTRY = 'pantry';
    case REFRIGERATOR = 'refrigerator';
    case FREEZER = 'freezer';

    // 画面表示用の日本語名を返すメソッド
    public function label(): string
    {
        return match ($this) {
            self::PANTRY => 'パントリー',
            self::REFRIGERATOR => '冷蔵庫',
            self::FREEZER => '冷凍庫',
        };
    }

    // 対応する色のTailwindクラスを返すメソッド
    public function colorClass(): string
    {
        return match ($this) {
            self::PANTRY => 'bg-gray-200 text-gray-800',   // グレー
            self::REFRIGERATOR => 'bg-amber-100 text-gray-800', // 黄色
            self::FREEZER => 'bg-blue-100 text-gray-800',         // 水色
        };
    }
}
