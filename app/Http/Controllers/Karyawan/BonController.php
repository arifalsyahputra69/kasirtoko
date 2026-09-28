<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bon;
use App\Models\BonPayment;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductSerial;
use App\Models\Category;

class BonController extends Controller
{
    /* ===========================
    | INDEX - DAFTAR SEMUA BON
    =========================== */
    public function index()
    {
        $bons = Bon::with(['transaction', 'user'])
                   ->latest()
                   ->paginate(10);

        return view('karyawan.bon.index', compact('bons'));
    }


    /* ===========================
    | CREATE - HALAMAN BON BARU
    =========================== */
    public function create()
    {
        $products = Product::whereHas('variants', function($q){
            $q->where('stock', '>', 0);
        })
        ->with([
            'category',
            'variants' => function($q){
                $q->where('stock', '>', 0);
            }
        ])
        ->get();

        $categories = Category::orderBy('name')->get();

        return view('karyawan.bon.create', compact('products', 'categories'));
    }


    /* ===========================
    | STORE - SIMPAN BON BARU
    =========================== */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'cart'         => 'required|json',
        ]);

        $cart = json_decode($request->cart, true);

        if(!$cart || count($cart) === 0){
            return back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();

        try {

            $totalCost    = 0;
            $totalSelling = 0;
            $totalProfit  = 0;
            $itemsToInsert = [];

            foreach ($cart as $item) {

                if($item['selling_price'] <= 0){
                    throw new \Exception("Harga jual belum diisi untuk {$item['name']}");
                }

                $variantQuery = ProductVariant::where('product_id', $item['product_id'])
                    ->where('stock', '>', 0);

                if(!empty($item['size'])){
                    if(str_contains($item['size'], 'Peci')){
                        preg_match('/Peci (.*) - (.*)/', $item['size'], $match);
                        if(!$match) throw new \Exception("Format peci tidak valid");
                        $variantQuery->where('peci_number', trim($match[1]))
                                     ->where('peci_height', trim($match[2]));
                    } else {
                        $variantQuery->where('size', $item['size']);
                    }
                } else {
                    $variantQuery->whereNull('size');
                }

                $variant = $variantQuery->lockForUpdate()->first();

                if(!$variant){
                    throw new \Exception("Variant tidak ditemukan atau stok habis untuk {$item['name']}");
                }

                if($variant->stock < $item['qty']){
                    throw new \Exception("Stok tidak cukup untuk {$item['name']}");
                }

                $serials = ProductSerial::where('product_variant_id', $variant->id)
                    ->where('is_sold', 0)
                    ->lockForUpdate()
                    ->take($item['qty'])
                    ->get();

                if($serials->count() < $item['qty']){
                    throw new \Exception("Serial tidak cukup untuk {$item['name']}");
                }

                foreach ($serials as $serial) {
                    $cost    = (int) preg_replace('/[^0-9]/', '', $item['cost_price']);
                    $selling = (int) preg_replace('/[^0-9]/', '', $item['selling_price']);
                    $profit  = $selling - $cost;

                    $totalCost    += $cost;
                    $totalSelling += $selling;
                    $totalProfit  += $profit;

                    $itemsToInsert[] = [
                        'serial_id'  => $serial->id,
                        'variant_id' => $variant->id,
                        'cost'       => $cost,
                        'selling'    => $selling,
                        'profit'     => $profit,
                    ];
                }
            }

            // buat transaksi baru khusus bon
            $transaction = Transaction::create([
                'user_id'       => Auth::id(),
                'status'        => 'bon',
                'total_cost'    => $totalCost,
                'total_selling' => $totalSelling,
                'total_profit'  => $totalProfit,
            ]);

            // simpan item
            foreach ($itemsToInsert as $data) {
                TransactionItem::create([
                    'transaction_id'    => $transaction->id,
                    'product_serial_id' => $data['serial_id'],
                    'cost_price'        => $data['cost'],
                    'selling_price'     => $data['selling'],
                    'profit'            => $data['profit'],
                    'is_returned'       => false,
                ]);

                ProductSerial::where('id', $data['serial_id'])->update(['is_sold' => 1]);
                ProductVariant::where('id', $data['variant_id'])->decrement('stock', 1);
            }

            // buat bon
            $bon = Bon::create([
                'transaction_id' => $transaction->id,
                'user_id'        => Auth::id(),
                'nama_pembeli'   => $request->nama_pembeli,
                'total_tagihan'  => $totalSelling,
                'total_dibayar'  => 0,
                'sisa_tagihan'   => $totalSelling,
                'status'         => 'cicil',
            ]);

            DB::commit();

            return redirect()->route('karyawan.bon.show', $bon->id)
                             ->with('success', 'Bon berhasil dibuat!');

        } catch(\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    /* ===========================
    | SHOW - DETAIL BON
    =========================== */
    public function show($id)
    {
        $bon = Bon::with([
            'transaction.items.productSerial.productVariant.product.category',
            'transaction.user',
            'payments.user',
            'user',
        ])->findOrFail($id);

        return view('karyawan.bon.show', compact('bon'));
    }


    /* ===========================
    | TAMBAH CICILAN
    =========================== */
    public function tambahCicilan(Request $request, $id)
    {
        $bon = Bon::findOrFail($id);

        $request->validate([
            'jumlah_bayar' => 'required|string',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        $jumlahBayar = (int) preg_replace('/[^0-9]/', '', $request->jumlah_bayar);

        if($jumlahBayar <= 0){
            return back()->with('error', 'Jumlah bayar tidak valid.');
        }

        if($jumlahBayar > $bon->sisa_tagihan){
            return back()->with('error', 'Jumlah bayar melebihi sisa tagihan.');
        }

        DB::transaction(function() use ($bon, $jumlahBayar, $request){

            BonPayment::create([
                'bon_id'       => $bon->id,
                'user_id'      => Auth::id(),
                'jumlah_bayar' => $jumlahBayar,
                'keterangan'   => $request->keterangan ?? null,
            ]);

            $bon->increment('total_dibayar', $jumlahBayar);
            $bon->decrement('sisa_tagihan', $jumlahBayar);

            $bon->refresh();
            if($bon->sisa_tagihan <= 0){
                $bon->update(['status' => 'lunas']);
            }
        });

        return back()->with('success', 'Cicilan berhasil ditambahkan.');
    }


    /* ===========================
    | RETUR ITEM
    =========================== */
    public function returItem(Request $request, $id)
    {
        $bon  = Bon::findOrFail($id);
        $item = TransactionItem::findOrFail($request->item_id);

        if($item->is_returned){
            return back()->with('error', 'Item ini sudah diretur.');
        }

        DB::transaction(function() use ($bon, $item){

            // kembalikan serial
            ProductSerial::where('id', $item->product_serial_id)
                ->update(['is_sold' => 0]);

            // tambah stok variant
            $variant = $item->productSerial?->productVariant;
            if($variant){
                $variant->increment('stock', 1);
            }

            // tandai item sudah diretur
            $item->update(['is_returned' => true]);

            // kurangi total tagihan bon
            $bon->decrement('total_tagihan', $item->selling_price);
            $bon->decrement('sisa_tagihan', $item->selling_price);

            // update transaksi
            $bon->transaction->decrement('total_cost', $item->cost_price);
            $bon->transaction->decrement('total_selling', $item->selling_price);
            $bon->transaction->decrement('total_profit', $item->profit);

            // cek apakah sudah lunas
            $bon->refresh();
            if($bon->sisa_tagihan <= 0){
                $bon->update(['status' => 'lunas']);
            }
        });

        return back()->with('success', 'Item berhasil diretur, stok dikembalikan.');
    }
}