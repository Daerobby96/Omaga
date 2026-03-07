<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Magang Ditolak</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f8f9fa; padding: 20px; border: 1px solid #ddd; }
        .detail { margin: 15px 0; }
        .detail strong { display: inline-block; width: 150px; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; border: 1px solid #ddd; border-top: none; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Pengajuan Magang Ditolak</h1>
    </div>
    
    <div class="content">
        <p>Yth. <strong>{{ $mahasiswa->nama }}</strong>,</p>
        
        <p>Mohon maaf, pengajuan magang Anda telah <strong>ditolak</strong>. Berikut detailnya:</p>
        
        <div class="detail">
            <strong>Kode Pengajuan:</strong> {{ $pengajuan->kode_pengajuan }}<br>
            <strong>Mitra:</strong> {{ $mitra->nama_perusahaan }}<br>
            <strong>Bidang Kerja:</strong> {{ $pengajuan->bidang_kerja }}
        </div>
        
        @if($alasan)
        <div class="detail">
            <strong>Alasan Penolakan:</strong><br>
            {{ $alasan }}
        </div>
        @endif
        
        <p>Anda dapat mengajukan kembali dengan memilih mitra lain atau memperbaiki pengajuan.</p>
        
        <a href="{{ url('/') }}" class="btn">Ajukan Kembali</a>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Magang</p>
        <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator.</p>
    </div>
</body>
</html>
