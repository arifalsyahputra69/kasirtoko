<style>

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInRow {
    from { opacity:0; transform:translateX(-10px); }
    to   { opacity:1; transform:translateX(0); }
}

.table-wrap { animation: fadeInUp .5s ease both; }

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

.variant-wrap { display:flex; flex-wrap:wrap; gap:4px; }
.variant-badge {
    display:inline-flex;
    align-items:center;
    gap:4px;
    padding:3px 8px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
    border:1.5px solid transparent;
    transition:.2s;
}
.variant-badge:hover { transform:scale(1.08); }
.variant-size   { background:#e0f2fe; color:#0369a1; border-color:#bae6fd; }
.variant-peci   { background:#fef9c3; color:#854d0e; border-color:#fde68a; }
.variant-simple { background:#dcfce7; color:#166534; border-color:#bbf7d0; }

.stok-badge {
    display:inline-flex;
    align-items:center;
    gap:5px;
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}
.stok-aman   { background:#dcfce7; color:#166534; }
.stok-tipis  { background:#fef9c3; color:#854d0e; }
.stok-habis  { background:#fee2e2; color:#991b1b; }

.btn-aksi {
    width:32px; height:32px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:.2s;
    border:none;
    font-size:13px;
}
.btn-aksi:hover { transform:scale(1.15); }
.btn-edit  { background:#fef3c7; color:#d97706; }
.btn-edit:hover { background:#fde68a; }
.btn-hapus { background:#fee2e2; color:#dc2626; }
.btn-hapus:hover { background:#fecaca; }

</style>


<!-- ===== TABEL ===== -->
<div class="table-responsive table-wrap">
    <table class="table table-hover table-bordered align-middle table-mobile-card" id="tabel-produk">

        <thead class="table-success text-center">
            <tr>
                <th width="50">No</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Warna</th>
                <th>Harga</th>
                <th>Variant & Stok</th>
                <th width="110">Total Stok</th>
                <th width="100">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse ($products as $index => $product)

            @php
                $totalStock = $product->variants->sum('stock');
                $stokStatus = $totalStock > 10 ? 'aman' : ($totalStock > 0 ? 'tipis' : 'habis');
            @endphp

            <tr data-stok="{{ $stokStatus }}">

                <td data-label="No" class="text-center text-muted fw-semibold">
                    {{ $products->firstItem() + $index }}
                </td>

                <td data-label="Kategori" class="text-center">
                    <span class="badge bg-dark px-2 py-1" style="border-radius:20px;font-size:11px;">
                        {{ $product->category->name ?? '-' }}
                    </span>
                </td>

                <td data-label="Brand" class="fw-semibold">
                    {{ $product->brand }}
                </td>

                <td data-label="Type" class="text-muted">
                    {{ $product->type ?? '-' }}
                </td>

                <td data-label="Warna">
                    <span class="badge bg-secondary px-2 py-1" style="border-radius:20px;font-size:11px;">
                        {{ $product->color }}
                    </span>
                </td>

                <td data-label="Harga" class="fw-bold text-success">
                    Rp {{ number_format($product->cost_price,0,',','.') }}
                </td>

                <td data-label="Variant & Stok">
                    <div class="variant-wrap">
                        @forelse ($product->variants as $variant)
                            @if($variant->size)
                                <span class="variant-badge variant-size">
                                    <i class="fas fa-ruler-horizontal" style="font-size:9px;"></i>
                                    {{ $variant->size }}
                                    <span class="fw-bold">{{ $variant->stock }}</span>
                                </span>
                            @elseif($variant->peci_number)
                                <span class="variant-badge variant-peci">
                                    <i class="fas fa-hat-cowboy" style="font-size:9px;"></i>
                                    {{ $variant->peci_number }}({{ $variant->peci_height }})
                                    <span class="fw-bold">{{ $variant->stock }}</span>
                                </span>
                            @else
                                <span class="variant-badge variant-simple">
                                    <i class="fas fa-box" style="font-size:9px;"></i>
                                    Qty: <span class="fw-bold">{{ $variant->stock }}</span>
                                </span>
                            @endif
                        @empty
                            <span class="text-muted small">-</span>
                        @endforelse
                    </div>
                </td>

                <td data-label="Total Stok" class="text-center">
                    @if($stokStatus === 'aman')
                        <span class="stok-badge stok-aman">✅ {{ $totalStock }}</span>
                    @elseif($stokStatus === 'tipis')
                        <span class="stok-badge stok-tipis">⚠️ {{ $totalStock }}</span>
                    @else
                        <span class="stok-badge stok-habis">❌ Habis</span>
                    @endif
                </td>

                <td data-label="Aksi" class="text-center">
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="btn-aksi btn-edit me-1" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button"
                            class="btn-aksi btn-hapus"
                            title="Hapus"
                            onclick="konfirmasiHapus({{ $product->id }}, '{{ addslashes($product->brand) }} {{ addslashes($product->type) }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center text-muted py-5">
                    <i class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                    Produk tidak ditemukan
                </td>
            </tr>

        @endforelse

        </tbody>
    </table>
</div>

<!-- ===== PAGINATION ===== -->
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>