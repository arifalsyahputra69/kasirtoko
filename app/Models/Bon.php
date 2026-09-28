<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bon extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'nama_pembeli',
        'total_tagihan',
        'total_dibayar',
        'sisa_tagihan',
        'status',
    ];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payments(){
        return $this->hasMany(BonPayment::class);
    }
}