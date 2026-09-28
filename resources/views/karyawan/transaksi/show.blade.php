@extends('layouts.karyawan')

@section('content')

<style>

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInRow {
    from { opacity:0; transform:translateX(-10px); }
    to   { opacity:1; transform:translateX(0); }
}

.stat-card {
    border-radius:14px;
    border:none;
    animation: fadeInUp .5s ease both;
    transition: transform .3s ease, box-shadow .3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.15) !important;
}
.stat-card:nth-child(1) { animation-delay:.1s; }
.stat-card:nth-child(2) { animation-delay:.2s; }
.stat-card:nth-child(3) { animation-delay:.3s; }

.info-card {
    border-radius:14px;
    border:none;
    animation: fadeInUp .5s ease both;
    animation-delay:.1s;
}

.table-animated {
    animation: fadeInUp .6s ease both;
    animation-delay:.3s;
}

tbody tr { animation: fadeInRow .4s ease both; }
tbody tr:nth-child(1)  { animation-delay:.05s; }
tbody tr:nth-child(2)  { animation-delay:.10s; }
tbody tr:nth-child(3)  { animation-delay:.15s; }
tbody tr:nth-child(4)  { animation-delay:.20s; }
tbody tr:nth-child(5)  { animation-delay:.25s; }
tbody tr:nth-child(6)  { animation-delay:.30s; }
tbody tr:nth-child(7)  { animation-delay:.35s; }
tbody tr:nth-child(8)  { animation-delay:.40s; }
tbody tr:nth-child(9)  { animation-delay:.45s; }
tbody tr:nth-child(10) { animation-delay:.50s; }

.info-row {
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 0;
    border-bottom:1px solid #f0f0f0;
}
.info-row:last-child { border-bottom:none; }
.info-label {
    font-size:12px;
    color:#888;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.5px;
    min-width:100px;
}
.info-value {
    font-size:15px;
    font-weight:600;
    color:#222;
}

</style>

<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-primary mb-0">🧾 Detail Transaksi</h3>
        <a href="{{ route('karyawan.transaksi.riwayat') }}" class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>


    <!-- ===== INFO TRANSAKSI ===== -->
    <div class="card info-card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold" style="border-radius:14px 14px 0 0;">
            <i class="fas fa-info-circle me-2"></i>Informasi Transaksi
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">📅 Tanggal</div>
                        <div class="info-value">{{ $transaction->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">👤 Karyawan</div>
                        <div class="info-value">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" style="font-size:14px;">
                                👤 {{ $transaction->user->name ?? 'Tidak Diketahui' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">🆔 ID Transaksi</div>
                        <div class="info-value text-muted">No {{ $transaction->id }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">📦 Status</div>
                        <div class="info-value">
                            @if($transaction->status === 'completed')
                                <span class="badge bg-success px-3 py-2" style="font-size:13px;">
                                    ✅ Selesai
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2" style="font-size:13px;">
                                    ⏳ {{ ucfirst($transaction->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ===== STAT CARDS ===== -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-4">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#0891b2,#06b6d4);">
                <div class="card-body text-white text-center">
                    <div class="small opacity-75 mb-1">💰 Total Modal</div>
                    <div class="fw-bold fs-6">Rp {{ number_format($transaction->total_cost,0,',','.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#db2777,#ec4899);">
                <div class="card-body text-white text-center">
                    <div class="small opacity-75 mb-1">🏷️ Total Jual</div>
                    <div class="fw-bold fs-6">Rp {{ number_format($transaction->total_selling,0,',','.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#059669,#10b981);">
                <div class="card-body text-white text-center">
                    <div class="small opacity-75 mb-1">📈 Total Profit</div>
                    <div class="fw-bold fs-6">Rp {{ number_format($transaction->total_profit,0,',','.') }}</div>
                </div>
            </div>
        </div>

    </div>


    <!-- ===== TABEL PRODUK TERJUAL ===== -->
    <div class="card shadow-sm rounded-4 border-0 table-animated">
        <div class="card-header bg-dark text-white fw-bold" style="border-radius:14px 14px 0 0;">
            <i class="fas fa-box-open me-2"></i>Produk Terjual
            <span class="badge bg-white text-dark ms-2">{{ $transaction->items->count() }} item</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 table-mobile-card">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Color</th>
                            <th>Variant / Size</th>
                            <th>Qty</th>
                            <th>Harga Modal</th>
                            <th>Harga Jual</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($transaction->items as $item)
                            @php
                                $variant    = $item->productSerial->productVariant ?? null;
                                $product    = $variant->product ?? null;
                                $sizeOrPeci = '';
                                if($variant){
                                    $sizeOrPeci = $variant->size ?? '';
                                    if($variant->peci_number){
                                        $sizeOrPeci = "Peci {$variant->peci_number} - {$variant->peci_height}";
                                    }
                                }
                            @endphp
                            <tr>
                                <td data-label="No" class="text-center text-muted">
                                    {{ $no++ }}
                                </td>
                                <td data-label="Nama Produk" class="fw-semibold">
                                    {{ $product->brand ?? '-' }} {{ $product->name ?? '-' }}
                                </td>
                                <td data-label="Color">
                                    {{ $product->color ?? '-' }}
                                </td>
                                <td data-label="Variant">
                                    @if($sizeOrPeci)
                                        <span class="badge bg-light text-dark border">{{ $sizeOrPeci }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td data-label="Qty" class="text-center">
                                    <span class="badge bg-secondary">1</span>
                                </td>
                                <td data-label="Harga Modal" class="text-muted">
                                    Rp {{ number_format($item->cost_price,0,',','.') }}
                                </td>
                                <td data-label="Harga Jual" class="fw-semibold">
                                    Rp {{ number_format($item->selling_price,0,',','.') }}
                                </td>
                                <td data-label="Profit" class="fw-bold text-success">
                                    Rp {{ number_format($item->profit,0,',','.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection