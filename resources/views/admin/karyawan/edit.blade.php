@extends('layouts.admin')

@section('content')

<h3 class="mb-4">
    <i class="fas fa-user-edit text-warning me-2"></i>
    Edit Karyawan
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

        <form action="{{ route('admin.karyawan.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $user->email) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="L" {{ $user->gender == 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="P" {{ $user->gender == 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label">
                    Ganti Password (Kosongkan jika tidak ingin mengganti)
                </label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Masukkan password baru">
            </div>

            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Update
            </button>

            <a href="{{ route('admin.karyawan.index') }}"
               class="btn btn-secondary">
               Kembali
            </a>

        </form>

    </div>
</div>

@endsection