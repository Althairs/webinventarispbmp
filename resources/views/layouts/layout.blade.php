<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Inventaris - BPMP Provinsi Gorontalo | @yield('title')</title>
    
    {{-- Tailwind CSS & Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    {{-- Custom Styles --}}
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
        }
        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563, #6b7280);
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
    </style>

    {{-- Script untuk Chart.js (jika diperlukan oleh halaman) --}}
    {{-- Chart.js hanya di-load jika diperlukan, bukan di semua halaman --}}
    @if(isset($loadChartJs) && $loadChartJs)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif

</head>
<body class="bg-gray-50">
    
    {{-- Memuat sidebar dari file terpisah --}}
    @include('partials.dashbord.sidebar')
    {{-- Script yang error dihapus --}}
    @stack('scripts') 

    {{-- Konten Utama --}}
    <div class="lg:ml-64">
        {{-- Header --}}
        <header class="bg-white shadow-sm border-b">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 id="pageTitle" class="text-2xl font-bold text-gray-800">@yield('title')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    {{-- Notifikasi (Bisa diimplementasikan di sini atau di sidebar) --}}
                    <div id="notifications" class="flex items-center space-x-2">
                        {{-- Logika notifikasi bisa dimasukkan di sini --}}
                    </div>
                </div>
            </div>
        </header>

        {{-- Area Konten Spesifik Halaman --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    {{-- Modal (Bisa ditempatkan di layout utama agar tersedia di semua halaman) --}}
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 max-h-96 overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalTitle" class="text-lg font-bold">Form</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent">
                {{-- Konten form modal akan diisi via JavaScript --}}
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
        {{-- Logika JavaScript yang bersifat global atau digunakan oleh semua halaman --}}

        // Setup CSRF token for AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
        
        // Setup fetch with CSRF token
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            options.headers = {
                ...options.headers,
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            };
            return originalFetch(url, options);
        };

        // Sidebar Toggle - Simple Version
        function initSidebar() {
            console.log('Initializing sidebar...');
            
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            console.log('Sidebar elements found:', { 
                sidebarToggle: !!sidebarToggle, 
                sidebar: !!sidebar, 
                overlay: !!overlay 
            });
            
            // Test if elements exist
            if (!sidebarToggle) {
                console.error('sidebarToggle element not found!');
                return;
            }
            if (!sidebar) {
                console.error('sidebar element not found!');
                return;
            }
            
            if (sidebarToggle && sidebar) {
                // Add click event with more debugging
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('=== HAMBURGER CLICKED ===');
                    console.log('Event:', e);
                    console.log('Target:', e.target);
                    
                    // Check current transform
                    const currentTransform = sidebar.style.transform;
                    console.log('Current transform:', currentTransform);
                    console.log('Sidebar element:', sidebar);
                    
                    if (currentTransform === 'translateX(-100%)' || currentTransform === '') {
                        // Show sidebar
                        sidebar.style.transform = 'translateX(0)';
                        if (overlay) {
                            overlay.classList.remove('hidden');
                        }
                        console.log('✅ Sidebar shown - transform set to translateX(0)');
                    } else {
                        // Hide sidebar
                        sidebar.style.transform = 'translateX(-100%)';
                        if (overlay) {
                            overlay.classList.add('hidden');
                        }
                        console.log('✅ Sidebar hidden - transform set to translateX(-100%)');
                    }
                    
                    // Verify the change
                    setTimeout(() => {
                        console.log('Final transform:', sidebar.style.transform);
                    }, 100);
                });
                
                // Also add mousedown and mouseup for debugging
                sidebarToggle.addEventListener('mousedown', function() {
                    console.log('Hamburger mousedown');
                });
                
                sidebarToggle.addEventListener('mouseup', function() {
                    console.log('Hamburger mouseup');
                });
                
                // Close sidebar when clicking overlay
                if (overlay) {
                    overlay.addEventListener('click', function() {
                        sidebar.style.transform = 'translateX(-100%)';
                        overlay.classList.add('hidden');
                        console.log('Sidebar closed via overlay');
                    });
                }
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.style.transform = 'translateX(-100%)';
                        if (overlay) {
                            overlay.classList.add('hidden');
                        }
                    }
                });
            } else {
                console.error('Required elements not found!');
            }
        }
        
        // Multiple initialization methods
        document.addEventListener('DOMContentLoaded', initSidebar);
        window.addEventListener('load', initSidebar);
        
        // Fallback - try again after a short delay
        setTimeout(initSidebar, 100);
        setTimeout(initSidebar, 500);
        
        // Force sidebar to be visible on desktop
        function ensureSidebarVisible() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && window.innerWidth >= 1024) {
                sidebar.style.transform = 'translateX(0)';
                console.log('Sidebar forced to be visible on desktop');
            }
        }
        
        // Run on load and resize
        window.addEventListener('load', ensureSidebarVisible);
        window.addEventListener('resize', ensureSidebarVisible);
        setTimeout(ensureSidebarVisible, 100);

        function logout() {
            if (confirm('Yakin ingin logout?')) {
                // Tambahkan logika logout di sini, misalnya redirect ke route logout
                window.location.href = "{{ route('logout') }}";
            }
        }
        
        // Logika modal
        function showModal(title, content) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

    </script>
    @stack('scripts') {{-- Tempat untuk script tambahan dari halaman turunan --}}
</body>
</html>
