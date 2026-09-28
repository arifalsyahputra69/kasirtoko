<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    // ===========================
    // RIWAYAT TRANSAKSI ADMIN
    // ===========================
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items.productSerial.productVariant.product', 'retur']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59'
            ]);
        }

        // hitung stat exclude retur
        $queryStats     = clone $query;
        $statsData      = $queryStats->whereDoesntHave('retur')->get();
        $totalTransaksi = $statsData->count();
        $totalModal     = $statsData->sum('total_cost');
        $totalJual      = $statsData->sum('total_selling');
        $totalProfit    = $statsData->sum('total_profit');

        $transactions = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.transaksi.riwayat', compact(
            'transactions',
            'totalTransaksi',
            'totalModal',
            'totalJual',
            'totalProfit'
        ));
    }

    // ===========================
    // EXPORT EXCEL HARIAN
    // ===========================
    public function exportDaily()
    {
        $today = now()->format('Y-m-d');

        return Excel::download(new SalesExport($today, $today), "penjualan-harian-{$today}.xlsx");
    }

    // ===========================
    // EXPORT EXCEL BULANAN
    // ===========================
    public function exportMonthly()
    {
        $start = now()->startOfMonth()->format('Y-m-d');
        $end   = now()->endOfMonth()->format('Y-m-d');

        return Excel::download(new SalesExport($start, $end), "penjualan-bulanan-".now()->format('Y-m').".xlsx");
    }
}