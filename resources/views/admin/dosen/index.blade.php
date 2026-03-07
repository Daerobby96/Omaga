{{-- resources/views/admin/dosen/index.blade.php --}}
@extends('layouts.app')
@section('title','Data Dosen')
@section('page-title','Data Dosen Pembimbing')
@section('page-sub','Kelola dosen pembimbing magang')

@section('topbar-actions')
<a href="{{ route('admin.dosen.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-plus"></i> Tambah Dosen
</a>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" class="row g-2">
            <div class="col-md-8"><input type="text" name="search" class="form-control" placeholder="Cari nama / NIDN..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary w-100">Reset</a></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-header-title">Daftar Dosen ({{ $dosen->total() }})</span></div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Dosen</th><th>NIDN</th><th>Program Studi</th><th>Bimbingan Aktif</th><th>Sisa Kuota</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($dosen as $d)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#0ea472;">{{ substr($d->nama_lengkap,0,2) }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $d->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $d->jabatan_fungsional }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:12px;font-family:'DM Mono',mono;color:#64748b;">{{ $d->nidn }}</td>
                    <td style="font-size:13px;">{{ $d->program_studi }}</td>
                    <td>
                        <span style="font-size:16px;font-weight:700;color:#1a56db;">{{ $d->aktif_count ?? 0 }}</span>
                        <span style="font-size:12px;color:#64748b;"> / {{ $d->kuota_bimbingan }}</span>
                    </td>
                    <td>
                        @php $sisa = $d->kuota_bimbingan - ($d->aktif_count ?? 0); @endphp
                        <span class="bdg {{ $sisa > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $sisa }} slot
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.dosen.show',$d) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 9px;">Detail</a>
                            <a href="{{ route('admin.dosen.edit',$d) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;border-radius:7px;padding:4px 9px;">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada dosen terdaftar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dosen->hasPages())<div class="card-body border-top">{{ $dosen->links() }}</div>@endif
</div>
@endsection
