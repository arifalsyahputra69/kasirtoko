<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_serial_id',
        'cost_price',
        'selling_price',
        'profit'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
   public function productSerial()
    {
        return $this->belongsTo(\App\Models\ProductSerial::class, 'product_serial_id');
    }
    public function serial()
    {
        return $this->belongsTo(\App\Models\ProductSerial::class, 'product_serial_id');
    }
}