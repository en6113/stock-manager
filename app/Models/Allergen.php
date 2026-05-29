<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relation\BelongsToMany;

class Allergen extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * このアレルギー物質を含む商品を取得
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }
}
