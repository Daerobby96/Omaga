{{-- resources/views/dosen/logbook/index.blade.php --}}
@extends('layouts.app')
@section('title','Review Logbook')
@section('page-title','Review Logbook Mahasiswa')
@section('page-sub','Setujui atau minta revisi logbook bimbingan')

@section('content')
{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <select name="mahasiswa_id" class="form-select">
                    <option value="">Semua Mahasiswa</option>
                    @foreach($mahasiswaBimbingan as $m)
                        <option value="{{ $m->id }}" @selected(request('mahasiswa_id')==$m->id)>{{ $m->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="submitted" @selected(request('status')==='submitted')>Menunggu Review</option>
                    <option value="disetujui" @selected(request('status')==='disetujui')>Disetujui</option>
                    <option value="revisi"    @selected(request('status')==='revisi')>Revisi</option>
                    <option value="draft"     @selected(request('status')==='draft')>Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dosen.logbook.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-header-title">Daftar Logbook ({{ $logbook->total() }})</span>
        @php $pendingCount = $logbook->getCollection()->where('status','submitted')->count(); @endphp
        @if($pendingCount)
            <span class="nav-badge red">{{ $pendingCount }} pending</span>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr><th>Mahasiswa</th><th>Tanggal</th><th>Kegiatan</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($logbook as $lb)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av av-sm" style="background:#0ea472;">{{ $lb->mahasiswa->avatar_initials }}</div>
                            <div style="font-size:13px;font-weight:600;">{{ $lb->mahasiswa->nama_lengkap }}</div>
                        </div>
                    </td>
                    <td style="font-size:12px;font-family:'DM Mono',mono;color:#64748b;">
                        {{ $lb->tanggal->locale('id')->translatedFormat('d F Y') }}
                    </td>
                    <td style="font-size:13px;max-width:280px;">{{ Str::limit($lb->kegiatan, 70) }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $lb->durasi_kerja ?? '—' }}</td>
                    <td><span class="bdg {{ $lb->status_badge['class'] }}">{{ $lb->status_badge['label'] }}</span></td>
                    <td>
                        @if($lb->status === 'submitted')
                        <div class="d-flex gap-1">
                            {{-- Setujui --}}
                            <form action="{{ route('dosen.logbook.setujui',$lb) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success" style="font-size:11px;border-radius:7px;padding:4px 8px;" title="Setujui">
                                    <i class="fas fa-check me-1"></i>Setujui
                                </button>
                            </form>
                            {{-- Revisi --}}
                            <button class="btn btn-sm btn-warning" style="font-size:11px;border-radius:7px;padding:4px 8px;"
                                data-bs-toggle="modal" data-bs-target="#revisiModal"
                                data-lb-id="{{ $lb->id }}"
                                data-lb-nama="{{ $lb->mahasiswa->nama_lengkap }}"
                                data-lb-tgl="{{ $lb->tanggal->locale('id')->translatedFormat('d F Y') }}">
                                <i class="fas fa-redo me-1"></i>Revisi
                            </button>
                        </div>
                        @else
                        <button class="btn btn-sm btn-outline-secondary"
                            style="font-size:11px;border-radius:7px;padding:4px 8px;"
                            data-bs-toggle="modal" data-bs-target="#detailModal"
                            data-kegiatan="{{ $lb->kegiatan }}"
                            data-hasil="{{ $lb->hasil }}"
                            data-kendala="{{ $lb->kendala }}"
                            data-catatan="{{ $lb->catatan_dosen }}">
                            Lihat
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-book-open fa-2x mb-2 d-block opacity-25"></i>Tidak ada logbook
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logbook->hasPages())
    <div class="card-body border-top">{{ $logbook->links() }}</div>
    @endif
</div>

{{-- Modal Revisi --}}
<div class="modal fade" id="revisiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Minta Revisi Logbook</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="revisiForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body pt-2">
                    <p id="revisiInfo" style="font-size:13px;color:#64748b;"></p>
                    <div class="mb-3">
                        <label class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea name="catatan_dosen" class="form-control" rows="4"
                            placeholder="Jelaskan bagian mana yang perlu diperbaiki..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-redo me-1"></i>Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0"><h6 class="modal-title fw-bold">Detail Logbook</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <h6 style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Kegiatan</h6>
                <p id="detailKegiatan" style="font-size:14px;white-space:pre-line;"></p>
                <h6 style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Hasil</h6>
                <p id="detailHasil" style="font-size:14px;"></p>
                <div id="detailCatatanWrap">
                    <h6 style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Catatan Dosen</h6>
                    <p id="detailCatatan" class="p-3 rounded-3" style="background:#fff8e6;font-size:13px;"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('revisiModal').addEventListener('show.bs.modal', function(e){
    const btn = e.relatedTarget;
    const lbId = btn.dataset.lbId;
    document.getElementById('revisiForm').action = `/dosen/logbook/${lbId}/revisi`;
    document.getElementById('revisiInfo').textContent = btn.dataset.lbNama + ' · ' + btn.dataset.lbTgl;
});
document.getElementById('detailModal').addEventListener('show.bs.modal', function(e){
    const btn = e.relatedTarget;
    document.getElementById('detailKegiatan').textContent = btn.dataset.kegiatan || '—';
    document.getElementById('detailHasil').textContent = btn.dataset.hasil || '—';
    const c = btn.dataset.catatan;
    document.getElementById('detailCatatanWrap').style.display = c ? 'block' : 'none';
    document.getElementById('detailCatatan').textContent = c;
});
</script>
@endpush
