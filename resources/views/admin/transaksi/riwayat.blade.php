@extends('layouts.admin')

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
    transform:translateY(-4px);
    box-shadow:0 12px 28px rgba(0,0,0,.12) !important;
}
.stat-card:nth-child(1) { animation-delay:.1s; }
.stat-card:nth-child(2) { animation-delay:.2s; }
.stat-card:nth-child(3) { animation-delay:.3s; }
.stat-card:nth-child(4) { animation-delay:.4s; }

.table-animated { animation: fadeInUp .6s ease both; animation-delay:.2s; }

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

.filter-shortcut .btn {
    border-radius:20px;
    font-size:13px;
    padding:5px 14px;
    transition:.2s;
}
.filter-shortcut .btn:hover { transform:translateY(-2px); }

.modal-item-row {
    border-bottom:1px solid #f0f0f0;
    padding:10px 0;
    animation: fadeInRow .3s ease both;
}
.modal-item-row:last-child { border-bottom:none; }

.modal-content { border-radius:18px; border:none; overflow:hidden; }
.modal-header-custom {
    background:linear-gradient(135deg,#0d6efd,#6610f2);
    color:white;
    border:none;
}

</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">📜 Riwayat Transaksi</h3>
    </div>

    {{-- ===== ALERT ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 border-0">
    <div class="card-body">

        <!-- ===== FORM FILTER ===== -->
        <form method="GET"
              action="{{ route('admin.transaksi.riwayat') }}"
              class="row g-3 mb-3"
              id="filter-form">

            <div class="col-12 col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date"
                       class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date"
                       class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="col-12 col-md-4 align-self-end d-flex gap-2">
                <button class="btn btn-primary">🔍 Filter</button>
                <a href="{{ route('admin.transaksi.riwayat') }}" class="btn btn-secondary">Reset</a>
            </div>

        </form>


        <!-- ===== SHORTCUT FILTER ===== -->
        <div class="filter-shortcut d-flex flex-wrap gap-2 mb-4">
            <span class="text-muted small align-self-center me-1">Filter Cepat:</span>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setFilter('today')">📅 Hari Ini</button>
            <button type="button" class="btn btn-outline-info btn-sm" onclick="setFilter('week')">📆 Minggu Ini</button>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="setFilter('month')">🗓️ Bulan Ini</button>
        </div>


        <!-- ===== STAT CARDS ===== -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100"
                     style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <div class="card-body text-white text-center">
                        <div class="small opacity-75 mb-1">Total Transaksi</div>
                        <div class="fw-bold fs-3">{{ $totalTransaksi }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100"
                     style="background:linear-gradient(135deg,#0891b2,#06b6d4);">
                    <div class="card-body text-white text-center">
                        <div class="small opacity-75 mb-1">Total Modal</div>
                        <div class="fw-bold fs-6">Rp {{ number_format($totalModal,0,',','.') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100"
                     style="background:linear-gradient(135deg,#db2777,#ec4899);">
                    <div class="card-body text-white text-center">
                        <div class="small opacity-75 mb-1">Total Penjualan</div>
                        <div class="fw-bold fs-6">Rp {{ number_format($totalJual,0,',','.') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100"
                     style="background:linear-gradient(135deg,#059669,#10b981);">
                    <div class="card-body text-white text-center">
                        <div class="small opacity-75 mb-1">Total Profit</div>
                        <div class="fw-bold fs-6">Rp {{ number_format($totalProfit,0,',','.') }}</div>
                    </div>
                </div>
            </div>

        </div>


        <!-- ===== TABEL ===== -->
        @if($transactions->count() > 0)

        <div class="table-responsive table-animated">
            <table class="table table-bordered table-hover align-middle table-mobile-card">

                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Karyawan</th>
                        <th>Barang Terjual</th>
                        <th>Total Modal</th>
                        <th>Total Jual</th>
                        <th>Total Profit</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $trx)
                    <tr>

                        <td data-label="No" class="text-center">
                            {{ $loop->iteration + ($transactions->currentPage()-1)*$transactions->perPage() }}
                        </td>

                        <td data-label="Tanggal">
                            {{ $trx->created_at->format('d M Y H:i') }}
                        </td>

                        <td data-label="Karyawan">
                            👤 {{ $trx->user->name ?? 'Tidak Diketahui' }}
                        </td>

                        <td data-label="Barang" class="text-center">
                            @if($trx->items->count())
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="showItems({{ $trx->id }})">
                                    🛍️ {{ $trx->items->count() }} item
                                </button>
                            @else
                                <span class="text-muted">Tidak ada item</span>
                            @endif
                        </td>

                        <td data-label="Total Modal"
                            class="{{ $trx->retur ? 'text-decoration-line-through text-muted' : '' }}">
                            Rp {{ number_format($trx->total_cost,0,',','.') }}
                        </td>

                        <td data-label="Total Jual"
                            class="{{ $trx->retur ? 'text-decoration-line-through text-muted' : '' }}">
                            Rp {{ number_format($trx->total_selling,0,',','.') }}
                        </td>

                        <td data-label="Profit"
                            class="fw-bold {{ $trx->retur ? 'text-decoration-line-through text-muted' : 'text-success' }}">
                            Rp {{ number_format($trx->total_profit,0,',','.') }}
                        </td>

                        <td data-label="Status" class="text-center">
                            @if($trx->retur)
                                <span class="badge bg-warning text-dark rounded-pill px-3">🔄 Retur</span>
                            @elseif($trx->status === 'completed')
                                <span class="badge bg-success rounded-pill px-3">✅ Selesai</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">⏳ {{ ucfirst($trx->status) }}</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $transactions->links() }}
        </div>

        @else

        <div class="text-center text-muted py-5">
            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
            Tidak ada transaksi pada rentang tanggal tersebut
        </div>

        @endif

    </div>
    </div>

</div>


<!-- ===== MODAL BARANG TERJUAL ===== -->
<div class="modal fade" id="itemsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title">🛍️ Detail Barang Terjual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-items-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>


<!-- DATA ITEMS HIDDEN -->
@foreach($transactions as $trx)
<div id="items-data-{{ $trx->id }}" style="display:none;">
    @foreach($trx->items as $item)
        @php
            $serial      = $item->productSerial;
            $variant     = $serial?->productVariant;
            $product     = $variant?->product;
            $variantText = '-';
            if($variant){
                if($variant->size)            $variantText = $variant->size;
                elseif($variant->peci_number) $variantText = 'Peci '.$variant->peci_number.' - '.$variant->peci_height;
                else                          $variantText = 'Simple';
            }
        @endphp
        <div class="modal-item-row">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold">{{ $product->brand ?? '-' }} {{ $product->type ?? '' }}</div>
                    <div class="small text-muted">Variant: {{ $variantText }}</div>
                </div>
                <span class="badge bg-success ms-2">
                    Rp {{ number_format($item->selling_price,0,',','.') }}
                </span>
            </div>
        </div>
    @endforeach
</div>
@endforeach


@endsection


@section('scripts')
<script>

    function setFilter(type){
        const today = new Date();
        let start, end;

        if(type === 'today'){
            start = end = formatDate(today);
        }
        else if(type === 'week'){
            const day  = today.getDay();
            const diff = today.getDate() - day + (day === 0 ? -6 : 1);
            const mon  = new Date(today.setDate(diff));
            const sun  = new Date(today.setDate(mon.getDate() + 6));
            start = formatDate(mon);
            end   = formatDate(sun);
        }
        else if(type === 'month'){
            const y = today.getFullYear();
            const m = today.getMonth();
            start = formatDate(new Date(y, m, 1));
            end   = formatDate(new Date(y, m+1, 0));
        }

        document.getElementById('start_date').value = start;
        document.getElementById('end_date').value   = end;
        document.getElementById('filter-form').submit();
    }

    function formatDate(d){
        const y  = d.getFullYear();
        const m  = String(d.getMonth()+1).padStart(2,'0');
        const dd = String(d.getDate()).padStart(2,'0');
        return `${y}-${m}-${dd}`;
    }

    function showItems(id){
        const data = document.getElementById('items-data-' + id);
        document.getElementById('modal-items-body').innerHTML =
            data ? data.innerHTML : '<p class="text-muted text-center py-3">Tidak ada data</p>';
        new bootstrap.Modal(document.getElementById('itemsModal')).show();
    }

</script>
@endsection