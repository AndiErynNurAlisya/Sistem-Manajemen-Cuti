{{-- resources/views/pdf/leave-approval-letter.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Cuti - {{ $employeeName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header .company-info {
            font-size: 10pt;
            line-height: 1.4;
        }
        
        .document-title {
            text-align: center;
            margin: 30px 0 40px 0;
        }
        
        .document-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        
        .document-meta {
            margin-bottom: 30px;
        }
        
        .document-meta table {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .document-meta td {
            padding: 3px 0;
        }
        
        .document-meta td:first-child {
            width: 100px;
            vertical-align: top;
        }
        
        .document-meta td:nth-child(2) {
            width: 10px;
            vertical-align: top;
        }
        
        .recipient {
            margin-bottom: 20px;
        }
        
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        
        .content p {
            margin-bottom: 15px;
        }
        
        .data-table {
            margin: 20px 0 20px 40px;
        }
        
        .data-table table {
            width: 100%;
        }
        
        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .data-table td:first-child {
            width: 150px;
        }
        
        .data-table td:nth-child(2) {
            width: 10px;
        }
        
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        
        .signature .signature-block {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        
        .signature .signature-space {
            height: 80px;
        }
        
        .signature .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            color: #666;
        }
        
        .closing {
            margin-top: 20px;
            text-align: justify;
        }
        
        .approval-box {
            border: 1px solid #000;
            padding: 15px;
            margin: 20px 0;
            background-color: #f9f9f9;
        }
        
        .approval-box h3 {
            font-size: 12pt;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    {{-- Header Perusahaan (Data dari Service) --}}
    <div class="header">
        <h1>{{ $companyName }}</h1>
        <div class="company-info">
            {{ $companyAddress }}<br>
            Telp: {{ $companyPhone }} | Email: {{ $companyEmail }}
        </div>
    </div>

    {{-- Judul Surat --}}
    <div class="document-title">
        <h2>SURAT IZIN CUTI</h2>
    </div>

    {{-- Nomor & Lampiran (Data dari Service: generateLetterNumber) --}}
    <div class="document-meta">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td><strong>{{ $letterNumber }}</strong></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>Izin {{ $leaveType }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- Penerima (Data dari Service) --}}
    <div class="recipient">
        <p>
            Kepada Yth.<br>
            <strong>Sdr/i. {{ $employeeName }}</strong><br>
            {{ $division }}<br>
            Di tempat
        </p>
    </div>

    {{-- Salam --}}
    <div class="content">
        <p>Dengan hormat,</p>
    </div>

    {{-- Isi Surat --}}
    <div class="content">
        <p>Berdasarkan surat permohonan cuti yang telah diajukan dan setelah melalui proses persetujuan dari Ketua Divisi dan Human Resource Department, dengan ini kami <strong>menyetujui</strong> pengajuan cuti Saudara/i dengan rincian sebagai berikut:</p>
    </div>

    {{-- Data Cuti (Semua dari Service) --}}
    <div class="approval-box">
        <h3>RINCIAN CUTI YANG DISETUJUI</h3>
        <div class="data-table" style="margin-left: 0;">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $employeeName }}</strong></td>
                </tr>
                <tr>
                    <td>Divisi</td>
                    <td>:</td>
                    <td>{{ $division }}</td>
                </tr>
                <tr>
                    <td>Jenis Cuti</td>
                    <td>:</td>
                    <td><strong>{{ $leaveType }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>:</td>
                    <td>{{ $startDate }}</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>:</td>
                    <td>{{ $endDate }}</td>
                </tr>
                <tr>
                    <td>Jumlah Hari</td>
                    <td>:</td>
                    <td><strong>{{ $totalDays }} hari kerja</strong></td>
                </tr>
                <tr>
                    <td>Alasan</td>
                    <td>:</td>
                    <td>{{ $reason }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Informasi Kontak (Data dari Service) --}}
    <div class="content">
        <p>Selama menjalankan cuti, yang bersangkutan dapat dihubungi pada:</p>
        <div class="data-table">
            <table>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $address }}</td>
                </tr>
                <tr>
                    <td>Kontak Darurat</td>
                    <td>:</td>
                    <td>{{ $emergencyContact }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Penutup --}}
    <div class="closing">
        <p>Demikian surat izin cuti ini dibuat untuk dapat dipergunakan sebagaimana mestinya. Kami berharap yang bersangkutan dapat kembali bekerja dengan kondisi yang prima setelah masa cuti berakhir.</p>
    </div>

    {{-- Tanda Tangan (Data dari Service: hrdName dari approver) --}}
    <div class="signature">
        <div class="signature-block">
            <p>{{ $city }}, {{ $approvalDate }}</p>
            <p style="margin-top: 5px;">Yang Menyetujui,</p>
            <p style="margin-top: 0;">Human Resource Department</p>
            <div class="signature-space"></div>
            <p class="signature-name">{{ $hrdName }}</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p style="text-align: center;">
            <em>Surat ini sah dan ditandatangani secara elektronik</em>
        </p>
    </div>
</body>
</html>