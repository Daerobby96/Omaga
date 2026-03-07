{{-- resources/views/mahasiswa/logbook/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Logbook')
@section('page-title','Edit Logbook Harian')
@section('page-sub', $logbook->pengajuan->mitra->nama_perusahaan . ' · ' . \Carbon\Carbon::parse($logbook->tanggal)->locale('id')->translatedFormat('d F Y'))

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-3" style="background:#e3f8ef;border:1px solid #a7f3d0;">
    <div class="card-body d-flex align-items-center gap-3 py-3">
        <div class="av" style="background:#0ea472;"><i class="fas fa-briefcase" style="font-size:14px;color:white;"></i></div>
        <div>
            <div style="font-size:14px;font-weight:700;color:#065f46;">{{ $logbook->pengajuan->mitra->nama_perusahaan }}</div>
            <div style="font-size:12px;color:#047857;">Dosen: {{ $logbook->pengajuan->dosen?->nama_lengkap ?? '-' }} · {{ $logbook->pengajuan->bidang_kerja }}</div>
        </div>
    </div>
</div>

@if($logbook->status === 'revisi')
<div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <div>
        <strong>Revisi dari Dosen:</strong> {{ $logbook->catatan_revisi ?? 'Silakan perbaiki logbook Anda sesuai catatan dari dosen.' }}
    </div>
</div>
@endif

<form action="{{ route('mahasiswa.logbook.update', $logbook->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-clock me-2 text-primary"></i>Kehadiran</span></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', $logbook->tanggal->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Masuk <span class="text-danger">*</span></label>
                    <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                           value="{{ old('jam_masuk', $logbook->jam_masuk) }}" step="1800">
                    @error('jam_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Keluar <span class="text-danger">*</span></label>
                    <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror"
                           value="{{ old('jam_keluar', $logbook->jam_keluar) }}" step="1800">
                    @error('jam_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div id="durasiKerja" class="mt-2" style="font-size:13px;color:#0ea472;"></div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><span class="card-header-title"><i class="fas fa-tasks me-2 text-success"></i>Aktivitas Kerja</span></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Kegiatan yang Dilakukan <span class="text-danger">*</span></label>
                <textarea name="kegiatan" class="form-control @error('kegiatan') is-invalid @enderror" rows="5"
                          placeholder="Deskripsikan secara detail kegiatan yang Anda lakukan hari ini. Minimal 20 karakter.
Contoh:
- Mengerjakan fitur login menggunakan Laravel Breeze
- Mengikuti daily standup meeting tim developer
- Review pull request dari rekan kerja">{{ old('kegiatan', $logbook->kegiatannya) }}</textarea>
                @error('kegiatannya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="d-flex justify-content-end mt-1">
                    <span id="kegiatancCount" style="font-size:11px;color:#94a3b8;">{{ strlen($logbook->kegiatannya) }} karakter</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hasil / Output</label>
                <textarea name="hasil" class="form-control" rows="3"
                          placeholder="Apa yang berhasil Anda selesaikan atau capai hari ini?">{{ old('hasil', $logbook->hasil) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Kendala (jika ada)</label>
                <textarea name="kendala" class="form-control" rows="2"
                          placeholder="Apakah ada kendala yang dihadapi? Bagaimana solusinya?">{{ old('kendala', $logbook->kendala) }}</textarea>
            </div>
            <div>
                <label class="form-label">Foto Kegiatan (opsional)</label>
                <input type="file" name="foto_kegiatannya" class="form-control" accept="image/*" id="fotoInput">
                <div class="form-text">JPG/PNG, maks. 2MB. Foto dapat berupa screenshot pekerjaan atau dokumentasi kegiatan.</div>
                @if($logbook->foto_kegiatannya)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$logbook->foto_kegiatannya) }}" class="rounded" style="max-height:150px;" alt="Foto kegiatan saat ini">
                    <div class="form-text">Foto saat ini. Unggah foto baru untuk mengganti.</div>
                </div>
                @endif
                <img id="fotoPreview" class="mt-2 rounded" style="max-height:150px;display:none;" alt="preview">
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <button type="submit" name="action" value="submit" class="btn btn-primary px-4">
            <i class="fas fa-paper-plane me-2"></i>Kirim ke Dosen
        </button>
        <button type="submit" name="action" value="draft" class="btn btn-outline-secondary px-4">
            <i class="fas fa-save me-2"></i>Simpan Draft
        </button>
        <a href="{{ route('mahasiswa.logbook.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Convert 12-hour to 24-hour format on input change
    function convertTo24Hour(timeStr) {
        if (!timeStr) return timeStr;
        // Check if already in 24-hour format
        if (!timeStr.toLowerCase().includes('am') && !timeStr.toLowerCase().includes('pm')) {
            return timeStr;
        }
        var parts = timeStr.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
        if (!parts) return timeStr;
        var hours = parseInt(parts[1]);
        var minutes = parts[2];
        var meridiem = parts[3].toUpperCase();
        if (meridiem === 'PM' && hours < 12) hours += 12;
        if (meridiem === 'AM' && hours === 12) hours = 0;
        return (hours < 10 ? '0' + hours : hours) + ':' + minutes;
    }
    
    $('input[type="time"]').on('change', function() {
        var val = $(this).val();
        $(this).val(convertTo24Hour(val));
    });
    
    // Hitung durasi kerja
    function hitungDurasi(){
        const masuk  = $('input[name="jam_masuk"]').val();
        const keluar = $('input[name="jam_keluar"]').val();
        if(masuk && keluar && keluar > masuk){
            const [hm,mm] = masuk.split(':').map(Number);
            const [hk,mk] = keluar.split(':').map(Number);
            const menit = (hk*60+mk)-(hm*60+mm);
            const jam = Math.floor(menit/60), sisa = menit%60;
            $('#durasiKerja').html(`<i class="fas fa-check-circle me-1"></i>Durasi kerja: <strong>${jam} jam ${sisa} menit</strong>`);
        }
    }
    $('input[name="jam_masuk"], input[name="jam_keluar"]').on('change', hitungDurasi);
    hitungDurasi();

    // Counter karakter
    $('textarea[name="kegiatannya"]').on('input',function(){
        const n = $(this).val().length;
        $('#kegiatancCount').text(n+' karakter').css('color', n < 20 ? '#ef4444' : '#94a3b8');
    });

    // Preview foto
    $('#fotoInput').on('change',function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = e => { $('#fotoPreview').attr('src',e.target.result).show(); };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
