<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Retur;
use App\Models\Transaction;
use App\Models\ProductVariant;
use App\Models\ProductSerial;

class ReturController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        // cek apakah sudah pernah diretur
        if($transaction->retur){
            return back()->with('error', 'Transaksi ini sudah pernah diretur.');
        }

        // karyawan hanya bisa retur transaksi miliknya sendiri
        if($transaction->user_id !== Auth::id()){
            return back()->with('error', 'Anda tidak berhak meretur transaksi ini.');
        }

        DB::transaction(function() use ($request, $transaction){

            foreach($transaction->items as $item){

                // kembalikan serial jadi belum terjual
                ProductSerial::where('id', $item->product_serial_id)
                    ->update(['is_sold' => 0]);

                // tambah stok variant
                ProductVariant::where('id', $item->productSerial->product_variant_id)
                    ->increment('stock', 1);
            }

            // catat retur
            Retur::create([
                'transaction_id' => $transaction->id,
                'user_id'        => Auth::id(),
                'alasan'         => $request->alasan ?? null,
            ]);

            // update status transaksi
            $transaction->update(['status' => 'retur']);
        });

        return back()->with('success', 'Retur berhasil, stok sudah dikembalikan.');
    }
}