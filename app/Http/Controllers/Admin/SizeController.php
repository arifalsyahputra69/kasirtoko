<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use App\Models\Category;

class SizeController extends Controller
{
    // Tampilkan daftar size + form tambah
    public function index()
    {
        $categories = Category::all();
        $sizes = Size::with('category')->get();
        return view('admin.sizes.index', compact('categories','sizes'));
    }

    // Simpan size baru
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'size' => 'required|string|max:10'
        ]);

        Size::create([
            'category_id' => $request->category_id,
            'size' => $request->size
        ]);

        return redirect()->route('admin.sizes.index')->with('success','Size berhasil ditambahkan!');
    }
}
