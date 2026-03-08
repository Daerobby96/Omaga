<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Isi Logbook Magang</title>
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
        <h1>📝 Reminder: Isi Logbook Magang</h1>
    </div>
    
    <div class="content">
        <p>Yth. <strong>{{ $mahasiswa->nama }}</strong>,</p>
        
        <p>Kami mengingatkan Anda untuk segera mengisi <strong>logbook magang</strong> Anda.</p>
        
        <div class="detail">
            <strong>Mitra:</strong> {{ $mitra->nama_perusahaan }}<br>
            <strong>Terakhir isi:</strong> {{ $hariTanpaLogbook }} hari yang lalu
        </div>
        
        <p>Mengisi logbook secara rutin sangat penting untuk:</p>
        <ul>
            <li>Melacak aktivitas harian Anda selama magang</li>
            <li>Sebagai bahan penilaian dari dosen pembimbing</li>
            <li>Dokumentasi pengalaman kerja Anda</li>
        </ul>
        
        <a href="{{ route('mahasiswa.logbook.create') }}" class="btn">Isi Logbook Sekarang</a>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Magang</p>
        <p>Jika Anda sudah mengisi logbook, abaikan email ini.</p>
    </div>
</body>
</html>
