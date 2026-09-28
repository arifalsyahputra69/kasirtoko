<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    // ==========================
    // DASHBOARD ADMIN
    // ==========================
    public function admin()
    {

        $today = Carbon::today();

        // ==========================
        // DATA KARYAWAN
        // ==========================
        $karyawans = User::where('role', 'karyawan')->get();

        // ==========================
        // 5 PRODUK TERBARU
        // ==========================
        $products = Product::with('variants')
            ->latest()
            ->take(5)
            ->get();

        // ==========================
        // TRANSAKSI HARI INI
        // ==========================
        $transactionsToday = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $totalTransaksiHariIni = $transactionsToday->count();
        $totalPendapatanHariIni = $transactionsToday->sum('total_selling');
        $totalProfitHariIni = $transactionsToday->sum('total_profit');

        // ==========================
        // RANKING KARYAWAN HARI INI
        // ==========================
        $penjualanPerKaryawan = Transaction::select(
                'user_id',
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(total_selling) as total_penjualan'),
                DB::raw('SUM(total_profit) as total_profit')
            )
            ->with('user')
            ->whereDate('created_at', $today)
            ->groupBy('user_id')
            ->orderByDesc('total_penjualan')
            ->get();

        // ==========================
        // TOP PRODUK BULAN INI
        // ==========================
        $topProductsMonthly = DB::table('transaction_items')
            ->join('product_serials', 'transaction_items.product_serial_id', '=', 'product_serials.id')
            ->join('product_variants', 'product_serials.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')

            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)

            ->select(
                'products.id',
                'products.brand',
                'products.type',
                'products.color',
                DB::raw('COUNT(transaction_items.id) as total_terjual')
            )

            ->groupBy(
                'products.id',
                'products.brand',
                'products.type',
                'products.color'
            )

            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'karyawans',
            'products',
            'transactionsToday',
            'totalTransaksiHariIni',
            'totalPendapatanHariIni',
            'totalProfitHariIni',
            'penjualanPerKaryawan',
            'topProductsMonthly'
        ));
    }


    // ==========================
    // HALAMAN PRODUK TERLARIS BULAN INI
    // ==========================
    public function topProductsMonthly(Request $request)
{

    $month = $request->month ?? now()->month;
    $year  = $request->year ?? now()->year;

    $topProductsMonthly = DB::table('transaction_items')
        ->join('product_serials', 'transaction_items.product_serial_id', '=', 'product_serials.id')
        ->join('product_variants', 'product_serials.product_variant_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')

        ->whereMonth('transactions.created_at', $month)
        ->whereYear('transactions.created_at', $year)

        ->select(
            'products.id',
            'products.brand',
            'products.type',
            'products.color',
            DB::raw('COUNT(transaction_items.id) as total_terjual')
        )

        ->groupBy(
            'products.id',
            'products.brand',
            'products.type',
            'products.color'
        )

        ->orderByDesc('total_terjual')
        ->limit(20)
        ->get();

    return view('admin.top_products_monthly', compact(
        'topProductsMonthly',
        'month',
        'year'
    ));
}
    // ==========================
    // DASHBOARD KARYAWAN
    // ==========================
    public function karyawan()
    {
        return view('karyawan.dashboard');
    }

}