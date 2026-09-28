<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSerial extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar
    protected $table = 'product_serials';

    protected $fillable = [
        'product_variant_id',
        'serial_number',
        'is_sold'
    ];

    protected $casts = [
        'is_sold' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Serial milik satu variant
    public function productVariant()
    {
        return $this->belongsTo(\App\Models\ProductVariant::class, 'product_variant_id');
    }
    public function variant()
    {
        return $this->belongsTo(\App\Models\ProductVariant::class, 'product_variant_id');
    }
    // Serial bisa akses product lewat variant
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            ProductVariant::class,
            'id',                // Foreign key di product_variants
            'id',                // Foreign key di products
            'product_variant_id',// Local key di product_serials
            'product_id'         // Local key di product_variants
        );
    }
}