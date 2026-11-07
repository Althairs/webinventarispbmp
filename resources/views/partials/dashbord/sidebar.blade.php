<style>
/* Custom Scrollbar untuk Sidebar */
#sidebar .flex-1::-webkit-scrollbar {
    width: 6px;
}

#sidebar .flex-1::-webkit-scrollbar-track {
    background: rgba(59, 130, 246, 0.1);
    border-radius: 3px;
}

#sidebar .flex-1::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.3);
    border-radius: 3px;
    transition: background 0.2s ease;
}

#sidebar .flex-1::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.5);
}

/* Firefox scrollbar */
#sidebar .flex-1 {
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.3) rgba(59, 130, 246, 0.1);
}

/* Smooth scrolling */
#sidebar .flex-1 {
    scroll-behavior: smooth;
}

/* Hide scrollbar when not needed */
#sidebar .flex-1::-webkit-scrollbar {
    width: 6px;
}

/* Show scrollbar on hover */
#sidebar:hover .flex-1::-webkit-scrollbar {
    width: 6px;
}

/* Hamburger Menu Styles */
#sidebarToggle {
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

#sidebarToggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

/* Mobile responsive */
@media (max-width: 1024px) {
    #sidebar {
        z-index: 60;
    }

    #sidebarOverlay {
        z-index: 50;
    }
}

/* Sidebar base styles */
#sidebar {
    /* Transform will be controlled by JavaScript */
    transform: translateX(-100%);
}

/* Ensure sidebar is visible on desktop */
@media (min-width: 1024px) {
    #sidebar {
        transform: translateX(0) !important;
    }
}

/* Mobile sidebar behavior */
@media (max-width: 1023px) {
    #sidebar {
        transform: translateX(-100%);
    }

    #sidebar.show {
        transform: translateX(0);
    }
}
</style>

<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

<div id="sidebar" class="fixed left-0 top-0 w-64 h-full sidebar-gradient text-white z-50 transition-transform duration-300 flex flex-col" style="transform: translateX(-100%);">
    <div class="p-6 border-b border-blue-400 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <i class="fas fa-warehouse text-blue-600 text-lg"></i>
            </div>
            <div>
                <h2 class="font-bold text-lg">SIPANDU</h2>
                {{-- <h2 class="font-bold text-lg">Sistem Inventaris</h2> --}}
                {{-- <p class="text-blue-200 text-sm">BPMP Provinsi Gorontalo</p> --}}
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        <div class="p-4">
        <div class="mb-4">
            <div class="bg-blue-800 rounded-lg p-3 mb-2">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-circle text-lg"></i>
                    <div>
                        <p class="font-medium" id="userName">{{ Auth::user()->name }}</p>
                        <p class="text-blue-200 text-sm" id="userRole">{{ Str::upper(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <nav id="sidebarMenu">

            @if (Auth::user()->role === 'super_admin')
                <a href="{{ route('super_admin.dashboard') }}"
                    class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('super_admin.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                 <a href="{{ route('super_admin.users') }}"
                 class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('super_admin.users') ? 'bg-blue-700' : '' }}">
                 <i class="fas fa-users-cog"></i>
                    <span>User Management</span>
                </a>
            @endif

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <!-- Dropdown Manajemen Data Barang -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('manajemenBarang')" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('admin.persediaan') || Request::routeIs('admin.aset-tetap') ? 'bg-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-boxes"></i>
                            <span>Manajemen Data Barang</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="manajemenBarang-icon"></i>
                    </button>
                    <div id="manajemenBarang-dropdown" class="ml-4 mt-1 space-y-1 hidden">
                        <a href="{{ route('admin.persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-warehouse"></i>
                            <span>Data Persediaan</span>
                        </a>
                        <a href="{{ route('admin.aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.aset-tetap') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Data Aset Tetap</span>
                        </a>
                    </div>
                </div>

                <!-- Dropdown Transaksi Barang Masuk -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('transaksiMasuk')" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('admin.transaksi-masuk.*') ? 'bg-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-arrow-down"></i>
                            <span>Transaksi Barang Masuk</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="transaksiMasuk-icon"></i>
                    </button>
                    <div id="transaksiMasuk-dropdown" class="ml-4 mt-1 space-y-1 hidden">
                        <a href="{{ route('admin.transaksi-masuk.index') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-masuk.index') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard Transaksi</span>
                        </a>
                        <a href="{{ route('admin.transaksi-masuk.persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-masuk.persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-warehouse"></i>
                            <span>Transaksi Persediaan</span>
                        </a>
                        <a href="{{ route('admin.transaksi-masuk.aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-masuk.aset-tetap') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Transaksi Aset Tetap</span>
                        </a>
                        <a href="{{ route('admin.transaksi-masuk.histori-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-masuk.histori-persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Histori Persediaan</span>
                        </a>
                        <a href="{{ route('admin.transaksi-masuk.histori-aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-masuk.histori-aset-tetap') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Histori Aset Tetap</span>
                        </a>
                    </div>
                </div>

                <!-- Dropdown Transaksi Barang Keluar -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('transaksiKeluar')" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('admin.transaksi-keluar.*') ? 'bg-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-arrow-up"></i>
                            <span>Transaksi Barang Keluar</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="transaksiKeluar-icon"></i>
                    </button>
                    <div id="transaksiKeluar-dropdown" class="ml-4 mt-1 space-y-1 hidden">
                        <a href="{{ route('admin.transaksi-keluar.index') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-keluar.index') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard Transaksi</span>
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-keluar.persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-warehouse"></i>
                            <span>Transaksi Persediaan</span>
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-keluar.aset-tetap') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Transaksi Aset Tetap</span>
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.histori-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-keluar.histori-persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Histori Persediaan</span>
                        </a>
                        <a href="{{ route('admin.transaksi-keluar.histori-aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.transaksi-keluar.histori-aset-tetap') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-history"></i>
                            <span>Histori Aset Tetap</span>
                        </a>
                    </div>
                </div>

                <!-- Menu Mutasi Barang -->
                <div class="mb-2">
                    <a href="{{ route('admin.mutasi-barang.index') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('admin.mutasi-barang*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Mutasi Barang</span>
                    </a>
                </div>

                <!-- Dropdown Manajemen Pengajuan -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('manajemenPengajuan')" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('admin.peminjaman-barang') || Request::routeIs('admin.permintaan-persediaan') || Request::routeIs('admin.peminjaman-kendaraan') || Request::routeIs('admin.peminjaman-aset') ? 'bg-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Manajemen Pengajuan</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="manajemenPengajuan-icon"></i>
                    </button>
                    <div id="manajemenPengajuan-dropdown" class="ml-4 mt-1 space-y-1 hidden">
                        <a href="{{ route('admin.peminjaman-barang') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.peminjaman-barang') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Peminjaman Barang</span>
                        </a>
                        <a href="{{ route('admin.permintaan-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.permintaan-persediaan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-boxes"></i>
                            <span>Permintaan Persediaan</span>
                        </a>
                        <a href="{{ route('admin.peminjaman-kendaraan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.peminjaman-kendaraan') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-car"></i>
                            <span>Peminjaman Kendaraan</span>
                        </a>
                        <a href="{{ route('admin.peminjaman-aset') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('admin.peminjaman-aset') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Peminjaman Aset</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.laporan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('admin.laporan') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan & Analitik</span>
                </a>
            @endif

            @if (Auth::user()->role === 'tamu')
                <a href="{{ route('guest.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('guest.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('guest.peminjaman-aset') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('guest.peminjaman-aset') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Peminjaman Aset</span>
                </a>
                <a href="{{ route('guest.histori-peminjaman-aset') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('guest.histori-peminjaman-aset*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Histori Peminjaman Aset</span>
                </a>
                <a href="{{ route('guest.settings') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('guest.settings') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan Akun</span>
                </a>
            @endif

            @if (Auth::user()->role === 'user')
                <a href="{{ route('user.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('user.request.create') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.request.create') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Peminjaman Barang</span>
                </a>
                <a href="{{ route('user.permintaan-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.permintaan-persediaan') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Permintaan Persediaan</span>
                </a>
                <a href="{{ route('user.peminjaman-kendaraan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.peminjaman-kendaraan') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-car"></i>
                    <span>Peminjaman Kendaraan</span>
                </a>
                <a href="{{ route('user.histori-permintaan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.histori-permintaan*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Histori Permintaan</span>
                </a>
                <a href="{{ route('user.settings') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('user.settings') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan Akun</span>
                </a>
            @endif

            @if (Auth::user()->role === 'kasubag')
                <a href="{{ route('kasubag.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kasubag.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kasubag.peminjaman-barang') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kasubag.peminjaman-barang*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Peminjaman Barang</span>
                </a>
                <a href="{{ route('kasubag.permintaan-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kasubag.permintaan-persediaan*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Permintaan Persediaan</span>
                </a>
                <a href="{{ route('kasubag.peminjaman-kendaraan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kasubag.peminjaman-kendaraan*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-car"></i>
                    <span>Peminjaman Kendaraan</span>
                </a>
                <a href="{{ route('kasubag.peminjaman-aset') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kasubag.peminjaman-aset*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Peminjaman Aset</span>
                </a>
            @endif

            @if (Auth::user()->role === 'kepala_bpmp')
                <a href="{{ route('kepala_bpmp.dashboard') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kepala_bpmp.laporan-aset-tetap') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-aset-tetap') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Laporan Aset Tetap</span>
                </a>
                <a href="{{ route('kepala_bpmp.laporan-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-persediaan') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Laporan Persediaan</span>
                </a>
                <a href="{{ route('kepala_bpmp.laporan-transaksi-masuk') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-transaksi-masuk') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-arrow-down"></i>
                    <span>Laporan Transaksi Masuk</span>
                </a>
                <a href="{{ route('kepala_bpmp.laporan-transaksi-keluar') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-transaksi-keluar') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-arrow-up"></i>
                    <span>Laporan Transaksi Keluar</span>
                </a>
                <a href="{{ route('kepala_bpmp.laporan-mutasi') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-mutasi') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Laporan Mutasi</span>
                </a>

                <a href="{{ route('kepala_bpmp.laporan-rekapitulasi') }}" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors mb-2 {{ Request::routeIs('kepala_bpmp.laporan-rekapitulasi') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Laporan Rekapitulasi</span>
                </a>
                <!-- Dropdown Manajemen Pengajuan -->
                <div class="mb-2">
                    <button onclick="toggleDropdown('manajemenPengajuan')" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors {{ Request::routeIs('kepala_bpmp.peminjaman-barang*') || Request::routeIs('kepala_bpmp.permintaan-persediaan*') || Request::routeIs('kepala_bpmp.peminjaman-kendaraan*') || Request::routeIs('kepala_bpmp.peminjaman-aset*') ? 'bg-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Manajemen Pengajuan</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="manajemenPengajuan-icon"></i>
                    </button>
                    <div id="manajemenPengajuan-dropdown" class="ml-4 mt-1 space-y-1 hidden">
                        <a href="{{ route('kepala_bpmp.peminjaman-barang') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('kepala_bpmp.peminjaman-barang*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Peminjaman Barang</span>
                        </a>
                        <a href="{{ route('kepala_bpmp.permintaan-persediaan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('kepala_bpmp.permintaan-persediaan*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Permintaan Persediaan</span>
                        </a>
                        <a href="{{ route('kepala_bpmp.peminjaman-kendaraan') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('kepala_bpmp.peminjaman-kendaraan*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-car"></i>
                            <span>Peminjaman Kendaraan</span>
                        </a>
                        <a href="{{ route('kepala_bpmp.peminjaman-aset') }}" class="w-full flex items-center space-x-3 px-4 py-2 text-left hover:bg-blue-700 rounded-lg transition-colors text-sm {{ Request::routeIs('kepala_bpmp.peminjaman-aset*') ? 'bg-blue-700' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>Peminjaman Aset</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>
        </div>
    </div>

    <!-- Logout Button - Fixed at bottom -->
    <div class="p-4 border-t border-blue-400 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-left hover:bg-blue-700 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId + '-dropdown');
    const icon = document.getElementById(dropdownId + '-icon');

    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        dropdown.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Auto-open dropdown if current route is one of the submenu items
document.addEventListener('DOMContentLoaded', function() {
    const currentRoute = window.location.pathname;

    // Auto-open manajemen barang dropdown
    const manajemenBarangRoutes = [
        '/admin/persediaan',
        '/admin/aset-tetap'
    ];

    if (manajemenBarangRoutes.some(route => currentRoute.includes(route))) {
        const dropdown = document.getElementById('manajemenBarang-dropdown');
        const icon = document.getElementById('manajemenBarang-icon');
        dropdown.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }

    // Auto-open transaksi masuk dropdown
    const transaksiMasukRoutes = [
        '/admin/transaksi-masuk'
    ];

    if (transaksiMasukRoutes.some(route => currentRoute.includes(route))) {
        const dropdown = document.getElementById('transaksiMasuk-dropdown');
        const icon = document.getElementById('transaksiMasuk-icon');
        dropdown.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }

    // Auto-open transaksi keluar dropdown
    const transaksiKeluarRoutes = [
        '/admin/transaksi-keluar'
    ];

    if (transaksiKeluarRoutes.some(route => currentRoute.includes(route))) {
        const dropdown = document.getElementById('transaksiKeluar-dropdown');
        const icon = document.getElementById('transaksiKeluar-icon');
        dropdown.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }

    // Auto-open manajemen pengajuan dropdown
    const manajemenPengajuanRoutes = [
        '/admin/peminjaman-barang',
        '/admin/permintaan-persediaan',
        '/admin/peminjaman-kendaraan',
        '/admin/peminjaman-aset'
    ];

    if (manajemenPengajuanRoutes.some(route => currentRoute.includes(route))) {
        const dropdown = document.getElementById('manajemenPengajuan-dropdown');
        const icon = document.getElementById('manajemenPengajuan-icon');
        dropdown.classList.remove('hidden');
        icon.classList.add('rotate-180');
    }

    // Load notification count
    loadNotificationCount();
});

// Function to load notification count
function loadNotificationCount() {
    // Determine the correct API endpoint based on user role
    let apiUrl = '';
    @if(Auth::user()->role === 'tamu')
        apiUrl = '/guest/api/notifikasi/unread';
    @elseif(Auth::user()->role === 'user')
        apiUrl = '/user/api/notifikasi/unread';
    @endif

    if (apiUrl) {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const badgeTamu = document.getElementById('notification-badge-tamu');
                const badgeUser = document.getElementById('notification-badge-user');

                if (data.unread_count > 0) {
                    if (badgeTamu) {
                        badgeTamu.textContent = data.unread_count;
                        badgeTamu.classList.remove('hidden');
                    }
                    if (badgeUser) {
                        badgeUser.textContent = data.unread_count;
                        badgeUser.classList.remove('hidden');
                    }
                } else {
                    if (badgeTamu) {
                        badgeTamu.classList.add('hidden');
                    }
                    if (badgeUser) {
                        badgeUser.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
    }
}

// Refresh notification count every 30 seconds
setInterval(loadNotificationCount, 30000);
</script>
