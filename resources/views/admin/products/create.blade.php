@extends('layouts.admin')

@section('content')

<style>

/* ===== ANIMASI ===== */
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
.alert-duplicate .alert-icon {
    font-size: 22px;
    color: #d97706;
    margin-top: 1px;
    flex-shrink: 0;
}
.alert-duplicate .alert-body {
    flex: 1;
}
.alert-duplicate .alert-title {
    font-weight: 700;
    font-size: 14px;
    color: #92400e;
    margin-bottom: 3px;
}
.alert-duplicate .alert-msg {
    font-size: 13px;
    color: #b45309;
    margin: 0;
}
.alert-duplicate .btn-close-alert {
    background: none;
    border: none;
    font-size: 16px;
    color: #d97706;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: .2s;
    flex-shrink: 0;
}
.alert-duplicate .btn-close-alert:hover {
    color: #92400e;
    transform: scale(1.2);
}

/* ===== PROGRESS STEP ===== */
.step-wrap {
    display:flex;
    align-items:center;
    justify-content:center;
    gap:0;
    margin-bottom:32px;
    animation: fadeInUp .4s ease both;
}
.step-item {
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    position:relative;
    flex:1;
    max-width:140px;
}
.step-circle {
    width:40px; height:40px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
    font-weight:700;
    transition:.3s;
    border:3px solid #dee2e6;
    background:#fff;
    color:#aaa;
    z-index:1;
}
.step-label {
    font-size:12px;
    color:#aaa;
    font-weight:600;
    text-align:center;
    transition:.3s;
}
.step-line {
    flex:1;
    height:3px;
    background:#dee2e6;
    margin-top:-28px;
    transition:.3s;
}

/* ACTIVE STEP */
.step-item.active .step-circle {
    background:linear-gradient(135deg,#0d6efd,#6610f2);
    border-color:#0d6efd;
    color:#fff;
    box-shadow:0 4px 14px rgba(13,110,253,.35);
}
.step-item.active .step-label { color:#0d6efd; }

/* DONE STEP */
.step-item.done .step-circle {
    background:linear-gradient(135deg,#198754,#20c997);
    border-color:#198754;
    color:#fff;
}
.step-item.done .step-label { color:#198754; }
.step-line.done { background:#198754; }


/* ===== STYLED INPUT ===== */
.input-group-styled {
    position:relative;
    margin-bottom:20px;
}
.input-group-styled .form-control,
.input-group-styled .form-select {
    border-radius:12px;
    border:2px solid #e2e8f0;
    padding:12px 16px;
    font-size:14px;
    transition:.25s;
    background:#fafafa;
}
.input-group-styled .form-control:focus,
.input-group-styled .form-select:focus {
    border-color:#0d6efd;
    background:#fff;
    box-shadow:0 0 0 4px rgba(13,110,253,.08);
}
.input-group-styled label {
    font-size:12px;
    font-weight:700;
    color:#64748b;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:6px;
    display:block;
}

/* ===== SECTION HEADER ===== */
.section-header {
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 16px;
    border-radius:10px;
    margin-bottom:16px;
    font-weight:700;
    font-size:15px;
}
.section-header.blue  { background:#eff6ff; color:#1d4ed8; border-left:4px solid #3b82f6; }
.section-header.green { background:#f0fdf4; color:#166534; border-left:4px solid #22c55e; }

/* ===== TABEL SIZE / PECI ===== */
.size-table thead th {
    background:#f8fafc;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.5px;
    color:#64748b;
    font-weight:700;
    padding:10px 12px;
}
.size-table td { padding:8px 10px; vertical-align:middle; }
.size-table .form-control,
.size-table .form-select {
    border-radius:8px;
    border:1.5px solid #e2e8f0;
    font-size:13px;
    padding:7px 10px;
}
.size-table .form-control:focus,
.size-table .form-select:focus {
    border-color:#0d6efd;
    box-shadow:0 0 0 3px rgba(13,110,253,.08);
}

/* TOMBOL HAPUS ROW */
.btn-hapus-row {
    width:28px; height:28px;
    border-radius:50%;
    border:none;
    background:#fee2e2;
    color:#dc2626;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    transition:.2s;
    cursor:pointer;
}
.btn-hapus-row:hover {
    background:#dc2626;
    color:#fff;
    transform:scale(1.1);
}

/* TOMBOL TAMBAH */
.btn-tambah {
    border-radius:20px;
    font-size:13px;
    padding:6px 16px;
    border:2px dashed #cbd5e1;
    color:#64748b;
    background:transparent;
    transition:.2s;
}
.btn-tambah:hover {
    border-color:#0d6efd;
    color:#0d6efd;
    background:#eff6ff;
}

/* TOMBOL KEMBALI */
.btn-kembali {
    border-radius:12px;
    padding:12px 28px;
    font-size:15px;
    font-weight:700;
    background:#f1f5f9;
    border:2px solid #e2e8f0;
    color:#475569;
    text-decoration:none;
    transition:.3s;
    display:inline-flex;
    align-items:center;
}
.btn-kembali:hover {
    background:#e2e8f0;
    color:#1e293b;
    transform:translateX(-3px);
    border-color:#cbd5e1;
}

/* TOMBOL SIMPAN */
.btn-simpan {
    border-radius:12px;
    padding:12px 32px;
    font-size:15px;
    font-weight:700;
    background:linear-gradient(135deg,#198754,#20c997);
    border:none;
    color:#fff;
    transition:.3s;
    box-shadow:0 4px 14px rgba(25,135,84,.3);
}
.btn-simpan:hover {
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(25,135,84,.4);
}

</style>


<div class="card shadow border-0 form-card">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="border-radius:16px 16px 0 0;">
        <h5 class="mb-0">
            <i class="fas fa-box-open me-2"></i>
            Tambah Produk
        </h5>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm text-primary rounded-pill px-3 fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card-body p-4">

        @if(session('warning'))
            <div class="alert-duplicate" id="alert-duplicate">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-body">
                    <div class="alert-title">Produk Sudah Ada!</div>
                    <p class="alert-msg">{{ session('warning') }}</p>
                </div>
                <button type="button" class="btn-close-alert" onclick="document.getElementById('alert-duplicate').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="step-wrap">
            <div class="step-item active" id="step-1">
                <div class="step-circle">1</div>
                <div class="step-label">Info Produk</div>
            </div>
            <div class="step-line" id="line-1"></div>
            <div class="step-item" id="step-2">
                <div class="step-circle">2</div>
                <div class="step-label">Size / Ukuran</div>
            </div>
            <div class="step-line" id="line-2"></div>
            <div class="step-item" id="step-3">
                <div class="step-circle">✓</div>
                <div class="step-label">Simpan</div>
            </div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" id="main-form">
            @csrf

            <div class="form-section mb-4">

                <div class="section-header blue">
                    <i class="fas fa-tag"></i> Informasi Produk
                </div>

                <div class="input-group-styled">
                    <label>Kategori</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group-styled">
                    <label>Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                </div>

                <div class="input-group-styled" id="type-field">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type') }}">
                </div>

                <div class="input-group-styled" id="color-field">
                    <label>Color</label>
                    <input type="text" name="color" id="color-input" class="form-control" value="{{ old('color') }}">
                </div>

                <div class="input-group-styled">
                    <label>Harga Modal</label>
                    <input type="text" name="cost_price" id="cost_price" class="form-control" placeholder="Rp 0" value="{{ old('cost_price') }}" required>
                </div>

                <div class="input-group-styled" id="qty-field" style="display:none;">
                    <label>Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" value="{{ old('qty', 1) }}">
                </div>

            </div>

            <hr class="my-4">

            <div id="size-section" class="form-section mb-4">
                <div class="section-header green">
                    <i class="fas fa-ruler-horizontal"></i> Size & Qty
                </div>
                <table class="table table-bordered size-table" id="size-table">
                    <thead>
                        <tr>
                            <th style="width:50%">Size</th>
                            <th style="width:25%">Qty</th>
                            <th style="width:25%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="sizes[0][size]" class="form-select size-select">
                                    <option value="">Pilih Size</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->size }}" data-category="{{ $size->category_id }}">
                                            {{ $size->size }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="sizes[0][qty]" class="form-control" min="1" value="1">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-size-row" class="btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah Size
                </button>
            </div>

            <div id="peci-section" style="display:none;" class="form-section mb-4">
                <div class="section-header green">
                    <i class="fas fa-hat-cowboy"></i> Ukuran Peci
                </div>
                <table class="table table-bordered size-table" id="peci-table">
                    <thead>
                        <tr>
                            <th>Nomor Peci</th>
                            <th>Tinggi</th>
                            <th style="width:150px">Qty</th>
                            <th style="width:80px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="peci[0][nomor]" class="form-control" placeholder="Nomor"></td>
                            <td><input type="text" name="peci[0][tinggi]" class="form-control" placeholder="Tinggi"></td>
                            <td><input type="number" name="peci[0][qty]" class="form-control" min="1" value="1"></td>
                            <td class="text-center">
                                <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-peci-row" class="btn-tambah">
                    <i class="fas fa-plus me-1"></i> Tambah Ukuran
                </button>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn-kembali">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                <button type="submit" class="btn-simpan" id="btn-simpan">
                    <i class="fas fa-save me-2"></i> Simpan Produk
                </button>
            </div>

        </form>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function(){

    /* ===== FORMAT RUPIAH ===== */
    const cleave = new Cleave('#cost_price', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        prefix: 'Rp ',
        rawValueTrimPrefix: true
    });

    document.getElementById('main-form').addEventListener('submit', function(){
        document.getElementById('cost_price').value = cleave.getRawValue();
    });

    /* ===== AUTO-DISMISS ALERT SETELAH 6 DETIK ===== */
    const alertDuplicate = document.getElementById('alert-duplicate');
    if(alertDuplicate){
        setTimeout(function(){
            alertDuplicate.style.transition = 'opacity .5s ease, transform .5s ease';
            alertDuplicate.style.opacity    = '0';
            alertDuplicate.style.transform  = 'translateY(-10px)';
            setTimeout(() => alertDuplicate.remove(), 500);
        }, 6000);
        alertDuplicate.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* ===== STEP INDICATOR ===== */
    function setStep(step){
        [1,2,3].forEach(i => {
            document.getElementById('step-'+i).classList.remove('active','done');
        });
        [1,2].forEach(i => {
            document.getElementById('line-'+i).classList.remove('done');
        });
        for(let i = 1; i < step; i++){
            document.getElementById('step-'+i).classList.add('done');
            document.getElementById('line-'+i).classList.add('done');
        }
        if(step <= 3){
            document.getElementById('step-'+step).classList.add('active');
        }
    }

    setStep(1);

    document.getElementById('category_id').addEventListener('change', function(){
        if(this.value) setStep(2);
        else setStep(1);
    });

    document.getElementById('btn-simpan').addEventListener('mouseenter', function(){
        setStep(3);
    });
    document.getElementById('btn-simpan').addEventListener('mouseleave', function(){
        setStep(2);
    });

    /* ===== DISABLE / ENABLE SIZE ===== */
    function disableSizeInputs(){
        document.querySelectorAll("#size-section input, #size-section select")
            .forEach(el => el.disabled = true);
    }
    function enableSizeInputs(){
        document.querySelectorAll("#size-section input, #size-section select")
            .forEach(el => el.disabled = false);
    }

    /* ===== FILTER SIZE ===== */
    function filterSize(select, categoryId){
        select.querySelectorAll("option").forEach(function(option){
            let sizeCategory = option.getAttribute("data-category");
            if(!sizeCategory) return;
            option.style.display = (sizeCategory == categoryId) ? "block" : "none";
        });
    }

    /* ===== SWITCH FORM ===== */
    const categorySelect = document.getElementById("category_id");

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
    |----------------------------------------------------------
    */
    const SIMPLE_PRODUCT_CATEGORY_IDS = [
        3, 8, 9, 11, 12, 13, 21, 23, 25, 27, 28, 33, 39, 43, 44, 56,60,
    ];

    // Fallback nama kategori (jaga-jaga untuk kategori baru yang belum ada di array ID)
    const simpleProductNames = [
        'tas', 'handuk', 'kaos kaki', 'dompet', 'ikat pinggang',
        'topi', 'dasi', 'mukenah', 'sarung', 'sprei', 'seprei',
        'mantel', 'sarung tangan', 'jilbab', 'manset','bedung bayi','koper','dll'
    ];

    function applyCategory(categoryId){

        if(!categoryId) return;

        const catId = parseInt(categoryId);
        let text = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();

        const sizeSection = document.getElementById("size-section");
        const peciSection = document.getElementById("peci-section");
        const typeField   = document.getElementById("type-field");
        const qtyField    = document.getElementById("qty-field");
        const colorField  = document.getElementById("color-field");
        const colorInput  = document.getElementById("color-input");

        /* ===== RESET SEMUA (Default: Brand, Type, Color, Modal, Size) ===== */
        sizeSection.style.display  = "block";
        peciSection.style.display  = "none";
        typeField.style.display    = "block";
        qtyField.style.display     = "none";
        colorField.style.display   = "block";
        enableSizeInputs();

        /* ===== PECI ===== */
        if(catId === PECI_CATEGORY_ID || text.includes("peci")){
            sizeSection.style.display = "none";
            peciSection.style.display = "block";
            disableSizeInputs();
        }

        /* ===== SAJADAH, MAHAR, SAPU TANGAN — hanya Brand + Modal + Qty ===== */
        else if(BRAND_MODAL_QTY_IDS.includes(catId) || text.includes("mahar") || text.includes("sapu tangan") || text.includes("sajadah")){
            sizeSection.style.display = "none";
            typeField.style.display   = "none";
            colorField.style.display  = "none";
            qtyField.style.display    = "block";
            disableSizeInputs();
        }

        /* ===== SIMPLE PRODUCT (Brand, Color, Modal, Qty) ===== */
        else if(SIMPLE_PRODUCT_CATEGORY_IDS.includes(catId) || simpleProductNames.some(item => text.includes(item))){
            sizeSection.style.display = "none";
            typeField.style.display   = "none";
            qtyField.style.display    = "block";
            disableSizeInputs();
        }

        /* ===== KOLOR — sembunyikan color ===== */
        else if(text.includes("kolor")){
            colorField.style.display = "none";
            if(!colorInput.value) colorInput.value = "-";
        }

        /* ===== BAJU / CELANA / ROK — sembunyikan type ===== */
        else if(
            text.includes("baju")           ||
            text.includes("singlet")        ||
            text.includes("baju sekolah")   ||
            text.includes("celana sekolah") ||
            text.includes("celana")         ||
            text.includes("rok sekolah")    ||
            text.includes("jaket")
        ){
            typeField.style.display = "none";
        }

        /* ===== FILTER SIZE sesuai kategori ===== */
        document.querySelectorAll(".size-select").forEach(function(select){
            filterSize(select, categoryId);
        });
    }

    categorySelect.addEventListener("change", function(){
        applyCategory(this.value);
        if(this.value) setStep(2);
        else setStep(1);
    });

    const oldCategory = "{{ old('category_id') }}";
    if(oldCategory){
        applyCategory(oldCategory);
        setStep(2);
    }

    /* ===== TAMBAH SIZE ===== */
    let sizeIndex = 1;

    document.getElementById("add-size-row").addEventListener("click", function(){
        const categoryId = document.getElementById("category_id").value;
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>
                <select name="sizes[${sizeIndex}][size]" class="form-select size-select">
                    <option value="">Pilih Size</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->size }}" data-category="{{ $size->category_id }}">
                            {{ $size->size }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="sizes[${sizeIndex}][qty]" class="form-control" min="1" value="1">
            </td>
            <td class="text-center">
                <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        document.querySelector("#size-table tbody").appendChild(row);
        filterSize(row.querySelector(".size-select"), categoryId);
        sizeIndex++;
    });

    /* ===== TAMBAH PECI ===== */
    let peciIndex = 1;

    document.getElementById("add-peci-row").addEventListener("click", function(){
        const row = `
            <tr>
                <td><input type="text" name="peci[${peciIndex}][nomor]" class="form-control" placeholder="Nomor"></td>
                <td><input type="text" name="peci[${peciIndex}][tinggi]" class="form-control" placeholder="Tinggi"></td>
                <td><input type="number" name="peci[${peciIndex}][qty]" class="form-control" min="1" value="1"></td>
                <td class="text-center">
                    <button type="button" class="btn-hapus-row remove-row" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        document.querySelector("#peci-table tbody").insertAdjacentHTML("beforeend", row);
        peciIndex++;
    });

    /* ===== HAPUS ROW ===== */
    document.addEventListener("click", function(e){
        if(e.target.closest(".remove-row")){
            const tbody = e.target.closest("tbody");
            if(tbody.querySelectorAll("tr").length > 1){
                e.target.closest("tr").remove();
            }
        }
    });

});

</script>

@endsection