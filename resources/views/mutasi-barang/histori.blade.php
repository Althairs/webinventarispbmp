@extends('layouts.layout')
@section('title', 'Histori Mutasi Barang')
@section('Mutasi Barang', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Histori Mutasi Barang</h3>
                <a href="{{ route('admin.mutasi-barang.index') }}" class="btn-secondary text-white px-4 py-2 rounded-lg hover:opacity-90">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="p-6">
            @if ($mutasi->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-exchange-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 mb-4">Belum ada data histori mutasi barang.</p>
                    <a href="{{ route('admin.mutasi-barang.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Mutasi
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Tanggal Mutasi</th>
                                <th class="px-6 py-3 text-left">Kode Barang</th>
                                <th class="px-6 py-3 text-left">Nama Barang</th>
                                <th class="px-6 py-3 text-left">NUP</th>
                                <th class="px-6 py-3 text-left">Lokasi Awal</th>
                                <th class="px-6 py-3 text-left">Lokasi Akhir</th>
                                <th class="px-6 py-3 text-left">Keterangan</th>
                                <th class="px-6 py-3 text-left">Waktu Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasi as $index => $item)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $mutasi->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $item->tanggal_mutasi->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $item->kode_barang }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $item->nup }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_awal }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $item->lokasi_akhir }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $item->keterangan ? Str::limit($item->keterangan, 30) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $mutasi->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
