<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductSerial;
use App\Models\Category;
use App\Models\Size;

class ProductController extends Controller
{

    /*
    |------------------------------------------------------------------
    | DAFTAR ID KATEGORI SIMPLE PRODUCT
    | (hanya ada 1 variant tanpa size, cukup input Qty)
    |------------------------------------------------------------------
    | 3  = TAS          | 13 = TOPI          | 28 = SARUNG TANGAN
    | 8  = HANDUK       | 21 = MUKENAH       | 33 = MANSET
    | 9  = KAOS KAKI    | 23 = SARUNG        | 37 = MAHAR
    | 11 = DOMPET       | 25 = SPREI         | 39 = DASI ✅
    | 12 = IKAT PINGGANG| 27 = MANTEL        | 41 = SAPU TANGAN
    |                   | 43 = JILBAB ✅     | 44 = SEPREI ✅
    |                   | 45 = SAJADAH ✅    | 48 = BEDUNG BAYI
    |------------------------------------------------------------------
    | CATATAN: 52 (JAKET) SENGAJA TIDAK dimasukkan ke sini.
    | Jaket pakai flow SIZE (S-XXXL), diproses di cabang "PRODUK SIZE".
    | -> HARUS SINKRON dengan SIMPLE_PRODUCT_CATEGORY_IDS di create.blade.php
    |    dan edit.blade.php
    |------------------------------------------------------------------
    */
   private array $simpleProductIds = [
    3,   // TAS
    8,   // HANDUK
    9,   // KAOS KAKI
    11,  // DOMPET
    12,  // IKAT PINGGANG
    13,  // TOPI
    21,  // MUKENAH
    23,  // SARUNG
    25,  // SPREI
    27,  // MANTEL
    28,  // SARUNG TANGAN
    33,  // MANSET
    37,  // MAHAR
    39,  // DASI
    41,  // SAPU TANGAN
    43,  // JILBAB
    44,  // SEPREI
    45,  // SAJADAH
    48,  // BEDUNG BAYI
    60,  // DLL
    // 52 (JAKET) dihapus dari sini — Jaket pakai Size (S-XXXL)
];


    /*
    |----------------------------------
    | INDEX
    |----------------------------------
    */

    public function index(Request $request)
    {
        $query = Product::with(['variants','category']);

        if($request->search){
            $query->where(function($q) use ($request){
                $q->where('brand','like','%'.$request->search.'%')
                  ->orWhere('type','like','%'.$request->search.'%')
                  ->orWhere('color','like','%'.$request->search.'%');
            });
        }

        if($request->category){
            $query->where('category_id', $request->category);
        }

        $products   = $query->latest()->paginate(40);
        $categories = Category::all();

        if($request->ajax()){
            return view('admin.products.partials.table', compact('products','categories'))->render();
        }

        return view('admin.products.index', compact('products','categories'));
    }


    /*
    |----------------------------------
    | CREATE
    |----------------------------------
    */

    public function create()
    {
        $categories = Category::all();
        $sizes      = Size::with('category')->get();

        return view('admin.products.create', compact('categories','sizes'));
    }


    /*
    |----------------------------------
    | STORE
    |----------------------------------
    */

    public function store(Request $request)
    {
        $request->merge([
            'cost_price' => preg_replace('/[^0-9]/', '', $request->cost_price)
        ]);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand'       => 'required|string|max:255',
            'type'        => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:100',
            'cost_price'  => 'required|numeric|min:0'
        ]);

        // =========================================
        // CEK DUPLIKASI PRODUK
        // (khusus kategori PECI / id:6 TIDAK dicek duplikat,
        //  karena brand/type/color yang sama boleh punya
        //  produk terpisah untuk tinggi peci yang berbeda)
        // =========================================
        if ((int) $request->category_id !== 6) {

            $existingProduct = Product::where('category_id', $request->category_id)
                ->whereRaw('LOWER(brand) = ?', [strtolower($request->brand)])
                ->whereRaw('LOWER(color) = ?', [strtolower($request->color ?? '')])
                ->when($request->filled('type'), fn($q) => $q->whereRaw('LOWER(type) = ?', [strtolower($request->type)]))
                ->first();

            if ($existingProduct) {
                $label = implode(' / ', array_filter([
                    $existingProduct->brand,
                    $existingProduct->type,
                    $existingProduct->color,
                ]));

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', "Produk \"$label\" sudah pernah ditambahkan sebelumnya! (ID: #{$existingProduct->id})");
            }
        }
        // =========================================

        DB::transaction(function () use ($request) {

            $category = Category::find($request->category_id);

            $product = Product::create([
                'category_id' => $request->category_id,
                'brand'       => $request->brand,
                'type'        => $request->type,
                'color'       => $request->color,
                'cost_price'  => $request->cost_price
            ]);


            /*
            ==================================
            PRODUK PECI (ID: 6)
            ==================================
            */

            if($category->id == 6){

                if($request->has('peci')){
                    foreach ($request->peci as $row) {
                        if(empty($row['qty'])) continue;
                        $qty     = (int)$row['qty'];
                        $variant = ProductVariant::create([
                            'product_id'  => $product->id,
                            'peci_number' => $row['nomor'] ?? null,
                            'peci_height' => $row['tinggi'] ?? null,
                            'stock'       => $qty
                        ]);
                        for ($i = 0; $i < $qty; $i++) {
                            ProductSerial::create([
                                'product_variant_id' => $variant->id,
                                'serial_number'      => strtoupper(uniqid()),
                                'is_sold'            => false
                            ]);
                        }
                    }
                }

            }


            /*
            ==================================
            PRODUK SIMPLE
            ==================================
            */

            elseif(in_array($category->id, $this->simpleProductIds)){

                $qty     = (int)($request->qty ?? 0);
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'stock'      => $qty
                ]);
                for ($i = 0; $i < $qty; $i++) {
                    ProductSerial::create([
                        'product_variant_id' => $variant->id,
                        'serial_number'      => strtoupper(uniqid()),
                        'is_sold'            => false
                    ]);
                }

            }


            /*
            ==================================
            PRODUK SIZE (BAJU, CELANA, JAKET, DLL)
            ==================================
            */

            else{

                if($request->has('sizes')){
                    foreach ($request->sizes as $row) {
                        if(empty($row['size'])) continue;
                        $qty     = (int)($row['qty'] ?? 0);
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'size'       => $row['size'],
                            'stock'      => $qty
                        ]);
                        for ($i = 0; $i < $qty; $i++) {
                            ProductSerial::create([
                                'product_variant_id' => $variant->id,
                                'serial_number'      => strtoupper(uniqid()),
                                'is_sold'            => false
                            ]);
                        }
                    }
                }

            }

        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }


    /*
    |----------------------------------
    | EDIT
    |----------------------------------
    */

    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes      = Size::with('category')->get();

        $product->load('variants');

        return view('admin.products.edit', compact('product','categories','sizes'));
    }


    /*
    |----------------------------------
    | UPDATE — ADDITIVE + SUBTRACTIVE STOCK & DELETE VARIANT
    |----------------------------------
    */

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'cost_price' => preg_replace('/[^0-9]/', '', $request->cost_price)
        ]);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand'       => 'required|string|max:255',
            'type'        => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:100',
            'cost_price'  => 'required|numeric|min:0'
        ]);

        // =========================================
        // CEK DUPLIKASI PRODUK (kecuali produk itu sendiri)
        // (khusus kategori PECI / id:6 TIDAK dicek duplikat,
        //  karena brand/type/color yang sama boleh punya
        //  produk terpisah untuk tinggi peci yang berbeda)
        // =========================================
        if ((int) $request->category_id !== 6) {

            $existingProduct = Product::where('category_id', $request->category_id)
                ->whereRaw('LOWER(brand) = ?', [strtolower($request->brand)])
                ->whereRaw('LOWER(color) = ?', [strtolower($request->color ?? '')])
                ->when($request->filled('type'), fn($q) => $q->whereRaw('LOWER(type) = ?', [strtolower($request->type)]))
                ->where('id', '!=', $product->id)
                ->first();

            if ($existingProduct) {
                $label = implode(' / ', array_filter([
                    $existingProduct->brand,
                    $existingProduct->type,
                    $existingProduct->color,
                ]));

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', "Produk \"$label\" sudah ada di database! (ID: #{$existingProduct->id})");
            }
        }
        // =========================================

        DB::transaction(function () use ($request, $product) {

            $category = Category::find($request->category_id);

            $product->update([
                'category_id' => $request->category_id,
                'brand'       => $request->brand,
                'type'        => $request->type,
                'color'       => $request->color,
                'cost_price'  => $request->cost_price
            ]);

            // mode: 'tambah' atau 'kurang' (dikirim dari form via hidden input)
            $mode = $request->input('stock_mode', 'tambah');


            /*
            ==================================
            PRODUK PECI (ID: 6)
            ==================================
            */

            if($category->id == 6){

                if($request->has('peci')){

                    $keepPeciNumbers = [];

                    foreach ($request->peci as $row) {
                        $peciNum = $row['nomor'] ?? null;
                        if ($peciNum !== null && $peciNum !== '') {
                            $keepPeciNumbers[] = $peciNum;
                        }

                        $qty = (int)($row['qty'] ?? 0);
                        if($qty <= 0) continue;

                        $variant = $product->variants()
                            ->where('peci_number', $peciNum)
                            ->first();

                        if($mode === 'kurang'){
                            if($variant){
                                $availableStock  = $variant->serials()->where('is_sold', false)->count();
                                $toRemove        = min($qty, $availableStock);
                                $serialsToDelete = $variant->serials()
                                    ->where('is_sold', false)
                                    ->latest('id')
                                    ->take($toRemove)
                                    ->pluck('id');
                                ProductSerial::whereIn('id', $serialsToDelete)->delete();
                                $variant->decrement('stock', $toRemove);
                            }
                        } else {
                            if($variant){
                                $variant->increment('stock', $qty);
                            } else {
                                $variant = ProductVariant::create([
                                    'product_id'  => $product->id,
                                    'peci_number' => $peciNum,
                                    'peci_height' => $row['tinggi'] ?? null,
                                    'stock'       => $qty
                                ]);
                            }

                            for ($i = 0; $i < $qty; $i++) {
                                ProductSerial::create([
                                    'product_variant_id' => $variant->id,
                                    'serial_number'      => strtoupper(uniqid()),
                                    'is_sold'            => false
                                ]);
                            }
                        }
                    }

                    $deletedVariants = $product->variants()->whereNotIn('peci_number', $keepPeciNumbers)->get();
                    foreach ($deletedVariants as $dv) {
                        ProductSerial::where('product_variant_id', $dv->id)->delete();
                        $dv->delete();
                    }
                }
            }


            /*
            ==================================
            PRODUK SIMPLE
            ==================================
            */

            elseif(in_array($category->id, $this->simpleProductIds)){

                $qty = (int)($request->qty ?? 0);
                if($qty > 0) {
                    $variant = $product->variants()->first();

                    if($mode === 'kurang'){
                        if($variant){
                            $availableStock  = $variant->serials()->where('is_sold', false)->count();
                            $toRemove        = min($qty, $availableStock);
                            $serialsToDelete = $variant->serials()
                                ->where('is_sold', false)
                                ->latest('id')
                                ->take($toRemove)
                                ->pluck('id');
                            ProductSerial::whereIn('id', $serialsToDelete)->delete();
                            $variant->decrement('stock', $toRemove);
                        }
                    } else {
                        if($variant){
                            $variant->increment('stock', $qty);
                        } else {
                            $variant = ProductVariant::create([
                                'product_id' => $product->id,
                                'stock'      => $qty
                            ]);
                        }

                        for ($i = 0; $i < $qty; $i++) {
                            ProductSerial::create([
                                'product_variant_id' => $variant->id,
                                'serial_number'      => strtoupper(uniqid()),
                                'is_sold'            => false
                            ]);
                        }
                    }
                }
            }


            /*
            ==================================
            PRODUK SIZE (BAJU, CELANA, JAKET, DLL)
            ==================================
            */

            else{

                if($request->has('sizes')){

                    $keepSizes = [];
                    foreach ($request->sizes as $row) {
                        if (!empty($row['size'])) {
                            $keepSizes[] = $row['size'];
                        }
                    }

                    $deletedVariants = $product->variants()->whereNotIn('size', $keepSizes)->get();
                    foreach ($deletedVariants as $dv) {
                        ProductSerial::where('product_variant_id', $dv->id)->delete();
                        $dv->delete();
                    }

                    foreach ($request->sizes as $row) {

                        if(empty($row['size'])) continue;

                        $qty = (int)($row['qty'] ?? 0);

                        $variant = $product->variants()
                            ->where('size', $row['size'])
                            ->first();

                        if($qty <= 0 && !$variant) continue;

                        if($mode === 'kurang'){
                            if($variant && $qty > 0){
                                $availableStock  = $variant->serials()->where('is_sold', false)->count();
                                $toRemove        = min($qty, $availableStock);
                                $serialsToDelete = $variant->serials()
                                    ->where('is_sold', false)
                                    ->latest('id')
                                    ->take($toRemove)
                                    ->pluck('id');
                                ProductSerial::whereIn('id', $serialsToDelete)->delete();
                                $variant->decrement('stock', $toRemove);
                            }
                        } else {
                            if($qty > 0) {
                                if($variant){
                                    $variant->increment('stock', $qty);
                                } else {
                                    $variant = ProductVariant::create([
                                        'product_id' => $product->id,
                                        'size'       => $row['size'],
                                        'stock'      => $qty
                                    ]);
                                }

                                for ($i = 0; $i < $qty; $i++) {
                                    ProductSerial::create([
                                        'product_variant_id' => $variant->id,
                                        'serial_number'      => strtoupper(uniqid()),
                                        'is_sold'            => false
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    $allVariants = $product->variants()->get();
                    foreach ($allVariants as $v) {
                        ProductSerial::where('product_variant_id', $v->id)->delete();
                        $v->delete();
                    }
                }
            }

        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }


    /*
    |----------------------------------
    | DESTROY
    |----------------------------------
    */

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {

            foreach ($product->variants as $variant) {
                ProductSerial::where('product_variant_id', $variant->id)->delete();
                $variant->delete();
            }

            $product->delete();

        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

}