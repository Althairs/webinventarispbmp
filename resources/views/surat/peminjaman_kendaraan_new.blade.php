<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peminjaman Kendaraan - {{ $request->user->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 50px;
            line-height: 1.6;
            color: #000;
            font-size: 14px;
        }

        body {
            font-family: 'Times New Roman', serif;
            margin: 40px 70px;
            line-height: 1.6;
            color: #000;
            font-size: 12px;
        }

        /* === JUDUL SURAT === */
        .title {
            text-align: center;
            margin: 30px 0 10px 0;
            font-size: 12px;
        }

        .title h2 {
            margin: 0;
            font-size: 12px;
            text-decoration: underline;
        }

        .title p {
            margin: 0;
            font-size: 12px;
        }

        /* === ISI SURAT === */
        .content p {
            text-align: justify;
            margin: 6px 0;
            font-size: 12px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 12px;
            line-height: 1.5;
        }

        td:first-child {
            width: 180px;
        }

        .notes {
            font-size: 13px;
            margin-top: 10px;
            font-size: 12px;
            line-height: 1.5;
        }

        /* === STATUS BOX === */
        .status {
            margin-top: 40px;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
        }

        .status.approved {
            border: 2px solid #28a745;
            background: #d4edda;
            color: #155724;
        }

        .status.rejected {
            border: 2px solid #dc3545;
            background: #f8d7da;
            color: #721c24;
        }

        .status.pending {
            border: 2px solid #ffc107;
            background: #fff3cd;
            color: #856404;
        }

        /* === FOOTER === */
        .footer {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-top: 40px;
            border-top: 1px dashed #999;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    {{-- KOP SURAT --}}
    <div class="kop-surat" style="border-bottom: 2px solid black; padding-bottom: 2px; margin-bottom: 15px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 10%; text-align: left; padding: 0; vertical-align: middle;">
                    <img src="{{ $logoPath ?? asset('logo-bpmp-small.png')}}" alt="Logo BPMP" width="70" height="70">
                </td>

                <td style="width: 70%; text-align: center; padding: 0; vertical-align: middle;">
                        <p style="margin: 0; line-height: 1.2;font-size: 15px;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,</p>
                        <p style="margin: 0; line-height: 1.2;font-size: 15px;">RISET, DAN TEKNOLOGI</p>
                        <h3 style="margin: 0; line-height: 1.2; font-weight: bold;font-size: 15px;">BALAI PENJAMINAN MUTU PENDIDIKAN</h3>
                        <h4 style="margin: 0; line-height: 1.2; font-weight: bold;font-size: 15px;">PROVINSI GORONTALO</h4>
                        <p style="font-size: 10px; margin: 0; line-height: 1.2;">
                            Jalan dr. Zainal Umar Sidiki, Tilongkabila, Bone Bolango<br>
                            Telepon: 085242700045 &nbsp; | &nbsp; Po. Box: 1024 &nbsp; | &nbsp; Kode Pos: 96554<br>
                            Laman: www.bpmpgorontalo.kemdikbud.go.id &nbsp; | &nbsp; Email: bpmpgorontalo@kemdikbud.go.id
                        </p>
                    </td>
                <td style="width: 20%;"></td>
            </tr>
        </table>
    </div>

    {{-- JUDUL --}}
    <div class="title">
        <h2>BERITA ACARA PINJAM PAKAI</h2>
        <p><strong>No: S0015/BPMP.GTLO/KPA/{{ date('Y') }}</strong></p>
    </div>

    {{-- ISI SURAT --}}
    <div class="content">
        <p>Pada hari ini <b>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}</b> tanggal <b>{{ \Carbon\Carbon::now()->translatedFormat('j') }}</b> bulan <b>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F') }}</b> Tahun <b>{{ \Carbon\Carbon::now()->translatedFormat('Y') }}</b> oleh yang bertanda tangan di bawah ini:</p>

        <table>
            <tr><td>Nama</td><td>:</td><td><b>{{ $request->user->nama_lengkap }}</b></td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $request->user->nip }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>{{ $request->user->jabatan ?? 'Pelaksana' }}</td></tr>
        </table>

        <p>Sesuai dengan <b>PMK Nomor:115/PMK.06/2020 Tentang Pemanfaatan Barang Milik Negara</b>, maka demi tertibnya administrasi Berita Acara Pinjam Pakai ini sebagai berikut:</p>

        <table>
            <tr><td>Nama Barang</td><td>:</td><td><b>{{ $request->nama_kendaraan }}</b></td></tr>
            <tr><td>Merk/Type</td><td>:</td><td>{{ $request->merek }} {{ ucfirst($request->kategori) }}</td></tr>
            <tr><td>Jumlah</td><td>:</td><td>{{ $request->jumlah }} unit</td></tr>
            <tr><td>Jangka Waktu</td><td>:</td><td>{{ $request->tanggal_peminjaman ? \Carbon\Carbon::parse($request->tanggal_peminjaman)->locale('id')->translatedFormat('d F Y') : 'N/A' }} s/d {{ $request->tanggal_pengembalian ? \Carbon\Carbon::parse($request->tanggal_pengembalian)->locale('id')->translatedFormat('d F Y') : 'N/A' }}</td></tr>
            <tr><td>Untuk Keperluan</td><td>:</td><td>{{ $request->deskripsi_peruntukan ?? 'Keperluan Dinas' }}</td></tr>
        </table>

        <p>Dengan penuh tanggung jawab, dan apabila dikemudian hari barang tersebut hilang maka sebagai Peminjam bertanggung jawab atas kerugian Aset Negara, sesuai dengan ketentuan <b>Pasal 1740 KUHPerdata</b>.</p>

        <p>Demikian berita acara ini ditandatangani dengan sebenar-benarnya dan untuk digunakan sebagaimana mestinya.</p>

        <div class="notes">
            <b>Catatan Penting:</b><br>
            1. Barang bisa ditarik sewaktu-waktu apabila terjadi penyimpangan / penyalahgunaan atau dibutuhkan untuk kepentingan lembaga/kantor.<br>
            2. Kerusakan yang diakibatkan karena kelalaian pengguna maka kerusakan tersebut menjadi tanggung jawab pihak peminjam.
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 40px;">
        <tr>
            {{-- Kolom Kiri: Mengetahui (Kasubag Umum) --}}
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="min-height: 150px; position: relative;">
                    <div>
                        <p style="font-weight: 600; font-size: 12px; margin-bottom: 0.75rem;">
                            Mengetahui,
                        </p>
                        <p style="font-weight: 600; font-size: 12px; margin-bottom: 0.75rem;">
                            Kasubag Umum
                        </p>
                        {{-- Area tanda tangan dengan tinggi fixed --}}
                        <div style="height: 80px;">
                            &nbsp;
                        </div>
                    </div>
                    <div style="margin-top: 0.75rem;">
                        <p style="font-weight: 700; font-size: 12px; margin: 0;">
                            Dr. Deisy Sampul, S.Kom., M.Si.
                        </p>
                        <p style="margin: 0; font-size: 12px;">NIP. 197612012003122001</p>
                    </div>
                </div>
            </td>
            {{-- Kolom Kanan: Peminjam --}}
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="min-height: 200px; position: relative;">
                    <div>
                        {{-- Gorontalo dipindah ke atas sendiri --}}
                        <p style="font-weight: 600; margin-bottom: 3rem; font-size: 12px; text-align: right; padding-right: 2rem;">
                            Gorontalo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </p>
                        <p style="font-weight: 600; font-size: 12px; margin-bottom: 0.75rem;">
                            Peminjam
                        </p>
                        {{-- Area tanda tangan dengan tinggi fixed --}}
                        <div style="height: 80px; text-align: center;">
                            @if (!empty($ttdBase64))
                                <img
                                    src="{{ $ttdBase64 }}"
                                    alt="Tanda Tangan"
                                    style="max-width: 70px; max-height: 80px;"
                                >
                            @else
                                &nbsp;
                            @endif
                        </div>
                    </div>
                    <div style="margin-top: 0.75rem;">
                        <p style="font-weight: 700; font-size: 12px; margin: 0;">
                            {{ $request->user->nama_lengkap ?? ($request->nama_peminjam ?? '........................') }}
                        </p>
                        <p style="margin: 0; font-size: 12px;">
                            NIP. {{ $request->user->nip ?? ($request->nip_peminjam ?? '........................') }}
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- STATUS --}}
    @if($request->status === 'approved')
        <div class="status approved">
            DITERIMA<br>
            Tanggal Persetujuan: {{ $request->updated_at ? \Carbon\Carbon::parse($request->updated_at)->translatedFormat('d F Y') : 'N/A' }}<br>
            @if($request->komentar)<small>Catatan: {{ $request->komentar }}</small>@endif
        </div>
    @elseif($request->status === 'rejected')
        <div class="status rejected">
            DITOLAK<br>
            Tanggal Penolakan: {{ $request->updated_at ? \Carbon\Carbon::parse($request->updated_at)->translatedFormat('d F Y') : 'N/A' }}<br>
            @if($request->komentar)<small>Alasan: {{ $request->komentar }}</small>@endif
        </div>
    @else
        <div class="status pending">
            MENUNGGU PERSETUJUAN<br>
            Status: Sedang dalam proses review
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini digenerate otomatis oleh Sistem Inventaris BPMP Provinsi Gorontalo<br>
        Dicetak pada: {{ $tanggalCetak ?? now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>
