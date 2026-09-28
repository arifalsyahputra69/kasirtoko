<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductSerial;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // ==========================
    // HALAMAN KASIR
    // ==========================
    public function index()
    {

        // ambil produk yang punya stok
        $products = Product::whereHas('variants', function($q){
            $q->where('stock','>',0);
        })
        ->with([
            'category', // ← tambahkan ini
            'variants' => function($q){
                $q->where('stock','>',0);
            }
        ])
        ->get();

        // ambil semua kategori untuk filter
        $categories = Category::orderBy('name')->get();

        return view('karyawan.transaksi.index', compact('products','categories'));
    }


    // ==========================
    // SIMPAN TRANSAKSI
    // ==========================
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'paid_amount' => 'required|string'
        ]);

        $cart = json_decode($request->cart, true);
        $paidAmount = (int) preg_replace('/[^0-9]/', '', $request->paid_amount);

        if (!$cart || count($cart) === 0) {
            return back()->with('error','Keranjang kosong!');
        }

        DB::beginTransaction();

        try {
            $totalCost = 0;
            $totalSelling = 0;
            $totalProfit = 0;
            $itemsToInsert = [];

            foreach ($cart as $item) {

                if ($item['selling_price'] <= 0) {
                    throw new \Exception("Harga jual belum diisi untuk produk {$item['name']}");
                }

                // ======================
                // CARI VARIANT
                // ======================
                $variantQuery = ProductVariant::where('product_id', $item['product_id'])
                    ->where('stock', '>', 0); // filter stock > 0

                if (!empty($item['size'])) {
                    if (str_contains($item['size'], 'Peci')) {
                        preg_match('/Peci (.*) - (.*)/', $item['size'], $match);
                        if (!$match) {
                            throw new \Exception("Format peci tidak valid");
                        }
                        $variantQuery->where('peci_number', trim($match[1]))
                                     ->where('peci_height', trim($match[2]));
                    } else {
                        $variantQuery->where('size', $item['size']);
                    }
                } else {
                    $variantQuery->whereNull('size');
                }

                $variant = $variantQuery->lockForUpdate()->first();

                if (!$variant) {
                    throw new \Exception("Variant tidak ditemukan atau stok habis untuk {$item['name']}");
                }

                if ($variant->stock < $item['qty']) {
                    throw new \Exception("Stok tidak cukup untuk {$item['name']}");
                }

                // ======================
                // AMBIL SERIAL
                // ======================
                $serials = ProductSerial::where('product_variant_id', $variant->id)
                    ->where('is_sold', 0)
                    ->lockForUpdate()
                    ->take($item['qty'])
                    ->get();

                if ($serials->count() < $item['qty']) {
                    throw new \Exception("Serial tidak cukup untuk {$item['name']}");
                }

                foreach ($serials as $serial) {
                    $cost = (int) preg_replace('/[^0-9]/', '', $item['cost_price']);
                    $selling = (int) preg_replace('/[^0-9]/', '', $item['selling_price']);
                    $profit = $selling - $cost;

                    $totalCost += $cost;
                    $totalSelling += $selling;
                    $totalProfit += $profit;

                    $itemsToInsert[] = [
                        'serial_id' => $serial->id,
                        'variant_id' => $variant->id,
                        'cost' => $cost,
                        'selling' => $selling,
                        'profit' => $profit
                    ];
                }
            }

            if ($paidAmount < $totalSelling) {
                throw new \Exception("Uang bayar kurang!");
            }

            // ======================
            // SIMPAN TRANSAKSI
            // ======================
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'status' => 'completed',
                'total_cost' => $totalCost,
                'total_selling' => $totalSelling,
                'total_profit' => $totalProfit,
            ]);

            // ======================
            // SIMPAN ITEM & UPDATE STOCK/SERIAL
            // ======================
            foreach ($itemsToInsert as $data) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_serial_id' => $data['serial_id'],
                    'cost_price' => $data['cost'],
                    'selling_price' => $data['selling'],
                    'profit' => $data['profit']
                ]);

                ProductSerial::where('id', $data['serial_id'])->update(['is_sold' => 1]);
                ProductVariant::where('id', $data['variant_id'])->decrement('stock', 1);
            }

            DB::commit();

            return redirect()
                ->route('karyawan.transaksi.riwayat')
                ->with('success','Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    // ==========================
    // RIWAYAT
    // ==========================
    public function riwayat(Request $request)
    {
        $query = Transaction::with(['user', 'items.productSerial.productVariant.product', 'retur']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59'
            ]);
        }

        // hitung stat dari semua data (bukan paginated), exclude yang retur
        $queryStats = clone $query;
        $statsData  = $queryStats->whereDoesntHave('retur')->get();

        $totalTransaksi = $statsData->count();
        $totalModal     = $statsData->sum('total_cost');
        $totalPenjualan = $statsData->sum('total_selling');
        $totalProfit    = $statsData->sum('total_profit');

        $transactions = $query->latest()->paginate(10)->appends($request->all());

        return view('karyawan.transaksi.riwayat', compact(
            'transactions',
            'totalTransaksi',
            'totalModal',
            'totalPenjualan',
            'totalProfit'
        ));
    }

    // ==========================
    // DETAIL
    // ==========================
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'items.productSerial.productVariant.product'])
            ->findOrFail($id);

        return view('karyawan.transaksi.show', compact('transaction'));
    }
}