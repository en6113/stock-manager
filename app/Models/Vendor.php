<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relation\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone_number',
        'contact_person',
        'product_category',
    ];

    /**
     * この業者が取り扱う商品を取得
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
