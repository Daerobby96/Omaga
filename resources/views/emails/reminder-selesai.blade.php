<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Magang Akan Selesai</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #ddd; }
        .detail { margin: 15px 0; }
        .detail strong { display: inline-block; width: 150px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; border: 1px solid #ddd; border-top: none; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ Pengingat: Magang Akan Selesai</h1>
    </div>
    
    <div class="content">
        <p>Yth. <strong>{{ $mahasiswa->nama }}</strong>,</p>
        
        <p>Kami mengingatkan bahwa masa magang Anda akan segera berakhir.</p>
        
        <div class="detail">
            <strong>Mitra:</strong> {{ $mitra->nama_perusahaan }}<br>
            <strong>Tanggal Mulai:</strong> {{ $pengajuan->tanggal_mulai->locale('id')->translatedFormat('d F Y') }}<br>
            <strong>Tanggal Selesai:</strong> {{ $pengajuan->tanggal_selesai->locale('id')->translatedFormat('d F Y') }}<br>
            <strong>Sisa Hari:</strong> <strong>{{ $sisaHari }} hari</strong>
        </div>
        
        <p>Silakan pastikan:</p>
        <ul>
            <li>Logbook sudah lengkap hingga akhir masa magang</li>
            <li>Memesan surat keterangan selesai magang (jika diperlukan)</li>
            <li>Mengisi evaluasi magang</li>
        </ul>
        
        <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn">Lihat Detail Pengajuan</a>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Magang</p>
    </div>
</body>
</html>
