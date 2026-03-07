{{-- resources/views/ketua_prodi/logbook/index.blade.php --}}
@extends('layouts.app')
@section('title','Logbook Prodi')
@section('page-title','Logbook Mahasiswa Program Studi')
@section('page-sub', $prodi)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-book me-2 text-success"></i>Logbook Mahasiswa {{ $prodi }}</h5>
        <span class="badge bg-success">{{ $logbook->total() }} entries</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mahasiswa</th>
                        <th>Mitra</th>
                        <th>Jam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logbook as $lb)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($lb->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>{{ $lb->pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $lb->pengajuan->mitra->nama_perusahaan }}</td>
                        <td>{{ $lb->jam_masuk }} - {{ $lb->jam_keluar }}</td>
                        <td>
                            @php $badge = $lb->status_badge; @endphp
                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data logbook</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $logbook->links() }}
    </div>
</div>
@endsection
