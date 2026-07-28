<?php

namespace App\Enums;

enum AdjustmentReason: string
{
    case Stocktaking = 'stocktaking';
    case Disposal = 'disposal';
    case Initial = 'initial';

    public function label(): string
    {
        return match ($this) {
            self::Stocktaking => '棚卸',
            self::Disposal => '廃棄',
            self::Initial => '初期',
        };
    }
}