<?php

namespace App\Enums;

enum ItemPurchaseUnit: string
{
    case Gram = 'g';
    case Milliliter = 'ml';
    case Piece = '個';
    case Stick = '本';
    case Bag = '袋';
    case Box = '箱';
    case Fish = '尾';

    public function label(): string
    {
        return match ($this) {
            self::Gram => 'g',
            self::Milliliter => 'ml',
            self::Piece => '個',
            self::Stick => '本',
            self::Bag => '袋',
            self::Box => '箱',
            self::Fish => '尾',
        };
    }
}