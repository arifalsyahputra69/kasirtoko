<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'peci_number',
        'peci_height',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION PRODUCT
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION SERIAL
    |--------------------------------------------------------------------------
    */
    public function serials()
    {
        return $this->hasMany(ProductSerial::class, 'product_variant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR UKURAN (AUTO DETECT)
    |--------------------------------------------------------------------------
    */
    public function getVariantSizeAttribute()
    {
        if ($this->size) {
            return $this->size;
        }

        if ($this->peci_number || $this->peci_height) {
            return "No {$this->peci_number} / Tinggi {$this->peci_height}";
        }

        return '-';
    }
}