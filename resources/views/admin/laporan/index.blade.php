{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan & Statistik Magang')
@section('page-sub','Rekap data program magang')

@section('topbar-actions')
<form action="{{ route('admin.laporan.index') }}" method="GET" class="d-flex gap-2">
    <select name="tahun" class="form-select form-select-sm" style="width:auto;">
        @foreach($tahun_list as $t)
        <option value="{{ $t }}" @selected($t==$tahun)>{{ $t }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary btn-sm"><i class="fas fa-sync me-1"></i>Tampilkan</button>
</form>
<a href="{{ route('admin.laporan.export-pdf') }}?tahun={{ $tahun }}" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>
@endsection

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value">{{ $data['total_pengajuan'] }}</div>
            <div class="stat-label">Total Pengajuan {{ $tahun }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $data['selesai'] }}</div>
            <div class="stat-label">Magang Selesai {{ $tahun }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card orange">
            <div class="stat-icon orange"><i class="fas fa-spinner"></i></div>
            <div class="stat-value">{{ $data['berjalan'] }}</div>
            <div class="stat-label">Sedang Berjalan</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Grafik per Bulan --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Pengajuan per Bulan — {{ $tahun }}</span></div>
            <div class="card-body"><canvas id="bulanChart" height="120"></canvas></div>
        </div>
    </div>

    {{-- Top Mitra --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Top 10 Mitra Terfavorit</span></div>
            <div class="card-body">
                @foreach($data['per_mitra'] as $m)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:12px;font-weight:500;">{{ Str::limit($m->nama_perusahaan,22) }}</span>
                        <span style="font-size:12px;font-weight:700;">{{ $m->pengajuan_count }}</span>
                    </div>
                    <div class="prog-wrap">
                        <div class="prog-fill" style="background:#1a56db;width:{{ $data['per_mitra']->max('pengajuan_count') > 0 ? ($m->pengajuan_count/$data['per_mitra']->max('pengajuan_count'))*100 : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Per Prodi --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Mahasiswa per Program Studi</span></div>
            <div class="card-body"><canvas id="prodiChart" height="200"></canvas></div>
        </div>
    </div>

    {{-- Tabel Rekapitulasi --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><span class="card-header-title">Rekap Bulanan {{ $tahun }}</span></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Bulan</th><th>Pengajuan</th></tr></thead>
                    <tbody>
                        @foreach($data['per_bulan'] as $b)
                        <tr>
                            <td style="font-size:13px;">{{ $b['bulan'] }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="prog-wrap" style="width:80px;flex-shrink:0;">
                                        <div class="prog-fill" style="background:#1a56db;width:{{ $data['per_bulan']->max('jumlah') > 0 ? ($b['jumlah']/$data['per_bulan']->max('jumlah'))*100 : 0 }}%"></div>
                                    </div>
                                    <span style="font-size:13px;font-weight:700;">{{ $b['jumlah'] }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Grafik per bulan
new Chart(document.getElementById('bulanChart'), {
    type: 'bar',
    data: {
        labels: @json($data['per_bulan']->pluck('bulan')),
        datasets: [{
            label: 'Jumlah Pengajuan',
            data:  @json($data['per_bulan']->pluck('jumlah')),
            backgroundColor: 'rgba(26,86,219,.8)',
            borderRadius: 6, borderSkipped: false,
        }]
    },
    options: {
        plugins:{ legend:{ display:false } },
        scales:{
            y:{ beginAtZero:true, grid:{ color:'#f1f5f9' }, ticks:{ font:{ size:11 } } },
            x:{ grid:{ display:false }, ticks:{ font:{ size:11 } } }
        }
    }
});

// Grafik per prodi
new Chart(document.getElementById('prodiChart'), {
    type: 'doughnut',
    data: {
        labels: @json($data['per_prodi']->pluck('program_studi')),
        datasets: [{
            data: @json($data['per_prodi']->pluck('total')),
            backgroundColor: ['#1a56db','#0ea472','#f59e0b','#8b5cf6','#ef4444','#ec4899'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: {
        plugins:{ legend:{ position:'bottom', labels:{ font:{ size:11 }, boxWidth:12 } } },
        cutout: '60%'
    }
});
</script>
@endpush
