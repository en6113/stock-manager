<?php

namespace App\Enums;

enum StorageLocation: string
{
    case ROOM_TEMPERATURE = '常温';
    case REFRIGERATED = '冷蔵';
    case FROZEN = '冷凍';

    // 画面表示用の日本語名を返すメソッド
    public function label(): string
    {
        return match($this) {
            self::ROOM_TEMPERATURE => '常温',
            self::REFRIGERATED => '冷蔵',
            self::FROZEN => '冷凍',
        };
    }

    // 対応する色のTailwindクラスを返すメソッド
    public function colorClass(): string
    {
        return match ($this) {
            self::ROOM_TEMPERATURE => 'bg-gray-200 text-gray-800',   // グレー
            self::REFRIGERATED => 'bg-amber-100 text-gray-800', // 黄色
            self::FROZEN => 'bg-blue-100 text-gray-800',         // 水色
        };
    }
}