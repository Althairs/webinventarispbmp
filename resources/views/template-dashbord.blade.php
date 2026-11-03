<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris BPMP Provinsi Gorontalo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .fade-in {
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .slide-in-left {
            animation: slideInLeft 1s ease-out;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-right {
            animation: slideInRight 1s ease-out;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-center {
            animation: slideInCenter 1s ease-out;
        }
        @keyframes slideInCenter {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">BPMP Provinsi Gorontalo</h1>
                        <p class="text-xs text-gray-600">SIPANDU</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="#statistik" class="text-gray-700 hover:text-blue-600 transition-colors">Statistik</a>
                    <a href="#about" class="text-gray-700 hover:text-blue-600 transition-colors">Tentang</a>
                    <a href="#fasilitas" class="text-gray-700 hover:text-blue-600 transition-colors">Fasilitas</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors">Kontak</a>
                    {{-- PERBAIKAN: Mengganti a href ke rute login --}}
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <div id="mobileMenu" class="md:hidden hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="#home" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#statistik" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Statistik</a>
                    <a href="#about" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Tentang</a>
                    <a href="#fasilitas" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Fasilitas</a>
                    <a href="#contact" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Kontak</a>
                    {{-- PERBAIKAN: Mengganti a href ke rute login --}}
                    <a href="{{ route('login') }}" class="w-full text-left px-3 py-2 text-blue-600 font-semibold">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden pt-16">
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-white opacity-10 rounded-full floating-animation"></div>
            <div class="absolute top-1/4 right-20 w-24 h-24 bg-white opacity-10 rounded-full floating-animation" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-20 left-1/4 w-20 h-20 bg-white opacity-10 rounded-full floating-animation" style="animation-delay: -4s;"></div>
            <div class="absolute bottom-1/4 right-1/3 w-28 h-28 bg-white opacity-10 rounded-full floating-animation" style="animation-delay: -1s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="fade-in">
                <div class="mb-8">
                    <i class="fas fa-warehouse text-white text-6xl mb-6 pulse-animation"></i>
                </div>
                <h1 class="text-5xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    Sistem Informasi Pendataan
                    <span class="text-yellow-300">BMN Terpadu</span>
                    (SIPANDU)
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Kelola inventaris barang BPMP Provinsi Gorontalo dengan mudah, efisien, dan profesional.
                    Solusi terpadu untuk manajemen aset pemerintah.
                </p>
                {{-- Tambahkan tombol login di bagian hero --}}
                <a href="{{ route('login') }}" class="bg-white text-blue-600 py-3 px-8 rounded-full font-semibold text-lg hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Mulai Login
                </a>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>

    <section id="statistik" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Statistik Inventaris BPMP</h2>
                <p class="text-xl text-gray-600">Data terkini pengelolaan aset dan inventaris BPMP Provinsi Gorontalo</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="slide-in-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2" id="counter1">{{ $stats['totalPersediaan'] }}</div>
                    <p class="text-gray-600 font-semibold">Total Persediaan</p>
                </div>
                <div class="slide-in-center" style="animation-delay: 0.2s;">
                    <div class="text-4xl font-bold text-green-600 mb-2" id="counter2">{{ $stats['totalAsetTetap'] }}</div>
                    <p class="text-gray-600 font-semibold">Total Aset Tetap</p>
                </div>
                <div class="slide-in-center" style="animation-delay: 0.4s;">
                    <div class="text-4xl font-bold text-red-600 mb-2" id="counter4">{{ $stats['totalItemTerdaftar'] }}</div>
                    <p class="text-gray-600 font-semibold">Item Terdaftar</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="slide-in-left">
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Tentang SIPANDU BPMP</h2>
                    <p class="text-lg text-gray-600 mb-8 max-w-4xl mx-auto">
                        Sistem Informasi Pencatatan BMN terpadu (SIPANDU) Provinsi Gorontalo dirancang khusus untuk mengelola aset dan inventaris
                        pemerintah daerah dengan standar akuntabilitas dan transparansi yang tinggi.
                    </p>
                    <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto mb-12">
                        <div class="flex flex-col items-center space-y-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <div class="text-center">
                                <h4 class="font-semibold text-gray-800 mb-2">Standar Pemerintahan</h4>
                                <p class="text-gray-600 text-sm">Sesuai dengan regulasi dan standar pemerintah daerah</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <div class="text-center">
                                <h4 class="font-semibold text-gray-800 mb-2">Transparansi Data</h4>
                                <p class="text-gray-600 text-sm">Laporan yang akurat dan dapat dipertanggungjawabkan</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <div class="text-center">
                                <h4 class="font-semibold text-gray-800 mb-2">Efisiensi Operasional</h4>
                                <p class="text-gray-600 text-sm">Mengoptimalkan pengelolaan Aset dan Persediaan Instansi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    {{-- Galeri Fasilitas --}}
    <section id="fasilitas" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fasilitas BPMP</h2>
                <p class="text-lg text-gray-600">Dokumentasi foto fasilitas yang tersedia di BPMP</p>
            </div>

            @php
                $allFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('fasilitas');
                $imageExtensions = ['jpg','jpeg','png','gif','webp','bmp'];
                // Daftar nama file (tanpa ekstensi) yang dikecualikan dari galeri
                $excludeBasenames = [
                    'poster tarif sewa fasilitas',
                ];
                $imageFiles = collect($allFiles)
                    ->filter(function ($path) use ($imageExtensions) {
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        return in_array($ext, $imageExtensions);
                    })
                    ->filter(function ($path) use ($excludeBasenames) {
                        $base = strtolower(trim(pathinfo($path, PATHINFO_FILENAME)));
                        // Kecualikan yang match tepat
                        if (in_array($base, $excludeBasenames)) return false;
                        // Kecualikan yang mengandung kata kunci umum "poster", "tarif", dan "fasilitas"
                        $normalized = preg_replace('/[-_]+/',' ', $base);
                        if (preg_match('/poster/i', $normalized) && preg_match('/tarif/i', $normalized) && preg_match('/fasilitas/i', $normalized)) {
                            return false;
                        }
                        return true;
                    })
                    ->values();
            @endphp

            @if($imageFiles->count() > 0)
                <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($imageFiles as $file)
                        @php
                            $url = asset('storage/' . $file);
                            $basename = pathinfo($file, PATHINFO_FILENAME);
                            // Bersihkan nama file menjadi judul yang rapi
                            $title = ucwords(trim(preg_replace('/[-_]+/',' ', $basename)));
                        @endphp
                        <div class="bg-white rounded-xl shadow hover:shadow-lg transition feature-card overflow-hidden">
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                                <img src="{{ $url }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="text-base font-semibold text-gray-800 line-clamp-2">{{ $title }}</h3>
                                <p class="text-xs text-gray-500 mt-1 break-all"></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-image text-3xl mb-3"></i>
                    <p>Belum ada foto fasilitas pada folder <span class="font-semibold">storage/app/public/fasilitas</span>.</p>
                </div>
            @endif

            <div class="text-center grid gap-6 mt-12">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white inline-flex items-center justify-center w-auto max-w-max mx-auto justify-self-center py-5 px-8 rounded-full font-semibold text-sm hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-70 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Pinjam Fasilitas
                </a>
            </div>
        </div>
    </section>

    <section id="contact" class="py-20 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Hubungi BPMP</h2>
                <p class="text-xl text-gray-300">Tim IT BPMP siap membantu Anda dalam penggunaan SIPANDU</p>
            </div>

            <div class=" grid lg:grid-cols-2 gap-12">
                <div class="slide-in-center">
                    <h3 class="text-2xl font-bold mb-6">Informasi Kontak</h3>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-600 p-3 rounded-lg">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Alamat</h4>
                                <p class="text-gray-300">Jalan Dr. Zainal Umar Sidiki, Tunggulo, Kecamatan Tilongkabila, Kabupaten Bone Bolango, Gorontalo 96571</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-600 p-3 rounded-lg">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Telepon</h4>
                                <p class="text-gray-300">(+62) 852-4270-0045</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-600 p-3 rounded-lg">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Email</h4>
                                <p class="text-gray-300">bpmp@gorontaloprov.go.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="slide-in-center" style="animation-delay: 0.3s;">
                    <h3 class="text-2xl font-bold mb-6">Jam Operasional</h3>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-600 p-3 rounded-lg">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Senin - Kamis</h4>
                                <p class="text-gray-300">08:00 - 16:00 WITA</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="bg-yellow-600 p-3 rounded-lg">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Jumat</h4>
                                <p class="text-gray-300">08:00 - 17:00 WITA</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-600 p-3 rounded-lg">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold">Sabtu & Minggu & Hari Libur</h4>
                                <p class="text-gray-300">Tutup</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-bold mb-6">Layanan IT Support</h3>
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 rounded-xl">
                            <div class="text-center">
                                <i class="fas fa-headset text-4xl mb-4 text-white"></i>
                                <h4 class="text-xl font-bold text-white mb-2">24/7 Support</h4>
                                <p class="text-blue-100 mb-4">Tim IT BPMP siap membantu Anda kapan saja</p>
                                <div class="flex justify-center space-x-4">
                                    <a href="mailto:it-support@gorontaloprov.go.id" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-envelope mr-2"></i>Email Support
                                    </a>
                                    <a href="tel:+62435821234" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                        <i class="fas fa-phone mr-2"></i>Call Center
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-black text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-blue-600 p-2 rounded-lg">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">BPMP Provinsi Gorontalo</h3>
                            <p class="text-xs text-gray-400">SIPANDU</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Solusi inventaris terdepan untuk bisnis modern dengan teknologi cloud terkini.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Fitur Utama</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Harga</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Demo</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutorial</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Sistem Inventaris BPMP Provinsi Gorontalo. Starsshinee.</p>
            </div>
        </div>
    </footer>

    <div id="featureModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-800"></h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalContent" class="text-gray-600"></div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                const target = document.querySelector(href);
                if (target) {
                    const navbarHeight = 80; // tinggi kira-kira nav fixed
                    const y = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        });

        // Counter animation
        function animateCounter(elementId, targetValue) {
            const element = document.getElementById(elementId);
            let currentValue = 0;
            const increment = targetValue / 100;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(currentValue).toLocaleString();
            }, 20);
        }

        // Intersection Observer for counter animation
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Ambil nilai dari data yang sudah ada di DOM
                    const counter1Value = parseInt(document.getElementById('counter1').textContent);
                    const counter2Value = parseInt(document.getElementById('counter2').textContent);
                    const counter4Value = parseInt(document.getElementById('counter4').textContent);

                    // Reset counter ke 0 untuk animasi
                    document.getElementById('counter1').textContent = '0';
                    document.getElementById('counter2').textContent = '0';
                    document.getElementById('counter4').textContent = '0';

                    // Animate ke nilai yang sebenarnya
                    animateCounter('counter1', counter1Value);
                    animateCounter('counter2', counter2Value);
                    animateCounter('counter4', counter4Value);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe the statistics section
        const statsSection = document.querySelector('#counter1').closest('section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Feature detail modal
        function showFeatureDetail(feature) {
            const modal = document.getElementById('featureModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('modalContent');

            const featureDetails = {
                reports: {
                    title: 'Laporan Real-time',
                    content: `
                        <p class="mb-4">Sistem pelaporan canggih yang memberikan insight mendalam tentang inventaris Anda:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>Dashboard interaktif dengan grafik dan chart</li>
                            <li>Laporan stok masuk dan keluar</li>
                            <li>Analisis trend penjualan</li>
                            <li>Prediksi kebutuhan stok</li>
                            <li>Export ke Excel, PDF, dan format lainnya</li>
                        </ul>
                        <p>Semua data diupdate secara real-time sehingga Anda selalu mendapat informasi terkini.</p>
                    `
                },
                scanner: {
                    title: 'Barcode Scanner',
                    content: `
                        <p class="mb-4">Teknologi scanning terdepan untuk efisiensi maksimal:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>Support berbagai format barcode (QR, Code128, EAN13, dll)</li>
                            <li>Scanning via kamera smartphone atau scanner khusus</li>
                            <li>Auto-complete data produk</li>
                            <li>Batch scanning untuk multiple items</li>
                            <li>Offline mode untuk area tanpa internet</li>
                        </ul>
                        <p>Tingkatkan akurasi data hingga 99.9% dan kurangi waktu input hingga 80%.</p>
                    `
                },
                notifications: {
                    title: 'Notifikasi Otomatis',
                    content: `
                        <p class="mb-4">Sistem notifikasi cerdas yang membantu Anda tetap update:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>Alert stok minimum dan maksimum</li>
                            <li>Peringatan expired date produk</li>
                            <li>Notifikasi transaksi penting</li>
                            <li>Reminder untuk stock opname</li>
                            <li>Custom alert sesuai kebutuhan bisnis</li>
                        </ul>
                        <p>Terima notifikasi via email, SMS, atau push notification di aplikasi mobile.</p>
                    `
                },
                multiuser: {
                    title: 'Multi-User Access',
                    content: `
                        <p class="mb-4">Sistem manajemen pengguna yang fleksibel dan aman:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>Role-based access control (Admin, Manager, Staff)</li>
                            <li>Permission granular untuk setiap fitur</li>
                            <li>Audit trail untuk tracking aktivitas user</li>
                            <li>Single Sign-On (SSO) integration</li>
                            <li>User activity monitoring</li>
                        </ul>
                        <p>Kelola tim Anda dengan aman sambil mempertahankan produktivitas maksimal.</p>
                    `
                },
                cloud: {
                    title: 'Cloud Storage',
                    content: `
                        <p class="mb-4">Infrastruktur cloud yang handal dan aman:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>99.9% uptime guarantee</li>
                            <li>Auto backup harian dengan multiple redundancy</li>
                            <li>Enkripsi data end-to-end</li>
                            <li>Scalable storage sesuai kebutuhan</li>
                            <li>Disaster recovery plan</li>
                        </ul>
                        <p>Data Anda aman tersimpan di server tier-1 dengan sertifikasi internasional.</p>
                    `
                },
                mobile: {
                    title: 'Mobile Friendly',
                    content: `
                        <p class="mb-4">Akses inventaris Anda dari mana saja, kapan saja:</p>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li>Progressive Web App (PWA) untuk semua device</li>
                            <li>Native mobile app untuk iOS dan Android</li>
                            <li>Offline sync capability</li>
                            <li>Touch-optimized interface</li>
                            <li>Real-time synchronization</li>
                        </ul>
                        <p>Interface yang responsif dan intuitif memastikan pengalaman pengguna yang optimal di semua perangkat.</p>
                    `
                }
            };

            const detail = featureDetails[feature];
            if (detail) {
                title.textContent = detail.title;
                content.innerHTML = detail.content;
                modal.classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('featureModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('featureModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Button functions
        function goToLogin() {
            alert('Fitur login akan segera tersedia! Terima kasih atas minat Anda.');
        }

        function sendMessage(event) {
            event.preventDefault();
            alert('Terima kasih! Pesan Anda telah terkirim. Tim kami akan menghubungi Anda dalam 1x24 jam.');
            event.target.reset();
        }

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('nav');
            if (window.scrollY > 100) {
                navbar.classList.add('bg-white', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-white', 'shadow-lg');
            }
        });
    </script>
</body>
</html>
