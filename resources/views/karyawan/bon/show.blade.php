@extends('layouts.karyawan')

@section('content')

<style>
body { background:#f4f6f9; }

.item-row { border-bottom:1px solid #f0f0f0; padding:10px 0; }
.item-row:last-child { border-bottom:none; }

.payment-row { border-bottom:1px solid #f0f0f0; padding:8px 0; font-size:13px; }
.payment-row:last-child { border-bottom:none; }

.input-styled {
    border-radius:10px;
    border:2px solid #e2e8f0;
    padding:10px 14px;
    font-size:14px;
    transition:.2s;
}
.input-styled:focus {
    border-color:#0d6efd;
    box-shadow:0 0 0 3px rgba(13,110,253,.08);
}

/* ===== MOBILE ===== */
@media (max-width: 767px) {

    /* Summary 3 kolom jadi lebih compact */
    .summary-box .col-4 {
        padding: 0 4px;
    }
    .summary-box .p-3 {
        padding: 10px 6px !important;
    }
    .summary-box .small {
        font-size: 10px;
    }
    .summary-box .fw-bold {
        font-size: 13px;
    }

    /* Item barang layout vertikal di mobile */
    .item-row .d-flex {
        flex-direction: column;
        gap: 6px;
    }
    .item-row .text-end {
        text-align: left !important;
    }

    /* Judul bon header */
    .bon-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
}
</style>

<div class="container-fluid">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 bon-header">
        <h3 class="fw-bold text-primary mb-0">
            🧾 Bon #{{ str_pad($bon->id, 4, '0', STR_PAD_LEFT) }}
        </h3>
        <a href="{{ route('karyawan.bon.index') }}"
           class="btn btn-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Daftar Bon
        </a>
    </div>


    {{-- ===== ALERT ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <div class="row g-3">

        <!-- ===== KOLOM KIRI ===== -->
        <div class="col-lg-7">

            <!-- INFO BON -->
            <div class="card shadow-sm border-0 rounded-4 mb-3">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">👤 {{ $bon->nama_pembeli }}</h5>
                            <div class="small text-muted">
                                Dibuat oleh {{ $bon->user->name ?? '-' }} •
                                {{ $bon->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        @if($bon->status === 'lunas')
                            <span class="badge bg-success rounded-pill px-3 fs-6">✅ Lunas</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 fs-6">⏳ Cicil</span>
                        @endif
                    </div>

                    {{-- Summary 3 kotak --}}
                    <div class="row g-2 text-center summary-box">
                        <div class="col-4">
                            <div class="p-3 rounded-3" style="background:#f0fdf4;">
                                <div class="small text-muted">Total Tagihan</div>
                                <div class="fw-bold text-success">
                                    Rp {{ number_format($bon->total_tagihan,0,',','.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-3" style="background:#eff6ff;">
                                <div class="small text-muted">Sudah Dibayar</div>
                                <div class="fw-bold text-primary">
                                    Rp {{ number_format($bon->total_dibayar,0,',','.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded-3" style="background:#fef2f2;">
                                <div class="small text-muted">Sisa Tagihan</div>
                                <div class="fw-bold text-danger">
                                    Rp {{ number_format($bon->sisa_tagihan,0,',','.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <!-- DAFTAR BARANG -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header" style="background:#f8fafc; border-radius:16px 16px 0 0;">
                    <h6 class="mb-0 fw-bold">🛍️ Daftar Barang</h6>
                </div>
                <div class="card-body">

                    @foreach($bon->transaction->items as $item)
                        @php
                            $serial  = $item->productSerial;
                            $variant = $serial?->productVariant;
                            $product = $variant?->product;
                            $vText   = '-';
                            if($variant){
                                if($variant->size)            $vText = $variant->size;
                                elseif($variant->peci_number) $vText = 'Peci '.$variant->peci_number.' ('.$variant->peci_height.')';
                                else                          $vText = 'Simple';
                            }
                        @endphp
                        <div class="item-row {{ $item->is_returned ? 'opacity-50' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div style="flex:1;">
                                    <div class="fw-semibold">
                                        {{ $product?->brand ?? '-' }} {{ $product?->type ?? '' }}
                                        @if($item->is_returned)
                                            <span class="badge bg-danger ms-1" style="font-size:10px;">Retur</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">
                                        {{ $product?->category?->name ?? '-' }} • {{ $vText }}
                                    </div>
                                </div>
                                <div class="text-end" style="flex-shrink:0;">
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($item->selling_price,0,',','.') }}
                                    </div>
                                    {{-- TOMBOL RETUR PER ITEM --}}
                                    @if(!$item->is_returned && $bon->status !== 'lunas')
                                        <form method="POST"
                                              action="{{ route('karyawan.bon.retur-item', $bon->id) }}"
                                              onsubmit="return confirm('Yakin retur item ini? Stok akan dikembalikan dan tagihan berkurang.')">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-pill mt-1"
                                                    style="font-size:11px; white-space:nowrap;">
                                                🔄 Retur Item
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>


        <!-- ===== KOLOM KANAN ===== -->
        <div class="col-lg-5">

            <!-- TAMBAH CICILAN -->
            @if($bon->status !== 'lunas')
            <div class="card shadow-sm border-0 rounded-4 mb-3">
                <div class="card-header" style="background:#fffbeb; border-radius:16px 16px 0 0;">
                    <h6 class="mb-0 fw-bold text-warning">💰 Tambah Cicilan</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('karyawan.bon.cicilan', $bon->id) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="small fw-bold">Jumlah Bayar</label>
                            <input type="text"
                                   name="jumlah_bayar"
                                   id="jumlah_bayar"
                                   class="form-control input-styled"
                                   placeholder="Rp 0"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Keterangan (opsional)</label>
                            <input type="text"
                                   name="keterangan"
                                   class="form-control input-styled"
                                   placeholder="Contoh: DP, Cicilan 1...">
                        </div>
                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-dark">
                            <i class="bi bi-cash me-2"></i> Simpan Cicilan
                        </button>
                    </form>
                </div>
            </div>
            @endif


            <!-- RIWAYAT CICILAN -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header" style="background:#f8fafc; border-radius:16px 16px 0 0;">
                    <h6 class="mb-0 fw-bold">📋 Riwayat Cicilan</h6>
                </div>
                <div class="card-body">
                    @forelse($bon->payments as $payment)
                        <div class="payment-row">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div style="flex:1;">
                                    <div class="fw-semibold">
                                        Rp {{ number_format($payment->jumlah_bayar,0,',','.') }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $payment->user->name ?? '-' }} •
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </div>
                                    @if($payment->keterangan)
                                        <div class="small text-muted fst-italic">
                                            {{ $payment->keterangan }}
                                        </div>
                                    @endif
                                </div>
                                <span class="badge bg-success align-self-start mt-1">✅ Bayar</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center small py-3">Belum ada cicilan</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
<script>
new Cleave('#jumlah_bayar', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    prefix: 'Rp ',
    rawValueTrimPrefix: true
});

document.querySelector('form[action*="cicilan"]')?.addEventListener('submit', function(){
    const input = document.getElementById('jumlah_bayar');
    input.value = input.value.replace(/[^0-9]/g, '');
});
</script>
@endsection