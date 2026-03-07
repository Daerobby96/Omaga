<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Magang Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #ddd; }
        .detail { margin: 15px 0; }
        .detail strong { display: inline-block; width: 150px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; border: 1px solid #ddd; border-top: none; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Penilaian Magang Masuk</h1>
    </div>
    
    <div class="content">
        <p>Yth. <strong>{{ $mahasiswa->nama }}</strong>,</p>
        
        <p>Anda telah menerima penilaian magang dari <strong>{{ $jenis === 'dosen' ? 'Dosen Pembimbing' : 'Mitra' }}</strong>.</p>
        
        <div class="detail">
            <strong>Kode Pengajuan:</strong> {{ $pengajuan->kode_pengajuan }}<br>
            <strong>Mitra:</strong> {{ $pengajuan->mitra->nama_perusahaan }}<br>
            <strong>Penilai:</strong> {{ $jenis === 'dosen' ? 'Dosen Pembimbing' : $pengajuan->mitra->nama_perusahaan }}
        </div>
        
        @if($jenis === 'dosen' && $penilaian->nilai_dosen)
        <div class="detail">
            <strong>Nilai Dosen:</strong> {{ $penilaian->nilai_dosen }}<br>
            @if($penilaian->nilai_akhir)
            <strong>Nilai Akhir:</strong> {{ $penilaian->nilai_akhir }} ({{ $penilaian->grade }})<br>
            <strong>Status:</strong> {{ $penilaian->lulus ? 'Lulus' : 'Tidak Lulus' }}
            @endif
        </div>
        @elseif($jenis === 'mitra' && $penilaian->nilai_mitra)
        <div class="detail">
            <strong>Nilai Mitra:</strong> {{ $penilaian->nilai_mitra }}<br>
            @if($penilaian->nilai_akhir)
            <strong>Nilai Akhir:</strong> {{ $penilaian->nilai_akhir }} ({{ $penilaian->grade }})<br>
            <strong>Status:</strong> {{ $penilaian->lulus ? 'Lulus' : 'Tidak Lulus' }}
            @endif
        </div>
        @endif
        
        <p>Silakan login untuk melihat detail penilaian lengkap.</p>
        
        <a href="{{ url('/') }}" class="btn">Lihat Penilaian</a>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Magang</p>
        <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator.</p>
    </div>
</body>
</html>
