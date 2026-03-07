{{-- resources/views/dosen/bimbingan/index.blade.php --}}
@extends('layouts.app')
@section('title','Mahasiswa Bimbingan')
@section('page-title','Mahasiswa Bimbingan')
@section('page-sub','Daftar mahasiswa yang dibimbing')

@section('content')
@php
$bimbingan = \App\Models\PengajuanMagang::with(['mahasiswa','mitra'])
    ->where('dosen_id', auth()->user()->dosen->id)
    ->orderByDesc('created_at')->paginate(15);
@endphp
<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Bimbingan ({{ $bimbingan->total() }})</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr><th>Mahasiswa</th><th>Perusahaan Mitra</th><th>Bidang</th><th>Periode</th><th>Progress</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($bimbingan as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:500;">{{ $p->mitra->nama_perusahaan }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->bidang_kerja }}</td>
                    <td style="font-size:11.5px;color:#64748b;">{{ $p->durasi }}</td>
                    <td style="width:120px;">
                        <div class="prog-wrap mb-1"><div class="prog-fill" style="background:#1a56db;width:{{ $p->progress }}%"></div></div>
                        <div style="font-size:11px;color:#64748b;">{{ $p->progress }}%</div>
                    </td>
                    <td><span class="bdg {{ $p->status_badge['class'] }}">{{ $p->status_badge['label'] }}</span></td>
                    <td>
                        <a href="{{ route('dosen.bimbingan.show',$p) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 10px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada mahasiswa bimbingan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bimbingan->hasPages())<div class="card-body border-top">{{ $bimbingan->links() }}</div>@endif
</div>
@endsection
