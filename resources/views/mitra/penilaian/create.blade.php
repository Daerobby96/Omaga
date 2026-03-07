{{-- resources/views/mitra/penilaian/create.blade.php --}}
@extends('layouts.app')
@section('title','Input Penilaian Mitra')
@section('page-title','Penilaian Mahasiswa Magang')
@section('page-sub', $pengajuan->mahasiswa->nama_lengkap)

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">

<div class="card mb-4">
    <div class="card-body d-flex align-items-center gap-3 py-3">
        <div class="av av-lg" style="background:#f59e0b;">{{ $pengajuan->mahasiswa->avatar_initials }}</div>
        <div>
            <div style="font-size:16px;font-weight:700;">{{ $pengajuan->mahasiswa->nama_lengkap }}</div>
            <div style="font-size:12px;color:#64748b;">{{ $pengajuan->mahasiswa->nim }} · {{ $pengajuan->mahasiswa->program_studi }}</div>
            <div style="font-size:12px;color:#64748b;">Bidang: <strong>{{ $pengajuan->bidang_kerja }}</strong> · {{ $pengajuan->durasi }}</div>
        </div>
        @if($penilaian->mitraSudahNilai())
            <div class="ms-auto"><span class="bdg bg-success-subtle text-success"><i class="fas fa-check me-1"></i>Sudah Dinilai</span></div>
        @endif
    </div>
</div>

<form action="{{ route('mitra.penilaian.store',$pengajuan) }}" method="POST">
    @csrf
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-header-title"><i class="fas fa-star me-2 text-warning"></i>Komponen Penilaian Perusahaan</span>
            <span class="bdg bg-warning-subtle text-warning">Bobot 40% dari Nilai Akhir</span>
        </div>
        <div class="card-body">
            @php
            $fields = [
                'nilai_kedisiplinan'     => ['label'=>'Kedisiplinan & Kehadiran',   'desc'=>'Ketepatan waktu, kehadiran, dan kepatuhan terhadap aturan perusahaan', 'icon'=>'fa-user-clock', 'color'=>'#1a56db'],
                'nilai_kemampuan_teknis' => ['label'=>'Kemampuan Teknis',           'desc'=>'Penguasaan keterampilan teknis yang dibutuhkan di bidang kerja',         'icon'=>'fa-code',       'color'=>'#0ea472'],
                'nilai_komunikasi'       => ['label'=>'Kemampuan Komunikasi',       'desc'=>'Kemampuan berkomunikasi secara lisan maupun tulisan dengan tim',          'icon'=>'fa-comments',   'color'=>'#8b5cf6'],
                'nilai_inisiatif'        => ['label'=>'Inisiatif & Kreativitas',    'desc'=>'Kemampuan mengambil inisiatif, kreativitas dalam menyelesaikan masalah',  'icon'=>'fa-lightbulb',  'color'=>'#f59e0b'],
                'nilai_kerjasama'        => ['label'=>'Kerjasama Tim',             'desc'=>'Kemampuan bekerja sama, berkontribusi, dan beradaptasi dengan tim',        'icon'=>'fa-handshake',  'color'=>'#ec4899'],
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
                <input type="range" class="form-range nilai-range" name="{{ $name }}"
                       min="0" max="100" step="1"
                       value="{{ old($name, $penilaian->$name ?? 75) }}"
                       data-display="display_{{ $name }}"
                       {{ $penilaian->mitraSudahNilai() ? 'disabled' : '' }}>
                <div class="d-flex justify-content-between mt-1">
                    <span style="font-size:11px;color:#94a3b8;">0 — Sangat Kurang</span>
                    <span style="font-size:11px;color:#94a3b8;">100 — Sangat Baik</span>
                </div>
            </div>
            @endforeach

            {{-- Preview rata-rata --}}
            <div class="p-3 rounded-3 text-center" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                <div style="font-size:12px;color:#64748b;margin-bottom:4px;">Rata-rata Penilaian Anda (Estimasi)</div>
                <div id="rataRataMitra" style="font-size:32px;font-weight:800;color:#f59e0b;">75</div>
                <div id="gradeMitra" class="bdg bg-warning-subtle text-warning mt-1">B</div>
            </div>

            <div class="mt-4">
                <label class="form-label">Catatan & Rekomendasi (opsional)</label>
                <textarea name="catatan_mitra" class="form-control" rows="4"
                    placeholder="Tuliskan kesan, catatan, atau rekomendasi untuk mahasiswa dan kampus..."
                    {{ $penilaian->mitraSudahNilai() ? 'disabled' : '' }}>{{ old('catatan_mitra', $penilaian->catatan_mitra ?? '') }}</textarea>
            </div>
        </div>
    </div>

    @if(!$penilaian->mitraSudahNilai())
    <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i>Simpan Penilaian</button>
        <a href="{{ route('mitra.dashboard') }}" class="btn px-4" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-weight:500;">Batal</a>
    </div>
    @else
    <div class="alert d-flex gap-2" style="background:#e3f8ef;color:#065f46;border-radius:12px;">
        <i class="fas fa-check-circle mt-1"></i>
        <div>Penilaian sudah diberikan pada {{ $penilaian->dinilai_mitra_at->format('d M Y H:i') }}. Tidak dapat diubah.</div>
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
        const vals = $('.nilai-range').map(function(){ return parseInt($(this).val())||0; }).get();
        const rata = vals.length ? Math.round(vals.reduce((a,b)=>a+b,0)/vals.length) : 0;
        $('#rataRataMitra').text(rata);
        let grade='E',color='#ef4444';
        if(rata>=85){grade='A';color='#0ea472';}
        else if(rata>=75){grade='B';color='#1a56db';}
        else if(rata>=65){grade='C';color='#f59e0b';}
        else if(rata>=55){grade='D';color='#f97316';}
        $('#gradeMitra').text(grade).css({'color':color,'background':color+'20'});
    }
    $('.nilai-range').on('input',function(){
        $('#'+$(this).data('display')).text($(this).val());
        hitungRata();
    });
    hitungRata();
});
</script>
@endpush
