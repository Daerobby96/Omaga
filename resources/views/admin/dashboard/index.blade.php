@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard')
@section('page-sub','Selamat datang, ' . auth()->user()->name)

@section('topbar-actions')
<a href="{{ route('admin.pengajuan.index') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
    <i class="fas fa-file-alt"></i> Semua Pengajuan
</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="stat-card blue">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value">{{ $stats['total_mahasiswa'] }}</div>
            <div class="stat-label">Total Mahasiswa</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card green">
            <div class="stat-icon green"><i class="fas fa-building"></i></div>
            <div class="stat-value">{{ $stats['total_mitra'] }}</div>
            <div class="stat-label">Mitra Aktif</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card purple" style="--r:#8b5cf6">
            <div class="stat-icon purple"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value">{{ $stats['total_dosen'] }}</div>
            <div class="stat-label">Dosen Pembimbing</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card orange">
            <div class="stat-icon orange"><i class="fas fa-spinner"></i></div>
            <div class="stat-value">{{ $stats['magang_berjalan'] }}</div>
            <div class="stat-label">Magang Berjalan</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card red">
            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ $stats['pengajuan_pending'] }}</div>
            <div class="stat-label">Pengajuan Pending</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card" style="border-top:3px solid #64748b;">
            <div class="stat-icon" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $stats['magang_selesai'] }}</div>
            <div class="stat-label">Magang Selesai</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Pengajuan Terbaru --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <span class="card-header-title">Pengajuan Magang Terbaru</span>
                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Mitra</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan_terbaru as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="av" style="background:#1a56db;">{{ $p->mahasiswa->avatar_initials }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:13.5px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                        <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:500;">{{ $p->mitra->nama_perusahaan }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->bidang_kerja }}</div>
                            </td>
                            <td style="font-size:12px;font-family:'DM Mono',mono;color:#64748b;">
                                {{ $p->created_at->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td>
                                <span class="bdg {{ $p->status_badge['class'] }}">
                                    {{ $p->status_badge['label'] }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pengajuan.show',$p) }}" class="btn btn-sm btn-outline-primary" style="font-size:12px;border-radius:7px;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pengajuan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Per Prodi & Grafik --}}
    <div class="col-xl-4">
        <div class="card mb-4">
            <div class="card-header">
                <span class="card-header-title">Mahasiswa per Program Studi</span>
            </div>
            <div class="card-body">
                @foreach($per_prodi as $prodi)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:13px;font-weight:500;">{{ $prodi->program_studi }}</span>
                        <span style="font-size:13px;font-weight:700;">{{ $prodi->total }}</span>
                    </div>
                    <div class="prog-wrap">
                        <div class="prog-fill" style="background:#1a56db;width:{{ ($prodi->total / max($per_prodi->max('total'),1)) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-header-title">Pengajuan 6 Bulan Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="grafikChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Magang Aktif Saat Ini --}}
<div class="card mt-4">
    <div class="card-header">
        <span class="card-header-title">Magang Sedang Berjalan</span>
        <span class="bdg bg-success-subtle text-success">{{ $stats['magang_berjalan'] }} Aktif</span>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Perusahaan Mitra</th>
                    <th>Dosen Pembimbing</th>
                    <th>Periode</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa_berjalan as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av" style="background:#0ea472;">{{ $p->mahasiswa->avatar_initials }}</div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <div style="font-size:11px;color:#64748b;">{{ $p->mahasiswa->program_studi }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $p->mitra->nama_perusahaan }}</td>
                    <td style="font-size:13px;">{{ $p->dosen?->nama_lengkap ?? '-' }}</td>
                    <td style="font-size:12px;color:#64748b;">{{ $p->durasi }}</td>
                    <td style="width:130px;">
                        <div class="prog-wrap mb-1">
                            <div class="prog-fill" style="background:#1a56db;width:{{ $p->progress }}%"></div>
                        </div>
                        <div style="font-size:11px;color:#64748b;">{{ $p->progress }}% • Sisa {{ $p->sisa_hari }} hari</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.pengajuan.show',$p) }}" class="btn btn-sm btn-outline-secondary" style="font-size:12px;border-radius:7px;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada magang aktif</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('grafikChart'), {
    type: 'bar',
    data: {
        labels: @json($grafik->pluck('bulan')),
        datasets: [{
            data: @json($grafik->pluck('jumlah')),
            backgroundColor: 'rgba(26,86,219,0.8)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
