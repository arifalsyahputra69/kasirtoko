<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'returis'; // ← tambah ini

    protected $fillable = ['transaction_id','user_id','alasan'];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}