{{-- resources/views/admin/laporan/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Magang {{ $tahun }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        .filter-info { margin-bottom: 15px; padding: 10px; background: #f5f5f5; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #1a56db; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Magang {{ $tahun }}</h2>
        <p>Institut Teknologi Indonesia</p>
    </div>
    
    @if($prodi || $semester)
    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong>
        @if($prodi) Prodi: {{ $prodi }} @endif
        @if($semester) | Semester: {{ $semester }} @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi</th>
                <th>Semester</th>
                <th>Mitra</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                <td>{{ $item->mahasiswa->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->mahasiswa->program_studi ?? '-' }}</td>
                <td>{{ $item->mahasiswa->semester ?? '-' }}</td>
                <td>{{ $item->mitra->nama_perusahaan ?? '-' }}</td>
                <td>{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @endforeach
            @if($data->isEmpty())
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
