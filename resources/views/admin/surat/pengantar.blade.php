<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Magang</title>
    <style>
        @page {
            size: A4;
            margin: 3cm 3cm 3cm 3cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 12pt; 
            line-height: 1; 
            color: #000;
            text-align: justify;
        }
        .container { 
            width: 100%; 
            position: relative; 
            min-height: 100vh;
            background: white;
        }
        
        /* Header Image */
        .header-img {
            width: 100%;
            height: auto;
            margin-bottom: 25px;
        }

        /* Footer Image */
        .footer-img {
            position: absolute;
            bottom: 0px;
            left: 0;
            width: 100%;
            height: auto;
        }

        /* Inner Content */
        .content-wrap {
            padding: 0 40px;
        }
        
        /* Identitas Surat */
        .surat-info { 
            margin-bottom: 25px; 
            margin-left: 0;
        }
        .surat-info table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .surat-info td { 
            vertical-align: top; 
            padding: 3px 0; 
        }
        .surat-info td:first-child { 
            width: 70px; 
            padding-right: 10px;
        }
        
        /* Penerima */
        .penerima { 
            margin-bottom: 25px; 
            margin-left: 0;
        }
        .penerima p { 
            margin: 2px 0; 
            line-height: 1.5;
        }
        
        /* Content */
        .content { 
            margin-bottom: 30px; 
            text-align: justify;
        }
        .content p { 
            margin-bottom: 15px; 
            text-indent: 0;
            line-height: 1.5;
        }
        .content p.salam {
            margin-bottom: 20px;
        }
        .content p.perihal {
            margin-bottom: 20px;
            text-indent: 2.5em;
        }
        .content p.lampiran {
            margin-bottom: 20px;
            margin-left: 0;
        }
        .content p.closing {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        /* Data Mahasiswa */
        .data-mahasiswa { 
            margin: 20px 0 20px 0; 
            padding-left: 0;
        }
        .data-mahasiswa table { 
            border-collapse: collapse; 
            margin-left: 0;
        }
        .data-mahasiswa td { 
            padding: 4px 0; 
            vertical-align: top;
            line-height: 1;
        }
        .data-mahasiswa td:first-child { 
            width: 130px; 
            padding-right: 10px;
        }
        
        /* Signature */
        .signature-section { 
            margin-top: 20px; 
            margin-bottom: 30px;
            float: right; 
            width: 320px;
            text-align: left;
        }
        .signature-date { 
            margin-bottom: 3px;
            line-height: 1;
        }
        .signature-title { 
            font-weight: bold; 
            margin-bottom: 90px;
            line-height: 1;
        }
        .signature-name { 
            font-weight: bold; 
            text-decoration: underline;
            line-height: 1;
        }
        .signature-nip { 
            font-weight: bold; 
            line-height: 1.5;
        }
        
        @media print {
            body { 
                -webkit-print-color-adjust: exact; 
                font-size: 12pt;
                line-height: 1.5;
            }
            .container { 
                padding: 0; 
                min-height: auto;
            }
            @page {
                margin: 3cm 3cm 3cm 3cm;
            }
        }
    </style>
</head>
<body>
    @php
        $kopAtas = '';
        if(file_exists(public_path('kop atas.png'))){
            $kopAtas = base64_encode(file_get_contents(public_path('kop atas.png')));
        }
        $kopBawah = '';
        if(file_exists(public_path('kop bawah.png'))){
            $kopBawah = base64_encode(file_get_contents(public_path('kop bawah.png')));
        }
        $ttd = '';
        if(file_exists(public_path('ttd.png'))){
            $ttd = base64_encode(file_get_contents(public_path('ttd.png')));
        }
        
        \Carbon\Carbon::setLocale('id');
        $tgl_surat = \Carbon\Carbon::now()->translatedFormat('d F Y');
    @endphp

    <div class="container">
        <!-- Header Image -->
        @if($kopAtas)
        <img src="data:image/png;base64,{{ $kopAtas }}" class="header-img" alt="Kop Surat Atas">
        @else
        <div style="text-align:center; margin-bottom: 30px; padding-top: 20px;">
            <h2>POLITEKNIK KRAKATAU</h2>
            <hr>
        </div>
        @endif
        
        <div class="content-wrap">
            <!-- Nomor Surat -->
            <div class="surat-info">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>: {{ $nomor_surat ?? '001/SPM/TRPL/I/2024' }}</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>: <strong>Permohonan Magang</strong></td>
                    </tr>
                </table>
            </div>
            
            <!-- Penerima -->
            <div class="penerima">
                <p>Kepada Yth,</p>
                <p><strong>Pimpinan</strong></p>
                <p><strong>{{ $mitra->nama_perusahaan ?? 'Perusahaan Mitra' }}</strong></p>
                <p>Di</p>
                <p>Tempat</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <p class="salam">Dengan hormat,</p>
                
                <p class="perihal">Bersama surat ini, saya selaku Wakil Direktur I Bidang Akademik Politeknik Krakatau, mewajibkan seluruh mahasiswa/i untuk melakukan Magang sebagai salah satu prasyarat kelulusan akademik.</p>
                <p>Selanjutnya kami mohohon kepada Bapak/Ibu Pimpinan agar mahasiswa/i tersebut dapat diizinkan melakukan Magang pada perusahaan/instansi yang Bapak/Ibu Pimpin.</p>
                <p class="lampiran">Berikut adalah lampiran data mahasiswa/i:</p>
                <div class="data-mahasiswa">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>: {{ $mahasiswa->nama_lengkap ?? 'Nama Mahasiswa' }}</td>
                        </tr>
                        <tr>
                            <td>NPM</td>
                            <td>: {{ $mahasiswa->nim ?? 'NIM' }}</td>
                        </tr>
                        <tr>
                            <td>Program Studi</td>
                            <td>: {{ $mahasiswa->program_studi ?? 'Program Studi' }}</td>
                        </tr>
                        <tr>
                            <td>No. Hp</td>
                            <td>: {{ $mahasiswa->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td>: {{ $mahasiswa->semester ?? '5' }}</td>
                        </tr>
                    </table>
                </div>
                
                <p class="closing">Demikian Surat Permohonan Magang ini kami buat dengan sesungguhnya. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
            </div>
            
            <!-- Signature -->
            <div class="signature-section">
                <p class="signature-date">Cilegon, {{ $tgl_surat }}</p>
                
                @php
                    $ttd = '';
                    if(file_exists(public_path('ttd.png'))){
                        $ttd = base64_encode(file_get_contents(public_path('ttd.png')));
                    }
                @endphp
                
                <p class="signature-title" style="{{ $ttd ? 'margin-bottom: 5px;' : '' }}">Wakil Dikrektur I</p>
                
                @if($ttd)
                <div style="margin-left: -15px;">
                    <img src="data:image/png;base64,{{ $ttd }}" alt="Tanda Tangan" style="width: 150px; height: auto; margin-bottom: 5px;">
                </div>
                @endif
                
                <p class="signature-name">Yuwan Ditra Krahara, MM</p>
                <p class="signature-nip">NIP. 22940122018</p>
            </div>
            
            <div style="clear: both;"></div>
        </div>

        <!-- Footer Image -->
        @if($kopBawah)
        <img src="data:image/png;base64,{{ $kopBawah }}" class="footer-img" alt="Kop Surat Bawah">
        @endif
    </div>
</body>
</html>
