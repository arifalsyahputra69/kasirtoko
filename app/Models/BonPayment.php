<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonPayment extends Model
{
    protected $fillable = [
        'bon_id',
        'user_id',
        'jumlah_bayar',
        'keterangan',
    ];

    public function bon(){
        return $this->belongsTo(Bon::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}