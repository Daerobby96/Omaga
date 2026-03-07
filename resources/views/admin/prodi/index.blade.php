{{-- resources/views/admin/prodi/index.blade.php --}}
@extends('layouts.app')
@section('title','Program Studi')
@section('page-title','Data Program Studi')
@section('page-sub','Kelola program studi diSIMAGANG')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-university me-2 text-primary"></i>Daftar Program Studi</h5>
        <a href="{{ route('admin.prodi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Prodi
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari prodi..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Program Studi</th>
                        <th>Fakultas</th>
                        <th>Jenjang</th>
                        <th>Akreditasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodi as $p)
                    <tr>
                        <td>{{ $p->kode_prodi }}</td>
                        <td>{{ $p->nama_prodi }}</td>
                        <td>{{ $p->fakultas }}</td>
                        <td>{{ $p->jenjang }}</td>
                        <td>
                            @if($p->akreditasi)
                            <span class="badge bg-{{ $p->akreditasi == 'A' ? 'success' : ($p->akreditasi == 'B' ? 'primary' : 'warning') }}">
                                {{ $p->akreditasi }} ({{ $p->tahun_akreditasi }})
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.prodi.show', $p->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.prodi.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.prodi.destroy', $p->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada data program studi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $prodi->links() }}
    </div>
</div>
@endsection
