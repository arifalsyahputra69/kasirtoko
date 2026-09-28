@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">
        <i class="fas fa-users me-2 text-primary"></i>Kelola Karyawan
    </h3>

    <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Karyawan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow border-0">
    <div class="card-body p-0">

        {{-- ✅ Tambah table-responsive agar bisa scroll horizontal di mobile --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Email</th>  {{-- ✅ Sembunyi di mobile --}}
                        <th class="d-none d-sm-table-cell">Gender</th> {{-- ✅ Sembunyi di layar xs --}}
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $k)
                        <tr>
                            <td>
                                <i class="fas fa-user-circle text-secondary me-1"></i>
                                {{ $k->name }}
                            </td>
                            <td class="d-none d-md-table-cell">{{ $k->email }}</td>  {{-- ✅ Sama dengan header --}}
                            <td class="d-none d-sm-table-cell">
                                {{ $k->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td>
                                <span class="badge bg-success">Aktif</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.karyawan.edit',$k->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.karyawan.destroy',$k->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus karyawan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                Belum ada karyawan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection