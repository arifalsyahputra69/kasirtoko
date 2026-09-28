<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Halaman Produk Terlaris
     */
    public function produkTerlaris(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $topProductsMonthly = DB::table('transaction_items')
            // 1. Join ke tabel transaksi (untuk filter tanggal)
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            
            // 2. Join ke tabel serial produk
            ->join('product_serials', 'transaction_items.product_serial_id', '=', 'product_serials.id')
            
            // 3. Join ke tabel varian produk (berdasarkan struktur DB kamu)
            ->join('product_variants', 'product_serials.product_variant_id', '=', 'product_variants.id')
            
            // 4. Join ke tabel produk utama (dari tabel varian)
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            
            // Filter berdasarkan bulan, tahun, dan barang yang tidak diretur
            ->whereMonth('transactions.created_at', $month)
            ->whereYear('transactions.created_at', $year)
            ->where('transaction_items.is_returned', false)
            
            ->select(
                'products.brand',
                'products.type',
                'products.color',
                // Menghitung jumlah item fisik yang terjual
                DB::raw('COUNT(transaction_items.id) as total_terjual')
            )
            // Disamakan group-nya dengan kolom yang diselect agar tidak memicu error 'sql_mode=only_full_group_by'
            ->groupBy('products.brand', 'products.type', 'products.color')
            ->orderByDesc('total_terjual')
            ->get();

        // REVISI UTAMA: Mengubah rute view agar sesuai dengan file top_products_monthly.blade.php
        return view('admin.top_products_monthly', compact('topProductsMonthly', 'month', 'year'));
    }

    /**
     * Export penjualan ke Excel.
     */
    public function export(Request $request)
    {
        $type = $request->query('type', 'daily');

        if ($type === 'daily') {
            $start = now()->format('Y-m-d');
            $end = null;
            $fileName = "penjualan_harian_{$start}.xlsx";
        } elseif ($type === 'monthly') {
            $start = now()->startOfMonth()->format('Y-m-d');
            $end = now()->endOfMonth()->format('Y-m-d');
            $fileName = "penjualan_bulanan_" . now()->format('Y_m') . ".xlsx";
        } else {
            $start = $request->query('start');
            $end = $request->query('end');
            $fileName = "penjualan.xlsx";
        }

        return Excel::download(new SalesExport($type, $start, $end), $fileName);
    }
}