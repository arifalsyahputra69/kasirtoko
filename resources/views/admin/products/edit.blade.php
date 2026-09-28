@extends('layouts.admin')

@section('content')

<style>

body { background:#f4f6f9; }

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInSection {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-16px); }
    to   { opacity:1; transform:translateY(0); }
}

.form-card {
    animation: fadeInUp .5s ease both;
    border-radius:16px;
}
.form-section {
    animation: fadeInSection .4s ease both;
}

/* ===== ALERT WARNING DUPLIKAT ===== */
.alert-duplicate {
    animation: slideDown .4s ease both;
    border-radius: 14px;
    border: none;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-left: 5px solid #f59e0b;
    box-shadow: 0 4px 16px rgba(245,158,11,.15);
    margin-bottom: 24px;
}
.alert-duplicate .alert-icon { font-size:22px; color:#d97706; margin-top:1px; flex-shrink:0; }
.alert-duplicate .alert-body { flex:1; }
.alert-duplicate .alert-title { font-weight:700; font-size:14px; color:#92400e; margin-bottom:3px; }
.alert-duplicate .alert-msg { font-size:13px; color:#b45309; margin:0; }
.alert-duplicate .btn-close-alert {
    background:none; border:none; font-size:16px; color:#d97706;
    cursor:pointer; padding:0; line-height:1; transition:.2s; flex-shrink:0;
}
.alert-duplicate .btn-close-alert:hover { color:#92400e; transform:scale(1.2); }

.input-group-styled { position:relative; margin-bottom:20px; }
.input-group-styled .form-control,
.input-group-styled .form-select {
    border-radius:12px; border:2px solid #e2e8f0;
    padding:12px 16px; font-size:14px; transition:.25s; background:#fafafa;
}
.input-group-styled .form-control:focus,
.input-group-styled .form-select:focus {
    border-color:#f59e0b; background:#fff; box-shadow:0 0 0 4px rgba(245,158,11,.08);
}
.input-group-styled label {
    font-size:12px; font-weight:700; color:#64748b;
    text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block;
}

/* ===== TOGGLE TAMBAH / KURANG ===== */
.stock-mode-toggle {
    display: flex;
    background: #f1f5f9;
    border-radius: 12px;
    padding: 4px;
    gap: 4px;
    margin-bottom: 16px;
    width: fit-content;
}
.stock-mode-toggle .mode-btn {
    padding: 8px 22px;
    border-radius: 9px;
    border: none;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: #94a3b8;
}
.stock-mode-toggle .mode-btn.active-tambah {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    box-shadow: 0 3px 10px rgba(34,197,94,.3);
}
.stock-mode-toggle .mode-btn.active-kurang {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 3px 10px rgba(239,68,68,.3);
}

/* ===== INFO HINT ===== */
.stock-hint-box {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .3s;
}
.stock-hint-box.mode-tambah {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.stock-hint-box.mode-kurang {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.section-header {
    display:flex; align-items:center; gap:10px;
    padding:10px 16px; border-radius:10px;
    margin-bottom:16px; font-weight:700; font-size:15px;
}
.section-header.yellow { background:#fffbeb; color:#92400e; border-left:4px solid #f59e0b; }
.section-header.green  { background:#f0fdf4; color:#166534; border-left:4px solid #22c55e; }

.size-table thead th {
    background:#f8fafc; font-size:12px; text-transform:uppercase;
    letter-spacing:.5px; color:#64748b; font-weight:700; padding:10px 12px;
}
.size-table td { padding:8px 10px; vertical-align:middle; }
.size-table .form-control,
.size-table .form-select {
    border-radius:8px; border:1.5px solid #e2e8f0; font-size:13px; padding:7px 10px;
}
.size-table .form-control:focus,
.size-table .form-select:focus {
    border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.08);
}

.current-stock-cell { font-size:12px; color:#64748b; text-align:center; }
.current-stock-badge {
    background:#f1f5f9; border-radius:6px; padding:3px 10px;
    font-weight:700; color:#475569; display:inline-block;
}

.qty-input-tambah { border-color: #22c55e !important; }
.qty-input-kurang  { border-color: #ef4444 !important; }

.btn-hapus-row {
    width:28px; height:28px; border-radius:50%; border:none;
    background:#fee2e2; color:#dc2626; display:inline-flex;
    align-items:center; justify-content:center; font-size:12px;
    transition:.2s; cursor:pointer;
}
.btn-hapus-row:hover { background:#dc2626; color:#fff; transform:scale(1.1); }

.btn-tambah {
    border-radius:20px; font-size:13px; padding:6px 16px;
    border:2px dashed #cbd5e1; color:#64748b;
    background:transparent; transition:.2s;
}
.btn-tambah:hover { border-color:#f59e0b; color:#f59e0b; background:#fffbeb; }

.btn-kembali {
    border-radius:12px; padding:12px 28px; font-size:15px; font-weight:700;
    background:#f1f5f9; border:2px solid #e2e8f0; color:#475569;
    text-decoration:none; transition:.3s; display:inline-flex; align-items:center;
}
.btn-kembali:hover { background:#e2e8f0; color:#1e293b; transform:translateX(-3px); border-color:#cbd5e1; }

.btn-update {
    border-radius:12px; padding:12px 32px; font-size:15px; font-weight:700;
    background:linear-gradient(135deg,#d97706,#f59e0b); border:none; color:#fff;
    transition:.3s; box-shadow:0 4px 14px rgba(245,158,11,.3);
}
.btn-update:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(245,158,11,.4); color:#fff; }

</style>


<div class="container-fluid">
<div class="card shadow border-0 form-card">

    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center" style="border-radius:16px 16px 0 0;">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Produk</h5>
        <a href="{{ route('admin.products.index') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card-body p-4">

        @if(session('warning'))
            <div class="alert-duplicate" id="alert-duplicate">
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="alert-body">
                    <div class="alert-title">Produk Sudah Ada!</div>
                    <p class="alert-msg">{{ session('warning') }}</p>
                </div>
                <button type="button" class="btn-close-alert" onclick="document.getElementById('alert-duplicate').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="stock_mode" id="stock_mode_input" value="tambah">

            <div class="row g-4">

                <div class="col-md-6 form-section">

                    <div class="section-header yellow">
                        <i class="fas fa-tag"></i> Informasi Produk
                    </div>

                    <div class="input-group-styled">
                        <label>Kategori</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected':'' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group-styled">
                        <label>Brand</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control" required>
                    </div>

                    <div class="input-group-styled" id="type-field">
                        <label>Type</label>
                        <input type="text" name="type" value="{{ old('type', $product->type) }}" class="form-control">
                    </div>

                    <div class="input-group-styled" id="color-field">
                        <label>Color</label>
                        <input type="text" name="color" id="color-input" value="{{ old('color', $product->color) }}" class="form-control">
                    </div>

                    <div class="input-group-styled">
                        <label>Harga Modal</label>
                        <input type="text" name="cost_price" id="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="form-control" required>
                    </div>

                    <div class="input-group-styled" id="qty-field" style="display:none;">
                        <label>
                            Jumlah Stok
                            @php $currentStockSimple = $product->variants->first()?->serials()->where('is_sold', false)->count() ?? 0; @endphp
                            <span class="badge bg-light text-secondary border ms-2">Tersedia: {{ $currentStockSimple }}</span>
                        </label>
                        <input type="number" name="qty" id="qty-input" class="form-control qty-input-tambah" value="0" min="0" disabled>
                        <div class="stock-hint" id="qty-hint" style="font-size:11px; color:#94a3b8; margin-top:4px; font-style:italic;">
                            Isi jumlah stok yang ingin ditambahkan.
                        </div>
                    </div>

                </div>


                <div class="col-md-6 form-section">

                    <div class="section-header yellow mb-3">
                        <i class="fas fa-boxes"></i> Kelola Stok
                    </div>

                    <div class="stock-mode-toggle" id="stock-mode-toggle">
                        <button type="button" class="mode-btn active-tambah" id="btn-tambah" onclick="setMode('tambah')">
                            <i class="fas fa-plus-circle"></i> Tambah Stok
                        </button>
                        <button type="button" class="mode-btn" id="btn-kurang" onclick="setMode('kurang')">
                            <i class="fas fa-minus-circle"></i> Kurangi Stok
                        </button>
                    </div>

                    <div class="stock-hint-box mode-tambah" id="mode-hint-box">
                        <i class="fas fa-info-circle"></i>
                        <span id="mode-hint-text">Masukkan jumlah stok yang ingin <strong>ditambahkan</strong>. Isi 0 jika tidak ada perubahan.</span>
                    </div>

                    <div id="size-section">

                        <div class="section-header green">
                            <i class="fas fa-ruler-horizontal"></i> Size & Stok
                        </div>

                        <table class="table table-bordered size-table" id="size-table">
                            <thead>
                                <tr>
                                    <th style="width:38%">Size</th>
                                    <th style="width:22%" class="text-center">Stok Tersedia</th>
                                    <th style="width:25%">Jumlah</th>
                                    <th style="width:15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $i => $v)
                                    @if(!$v->peci_number && $v->size)
                                    <tr>
                                        <td>
                                            <select name="sizes[{{ $i }}][size]" class="form-select size-select">
                                                <option value="">-- Pilih Size --</option>
                                                @foreach($sizes as $size)
                                                    @if($size->category_id == $product->category_id)
                                                        <option value="{{ $size->size }}" {{ $v->size == $size->size ? 'selected':'' }}>
                                                            {{ $size->size }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="current-stock-cell">
                                            <span class="current-stock-badge">
                                                {{ $v->serials()->where('is_sold', false)->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" name="sizes[{{ $i }}][qty]" class="form-control qty-input-tambah qty-field" value="0" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" id="add-size-row" class="btn-tambah">
                            <i class="fas fa-plus me-1"></i> Tambah Size Baru
                        </button>

                    </div>


                    <div id="peci-section" style="display:none;">

                        <div class="section-header green">
                            <i class="fas fa-hat-cowboy"></i> Variant Peci
                        </div>

                        <table class="table table-bordered size-table" id="peci-table">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tinggi</th>
                                    <th class="text-center">Stok Tersedia</th>
                                    <th>Jumlah</th>
                                    <th style="width:55px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $i => $v)
                                    @if($v->peci_number)
                                    <tr>
                                        <td><input type="text" name="peci[{{ $i }}][nomor]" class="form-control" value="{{ $v->peci_number }}" placeholder="Nomor"></td>
                                        <td><input type="text" name="peci[{{ $i }}][tinggi]" class="form-control" value="{{ $v->peci_height }}" placeholder="Tinggi"></td>
                                        <td class="current-stock-cell">
                                            <span class="current-stock-badge">
                                                {{ $v->serials()->where('is_sold', false)->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" name="peci[{{ $i }}][qty]" class="form-control qty-input-tambah qty-field" value="0" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" id="add-peci-row" class="btn-tambah">
                            <i class="fas fa-plus me-1"></i> Tambah Variant Peci Baru
                        </button>

                    </div>

                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.products.index') }}" class="btn-kembali">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                <button type="submit" class="btn-update">
                    <i class="fas fa-save me-2"></i> Update Produk
                </button>
            </div>

        </form>
    </div>
</div>
</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    /* ===== FORMAT RUPIAH ===== */
    const costInput = document.getElementById('cost_price');
    const cleave = new Cleave(costInput, {
        numeral: true,
        numeralDecimalScale: 0,
        numeralThousandsGroupStyle: 'thousand',
        prefix: 'Rp ',
        rawValueTrimPrefix: true
    });

    document.querySelector('form').addEventListener('submit', function(e){
        const rawValue = costInput.value.replace(/[^0-9]/g, '');
        if(!rawValue || parseInt(rawValue) <= 0){
            e.preventDefault();
            alert("Harga modal tidak boleh kosong atau 0");
            return false;
        }
        costInput.value = rawValue;
    });

    /* ===== AUTO-DISMISS ALERT ===== */
    const alertDuplicate = document.getElementById('alert-duplicate');
    if(alertDuplicate){
        setTimeout(function(){
            alertDuplicate.style.transition = 'opacity .5s ease, transform .5s ease';
            alertDuplicate.style.opacity    = '0';
            alertDuplicate.style.transform  = 'translateY(-10px)';
            setTimeout(() => alertDuplicate.remove(), 500);
        }, 6000);
        alertDuplicate.scrollIntoView({ behavior:'smooth', block:'center' });
    }

    /* ===== DATA SIZES ===== */
    const allSizes = @json($sizes->map(fn($s) => ['size' => $s->size, 'category_id' => $s->category_id]));

    function filterSizeSelect(selectEl, categoryId){
        const currentVal  = selectEl.value;
        const firstOption = selectEl.querySelector('option[value=""]');
        selectEl.innerHTML = '';
        if(firstOption) selectEl.appendChild(firstOption);
        allSizes.forEach(function(s){
            if(s.category_id == categoryId){
                const opt = document.createElement('option');
                opt.value = s.size;
                opt.textContent = s.size;
                if(s.size === currentVal) opt.selected = true;
                selectEl.appendChild(opt);
            }
        });
    }

    function filterAllExistingRows(){
        const categoryId = document.getElementById("category_id").value;
        document.querySelectorAll(".size-select").forEach(function(sel){
            filterSizeSelect(sel, categoryId);
        });
    }

    /* ===== TOGGLE MODE TAMBAH / KURANG ===== */
    let currentMode = 'tambah';

    window.setMode = function(mode){
        currentMode = mode;
        document.getElementById('stock_mode_input').value = mode;

        const btnTambah = document.getElementById('btn-tambah');
        const btnKurang = document.getElementById('btn-kurang');
        const hintBox   = document.getElementById('mode-hint-box');
        const hintText  = document.getElementById('mode-hint-text');
        const qtyHint   = document.getElementById('qty-hint');
        const qtyInput  = document.getElementById('qty-input');

        btnTambah.className = 'mode-btn';
        btnKurang.className = 'mode-btn';

        if(mode === 'tambah'){
            btnTambah.classList.add('active-tambah');
            hintBox.className  = 'stock-hint-box mode-tambah';
            hintText.innerHTML = 'Masukkan jumlah stok yang ingin <strong>ditambahkan</strong>. Isi 0 jika tidak ada perubahan.';
            if(qtyHint) qtyHint.textContent = 'Isi jumlah stok yang ingin ditambahkan.';
            document.querySelectorAll('.qty-field').forEach(el => {
                el.classList.remove('qty-input-kurang');
                el.classList.add('qty-input-tambah');
            });
            if(qtyInput){
                qtyInput.classList.remove('qty-input-kurang');
                qtyInput.classList.add('qty-input-tambah');
            }
        } else {
            btnKurang.classList.add('active-kurang');
            hintBox.className  = 'stock-hint-box mode-kurang';
            hintText.innerHTML = 'Masukkan jumlah stok yang ingin <strong>dikurangi</strong>. Stok terjual tidak akan terhapus.';
            if(qtyHint) qtyHint.textContent = 'Isi jumlah stok yang ingin dikurangi.';
            document.querySelectorAll('.qty-field').forEach(el => {
                el.classList.remove('qty-input-tambah');
                el.classList.add('qty-input-kurang');
            });
            if(qtyInput){
                qtyInput.classList.remove('qty-input-tambah');
                qtyInput.classList.add('qty-input-kurang');
            }
        }
    };

    /* ===== SWITCH FORM KATEGORI ===== */
    const categorySelect = document.getElementById("category_id");
    const sizeSection    = document.getElementById("size-section");
    const peciSection    = document.getElementById("peci-section");
    const typeField      = document.getElementById("type-field");
    const qtyField       = document.getElementById("qty-field");
    const colorField     = document.getElementById("color-field");
    const colorInput     = document.getElementById("color-input");
    const qtyInput       = document.getElementById("qty-input");

    /*
    |----------------------------------------------------------
    | ID KATEGORI PECI
    |----------------------------------------------------------
    */
    const PECI_CATEGORY_ID = 6;

    /*
    |----------------------------------------------------------
    | ID KATEGORI: hanya Brand + Modal + Qty
    | (Type & Color disembunyikan)
    |----------------------------------------------------------
    | 37 = MAHAR | 41 = SAPU TANGAN | 45 = SAJADAH ✅
    |----------------------------------------------------------
    */
    const BRAND_MODAL_QTY_IDS = [37, 41, 45];

    /*
    |----------------------------------------------------------
    | ID KATEGORI SIMPLE PRODUCT
    | (Brand, Color, Modal, Qty — Type & Size disembunyikan)
    | -> HARUS SINKRON dengan $simpleProductIds di ProductController.php
    |----------------------------------------------------------
    | 3  = TAS          | 13 = TOPI          | 28 = SARUNG TANGAN
    | 8  = HANDUK       | 21 = MUKENAH       | 33 = MANSET
    | 9  = KAOS KAKI    | 23 = SARUNG        | 39 = DASI
    | 11 = DOMPET       | 25 = SPREI         | 43 = JILBAB
    | 12 = IKAT PINGGANG| 27 = MANTEL        | 44 = SEPREI ✅
    | 48 = BEDUNG BAYI
    |----------------------------------------------------------
    | CATATAN: 52 (JAKET) SENGAJA TIDAK dimasukkan ke sini.
    | Jaket pakai flow SIZE (S-XXXL), bukan simple product.
    |----------------------------------------------------------
    */
    const SIMPLE_PRODUCT_CATEGORY_IDS = [
        3, 8, 9, 11, 12, 13, 21, 23, 25, 27, 28, 33, 39, 43, 44, 48, 56,60
    ];

    // Fallback nama kategori
    // CATATAN: 'jaket' SENGAJA TIDAK ada di sini — Jaket pakai Size, bukan simple product.
    const simpleProductNames = [
        'tas', 'handuk', 'kaos kaki', 'dompet', 'ikat pinggang',
        'topi', 'dasi', 'mukenah', 'sarung', 'sprei', 'seprei',
        'mantel', 'sarung tangan', 'jilbab', 'manset', 'bedung bayi','koper','dll'
    ];

    function checkCategory(){
        if(!categorySelect.value) return;

        const catId = parseInt(categorySelect.value);
        const text  = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();

        /* ===== RESET SEMUA ===== */
        sizeSection.style.display = "block";
        peciSection.style.display = "none";
        typeField.style.display   = "block";
        qtyField.style.display    = "none";
        colorField.style.display  = "block";
        qtyInput.disabled         = true;

        if(colorInput.value === '-') colorInput.value = '';

        /* ===== PECI ===== */
        if(catId === PECI_CATEGORY_ID || text.includes("peci")){
            sizeSection.style.display = "none";
            peciSection.style.display = "block";
        }

        /* ===== SAJADAH, MAHAR, SAPU TANGAN — hanya Brand + Modal + Qty ===== */
        else if(BRAND_MODAL_QTY_IDS.includes(catId) || text.includes("mahar") || text.includes("sapu tangan") || text.includes("sajadah")){
            sizeSection.style.display = "none";
            typeField.style.display   = "none";
            colorField.style.display  = "none";
            qtyField.style.display    = "block";
            qtyInput.disabled         = false;
        }

        /* ===== SIMPLE PRODUCT — Brand, Color, Modal, Qty ===== */
        else if(SIMPLE_PRODUCT_CATEGORY_IDS.includes(catId) || simpleProductNames.some(item => text.includes(item))){
            sizeSection.style.display = "none";
            typeField.style.display   = "none";
            qtyField.style.display    = "block";
            qtyInput.disabled         = false;
        }

        /* ===== KOLOR — sembunyikan color ===== */
        else if(text.includes("kolor")){
            colorField.style.display = "none";
            if(!colorInput.value) colorInput.value = "-";
        }

        /* ===== BAJU / CELANA / ROK / JAKET — sembunyikan type, Size tetap tampil ===== */
        else if(
            text.includes("baju")           ||
            text.includes("singlet")        ||
            text.includes("baju sekolah")   ||
            text.includes("celana sekolah") ||
            text.includes("rok sekolah")    ||
            text.includes("celana")         ||
            text.includes("manset")         ||
            text.includes("jaket")
        ){
            typeField.style.display = "none";
        }

        filterAllExistingRows();
    }

    checkCategory();
    categorySelect.addEventListener("change", checkCategory);

    /* ===== TAMBAH SIZE ===== */
    let sizeIndex = {{ $product->variants->filter(fn($v) => !$v->peci_number)->count() }};

    document.getElementById("add-size-row").addEventListener("click", function(){
        const categoryId = categorySelect.value;
        const qtyClass   = currentMode === 'kurang' ? 'qty-input-kurang' : 'qty-input-tambah';
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>
                <select name="sizes[${sizeIndex}][size]" class="form-select size-select">
                    <option value="">Pilih Size</option>
                </select>
            </td>
            <td class="current-stock-cell"><span class="current-stock-badge">0</span></td>
            <td>
                <input type="number" name="sizes[${sizeIndex}][qty]" class="form-control ${qtyClass} qty-field" min="0" value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn-hapus-row remove-row">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        document.querySelector("#size-table tbody").appendChild(tr);
        filterSizeSelect(tr.querySelector(".size-select"), categoryId);
        sizeIndex++;
    });

    /* ===== TAMBAH PECI ===== */
    let peciIndex = {{ $product->variants->filter(fn($v) => $v->peci_number)->count() }};

    document.getElementById("add-peci-row").addEventListener("click", function(){
        const qtyClass = currentMode === 'kurang' ? 'qty-input-kurang' : 'qty-input-tambah';
        const row = `
        <tr>
            <td><input type="text" name="peci[${peciIndex}][nomor]" class="form-control" placeholder="Nomor"></td>
            <td><input type="text" name="peci[${peciIndex}][tinggi]" class="form-control" placeholder="Tinggi"></td>
            <td class="current-stock-cell"><span class="current-stock-badge">0</span></td>
            <td><input type="number" name="peci[${peciIndex}][qty]" class="form-control ${qtyClass} qty-field" min="0" value="0"></td>
            <td class="text-center">
                <button type="button" class="btn-hapus-row remove-row">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        document.querySelector("#peci-table tbody").insertAdjacentHTML("beforeend", row);
        peciIndex++;
    });

    /* ===== HAPUS ROW ===== */
    document.addEventListener("click", function(e){
        if(e.target.closest(".remove-row")){
            e.target.closest("tr").remove();
        }
    });

});
</script>
@endsection