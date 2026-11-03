@extends('template-dashbord')

@section('content')
<div class="container">
    <h3 style="margin-bottom: 16px;">Laporan Transaksi Barang Keluar</h3>

    <form method="get" style="margin-bottom: 16px; display:flex; gap:8px; align-items:flex-end; flex-wrap: wrap;">
        <div>
            <label>Tanggal Mulai</label>
            <input type="date" name="start" value="{{ request('start') }}" class="form-control">
        </div>
        <div>
            <label>Tanggal Selesai</label>
            <input type="date" name="end" value="{{ request('end') }}" class="form-control">
        </div>
        <div>
            <label>Tipe</label>
            <select name="tipe" class="form-control">
                <option value="semua" {{ request('tipe','semua')==='semua'?'selected':'' }}>Semua</option>
                <option value="persediaan" {{ request('tipe')==='persediaan'?'selected':'' }}>Persediaan</option>
                <option value="aset" {{ request('tipe')==='aset'?'selected':'' }}>Aset Tetap</option>
            </select>
        </div>
        <div>
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
        <div>
            <a href="{{ route('laporan.keluar.export', request()->query()) }}" class="btn btn-success">Download Excel</a>
        </div>
    </form>

    <div style="margin-bottom: 12px;">
        <strong>Total Keluar Persediaan:</strong> {{ number_format($totalKeluarPersediaan) }} unit
        &nbsp;|&nbsp;
        <strong>Total Keluar Aset:</strong> {{ number_format($totalKeluarAset) }} unit
        &nbsp;|&nbsp;
        <strong>Total Keseluruhan:</strong> {{ number_format($totalKeluarPersediaan + $totalKeluarAset) }} unit
    </div>

    @if($tipe !== 'aset')
    <h5>Persediaan</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($persediaan as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y') }}</td>
                        <td>{{ $row->kode_barang }}</td>
                        <td>{{ $row->nama_barang }}</td>
                        <td>{{ number_format($row->jumlah_keluar) }}</td>
                        <td>-</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists($persediaan,'links'))
            {{ $persediaan->appends(request()->query())->links() }}
        @endif
    </div>
    @endif

    @if($tipe !== 'persediaan')
    <h5 style="margin-top: 20px;">Aset Tetap</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Keluar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aset as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_input)->format('d/m/Y') }}</td>
                        <td>{{ $row->kode_barang }}</td>
                        <td>{{ $row->nama_barang }}</td>
                        <td>{{ number_format($row->jumlah_keluar) }}</td>
                        <td>-</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists($aset,'links'))
            {{ $aset->appends(request()->query())->links() }}
        @endif
    </div>
    @endif
</div>
@endsection


