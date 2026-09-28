@extends('layouts.karyawan')

@section('content')

<style>

/* ===== BODY ===== */
body { background:#f4f6f9; }

/* ===== ANIMASI ===== */
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(24px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInLeft {
    from { opacity:0; transform:translateX(-24px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes fadeInRight {
    from { opacity:0; transform:translateX(24px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes pulse-icon {
    0%,100% { transform:scale(1); }
    50%      { transform:scale(1.15); }
}

/* ===== STAT CARDS ===== */
.stat-card {
    border-radius:18px;
    border:none;
    animation: fadeInUp .6s ease both;
    transition: transform .3s ease, box-shadow .3s ease;
    overflow:hidden;
    position:relative;
}
.stat-card::after {
    content:'';
    position:absolute;
    top:-30px; right:-30px;
    width:100px; height:100px;
    border-radius:50%;
    background:rgba(255,255,255,0.08);
}
.stat-card:hover {
    transform:translateY(-6px) scale(1.02);
    box-shadow:0 20px 40px rgba(0,0,0,.18) !important;
}
.stat-card:nth-child(1) { animation-delay:.1s; }
.stat-card:nth-child(2) { animation-delay:.2s; }
.stat-card:nth-child(3) { animation-delay:.3s; }
.stat-card:nth-child(4) { animation-delay:.4s; }

.stat-icon {
    width:52px; height:52px;
    border-radius:14px;
    background:rgba(255,255,255,0.2);
    display:flex; align-items:center; justify-content:center;
    font-size:22px;
    animation: pulse-icon 3s ease infinite;
}
.stat-label { font-size:12px; opacity:.8; letter-spacing:.5px; text-transform:uppercase; }
.stat-value { font-size:22px; font-weight:800; line-height:1.2; }

/* ===== GRAFIK CARDS ===== */
.chart-card {
    border-radius:18px;
    border:none;
    animation: fadeInUp .7s ease both;
    transition: box-shadow .3s ease;
}
.chart-card:hover { box-shadow:0 12px 30px rgba(0,0,0,.1) !important; }
.chart-card-left  { animation: fadeInLeft  .7s ease both; animation-delay:.2s; }
.chart-card-right { animation: fadeInRight .7s ease both; animation-delay:.3s; }
.chart-card-full  { animation: fadeInUp    .7s ease both; animation-delay:.4s; }

/* ===== TRANSAKSI TERBARU ===== */
.trx-card {
    border-radius:18px;
    border:none;
    animation: fadeInUp .7s ease both;
    animation-delay:.5s;
}
.trx-item {
    border-left:4px solid #198754;
    border-radius:0 10px 10px 0;
    margin-bottom:10px;
    padding:12px 16px;
    background:#fff;
    transition:.25s;
    animation: fadeInLeft .4s ease both;
}
.trx-item:hover {
    background:#f0fff4;
    transform:translateX(4px);
    box-shadow:0 4px 12px rgba(0,0,0,.06);
}
.trx-item:nth-child(1) { animation-delay:.1s; }
.trx-item:nth-child(2) { animation-delay:.2s; }
.trx-item:nth-child(3) { animation-delay:.3s; }
.trx-item:nth-child(4) { animation-delay:.4s; }
.trx-item:nth-child(5) { animation-delay:.5s; }

/* ===== JAM ===== */
.jam-box { text-align:right; }
.jam-box #tanggal { font-weight:700; font-size:14px; }
.jam-box #jam { font-size:22px; font-weight:800; color:#1D3557; letter-spacing:1px; }

/* ===== FAB ===== */
.fab-transaksi { display:none; }
@media(max-width:768px){
    .fab-transaksi {
        display:flex;
        position:fixed; bottom:25px; right:25px;
        width:65px; height:65px;
        background:#198754; color:white;
        border-radius:50%; font-size:26px;
        align-items:center; justify-content:center;
        text-decoration:none;
        box-shadow:0 10px 25px rgba(0,0,0,.25);
        z-index:999; transition:.2s;
    }
    .fab-transaksi:hover { transform:scale(1.08); background:#157347; }
}

</style>


<div class="container-fluid py-4">

    <!-- ===== HEADER ===== -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-0">Dashboard Karyawan</h2>
            <small class="text-muted">Ringkasan aktivitas hari ini</small>
        </div>
        <div class="jam-box">
            <div id="tanggal"></div>
            <div id="jam"></div>
        </div>
    </div>


    <!-- ===== STAT CARDS ===== -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#1D3557,#3a5d8c);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon">🧾</div>
                    <div>
                        <div class="stat-label">Transaksi Hari Ini</div>
                        <div class="stat-value">{{ $totalTransaksiHariIni }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#059669,#10b981);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon">💰</div>
                    <div>
                        <div class="stat-label">Pendapatan Hari Ini</div>
                        <div class="stat-value" style="font-size:16px;">Rp {{ number_format($pendapatanHariIni,0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon">📈</div>
                    <div>
                        <div class="stat-label">Profit Hari Ini</div>
                        <div class="stat-value" style="font-size:16px;">Rp {{ number_format($profitHariIni,0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#0891b2,#06b6d4);">
                <div class="card-body text-white d-flex align-items-center gap-3">
                    <div class="stat-icon">🗓️</div>
                    <div>
                        <div class="stat-label">Pendapatan Bulan Ini</div>
                        <div class="stat-value" style="font-size:16px;">Rp {{ number_format($pendapatanBulanIni,0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- ===== GRAFIK 7 HARI ===== -->
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="card chart-card chart-card-left shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-primary">📊 Jumlah Transaksi 7 Hari Terakhir</h6>
                <canvas id="transaksiChart"></canvas>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card chart-card chart-card-right shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-success">💵 Pendapatan 7 Hari Terakhir</h6>
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

    </div>


    <!-- ===== GRAFIK BULANAN ===== -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card chart-card chart-card-full shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-info">📅 Pendapatan Bulanan</h6>
                <canvas id="pendapatanBulananChart"></canvas>
            </div>
        </div>
    </div>


    <!-- ===== TRANSAKSI TERBARU ===== -->
    <div class="card trx-card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">🔔 Transaksi Terbaru</h6>
            <a href="{{ route('karyawan.transaksi.riwayat') }}" class="btn btn-outline-success btn-sm">
                Lihat Semua →
            </a>
        </div>

        @forelse($transaksiTerbaru as $trx)
            <div class="trx-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:14px;">
                            {{ $trx->created_at->format('d M Y') }}
                            <span class="text-muted fw-normal ms-1" style="font-size:13px;">
                                {{ $trx->created_at->format('H:i') }}
                            </span>
                        </div>
                        <div class="mt-1">
                            @if($trx->status === 'completed')
                                <span class="badge bg-success" style="font-size:11px;">✅ Selesai</span>
                            @else
                                <span class="badge bg-warning text-dark" style="font-size:11px;">⏳ {{ ucfirst($trx->status) }}</span>
                            @endif
                            <span class="badge bg-light text-dark border ms-1" style="font-size:11px;">
                                {{ $trx->items->count() }} item
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success" style="font-size:16px;">
                            Rp {{ number_format($trx->total_selling,0,',','.') }}
                        </div>
                        <div class="small text-muted">
                            Profit: Rp {{ number_format($trx->total_profit,0,',','.') }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                Belum ada transaksi
            </div>
        @endforelse
    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* ===== JAM REALTIME ===== */
function updateWaktu(){
    const now = new Date();
    document.getElementById("tanggal").innerHTML = now.toLocaleDateString('id-ID',{
        weekday:'long', year:'numeric', month:'long', day:'numeric'
    });
    document.getElementById("jam").innerHTML = now.toLocaleTimeString('id-ID');
}
setInterval(updateWaktu, 1000);
updateWaktu();


/* ===== DATA ===== */
const labels               = @json($dates);
const transaksiData        = @json($transaksiData);
const pendapatanData       = @json($pendapatanData);
const bulanLabels          = @json($bulanLabels);
const pendapatanBulananData = @json($pendapatanBulananData);


/* ===== CHART DEFAULTS ===== */
Chart.defaults.font.family = "'Segoe UI', sans-serif";
Chart.defaults.font.size   = 12;

const tooltipStyle = {
    backgroundColor: 'rgba(0,0,0,0.75)',
    titleColor: '#fff',
    bodyColor: '#ccc',
    padding: 10,
    cornerRadius: 8,
    displayColors: false,
};


/* ===== CHART TRANSAKSI ===== */
new Chart(document.getElementById('transaksiChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: transaksiData,
            borderColor: '#1D3557',
            backgroundColor: 'rgba(29,53,87,0.12)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1D3557',
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, labels: { color:'#444' } },
            tooltip: tooltipStyle,
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize:1, color:'#888' },
                grid: { color:'rgba(0,0,0,.05)' }
            },
            x: { ticks: { color:'#888' }, grid: { display:false } }
        }
    }
});


/* ===== CHART PENDAPATAN ===== */
new Chart(document.getElementById('pendapatanChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Pendapatan',
            data: pendapatanData,
            backgroundColor: 'rgba(5,150,105,0.75)',
            borderRadius: 8,
            borderSkipped: false,
            hoverBackgroundColor: '#059669',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, labels:{ color:'#444' } },
            tooltip: {
                ...tooltipStyle,
                callbacks: {
                    label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color:'#888',
                    callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                },
                grid: { color:'rgba(0,0,0,.05)' }
            },
            x: { ticks:{ color:'#888' }, grid:{ display:false } }
        }
    }
});


/* ===== CHART BULANAN ===== */
new Chart(document.getElementById('pendapatanBulananChart'), {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Pendapatan Bulanan',
            data: pendapatanBulananData,
            backgroundColor: bulanLabels.map((_,i) =>
                `hsla(${160 + i * 15}, 65%, 45%, 0.8)`
            ),
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, labels:{ color:'#444' } },
            tooltip: {
                ...tooltipStyle,
                callbacks: {
                    label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color:'#888',
                    callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                },
                grid: { color:'rgba(0,0,0,.05)' }
            },
            x: { ticks:{ color:'#888' }, grid:{ display:false } }
        }
    }
});

</script>

@endsection