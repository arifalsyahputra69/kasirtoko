<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Nama tabel jika tidak menggunakan konvensi Laravel
    protected $table = 'sales';

    // Mass assignable
    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'cost',
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor untuk profit
     */
    public function getProfitAttribute()
    {
        return ($this->price - $this->cost) * $this->quantity;
    }

    /**
     * Accessor untuk total harga jual
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Accessor untuk total modal
     */
    public function getTotalCostAttribute()
    {
        return $this->cost * $this->quantity;
    }
}