@extends('layouts.admin')

@section('content')

<style>

/* ===== ANIMASI MASUK ===== */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to   { opacity: 1; transform: translateX(0); }
}

@keyframes fadeInRight {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes pulse-soft {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
    50%       { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
}

@keyframes shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

@keyframes rowFadeIn {
    from { opacity: 0; transform: translateX(-10px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ===== STAT CARDS ===== */
.stat-card {
    border-radius: 16px;
    border: none;
    transition: transform .3s ease, box-shadow .3s ease;
    animation: fadeInUp .6s ease both;
}
.stat-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,.2) !important;
}
.stat-card:nth-child(1) { animation-delay: .1s; }
.stat-card:nth-child(2) { animation-delay: .2s; }
.stat-card:nth-child(3) { animation-delay: .3s; }

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    animation: pulse-soft 2s infinite;
}

.stat-value {
    animation: countUp .8s ease both;
    animation-delay: .4s;
}

/* ===== SECTION CARDS ===== */
.section-card {
    border-radius: 16px;
    border: none;
    animation: fadeInUp .7s ease both;
}
.section-card .card-header {
    border-radius: 16px 16px 0 0 !important;
}
.section-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,.10) !important;
    transition: box-shadow .3s ease;
}

/* ===== ANIMASI DELAY PER SECTION ===== */
.section-left  { animation: fadeInLeft  .7s ease both; animation-delay: .3s; }
.section-right { animation: fadeInRight .7s ease both; animation-delay: .4s; }
.section-bot-left  { animation: fadeInLeft  .7s ease both; animation-delay: .5s; }
.section-bot-right { animation: fadeInRight .7s ease both; animation-delay: .6s; }

/* ===== TABEL ROW ANIMASI ===== */
tbody tr {
    animation: rowFadeIn .4s ease both;
}
tbody tr:nth-child(1)  { animation-delay: .05s; }
tbody tr:nth-child(2)  { animation-delay: .10s; }
tbody tr:nth-child(3)  { animation-delay: .15s; }
tbody tr:nth-child(4)  { animation-delay: .20s; }
tbody tr:nth-child(5)  { animation-delay: .25s; }
tbody tr:nth-child(6)  { animation-delay: .30s; }
tbody tr:nth-child(7)  { animation-delay: .35s; }
tbody tr:nth-child(8)  { animation-delay: .40s; }
tbody tr:nth-child(9)  { animation-delay: .45s; }
tbody tr:nth-child(10) { animation-delay: .50s; }

/* ===== BADGE ANIMASI ===== */
.badge {
    transition: transform .2s ease;
}
.badge:hover {
    transform: scale(1.15);
}

/* ===== BADGE RANK ===== */
.badge-rank {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

/* ===== HEADER TITLE ===== */
.dashboard-title {
    animation: fadeInLeft .6s ease both;
}
.dashboard-actions {
    animation: fadeInRight .6s ease both;
}

/* ===== SHIMMER LOADING EFFECT ON CARD HEADER ===== */
.card-header {
    position: relative;
    overflow: hidden;
}
.card-header::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
    background-size: 200% 100%;
    animation: shimmer 3s infinite;
}

</style>

<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="dashboard-title">
            <h2 class="fw-bold mb-0">Dashboard Admin</h2>
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</small>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('admin.transaksi.export.daily') }}" class="btn btn-outline-primary btn-sm me-2">
                <i class="fas fa-file-excel me-1"></i> Export Harian
            </a>
            <a href="{{ route('admin.transaksi.export.monthly') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Export Bulanan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- ================= STAT CARDS ================= -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-4" style="animation-delay:.1s">
            <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg,#4f46e5,#7c3aed);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Transaksi Hari Ini</div>
                        <div class="fw-bold fs-3 stat-value">{{ $totalTransaksiHariIni }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4" style="animation-delay:.2s">
            <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg,#0891b2,#06b6d4);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pendapatan Hari Ini</div>
                        <div class="fw-bold fs-6 stat-value">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4" style="animation-delay:.3s">
            <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg,#059669,#10b981);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Profit Hari Ini</div>
                        <div class="fw-bold fs-6 stat-value">Rp {{ number_format($totalProfitHariIni, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row g-3 mb-4">

        <!-- ================= TRANSAKSI HARI INI ================= -->
        <div class="col-12 col-md-7">
            <div class="card section-card section-left shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clock me-2"></i>Transaksi Hari Ini</span>
                    <span class="badge bg-white text-primary">{{ $totalTransaksiHariIni }}</span>
                </div>
                <div class="card-body p-0" style="max-height:350px; overflow-y:auto;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Waktu</th>
                                <th>Karyawan</th>
                                <th>Total</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactionsToday as $trx)
                                <tr>
                                    <td class="small text-muted">{{ $trx->created_at->format('H:i') }}</td>
                                    <td>{{ $trx->user->name ?? '-' }}</td>
                                    <td class="fw-semibold text-success">Rp {{ number_format($trx->total_selling, 0, ',', '.') }}</td>
                                    <td class="fw-semibold text-primary">Rp {{ number_format($trx->total_profit, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada transaksi hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ================= RANKING KARYAWAN ================= -->
        <div class="col-12 col-md-5">
            <div class="card section-card section-right shadow-sm h-100">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-trophy me-2"></i>Ranking Karyawan Hari Ini</span>
                </div>
                <div class="card-body p-0" style="max-height:350px; overflow-y:auto;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>No</th>
                                <th>Karyawan</th>
                                <th>Transaksi</th>
                                <th>Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualanPerKaryawan as $i => $p)
                                <tr>
                                    <td>
                                        @if($i == 0)
                                            <span class="badge-rank bg-warning text-dark">🥇</span>
                                        @elseif($i == 1)
                                            <span class="badge-rank bg-secondary text-white">🥈</span>
                                        @elseif($i == 2)
                                            <span class="badge-rank" style="background:#cd7f32;color:white;">🥉</span>
                                        @else
                                            <span class="badge-rank bg-light text-dark border">{{ $i+1 }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->user->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $p->total_transaksi }}</span>
                                    </td>
                                    <td class="fw-semibold text-success small">Rp {{ number_format($p->total_penjualan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                                        Belum ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <div class="row g-3">

        <!-- ================= TOP PRODUK BULAN INI ================= -->
        <div class="col-12 col-md-6">
            <div class="card section-card section-bot-left shadow-sm h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-fire me-2"></i>Top Produk Bulan Ini</span>
                    <span class="badge bg-white text-success">{{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProductsMonthly as $i => $p)
                                <tr>
                                    <td>
                                        @if($i == 0) 🥇
                                        @elseif($i == 1) 🥈
                                        @elseif($i == 2) 🥉
                                        @else <span class="text-muted">{{ $i+1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $p->brand }}</div>
                                        <div class="small text-muted">{{ $p->type }} · {{ $p->color }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $p->total_terjual }} pcs</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        Belum ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ================= PRODUK TERBARU ================= -->
        <div class="col-12 col-md-6">
            <div class="card section-card section-bot-right shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-box-open me-2"></i>5 Produk Terbaru
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Color</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                                <tr>
                                    <td class="fw-semibold">{{ $p->brand }}</td>
                                    <td>{{ $p->type ?? '-' }}</td>
                                    <td>{{ $p->color }}</td>
                                    <td>
                                        @php $stok = $p->variants->sum('stock'); @endphp
                                        @if($stok <= 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($stok <= 3)
                                            <span class="badge bg-warning text-dark">Tipis ({{ $stok }})</span>
                                        @else
                                            <span class="badge bg-success">{{ $stok }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        Belum ada produk
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection