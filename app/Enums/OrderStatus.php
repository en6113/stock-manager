<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Ordered = 'ordered';
    case Received = 'received';

    public function label(): string
    {
        return match ($this) {
        self::Pending => '未発注',
        self::Ordered => '発注済',
        self::Received => '納品済',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-gray-200 text-gray-800',   // グレー
            self::Ordered => 'bg-blue-100 text-gray-800',         // 水色
            self::Received => 'bg-amber-100 text-gray-800', // 黄色
        };
    }
}