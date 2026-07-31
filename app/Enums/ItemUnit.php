<?php

namespace App\Enums;

enum ItemUnit: string
{
    case Gram = 'g';
    case Milliliter = 'ml';
    case Piece = '個';
    case Bottle = '本';
    case Pack = '袋/パック';
    case Box = '箱';
    case Fish = '尾';
    case Head = '玉';

    public function label(): string
    {
        return match ($this) {
            self::Gram => 'g',
            self::Milliliter => 'ml',
            self::Piece => '個',
            self::Bottle => '本',
            self::Pack => '袋/パック',
            self::Box => '箱',
            self::Fish => '尾',
            self::Head => '玉',
        };
    }
}
