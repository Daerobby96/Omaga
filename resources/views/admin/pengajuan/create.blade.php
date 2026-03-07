@extends('layouts.app')
@section('title','Ajukan Magang')
@section('page-title','Ajukan Magang Baru')
@section('page-sub','Lengkapi formulir pengajuan magang ke perusahaan mitra')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="alert d-flex gap-2 mb-4" style="background:#ebf0ff;color:#1a56db;border-radius:12px;">
    <i class="fas fa-info-circle mt-1"></i>
    <div style="font-size:13px;"><strong>Alur Pengajuan:</strong> Mahasiswa → Koordinator → Mitra Perusahaan. Pastikan semua dokumen telah siap sebelum mengajukan.</div>
</div>

<form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-building me-2 text-primary"></i>Pilih Perusahaan Mitra</span></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Perusahaan Mitra <span class="text-danger">*</span></label>
                <select name="mitra_id" class="form-select select2 @error('mitra_id') is-invalid @enderror" required>
                    <option value="">— Cari & pilih perusahaan —</option>
                    @foreach($mitra as $m)
                        <option value="{{ $m->id }}"
                            data-bidang="{{ $m->bidang_usaha }}"
                            data-kuota="{{ $m->sisa_kuota }}"
                            @selected(old('mitra_id')==$m->id)>
                            {{ $m->nama_perusahaan }} ({{ $m->bidang_usaha }}) · Sisa kuota: {{ $m->sisa_kuota }}
                        </option>
                    @endforeach
                </select>
                @error('mitra_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="mitraInfo" class="mt-2 p-3 rounded-3" style="background:#f8fafc;display:none;">
                    <span id="mitraBidang" class="bdg bg-primary-subtle text-primary"></span>
                    <span id="mitraKuota" class="ms-2" style="font-size:12px;color:#64748b;"></span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Bidang Kerja yang Dilamar <span class="text-danger">*</span></label>
                <input type="text" name="bidang_kerja" class="form-control @error('bidang_kerja') is-invalid @enderror"
                       value="{{ old('bidang_kerja') }}" placeholder="Contoh: Web Development, Data Analysis, Digital Marketing">
                @error('bidang_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="form-label">Deskripsi Pekerjaan (opsional)</label>
                <textarea name="deskripsi_pekerjaan" class="form-control" rows="3"
                          placeholder="Jelaskan lebih detail pekerjaan yang ingin dilakukan...">{{ old('deskripsi_pekerjaan') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-calendar me-2 text-success"></i>Periode Magang</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                           value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}">
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                           value="{{ old('tanggal_selesai') }}">
                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div id="durasiInfo" class="mt-2" style="font-size:13px;color:#0ea472;display:none;">
                <i class="fas fa-clock me-1"></i><span id="durasiText"></span>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-file-upload me-2 text-warning"></i>Upload Dokumen</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Surat Pengantar dari Prodi/Fakultas <span class="text-danger">*</span></label>
                    <input type="file" name="surat_pengantar" class="form-control @error('surat_pengantar') is-invalid @enderror" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                    @error('surat_pengantar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Proposal Magang (opsional)</label>
                    <input type="file" name="proposal" class="form-control" accept=".pdf">
                    <div class="form-text">Format PDF, maks. 5MB</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-paper-plane me-2"></i>Kirim Pengajuan</button>
        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Tampilkan info mitra
    $('select[name="mitra_id"]').on('change',function(){
        const opt = this.options[this.selectedIndex];
        if(this.value){
            $('#mitraBidang').text(opt.dataset.bidang);
            $('#mitraKuota').text('Sisa kuota: '+opt.dataset.kuota+' mahasiswa');
            $('#mitraInfo').show();
        } else { $('#mitraInfo').hide(); }
    });

    // Hitung durasi
    function updateDurasi(){
        const mulai  = new Date($('input[name="tanggal_mulai"]').val());
        const selesai= new Date($('input[name="tanggal_selesai"]').val());
        if(!isNaN(mulai)&&!isNaN(selesai)&&selesai>mulai){
            const hari  = Math.round((selesai-mulai)/(1000*60*60*24));
            const bulan = Math.floor(hari/30);
            $('#durasiText').text(`Durasi: ${hari} hari (±${bulan} bulan)`);
            $('#durasiInfo').show();
        } else { $('#durasiInfo').hide(); }
    }
    $('input[name="tanggal_mulai"], input[name="tanggal_selesai"]').on('change', updateDurasi);
});
</script>
@endpush