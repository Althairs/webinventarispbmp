<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Surat Resmi' }}</title>
    <link rel="stylesheet" href="{{ asset('css/letterhead.css') }}">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        
        .letter-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            text-decoration: underline;
        }
        
        .content {
            margin-bottom: 30px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .detail-table td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
        }
        
        .detail-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        
        .signature-space {
            height: 60px;
            border-bottom: 1px solid #000;
        }
        
        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        
        .status-approved {
            margin-top: 40px;
            padding: 15px;
            border: 2px solid #28a745;
            background-color: #d4edda;
            text-align: center;
        }
        
        .status-rejected {
            margin-top: 40px;
            padding: 15px;
            border: 2px solid #dc3545;
            background-color: #f8d7da;
            text-align: center;
        }
        
        .status-pending {
            margin-top: 40px;
            padding: 15px;
            border: 2px solid #ffc107;
            background-color: #fff3cd;
            text-align: center;
        }
    </style>
</head>
<body>
    @include('partials.letterhead')
    
    <div class="content">
        @yield('content')
    </div>
    
    @yield('signature')
    
    @yield('status')
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Inventaris Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>
