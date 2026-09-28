@extends('layouts.karyawan')

@section('content')

<style>
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeInRow {
    from { opacity:0; transform:translateX(-10px); }
    to   { opacity:1; transform:translateX(0); }
}
.table-animated { animation: fadeInUp .5s ease both; }
tbody tr { animation: fadeInRow .4s ease both; }
tbody tr:nth-child(1)  { animation-delay:.05s; }
tbody tr:nth-child(2)  { animation-delay:.10s; }
tbody tr:nth-child(3)  { animation-delay:.15s; }
tbody tr:nth-child(4)  { animation-delay:.20s; }
tbody tr:nth-child(5)  { animation-delay:.25s; }
</style>

<div class="container-fluid">

    <a href="{{ route('karyawan.bon.create') }}" class="btn btn-primary rounded-pill px-4 mb-3">
        <i class="bi bi-plus-lg me-1"></i> Buat Bon Baru
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 border-0">
    <div class="card-body">

        <div class="table-responsive table-animated">
            <table class="table table-bordered table-hover align-middle table-mobile-card">

                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Dibuat Oleh</th>
                        <th>Total Tagihan</th>
                        <th>Sudah Dibayar</th>
                        <th>Sisa Tagihan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($bons as $i => $bon)
                    <tr>

                        <td data-label="No" class="text-center">
                            {{ $loop->iteration + ($bons->currentPage()-1)*$bons->perPage() }}
                        </td>

                        <td data-label="Nama Pembeli" class="fw-semibold">
                            {{ $bon->nama_pembeli }}
                        </td>

                        <td data-label="Dibuat Oleh">
                            {{ $bon->user->name ?? '-' }}
                        </td>

                        <td data-label="Total Tagihan">
                            Rp {{ number_format($bon->total_tagihan,0,',','.') }}
                        </td>

                        <td data-label="Sudah Dibayar" class="text-success fw-bold">
                            Rp {{ number_format($bon->total_dibayar,0,',','.') }}
                        </td>

                        <td data-label="Sisa Tagihan"
                            class="{{ $bon->sisa_tagihan > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                            Rp {{ number_format($bon->sisa_tagihan,0,',','.') }}
                        </td>

                        <td data-label="Status" class="text-center">
                            @if($bon->status === 'lunas')
                                <span class="badge bg-success rounded-pill px-3">✅ Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">⏳ Cicil</span>
                            @endif
                        </td>

                        <td data-label="Tanggal">
                            {{ $bon->created_at->format('d M Y H:i') }}
                        </td>

                        <td data-label="Aksi" class="text-center">
                            <a href="{{ route('karyawan.bon.show', $bon->id) }}"
                               class="btn btn-sm btn-primary rounded-pill px-3">
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada bon
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-3">
            {{ $bons->links() }}
        </div>

    </div>
    </div>

</div>

@endsection