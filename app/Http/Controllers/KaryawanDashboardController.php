<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        $timezone = 'Asia/Jakarta';

        $today = Carbon::now($timezone)->toDateString();

        // =========================
        // STATISTIK HARI INI
        // =========================
        $totalTransaksiHariIni = Transaction::whereDate('created_at', $today)
            ->count();

        $pendapatanHariIni = Transaction::whereDate('created_at', $today)
            ->sum('total_selling');

        $profitHariIni = Transaction::whereDate('created_at', $today)
            ->sum('total_profit');

        // =========================
        // TOTAL SEMUA TRANSAKSI
        // =========================
        $totalSemuaTransaksi = Transaction::count();

        // =========================
        // DATA GRAFIK 7 HARI TERAKHIR
        // =========================
        $dates = [];
        $transaksiData = [];
        $pendapatanData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::now($timezone)->subDays($i);

            $dates[] = $date->format('d M');

            $transaksiData[] = Transaction::whereDate(
                'created_at',
                $date->toDateString()
            )->count();

            $pendapatanData[] = Transaction::whereDate(
                'created_at',
                $date->toDateString()
            )->sum('total_selling');
        }

        // =========================
        // PENDAPATAN BULAN INI
        // =========================
        $pendapatanBulanIni = Transaction::whereMonth('created_at', Carbon::now($timezone)->month)
            ->whereYear('created_at', Carbon::now($timezone)->year)
            ->sum('total_selling');

        // =========================
        // DATA GRAFIK BULANAN
        // =========================
        $bulanLabels = [];
        $pendapatanBulananData = [];

        for ($i = 1; $i <= 12; $i++) {

            $bulan = Carbon::create()
                ->month($i)
                ->locale('id')
                ->translatedFormat('F');

            $bulanLabels[] = $bulan;

            $pendapatanBulananData[] = Transaction::whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now($timezone)->year)
                ->sum('total_selling');
        }

        // =========================
        // 5 TRANSAKSI TERBARU
        // =========================
        $transaksiTerbaru = Transaction::latest()
            ->take(5)
            ->get();

        // =========================
        // RETURN VIEW
        // =========================
        return view('karyawan.dashboard', [
            'totalTransaksiHariIni' => $totalTransaksiHariIni,
            'pendapatanHariIni' => $pendapatanHariIni,
            'profitHariIni' => $profitHariIni,
            'totalSemuaTransaksi' => $totalSemuaTransaksi,
            'dates' => $dates,
            'transaksiData' => $transaksiData,
            'pendapatanData' => $pendapatanData,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'bulanLabels' => $bulanLabels,
            'pendapatanBulananData' => $pendapatanBulananData,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}