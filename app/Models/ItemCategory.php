<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // このカテゴリに紐づく食材を取得する
    public function items(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
