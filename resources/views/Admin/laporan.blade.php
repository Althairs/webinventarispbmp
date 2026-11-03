@extends('layouts.layout')
@section('title', 'Laporan & Analitik')
@section('Laporan & Analitik', 'active')

@section('content')
<!-- Statistik Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Persediaan</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['totalPersediaan'] }}</p>
            </div>
            <i class="fas fa-boxes text-blue-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Aset Tetap</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['totalAsetTetap'] }}</p>
            </div>
            <i class="fas fa-building text-green-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Transaksi</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['totalTransaksi'] }}</p>
            </div>
            <i class="fas fa-exchange-alt text-purple-500 text-2xl"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg card-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Mutasi</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['totalMutasi'] }}</p>
            </div>
            <i class="fas fa-arrows-alt text-orange-500 text-2xl"></i>
        </div>
    </div>
</div>

<!-- Grafik Analitik -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
            Grafik Transaksi Per Bulan
        </h3>
        <canvas id="transactionChart" width="400" height="200"></canvas>
    </div>
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-chart-pie text-green-500 mr-2"></i>
            Distribusi Kategori Barang
        </h3>
        <canvas id="categoryChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- Grafik Mutasi dan Stok -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-chart-line text-purple-500 mr-2"></i>
            Grafik Mutasi Barang
        </h3>
        <canvas id="mutationChart" width="400" height="200"></canvas>
    </div>
    <div class="bg-white rounded-lg card-shadow p-6">
        <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-chart-area text-orange-500 mr-2"></i>
            Trend Stok Barang
        </h3>
        <canvas id="stockChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- Laporan Detail -->
<div class="bg-white rounded-lg card-shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold flex items-center">
            <i class="fas fa-file-alt text-gray-500 mr-2"></i>
            Laporan Detail Sistem
        </h3>
        <div class="flex space-x-2 items-end">
            <div>
                <label class="text-sm text-gray-600">Pilih Bulan</label>
                <input type="month" id="filterMonth" value="{{ request('month', now()->format('Y-m')) }}" class="form-input px-3 py-2 border rounded" />
            </div>
            <button onclick="downloadPengajuanExcel()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-download mr-2"></i>Download Laporan Pengajuan (Excel)
            </button>
            <button onclick="downloadMutationReport()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-download mr-2"></i>Download Laporan Mutasi
            </button>
            <button onclick="downloadMasukExcel()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-file-download mr-2"></i>Download Transaksi Masuk (Excel)
            </button>
            <button onclick="downloadKeluarExcel()" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-file-download mr-2"></i>Download Transaksi Keluar (Excel)
            </button>
        </div>
    </div>

    <!-- Statistik Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-medium text-blue-800">Transaksi Masuk</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['transaksiMasuk'] }}</p>
            <p class="text-sm text-blue-600">Hari ini: {{ $stats['transaksiMasukHariIni'] }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <h4 class="font-medium text-red-800">Transaksi Keluar</h4>
            <p class="text-2xl font-bold text-red-600">{{ $stats['transaksiKeluar'] }}</p>
            <p class="text-sm text-red-600">Hari ini: {{ $stats['transaksiKeluarHariIni'] }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <h4 class="font-medium text-purple-800">Total Mutasi</h4>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['totalMutasi'] }}</p>
            <p class="text-sm text-purple-600">Hari ini: {{ $stats['mutasiHariIni'] }}</p>
        </div>
        <div class="bg-orange-50 p-4 rounded-lg">
            <h4 class="font-medium text-orange-800">Total Pengajuan</h4>
            <p class="text-2xl font-bold text-orange-600">{{ $stats['totalPengajuan'] }}</p>
            <p class="text-sm text-orange-600">Menunggu: {{ $stats['MenungguRequests'] }}</p>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Kategori</th>
                    <th class="px-6 py-3 text-left">Total Barang</th>
                    <th class="px-6 py-3 text-left">Stok Tersedia</th>
                    <th class="px-6 py-3 text-left">Nilai Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorySummary as $category => $data)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $category }}</td>
                        <td class="px-6 py-4">{{ $data['total'] }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">{{ $data['stok'] }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($data['nilai'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($data['stok'] > 10)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Stok Cukup</span>
                            @elseif($data['stok'] > 0)
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Stok Menipis</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Stok Habis</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="bg-white rounded-lg card-shadow p-6">
    <h3 class="text-lg font-bold mb-4 flex items-center">
        <i class="fas fa-history text-blue-500 mr-2"></i>
        Aktivitas Sistem Terbaru
    </h3>
    <div class="space-y-4">
        @if($recentActivities->count() > 0)
            @foreach($recentActivities as $activity)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas {{ $activity['icon'] }} text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-sm">{{ $activity['description'] }}</p>
                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-8">
                <i class="fas fa-chart-line text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Belum ada aktivitas sistem</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari server (akan diisi oleh controller)
    const chartData = @json($chartData);

    // Fungsi untuk merender semua grafik
    function renderCharts() {
        // 1. Grafik Transaksi Per Bulan
        const transactionCtx = document.getElementById('transactionChart').getContext('2d');
        new Chart(transactionCtx, {
            type: 'bar',
            data: {
                labels: chartData.monthlyLabels || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Transaksi Masuk',
                    data: chartData.monthlyMasuk || [12, 15, 8, 20, 18, 25],
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2
                }, {
                    label: 'Transaksi Keluar',
                    data: chartData.monthlyKeluar || [8, 12, 6, 15, 10, 18],
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // 2. Grafik Distribusi Kategori
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.categoryLabels || ['Elektronik', 'Furniture', 'Kendaraan', 'Lainnya'],
                datasets: [{
                    data: chartData.categoryData || [30, 25, 20, 25],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // 3. Grafik Mutasi Barang
        const mutationCtx = document.getElementById('mutationChart').getContext('2d');
        new Chart(mutationCtx, {
            type: 'line',
            data: {
                labels: chartData.mutationLabels || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Mutasi Barang',
                    data: chartData.mutationData || [5, 8, 12, 6, 9, 15],
                    borderColor: 'rgba(168, 85, 247, 1)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // 4. Grafik Trend Stok
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        new Chart(stockCtx, {
            type: 'line',
            data: {
                labels: chartData.stockLabels || ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Stok Persediaan',
                    data: chartData.stockPersediaan || [100, 120, 110, 130, 125, 140],
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Stok Aset Tetap',
                    data: chartData.stockAsetTetap || [50, 55, 60, 58, 62, 65],
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
    }

    // Panggil fungsi renderCharts saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        renderCharts();
    });

    // Fungsi untuk download laporan
    function downloadMonthlyReport() {
        showLoading('Mengunduh Laporan Bulanan...');
        window.location.href = "{{ route('admin.laporan.download_bulanan') }}";
    }

    function downloadTransactionReport() {
        showLoading('Mengunduh Laporan Pengajuan...');
        const url = buildQueryWithMonth("{{ route('admin.laporan.pengajuan.export') }}");
        window.location.href = url;
    }

    function downloadMutationReport() {
        showLoading('Mengunduh Laporan Mutasi...');
        const url = buildQueryWithMonth("{{ route('admin.laporan.mutasi.export') }}");
        window.location.href = url;
    }

    // helper: tambah parameter month ke URL
    function buildQueryWithMonth(baseUrl) {
        const m = document.getElementById('filterMonth')?.value;
        if (!m) return baseUrl;
        const url = new URL(baseUrl, window.location.origin);
        url.searchParams.set('month', m);
        return url.toString();
    }

    function downloadPengajuanExcel() { downloadTransactionReport(); }
    function downloadMasukExcel() { window.location.href = buildQueryWithMonth("{{ route('admin.laporan.masuk.export') }}"); }
    function downloadKeluarExcel() { window.location.href = buildQueryWithMonth("{{ route('admin.laporan.keluar.export') }}"); }

    // Fungsi loading
    function showLoading(message) {
        // Buat overlay loading
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-700">${message}</p>
            </div>
        `;
        document.body.appendChild(overlay);

        // Hapus overlay setelah 2 detik
        setTimeout(() => {
            document.body.removeChild(overlay);
        }, 2000);
    }
</script>
@endpush
