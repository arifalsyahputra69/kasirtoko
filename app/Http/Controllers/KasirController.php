<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->get();
        return view('karyawan.kasir.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'size' => 'required',
            'qty' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {

            // Ambil serial available
            $serials = ProductSerial::where('product_id', $request->product_id)
                ->where('size', $request->size)
                ->where('status', 'available')
                ->limit($request->qty)
                ->lockForUpdate()
                ->get();

            if ($serials->count() < $request->qty) {
                return back()->with('error', 'Stok tidak cukup!');
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'status' => 'completed',
                'total_cost' => 0,
                'total_selling' => 0,
                'total_profit' => 0
            ]);

            $totalCost = 0;
            $totalSelling = 0;
            $totalProfit = 0;

            foreach ($serials as $serial) {

                $profit = $request->selling_price - $serial->cost_price;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_serial_id' => $serial->id,
                    'cost_price' => $serial->cost_price,
                    'selling_price' => $request->selling_price,
                    'profit' => $profit
                ]);

                // Update serial jadi sold
                $serial->update([
                    'status' => 'sold'
                ]);

                $totalCost += $serial->cost_price;
                $totalSelling += $request->selling_price;
                $totalProfit += $profit;
            }

            // Update total transaksi
            $transaction->update([
                'total_cost' => $totalCost,
                'total_selling' => $totalSelling,
                'total_profit' => $totalProfit
            ]);

            DB::commit();

            return redirect()->route('karyawan.dashboard')
                ->with('success', 'Transaksi berhasil diproses!');

        } catch (\Exception $e) {

            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan!');
        }
    }
}