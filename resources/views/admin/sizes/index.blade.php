@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Daftar Produk</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Brand</th>
            <th>Type</th>
            <th>Color</th>
            <th>Harga Modal</th>
            <th>Variants</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $p->brand }}</td>
            <td>{{ $p->type }}</td>
            <td>{{ $p->color }}</td>
            <td>Rp {{ number_format($p->cost_price,0,',','.') }}</td>
            <td>
                @foreach($p->variants as $v)
                    {{ $v->size }} ({{ $v->stock }})<br>
                @endforeach
            </td>
            <td>
                <a href="{{ route('admin.products.edit',$p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.products.destroy',$p->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}

@endsection
