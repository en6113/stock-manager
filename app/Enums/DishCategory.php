<?php

namespace App\Enums;

enum DishCategory: string
{
    case Staple = 'staple';
    case Main = 'main';
    case Side = 'side';
    case Soup = 'soup';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Staple => '主食',
            self::Main => '主菜',
            self::Side => '副菜',
            self::Soup => '汁もの',
            self::Other => 'その他',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::Staple => 'bg-orange-100 text-gray-800',
            self::Main => 'bg-red-100 text-gray-800',
            self::Side => 'bg-green-100 text-gray-800',
            self::Soup => 'bg-blue-100 text-gray-800',
            self::Other => 'bg-yellow-100 text-gray-800',
        };
    }
}
