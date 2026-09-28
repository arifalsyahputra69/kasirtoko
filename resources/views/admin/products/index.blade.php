@extends('layouts.admin')

@section('content')

<style>

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
#product-table {
    animation: fadeInUp .4s ease both;
}
#product-table.reloading {
    animation: none;
}

@keyframes shimmer {
    0%   { background-position:-600px 0; }
    100% { background-position: 600px 0; }
}

.skeleton-wrap { padding:4px 0; }
.skeleton-row {
    display:flex;
    gap:12px;
    padding:14px 16px;
    border-bottom:1px solid #f0f0f0;
    animation: fadeInUp .3s ease both;
}
.skeleton-row:nth-child(1) { animation-delay:.05s; }
.skeleton-row:nth-child(2) { animation-delay:.10s; }
.skeleton-row:nth-child(3) { animation-delay:.15s; }
.skeleton-row:nth-child(4) { animation-delay:.20s; }
.skeleton-row:nth-child(5) { animation-delay:.25s; }

.skeleton-cell {
    height:18px;
    border-radius:6px;
    background: linear-gradient(90deg, #e8e8e8 25%, #f5f5f5 50%, #e8e8e8 75%);
    background-size:600px 100%;
    animation: shimmer 1.4s infinite;
}

.search-input {
    border-radius:30px;
    padding-left:18px;
    border:1.5px solid #dee2e6;
    transition:.2s;
}
.search-input:focus {
    border-color:#198754;
    box-shadow:0 0 0 3px rgba(25,135,84,.1);
}
.filter-select {
    border-radius:30px;
    border:1.5px solid #dee2e6;
    transition:.2s;
}
.filter-select:focus {
    border-color:#198754;
    box-shadow:0 0 0 3px rgba(25,135,84,.1);
}

</style>


<div class="card shadow border-0">

    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i> Kelola Produk
        </h5>
        <a href="{{ route('admin.products.create') }}"
           class="btn btn-light btn-sm rounded-pill px-3">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>

    <div class="card-body">

        <!-- ===== SEARCH & FILTER ===== -->
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-5">
                <input type="text"
                       id="search-produk"
                       class="form-control search-input"
                       placeholder="🔍 Cari brand, type, warna...">
            </div>
            <div class="col-6 col-md-3">
                <select id="filter-kategori" class="form-select filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select id="filter-stok" class="form-select filter-select">
                    <option value="">Semua Stok</option>
                    <option value="aman">Stok Aman</option>
                    <option value="tipis">Stok Tipis</option>
                    <option value="habis">Stok Habis</option>
                </select>
            </div>
        </div>

        <!-- ===== TABEL ===== -->
        <div id="product-table">
            @include('admin.products.partials.table')
        </div>

    </div>

</div>

@endsection


@section('scripts')
<script>

document.addEventListener("DOMContentLoaded", function(){

    const BASE_URL       = "{{ route('admin.products.index') }}";
    const tableContainer = document.getElementById("product-table");
    let   searchTimer    = null;
    let   currentSearch  = '';
    let   currentKategori= '';
    let   currentStok    = '';


    /* ===== SKELETON ===== */
    function skeletonHTML(){
        let rows = '';
        for(let i = 0; i < 5; i++){
            rows += `
            <div class="skeleton-row">
                <div class="skeleton-cell" style="width:40px;"></div>
                <div class="skeleton-cell" style="width:90px;"></div>
                <div class="skeleton-cell" style="width:120px;"></div>
                <div class="skeleton-cell" style="width:100px;"></div>
                <div class="skeleton-cell" style="width:80px;"></div>
                <div class="skeleton-cell" style="width:100px;"></div>
                <div class="skeleton-cell" style="min-width:120px;flex:1;"></div>
                <div class="skeleton-cell" style="width:70px;"></div>
                <div class="skeleton-cell" style="width:80px;"></div>
            </div>`;
        }
        return `<div class="skeleton-wrap">${rows}</div>`;
    }


    /* ===== BUILD URL ===== */
    function buildUrl(page){
        let params = [];
        if(currentSearch)   params.push('search='   + encodeURIComponent(currentSearch));
        if(currentKategori) params.push('category=' + encodeURIComponent(currentKategori));
        if(page)            params.push('page='     + page);
        return BASE_URL + (params.length ? '?' + params.join('&') : '');
    }


    /* ===== LOAD TABLE ===== */
    function loadTable(url){
        tableContainer.innerHTML = skeletonHTML();
        tableContainer.classList.add('reloading');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(data => {
            tableContainer.classList.remove('reloading');
            tableContainer.innerHTML = data;
            tableContainer.style.animation = 'none';
            void tableContainer.offsetWidth;
            tableContainer.style.animation = '';
            applyStok();
        })
        .catch(err => {
            tableContainer.innerHTML = '<p class="text-danger text-center py-4">Gagal memuat data.</p>';
        });
    }


    /* ===== FILTER STOK CLIENT-SIDE ===== */
    function applyStok(){
        const rows = tableContainer.querySelectorAll('#tabel-produk tbody tr');

        rows.forEach(row => {
            if(!row.dataset.stok) return;
            const show = currentStok === '' || row.dataset.stok === currentStok;
            row.style.display = show ? '' : 'none';
        });
    }


    /* ===== SEARCH ===== */
    document.getElementById('search-produk').addEventListener('keyup', function(){
        currentSearch = this.value.trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function(){
            loadTable(buildUrl());
        }, 400);
    });


    /* ===== FILTER KATEGORI ===== */
    document.getElementById('filter-kategori').addEventListener('change', function(){
        currentKategori = this.value;
        loadTable(buildUrl());
    });


    /* ===== FILTER STOK ===== */
    document.getElementById('filter-stok').addEventListener('change', function(){
        currentStok = this.value;
        applyStok();
    });


    /* ===== PAGINATION ===== */
    document.addEventListener('click', function(e){
        const link = e.target.closest('.pagination a');
        if(!link) return;
        e.preventDefault();
        const href   = link.getAttribute('href');
        const urlObj = new URL(href, window.location.origin);
        const page   = urlObj.searchParams.get('page');
        loadTable(buildUrl(page));
    });


    /* ===== MODAL HAPUS ===== */
    window.konfirmasiHapus = function(id, nama){
        const existing = document.getElementById('modalHapusDinamis');
        if(existing) existing.remove();

        const modalHtml = `
        <div class="modal fade" id="modalHapusDinamis" tabindex="-1" style="z-index:99999;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:18px;border:none;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);">
                    <div class="modal-header" style="background:linear-gradient(135deg,#dc2626,#ef4444);color:white;border:none;">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-trash me-2"></i> Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div style="font-size:48px;" class="mb-3">🗑️</div>
                        <p class="mb-1">Yakin ingin menghapus produk:</p>
                        <p class="fw-bold fs-5">${nama}</p>
                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <form method="POST" action="/admin/products/${id}" class="d-inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger rounded-pill px-4">
                                <i class="fas fa-trash me-1"></i> Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modalEl = document.getElementById('modalHapusDinamis');
        new bootstrap.Modal(modalEl).show();
        modalEl.addEventListener('hidden.bs.modal', function(){ this.remove(); });
    }

});

</script>
@endsection