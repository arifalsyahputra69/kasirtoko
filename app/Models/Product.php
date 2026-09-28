<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand',
        'type',
        'color',
        'cost_price'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function retur()
    {
    return $this->hasOne(\App\Models\Retur::class);
    }
}
