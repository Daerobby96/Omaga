{{-- resources/views/ketua_prodi/mahasiswa/index.blade.php --}}
@extends('layouts.app')
@section('title','Mahasiswa Prodi')
@section('page-title','Data Mahasiswa Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Daftar Mahasiswa {{ $prodi }}</h5>
        <span class="badge bg-primary">{{ $mahasiswa->total() }} mahasiswa</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $m)
                    <tr>
                        <td>{{ $m->nim }}</td>
                        <td>{{ $m->nama_lengkap }}</td>
                        <td>{{ $m->program_studi }}</td>
                        <td>{{ $m->angkatan }}</td>
                        <td>{{ $m->semester }}</td>
                        <td>{{ number_format($m->ipk, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $m->status_akademik === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($m->status_akademik) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $mahasiswa->links() }}
    </div>
</div>
@endsection
