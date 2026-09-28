@extends('layouts.app') {{-- pastikan menggunakan layout yang sudah kita buat sebelumnya --}}

@section('content')
<div class="container-fluid py-4">

    <h1 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <!-- Total Karyawan -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users"></i> Total Karyawan</h5>
                    <p class="card-text display-6">{{ $karyawans->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-box-open"></i> Total Produk</h5>
                    <p class="card-text display-6">{{ $products->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Karyawan -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            <i class="fas fa-user-friends"></i> Daftar Karyawan
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($karyawans as $k)
                        <tr>
                            <td>{{ $k->name }}</td>
                            <td>{{ $k->email }}</td>
                            <td>{{ $k->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.karyawan.destroy', $k->id) }}" onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-boxes"></i> Daftar Produk Terbaru
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Color</th>
                        <th>Variants (Size & Stok)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <tr>
                            <td>{{ $p->brand }}</td>
                            <td>{{ $p->type }}</td>
                            <td>{{ $p->color }}</td>
                            <td>
                                @foreach($p->variants as $v)
                                    <span class="badge bg-info text-dark">{{ $v->size }}: {{ $v->stock }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
