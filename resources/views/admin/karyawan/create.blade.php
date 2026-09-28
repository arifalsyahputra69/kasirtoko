@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    <i class="fas fa-user-plus me-2 text-success"></i>Tambah Karyawan
</h3>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.karyawan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email"
                       class="form-control"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>

            <a href="{{ route('admin.karyawan.index') }}"
               class="btn btn-secondary">
               Kembali
            </a>
        </form>
    </div>
</div>

@endsection