<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Magang Siap Diunduh</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #ffc107; color: #333; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #ddd; }
        .detail { margin: 15px 0; }
        .detail strong { display: inline-block; width: 150px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; border: 1px solid #ddd; border-top: none; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #ffc107; color: #333; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏆 Sertifikat Magang Siap Diunduh</h1>
    </div>
    
    <div class="content">
        <p>Yth. <strong>{{ $mahasiswa->nama }}</strong>,</p>
        
        <p>Selamat! Sertifikat magang Anda telah diterbitkan dan siap untuk diunduh.</p>
        
        <div class="detail">
            <strong>Nomor Sertifikat:</strong> {{ $sertifikat->nomor_sertifikat }}<br>
            <strong>Nama:</strong> {{ $mahasiswa->nama }}<br>
            <strong>NIM:</strong> {{ $mahasiswa->nim }}<br>
            <strong>Mitra:</strong> {{ $pengajuan->mitra->nama_perusahaan }}<br>
            <strong>Periode:</strong> {{ $pengajuan->tanggal_mulai->format('d F Y') }} - {{ $pengajuan->tanggal_selesai->format('d F Y') }}<br>
            <strong>Tanggal Terbit:</strong> {{ $sertifikat->diterbitkan_at->format('d F Y') }}
        </div>
        
        @if($pengajuan->penilaian && $pengajuan->penilaian->nilai_akhir)
        <div class="detail">
            <strong>Nilai Akhir:</strong> {{ $pengajuan->penilaian->nilai_akhir }} ({{ $pengajuan->penilaian->grade }})<br>
            <strong>Status:</strong> {{ $pengajuan->penilaian->lulus ? 'Lulus' : 'Tidak Lulus' }}
        </div>
        @endif
        
        <p>Silakan unduh sertifikat Anda melalui tombol di bawah ini.</p>
        
        <a href="{{ route('mahasiswa.sertifikat.download', $sertifikat) }}" class="btn">Unduh Sertifikat</a>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Magang</p>
        <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator.</p>
    </div>
</body>
</html>
