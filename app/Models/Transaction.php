<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_cost',
        'total_selling',
        'total_profit',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'total_selling' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function retur(){
    return $this->hasOne(\App\Models\Retur::class);
    }  
    public function bon(){
    return $this->hasOne(\App\Models\Bon::class);
    } 
}