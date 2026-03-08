{{-- resources/views/admin/mahasiswa/index.blade.php --}}
@extends('layouts.app')
@section('title','Data Mahasiswa')
@section('page-title','Data Mahasiswa')
@section('page-sub','Kelola seluruh data mahasiswa terdaftar')

@section('topbar-actions')
<a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-plus"></i> Tambah Mahasiswa
</a>
@endsection

@section('content')

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body" style="padding:16px 20px;">
        <form action="" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="prodi" class="form-select">
                    <option value="">Semua Program Studi</option>
                    @foreach($prodi as $p)
                        <option value="{{ $p }}" @selected(request('prodi')===$p)>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="angkatan" class="form-select">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatan as $a)
                        <option value="{{ $a }}" @selected(request('angkatan')===$a)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status')==='aktif')>Aktif</option>
                    <option value="cuti" @selected(request('status')==='cuti')>Cuti</option>
                    <option value="lulus" @selected(request('status')==='lulus')>Lulus</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Mahasiswa ({{ $mahasiswa->total() }})</span>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-success btn-sm"><i class="fas fa-file-excel me-1"></i>Export</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mahasiswa</th>
                    <th>Program Studi</th>
                    <th>Angkatan</th>
                    <th>IPK</th>
                    <th>Status Akademik</th>
                    <th>Status Akun</th>
                    <th>Status Magang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $m)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $mahasiswa->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av" style="background:hsl({{ crc32($m->nim) % 360 }},60%,50%);">{{ $m->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;">{{ $m->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;font-family:'DM Mono',mono;">{{ $m->nim }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $m->program_studi }}</td>
                    <td style="font-size:13px;font-weight:600;">{{ $m->angkatan }}</td>
                    <td>
                        <span style="font-size:14px;font-weight:700;color:{{ $m->ipk >= 3.5 ? '#0ea472' : ($m->ipk >= 3.0 ? '#1a56db' : '#f59e0b') }}">
                            {{ number_format($m->ipk,2) }}
                        </span>
                    </td>
                    <td><span class="bdg {{ $m->status_badge['class'] }}">{{ $m->status_badge['label'] }}</span></td>
                    <td>
                        @if($m->user->is_active)
                            <span class="bdg bg-success-subtle text-success"><i class="fas fa-check-circle"></i> Aktif</span>
                        @else
                            <span class="bdg bg-danger-subtle text-danger"><i class="fas fa-times-circle"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        @php $aktif = $m->pengajuanAktif; @endphp
                        @if($aktif)
                            <span class="bdg bg-success-subtle text-success"><i class="fas fa-circle" style="font-size:8px;"></i> Sedang Magang</span>
                        @else
                            <span class="bdg bg-secondary-subtle text-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <form action="{{ route('admin.mahasiswa.aktivasi', $m) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $m->user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size:11px;border-radius:7px;padding:4px 9px;" title="{{ $m->user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun">
                                    <i class="fas fa-{{ $m->user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.mahasiswa.show',$m) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;border-radius:7px;padding:4px 10px;">Detail</a>
                            <form action="{{ route('admin.mahasiswa.destroy',$m) }}" method="POST" onsubmit="return confirm('Hapus mahasiswa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:11px;border-radius:7px;padding:4px 10px;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-user-graduate fa-2x mb-2 d-block opacity-25"></i>
                    Belum ada mahasiswa terdaftar
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mahasiswa->hasPages())
    <div class="card-body border-top" style="padding:14px 20px;">
        {{ $mahasiswa->links() }}
    </div>
    @endif
</div>
@endsection
