{{-- resources/views/admin/mitra/index.blade.php --}}
@extends('layouts.app')
@section('title','Perusahaan Mitra')
@section('page-title','Perusahaan Mitra')
@section('page-sub','Kelola data mitra perusahaan')

@section('topbar-actions')
<a href="{{ route('admin.mitra.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-plus"></i> Tambah Mitra
</a>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6"><input type="text" name="search" class="form-control" placeholder="Cari nama perusahaan..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif"    @selected(request('status')==='aktif')>Aktif</option>
                    <option value="pending"  @selected(request('status')==='pending')>Pending</option>
                    <option value="nonaktif" @selected(request('status')==='nonaktif')>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-1"><a href="{{ route('admin.mitra.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times"></i></a></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-header-title">Daftar Mitra ({{ $mitra->total() }})</span></div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Perusahaan</th><th>Bidang Usaha</th><th>Kontak</th><th>Kuota</th><th>Mahasiswa Aktif</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($mitra as $m)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $m->nama_perusahaan }}</div>
                        <div style="font-size:11px;color:#64748b;">{{ $m->email_perusahaan }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $m->bidang_usaha }}</td>
                    <td>
                        <div style="font-size:13px;font-weight:500;">{{ $m->nama_kontak }}</div>
                        <div style="font-size:11px;color:#64748b;">{{ $m->jabatan_kontak }}</div>
                    </td>
                    <td>
                        <span style="font-size:15px;font-weight:700;color:#1a56db;">{{ $m->sisa_kuota }}</span>
                        <span style="font-size:12px;color:#64748b;">/{{ $m->kuota_magang }}</span>
                    </td>
                    <td>
                        <span style="font-size:15px;font-weight:700;color:#0ea472;">{{ $m->pengajuanAktif()->count() }}</span>
                    </td>
                    <td><span class="bdg {{ $m->status_badge['class'] }}">{{ $m->status_badge['label'] }}</span></td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.mitra.show',$m) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 9px;">Detail</a>
                            <form action="{{ route('admin.mitra.aktivasi',$m) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $m->status==='aktif' ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size:11px;border-radius:7px;padding:4px 9px;">
                                    {{ $m->status==='aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada mitra terdaftar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mitra->hasPages())<div class="card-body border-top">{{ $mitra->links() }}</div>@endif
</div>
@endsection
