@extends('layouts.karyawan')

@section('content')

<style>
body { background:#f4f6f9; }
.product-card { border-radius:18px; transition:.25s; cursor:pointer; background:#fff; }
.product-card:hover { transform:translateY(-8px); box-shadow:0 18px 40px rgba(0,0,0,.15); }
.product-title { font-weight:700; font-size:15px; }
.product-type { font-size:13px; color:#777; }
.product-color { font-size:13px; color:#000; }
.product-category { font-size:12px; color:#0d6efd; font-weight:600; }
.search-input { border-radius:30px; padding-left:20px; }
.cart-card { border-radius:25px; }

.fade-in { animation:fadeIn .3s ease; }
@keyframes fadeIn { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }

.category-header { background:#e9f0ff; border-left:4px solid #0d6efd; padding:8px 14px; border-radius:8px; font-weight:700; color:#0d6efd; margin-bottom:12px; margin-top:8px; cursor:pointer; user-select:none; transition: background .2s ease; }
.category-header:hover { background:#d0e2ff; }
.category-products { display:none; }
.category-group { margin-bottom:20px; }

#toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
.toast-item { background:#198754; color:white; padding:12px 20px; border-radius:12px; font-weight:600; font-size:14px; box-shadow:0 6px 20px rgba(0,0,0,.2); animation: toastIn .3s ease; min-width:220px; }
.toast-item.removing { animation: toastOut .3s ease forwards; }
@keyframes toastIn  { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
@keyframes toastOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(60px); } }

.cart-counter { background:#fff; color:#198754; font-weight:700; border-radius:50%; width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; margin-left:6px; }

@media(max-width:767px){ .product-item { flex:0 0 100%; max-width:100%; } }
@media(min-width:768px) and (max-width:991px){ .product-item { flex:0 0 50%; max-width:50%; } }
@media(min-width:992px){ .product-item { flex:0 0 33.333%; max-width:33.333%; } }
</style>

<div id="toast-container"></div>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-primary">🧾 Buat Bon Baru</h3>
    <a href="{{ route('karyawan.bon.index') }}" class="btn btn-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger rounded-3">❌ {{ session('error') }}</div>
@endif

<div class="row g-3">

    <!-- PRODUK LIST -->
    <div class="col-12 col-md-7">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white">
            <strong>Pilih Produk</strong>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-7">
                    <input type="text" id="search" class="form-control search-input" placeholder="🔍 Ketik nama produk...">
                </div>
                <div class="col-md-5">
                    <select id="filter-category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @foreach($categories as $cat)
                @php $categoryProducts = $products->filter(fn($p) => $p->category_id === $cat->id); @endphp
                @if($categoryProducts->count() > 0)
                <div class="category-group" data-category-group="{{ strtolower($cat->name) }}">
                    <div class="category-header" onclick="toggleCategory(this)">
                        {{ $cat->name }}
                        <span class="badge bg-primary bg-opacity-75 ms-1" style="font-size:11px;">{{ $categoryProducts->count() }}</span>
                        <span class="float-end arrow-icon">▼</span>
                    </div>
                    <div class="category-products">
                    <div class="row g-3 d-flex flex-wrap">
                        @foreach($categoryProducts as $product)
                        @php $totalStock = $product->variants->sum('stock'); @endphp
                        <div class="product-item mb-3"
                             data-name="{{ strtolower($product->brand.' '.$product->type.' '.$product->color.' '.$product->category->name) }}"
                             data-category="{{ strtolower($product->category->name) }}">
                            <div class="card product-card shadow-sm border-0 position-relative"
                                 onclick='addToCart({{ $product->id }}, @json($product->brand." ".$product->type), {{ $product->cost_price }}, @json($product->variants), @json($product->color))'>
                                <div class="card-body">
                                    <div class="product-category mb-1">{{ $product->category->name }}</div>
                                    <div class="product-title">{{ $product->brand }}</div>
                                    <div class="product-type mb-1">{{ $product->type }}</div>
                                    <div class="product-color mb-2">Color: {{ $product->color }}</div>
                                    <div class="mb-3">
                                        @foreach($product->variants as $v)
                                            @if($v->stock > 0)
                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                    @if($v->size) {{ $v->size }}
                                                    @elseif($v->peci_number) P{{ $v->peci_number }}-{{ $v->peci_height }}
                                                    @endif
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="fw-bold text-success fs-5">Rp {{ number_format($product->cost_price,0,',','.') }}</div>
                                    <div class="small text-muted">Harga Modal</div>
                                </div>
                                @if($totalStock <= 0)
                                    <span class="position-absolute top-0 end-0 badge bg-danger m-2">Stok Habis</span>
                                @elseif($totalStock <= 3)
                                    <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">Stok Tipis</span>
                                @else
                                    <span class="position-absolute top-0 end-0 badge bg-success m-2">Stok {{ $totalStock }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </div>
                </div>
                @endif
            @endforeach

        </div>
    </div>
    </div>


    <!-- KERANJANG BON -->
    <div class="col-12 col-md-5">
    <form method="POST" action="{{ route('karyawan.bon.store') }}" onsubmit="return validateBon()">
        @csrf

        <div class="card cart-card shadow-lg border-0 overflow-hidden">

            <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    🧾 Bon
                    <span class="cart-counter" id="cart-count">0</span>
                </h5>
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill" onclick="clearCart()">
                    🗑️ Kosongkan
                </button>
            </div>

            <div class="card-body" style="max-height:350px;overflow-y:auto;" id="cart-container">
                <div class="text-center text-muted py-4">Keranjang kosong</div>
            </div>

            <div class="border-top p-3 bg-light">

                <!-- NAMA PEMBELI -->
                <div class="mb-3">
                    <label class="fw-semibold">👤 Nama Pembeli <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama_pembeli"
                           class="form-control rounded-3"
                           placeholder="Masukkan nama pembeli"
                           required>
                </div>

                <!-- TOTAL -->
                <div class="d-flex justify-content-between fw-bold fs-5 mb-3 p-3 rounded-3"
                     style="background:linear-gradient(45deg,#0d6efd,#6610f2); color:white;">
                    <span>Total Tagihan</span>
                    <span id="total-display">Rp 0</span>
                </div>

                <input type="hidden" name="cart" id="cart-input">

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                    🧾 Buat Bon
                </button>

            </div>
        </div>
    </form>
    </div>

</div>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){

    let cart = [];

    function formatRupiah(num){ return 'Rp ' + new Intl.NumberFormat('id-ID').format(num); }
    function cleanRupiah(val){ return val.replace(/[^0-9]/g,''); }

    function showToast(msg){
        const c = document.getElementById('toast-container');
        const t = document.createElement('div');
        t.className = 'toast-item';
        t.innerHTML = '✅ ' + msg;
        c.appendChild(t);
        setTimeout(()=>{ t.classList.add('removing'); setTimeout(()=>t.remove(),300); }, 2500);
    }

    function updateCounter(){
        document.getElementById('cart-count').innerText = cart.length;
    }

    window.clearCart = function(){
        if(!cart.length) return;
        if(!confirm('Yakin ingin mengosongkan keranjang?')) return;
        cart = [];
        renderCart();
    }

    window.addToCart = function(id, name, cost, variants, color){
        cart.push({
            product_id:    id,
            name:          name,
            cost_price:    cost,
            variants:      variants,
            size:          null,
            color:         color,
            selling_price: 0,
            qty:           1,
            subtotal:      0,
        });
        renderCart();
        showToast(name + ' ditambahkan');
    }

    function renderCart(){
        const container = document.getElementById('cart-container');
        updateCounter();

        if(!cart.length){
            container.innerHTML = '<div class="text-center text-muted py-4">Keranjang kosong</div>';
            updateTotal();
            return;
        }

        container.innerHTML = '';
        cart.forEach((item, i) => {

            let hasVariant = item.variants.some(v => v.size || v.peci_number);
            let sizeHtml = '';

            if(hasVariant){
                let opts = '<option value="">Pilih Variant</option>';
                item.variants.forEach(v => {
                    if(v.stock > 0){
                        let label = v.size ? v.size : (v.peci_number ? 'Peci '+v.peci_number+' - '+v.peci_height : '');
                        opts += `<option value="${label}" ${item.size==label?'selected':''}>${label} (Stok:${v.stock})</option>`;
                    }
                });
                sizeHtml = `<select class="form-select form-select-sm" onchange="updateSize(${i}, this.value)">${opts}</select>`;
            } else {
                sizeHtml = `<input type="text" class="form-control form-control-sm" value="Tanpa Variant" disabled>`;
            }

            container.insertAdjacentHTML('beforeend', `
            <div class="card mb-2 shadow-sm border-0 fade-in">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:13px;">${item.name}</strong>
                        <button type="button" onclick="removeItem(${i})" class="btn btn-sm btn-outline-danger" style="padding:2px 8px;">✕</button>
                    </div>
                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Harga Jual *"
                        value="${item.selling_price>0?formatRupiah(item.selling_price):''}"
                        oninput="updatePrice(${i}, this)">
                    <div class="row mt-1 g-1">
                        <div class="col-7">${sizeHtml}</div>
                        <div class="col-5">
                            <input type="number" min="1" value="${item.qty}" class="form-control form-control-sm"
                                   onchange="updateQty(${i}, this.value)">
                        </div>
                    </div>
                    <div class="text-end mt-1 fw-bold text-success subtotal-${i}" style="font-size:13px;">${formatRupiah(item.subtotal)}</div>
                </div>
            </div>`);
        });

        updateTotal();
    }

    window.updatePrice = function(idx, input){
        let val = parseInt(cleanRupiah(input.value)) || 0;
        cart[idx].selling_price = val;
        cart[idx].subtotal = val * cart[idx].qty;
        input.value = val ? formatRupiah(val) : '';
        document.querySelector('.subtotal-'+idx).innerText = formatRupiah(cart[idx].subtotal);
        updateTotal();
    }

    window.updateQty = function(idx, qty){
        cart[idx].qty = parseInt(qty) || 1;
        cart[idx].subtotal = cart[idx].selling_price * cart[idx].qty;
        document.querySelector('.subtotal-'+idx).innerText = formatRupiah(cart[idx].subtotal);
        updateTotal();
    }

    window.updateSize  = function(idx, size){ cart[idx].size = size; }
    window.removeItem  = function(idx){ cart.splice(idx,1); renderCart(); }

    function updateTotal(){
        let total = cart.reduce((sum,i) => sum+i.subtotal, 0);
        document.getElementById('total-display').innerText = formatRupiah(total);
    }

    window.toggleCategory = function(header){
        let products = header.nextElementSibling;
        let arrow    = header.querySelector('.arrow-icon');
        if(products.style.display === 'block'){
            products.style.display = 'none';
            arrow.innerText = '▼';
        } else {
            products.style.display = 'block';
            arrow.innerText = '▲';
        }
    }

    document.getElementById('search').addEventListener('keyup', function(){
        let kw  = this.value.toLowerCase();
        let cat = document.getElementById('filter-category').value;
        document.querySelectorAll('.product-item').forEach(i=>{
            let matchSearch   = i.dataset.name.includes(kw);
            let matchCategory = (cat==='' || cat===i.dataset.category);
            i.style.display   = (matchSearch && matchCategory) ? '' : 'none';
        });
        document.querySelectorAll('.category-group').forEach(g=>{
            let visible = [...g.querySelectorAll('.product-item')].some(i=>i.style.display!=='none');
            g.style.display = visible ? '' : 'none';
            if(visible){ g.querySelector('.category-products').style.display='block'; g.querySelector('.arrow-icon').innerText='▲'; }
        });
    });

    document.getElementById('filter-category').addEventListener('change', function(){
        document.getElementById('search').dispatchEvent(new Event('keyup'));
    });

    window.validateBon = function(){
        if(!cart.length){ alert('Keranjang kosong!'); return false; }
        for(let i of cart){
            let hasVariant = i.variants.some(v=>v.size||v.peci_number);
            if(hasVariant && !i.size){ alert('Variant belum dipilih untuk '+i.name); return false; }
            if(i.selling_price<=0){ alert('Harga jual belum diisi untuk '+i.name); return false; }
        }
        document.getElementById('cart-input').value = JSON.stringify(cart);
        return true;
    }

});
</script>
@endsection