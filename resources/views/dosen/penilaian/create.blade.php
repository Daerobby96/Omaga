{{-- resources/views/dosen/penilaian/create.blade.php --}}
@extends('layouts.app')
@section('title','Input Penilaian')
@section('page-title','Input Penilaian Mahasiswa')
@section('page-sub',$pengajuan->mahasiswa->nama_lengkap . ' — ' . $pengajuan->mitra->nama_perusahaan)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

{{-- Info Mahasiswa --}}
<div class="card mb-4">
    <div class="card-body d-flex align-items-center gap-3 py-3">
        <div class="av av-lg" style="background:#1a56db;">{{ $pengajuan->mahasiswa->avatar_initials }}</div>
        <div>
            <div style="font-size:16px;font-weight:700;">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
            <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mahasiswa->nim }} · {{ $pengajuan->mahasiswa->program_studi }}</div>
            <div style="font-size:12px;color:#64748b;">Magang di <strong>{{ $pengajuan->mitra->nama_perusahaan }}</strong> · {{ $pengajuan->durasi }}</div>
        </div>
        @if($penilaian->dosenSudahNilai())
        <div class="ms-auto">
            <span class="bdg bg-success-subtle text-success"><i class="fas fa-check me-1"></i>Sudah Dinilai</span>
        </div>
        @endif
    </div>
</div>

<form action="{{ route('dosen.penilaian.store',$pengajuan) }}" method="POST" id="penilaianForm">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-star me-2 text-warning"></i>Komponen Penilaian Dosen</span>
            <span class="bdg bg-primary-subtle text-primary">Bobot 60% dari Nilai Akhir</span>
        </div>
        <div class="card-body">
            @php
            $fields = [
                'nilai_pembimbingan' => [
                    'label' => 'Nilai Pembimbingan',
                    'desc'  => 'Kualitas interaksi & ketepatan konsultasi selama masa magang',
                    'icon'  => 'fa-handshake',
                    'color' => '#1a56db',
                ],
                'nilai_laporan' => [
                    'label' => 'Nilai Laporan Akhir',
                    'desc'  => 'Kualitas penulisan, sistematika, dan isi laporan magang',
                    'icon'  => 'fa-file-alt',
                    'color' => '#0ea472',
                ],
                'nilai_seminar' => [
                    'label' => 'Nilai Seminar / Presentasi',
                    'desc'  => 'Kemampuan presentasi, penguasaan materi, dan menjawab pertanyaan',
                    'icon'  => 'fa-microphone',
                    'color' => '#8b5cf6',
                ],
            ];
            @endphp

            @foreach($fields as $name => $cfg)
            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:32px;height:32px;border-radius:8px;background:{{ $cfg['color'] }}20;display:flex;align-items:center;justify-content:center;">
                        <i class="fas {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};font-size:13px;"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;">{{ $cfg['label'] }} <span class="text-danger">*</span></div>
                        <div style="font-size:12px;color:#64748b;">{{ $cfg['desc'] }}</div>
                    </div>
                    <div class="ms-auto">
                        <span id="display_{{ $name }}" style="font-size:26px;font-weight:800;color:{{ $cfg['color'] }};">
                            {{ old($name, $penilaian->$name ?? 75) }}
                        </span>
                        <span style="font-size:13px;color:#64748b;">/100</span>
                    </div>
                </div>
                <input type="range" class="form-range nilai-range" name="{{ $name }}" id="{{ $name }}"
                       min="0" max="100" step="1"
                       value="{{ old($name, $penilaian->$name ?? 75) }}"
                       data-display="display_{{ $name }}"
                       data-color="{{ $cfg['color'] }}"
                       {{ $penilaian->dosenSudahNilai() ? 'disabled' : '' }}>
                <div class="d-flex justify-content-between mt-1">
                    <span style="font-size:11px;color:#94a3b8;">0 — Sangat Kurang</span>
                    <span style="font-size:11px;color:#94a3b8;">100 — Sangat Baik</span>
                </div>
                @error($name)<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>
            @endforeach

            {{-- Preview Nilai Rata-rata --}}
            <div class="p-3 rounded-3 text-center" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                <div style="font-size:12px;color:#64748b;margin-bottom:4px;">Rata-rata Nilai Dosen (Estimasi)</div>
                <div id="rataRataDosen" style="font-size:32px;font-weight:800;color:#1a56db;">75</div>
                <div id="gradeDosen" class="bdg bg-primary-subtle text-primary mt-1">B</div>
            </div>

            <div class="mt-4">
                <label class="form-label">Catatan untuk Mahasiswa (opsional)</label>
                <textarea name="catatan_dosen" class="form-control" rows="3"
                    placeholder="Masukkan catatan, saran, atau rekomendasi untuk mahasiswa..."
                    {{ $penilaian->dosenSudahNilai() ? 'disabled' : '' }}>{{ old('catatan_dosen', $penilaian->catatan_dosen ?? '') }}</textarea>
            </div>
        </div>
    </div>

    @if(!$penilaian->dosenSudahNilai())
    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-5">
            <i class="fas fa-save me-2"></i>Simpan Penilaian
        </button>
        <a href="{{ route('dosen.penilaian.index') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
    @else
    <div class="alert d-flex gap-2" style="background:#e3f8ef;color:#065f46;border-radius:12px;">
        <i class="fas fa-check-circle mt-1"></i>
        <div>Penilaian sudah diberikan pada {{ $penilaian->dinilai_dosen_at->locale('id')->translatedFormat('d F Y H:i') }}. Tidak dapat diubah.</div>
    </div>
    @endif
</form>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    function hitungRata(){
        const vals = $('.nilai-range').map(function(){ return parseInt($(this).val()) || 0; }).get();
        const rata = vals.length ? Math.round(vals.reduce((a,b)=>a+b,0)/vals.length) : 0;
        $('#rataRataDosen').text(rata);
        let grade='E', color='#ef4444';
        if(rata>=85){grade='A';color='#0ea472';}
        else if(rata>=75){grade='B';color='#1a56db';}
        else if(rata>=65){grade='C';color='#f59e0b';}
        else if(rata>=55){grade='D';color='#f97316';}
        $('#gradeDosen').text(grade).css({'color':color,'background':color+'20'});
    }

    $('.nilai-range').on('input',function(){
        const val = $(this).val();
        $('#'+$(this).data('display')).text(val);
        // Dynamic color based on value
        const pct = val/100;
        const color = pct >= 0.85 ? '#0ea472' : pct >= 0.75 ? '#1a56db' : pct >= 0.65 ? '#f59e0b' : '#ef4444';
        $('#'+$(this).data('display')).css('color', color);
        hitungRata();
    });
    hitungRata();
});
</script>
@endpush
