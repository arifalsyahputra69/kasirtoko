<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController; // Controller Admin untuk transaksi & Excel
use App\Http\Controllers\DashboardController; // Controller Admin Dashboard
use App\Http\Controllers\KaryawanDashboardController;
use App\Http\Controllers\Karyawan\TransactionController as KaryawanTransactionController;
use App\Http\Controllers\Karyawan\ReturController;
use App\Http\Controllers\Karyawan\BonController;
use App\Http\Controllers\Admin\SalesController;

/*
|--------------------------------------------------------------------------
| ROOT ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('karyawan.dashboard');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| REDIRECT AFTER LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('karyawan.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // CRUD Karyawan
        Route::resource('karyawan', UserController::class)
            ->parameters(['karyawan' => 'user']);

        // CRUD Produk
        Route::resource('products', ProductController::class);

        // Riwayat Transaksi Admin
        Route::get('/riwayat-transaksi', [TransactionController::class, 'index'])
            ->name('transaksi.riwayat');

        /*
        |--------------------------------------------------------------------------
        | EXPORT EXCEL PENJUALAN
        |--------------------------------------------------------------------------
        */
        Route::get('/transaksi/export/daily', [TransactionController::class, 'exportDaily'])
            ->name('transaksi.export.daily');

        Route::get('/transaksi/export/monthly', [TransactionController::class, 'exportMonthly'])
            ->name('transaksi.export.monthly');

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS BULAN INI
        |--------------------------------------------------------------------------
        */
        Route::get('/top-products-monthly', [DashboardController::class, 'topProductsMonthly'])
            ->name('top-products.monthly');
            
        // REVISI: Dihapus awalan '/admin' pada URL dan 'admin.' pada name
        Route::get('/produk-terlaris', [SalesController::class, 'produkTerlaris'])
            ->name('produk-terlaris');
});

/*
|--------------------------------------------------------------------------
| KARYAWAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        // Dashboard Karyawan
        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');

        // POS / Transaksi
        Route::get('/transaksi', [KaryawanTransactionController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi', [KaryawanTransactionController::class, 'store'])->name('transaksi.store');

        // Riwayat Transaksi Karyawan
        Route::get('/transaksi/riwayat', [KaryawanTransactionController::class, 'riwayat'])->name('transaksi.riwayat');

        // Detail transaksi
        Route::get('/transaksi/detail/{transaction}', [KaryawanTransactionController::class, 'show'])
            ->name('transaksi.show');
        Route::post('/retur/{transaction}', [ReturController::class, 'store'])
            ->name('retur.store');
        Route::get('/bon', [BonController::class, 'index'])->name('bon.index');
        Route::get('/bon/create', [BonController::class, 'create'])->name('bon.create');
        Route::post('/bon', [BonController::class, 'store'])->name('bon.store');
        Route::get('/bon/{bon}', [BonController::class, 'show'])->name('bon.show');
        Route::post('/bon/{bon}/cicilan', [BonController::class, 'tambahCicilan'])->name('bon.cicilan');
        Route::post('/bon/{bon}/retur-item', [BonController::class, 'returItem'])->name('bon.retur-item');
    });