@extends('layouts.admin')

@section('content')

<style>
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(24px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInLeft {
    from { opacity:0; transform:translateX(-20px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes shimmerBar {
    from { width: 0%; }
    to   { width: var(--bar-width); }
}
@keyframes popIn {
    0%   { opacity:0; transform:scale(.7); }
    70%  { transform:scale(1.08); }
    100% { opacity:1; transform:scale(1); }
}
@keyframes rowIn {
    from { opacity:0; transform:translateX(-16px); }
    to   { opacity:1; transform:translateX(0); }
}

/* ===== PAGE HEADER ===== */
.page-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    animation: fadeInUp .5s ease both;
    box-shadow: 0 8px 32px rgba(245,158,11,.3);
}
.page-header::before {
    content: '🏆';
    position: absolute;
    right: 28px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 72px;
    opacity: .15;
    pointer-events: none;
}
.page-header::after {
    content: '';
    position: absolute;
    top: -40px; left: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.page-header h2 {
    color: white;
    font-weight: 800;
    font-size: 22px;
    margin: 0;
    position: relative;
}
.page-header p {
    color: rgba(255,255,255,.8);
    margin: 4px 0 0;
    font-size: 13px;
    position: relative;
}

/* ===== FILTER CARD ===== */
.filter-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    animation: fadeInUp .5s ease both;
    animation-delay: .1s;
    margin-bottom: 24px;
}
.filter-card .card-body { padding: 20px 24px; }

.filter-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #64748b;
    margin-bottom: 6px;
    display: block;
}

.filter-select {
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    padding: 9px 14px;
    font-size: 14px;
    font-weight: 600;
    background: #fafafa;
    transition: .2s;
    cursor: pointer;
}
.filter-select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 4px rgba(245,158,11,.1);
    outline: none;
    background: #fff;
}

/* ===== PODIUM — TOP 3 ===== */
.podium-wrap {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 12px;
    padding: 0 16px 16px;
    animation: fadeInUp .6s ease both;
    animation-delay: .2s;
}

.podium-item {
    flex: 1;
    max-width: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.podium-card {
    width: 100%;
    border-radius: 16px;
    padding: 16px 12px;
    text-align: center;
    position: relative;
    transition: transform .3s, box-shadow .3s;
    cursor: default;
}
.podium-card:hover {
    transform: translateY(-6px);
}

.podium-rank {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 800;
    animation: popIn .6s ease both;
}

.podium-1 {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border: 2px solid #fbbf24;
    box-shadow: 0 8px 24px rgba(251,191,36,.3);
}
.podium-1 .podium-rank { background: #f59e0b; color: white; animation-delay: .3s; }

.podium-2 {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border: 2px solid #94a3b8;
    box-shadow: 0 8px 24px rgba(148,163,184,.2);
}
.podium-2 .podium-rank { background: #64748b; color: white; animation-delay: .4s; }

.podium-3 {
    background: linear-gradient(135deg, #fef5ee, #fed7aa);
    border: 2px solid #fb923c;
    box-shadow: 0 8px 24px rgba(251,146,60,.2);
}
.podium-3 .podium-rank { background: #f97316; color: white; animation-delay: .5s; }

.podium-medal { font-size: 28px; margin-bottom: 4px; animation: popIn .6s ease both; }
.podium-1 .podium-medal { animation-delay: .35s; }
.podium-2 .podium-medal { animation-delay: .45s; }
.podium-3 .podium-medal { animation-delay: .55s; }

.podium-brand {
    font-weight: 800;
    font-size: 13px;
    color: #1e293b;
    line-height: 1.3;
}
.podium-type {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}
.podium-count {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    margin-top: 4px;
}
.podium-count-label {
    font-size: 10px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.podium-bar-wrap {
    width: 100%;
}
.podium-1 .podium-bar-wrap { height: 60px; }
.podium-2 .podium-bar-wrap { height: 40px; }
.podium-3 .podium-bar-wrap { height: 24px; }

.podium-base {
    width: 100%;
    height: 100%;
    border-radius: 10px 10px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
}
.podium-1 .podium-base { background: linear-gradient(180deg, #fbbf24, #f59e0b); color: white; }
.podium-2 .podium-base { background: linear-gradient(180deg, #94a3b8, #64748b); color: white; }
.podium-3 .podium-base { background: linear-gradient(180deg, #fb923c, #f97316); color: white; }

/* ===== TABEL RANKING ===== */
.ranking-card {
    border-radius: 18px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    overflow: hidden;
    animation: fadeInUp .6s ease both;
    animation-delay: .25s;
}

.ranking-card .card-header {
    background: linear-gradient(135deg, #1e293b, #334155);
    color: white;
    padding: 16px 24px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ranking-table thead th {
    background: #f8fafc;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #64748b;
    padding: 12px 16px;
    border-bottom: 2px solid #e2e8f0;
}

.ranking-table tbody tr {
    animation: rowIn .4s ease both;
    transition: background .2s;
    border-bottom: 1px solid #f1f5f9;
}
.ranking-table tbody tr:hover { background: #fffbeb; }

@for($i = 1; $i <= 20; $i++)
.ranking-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ $i * 0.04 }}s; }
@endfor

.ranking-table tbody td {
    padding: 12px 16px;
    font-size: 13px;
    vertical-align: middle;
}

.rank-num {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 13px;
}
.rank-1 { background: linear-gradient(135deg,#fbbf24,#f59e0b); color:white; box-shadow:0 3px 10px rgba(245,158,11,.4); }
.rank-2 { background: linear-gradient(135deg,#94a3b8,#64748b); color:white; box-shadow:0 3px 10px rgba(100,116,139,.3); }
.rank-3 { background: linear-gradient(135deg,#fb923c,#f97316); color:white; box-shadow:0 3px 10px rgba(249,115,22,.3); }
.rank-other { background: #f1f5f9; color: #64748b; }

.bar-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.bar-bg {
    flex: 1;
    height: 8px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    width: var(--bar-width);
    animation: shimmerBar .8s ease both;
    animation-delay: .3s;
}
.bar-fill.rank-1-bar { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.bar-fill.rank-2-bar { background: linear-gradient(90deg,#64748b,#94a3b8); }
.bar-fill.rank-3-bar { background: linear-gradient(90deg,#f97316,#fb923c); }
.bar-fill.rank-other-bar { background: linear-gradient(90deg,#6366f1,#818cf8); }

.bar-count {
    font-weight: 800;
    font-size: 14px;
    color: #1e293b;
    min-width: 28px;
    text-align: right;
}
</style>

<div class="container-fluid">

    <div class="page-header">
        <h2><i class="fas fa-trophy me-2"></i>Produk Terlaris</h2>
        <p>Ranking produk berdasarkan jumlah penjualan</p>
    </div>

    <div class="card filter-card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.produk-terlaris') }}" id="filterForm" class="row g-3 align-items-end">

                <div class="col-6 col-md-3">
                    <label class="filter-label">📅 Bulan</label>
                    <select name="month" class="form-select filter-select" onchange="document.getElementById('filterForm').submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $m, 1)->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <label class="filter-label">📆 Tahun</label>
                    <select name="year" class="form-select filter-select" onchange="document.getElementById('filterForm').submit()">
                        @for($y = 2023; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <div style="padding:6px 14px; background:#fffbeb; border-radius:10px; border:1px solid #fde68a; font-size:13px; color:#92400e;">
                        🗓️ Menampilkan data 
                        <strong>{{ \Carbon\Carbon::createFromDate(null, $month, 1)->isoFormat('MMMM') }} {{ $year }}</strong> 
                        — <strong>{{ count($topProductsMonthly) }}</strong> produk ditemukan
                    </div>
                </div>

            </form>
        </div>
    </div>


    @if(count($topProductsMonthly) > 0)

        @php $maxTerjual = $topProductsMonthly->first()->total_terjual ?? 1; @endphp

        {{-- REVISI: Mengubah >= 3 menjadi >= 1 agar podium tetap muncul jika baru ada 1 atau 2 produk yang laku --}}
        @if(count($topProductsMonthly) >= 1)
        <div class="card ranking-card mb-4" style="border-radius:18px; overflow:hidden;">
            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:20px;">🏆</span>
                    <span class="fw-700" style="font-size:15px; font-weight:700;">Top 3 Terbaik</span>
                </div>
            </div>
            <div class="card-body pt-5 pb-2">
                <div class="podium-wrap">

                    {{-- RANK 2 --}}
                    @if(isset($topProductsMonthly[1]))
                    @php $p2 = $topProductsMonthly[1]; @endphp
                    <div class="podium-item">
                        <div class="podium-card podium-2" style="animation: fadeInUp .5s ease .3s both; opacity:0;">
                            <div class="podium-rank">2</div>
                            <div class="podium-medal">🥈</div>
                            <div class="podium-brand">{{ $p2->brand }}</div>
                            <div class="podium-type">{{ $p2->type ?: $p2->color }}</div>
                            <div class="podium-count">{{ $p2->total_terjual }}</div>
                            <div class="podium-count-label">terjual</div>
                        </div>
                        <div class="podium-bar-wrap" style="height:40px;">
                            <div class="podium-base">2nd</div>
                        </div>
                    </div>
                    @endif

                    {{-- RANK 1 (Dipastikan ada karena cek count >= 1) --}}
                    @php $p1 = $topProductsMonthly[0]; @endphp
                    <div class="podium-item">
                        <div class="podium-card podium-1" style="animation: fadeInUp .5s ease .2s both; opacity:0;">
                            <div class="podium-rank">1</div>
                            <div class="podium-medal">🥇</div>
                            <div class="podium-brand">{{ $p1->brand }}</div>
                            <div class="podium-type">{{ $p1->type ?: $p1->color }}</div>
                            <div class="podium-count">{{ $p1->total_terjual }}</div>
                            <div class="podium-count-label">terjual</div>
                        </div>
                        <div class="podium-bar-wrap" style="height:60px;">
                            <div class="podium-base">1st</div>
                        </div>
                    </div>

                    {{-- RANK 3 --}}
                    @if(isset($topProductsMonthly[2]))
                    @php $p3 = $topProductsMonthly[2]; @endphp
                    <div class="podium-item">
                        <div class="podium-card podium-3" style="animation: fadeInUp .5s ease .4s both; opacity:0;">
                            <div class="podium-rank">3</div>
                            <div class="podium-medal">🥉</div>
                            <div class="podium-brand">{{ $p3->brand }}</div>
                            <div class="podium-type">{{ $p3->type ?: $p3->color }}</div>
                            <div class="podium-count">{{ $p3->total_terjual }}</div>
                            <div class="podium-count-label">terjual</div>
                        </div>
                        <div class="podium-bar-wrap" style="height:24px;">
                            <div class="podium-base">3rd</div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endif

        <div class="ranking-card">

            <div class="card-header">
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:18px;">📊</span>
                    <span style="font-size:15px; font-weight:700;">Ranking Lengkap</span>
                </div>
                <span class="badge" style="background:rgba(255,255,255,.15); font-size:12px; padding:5px 12px; border-radius:20px;">
                    {{ count($topProductsMonthly) }} produk
                </span>
            </div>

            <div class="table-responsive">
                <table class="table ranking-table table-mobile-card mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">Rank</th>
                            <th>Brand</th>
                            <th>Type</th>
                            <th>Warna</th>
                            <th>Jumlah Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($topProductsMonthly as $index => $item)
                        @php
                            $rank     = $index + 1;
                            $barPct   = round(($item->total_terjual / $maxTerjual) * 100);
                            $rankClass = match($rank) {
                                1 => 'rank-1',
                                2 => 'rank-2',
                                3 => 'rank-3',
                                default => 'rank-other'
                            };
                            $barClass = match($rank) {
                                1 => 'rank-1-bar',
                                2 => 'rank-2-bar',
                                3 => 'rank-3-bar',
                                default => 'rank-other-bar'
                            };
                            $medal = match($rank) {
                                1 => '🥇',
                                2 => '🥈',
                                3 => '🥉',
                                default => ''
                            };
                        @endphp
                        <tr>
                            <td data-label="Rank">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rank-num {{ $rankClass }}">{{ $rank }}</span>
                                    @if($medal)<span style="font-size:16px;">{{ $medal }}</span>@endif
                                </div>
                            </td>
                            <td data-label="Brand" class="fw-bold" style="color:#1e293b;">
                                {{ $item->brand }}
                            </td>
                            <td data-label="Type" style="color:#64748b;">
                                {{ $item->type ?: '-' }}
                            </td>
                            <td data-label="Warna">
                                <span class="badge" style="background:#f1f5f9; color:#475569; border-radius:20px; padding:4px 10px; font-size:11px; font-weight:600;">
                                    {{ $item->color ?: '-' }}
                                </span>
                            </td>
                            <td data-label="Terjual">
                                <div class="bar-wrap">
                                    <div class="bar-bg">
                                        <div class="bar-fill {{ $barClass }}" style="--bar-width:{{ $barPct }}%;"></div>
                                    </div>
                                    <div class="bar-count">{{ $item->total_terjual }}</div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div style="font-size:48px; opacity:.2;">📦</div>
                                <div class="mt-2">Belum ada produk terjual bulan ini</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else

        <div class="text-center py-5" style="animation: fadeInUp .5s ease both;">
            <div style="font-size:80px; opacity:.2; margin-bottom:16px;">🏆</div>
            <div style="font-size:18px; font-weight:700; color:#1e293b;">Belum Ada Data</div>
            <p class="text-muted mt-2">
                Tidak ada produk terjual pada
                <strong>{{ \Carbon\Carbon::createFromDate(null, $month, 1)->isoFormat('MMMM') }} {{ $year }}</strong>
            </p>
        </div>

    @endif

</div>

@endsection