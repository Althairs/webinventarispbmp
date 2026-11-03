<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Sistem Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <i class="fas fa-cog text-2xl text-indigo-600 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Akun</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Selamat datang, <strong>{{ Auth::user()->nama_lengkap }}</strong></span>
                    <a href="{{ route('user.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Notification -->
        @if(session('notification'))
            <div class="mb-6 p-4 rounded-lg {{ session('notification.type') == 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                <div class="flex items-center">
                    <i class="fas {{ session('notification.type') == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-2"></i>
                    {{ session('notification.message') }}
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-200 rounded-lg">
                <h3 class="font-bold mb-2">Terjadi kesalahan:</h3>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Profile Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl card-shadow p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-user-circle text-3xl text-indigo-600 mr-3"></i>
                        <h2 class="text-xl font-bold text-gray-900">Informasi Profil</h2>
                    </div>

                    <form action="{{ route('user.update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2"></i>Nama Lengkap
                                </label>
                                <input
                                    type="text"
                                    id="nama_lengkap"
                                    name="nama_lengkap"
                                    value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    required
                                >
                            </div>

                            <!-- Instansi -->
                            <div>
                                <label for="instansi" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-building mr-2"></i>Instansi
                                </label>
                                <input
                                    type="text"
                                    id="instansi"
                                    name="instansi"
                                    value="{{ old('instansi', Auth::user()->instansi) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    required
                                >
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-briefcase mr-2"></i>Jabatan
                                </label>
                                <input
                                    type="text"
                                    id="jabatan"
                                    name="jabatan"
                                    value="{{ old('jabatan', Auth::user()->jabatan) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    placeholder="Contoh: Staff, Kepala Seksi, Koordinator"
                                >
                            </div>

                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2"></i>NIP
                                </label>
                                <input
                                    type="text"
                                    id="nip"
                                    name="nip"
                                    value="{{ old('nip', Auth::user()->nip) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    required
                                >
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-at mr-2"></i>Username
                                </label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username', Auth::user()->username) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    required
                                >
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2"></i>Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                    required
                                >
                            </div>
                        </div>

                        {{-- <!-- Password Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-lock mr-2"></i>Ubah Password
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password Lama
                                    </label>
                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                        placeholder="Kosongkan jika tidak ingin mengubah password"
                                    >
                                </div>
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password Baru
                                    </label>
                                    <input
                                        type="password"
                                        id="new_password"
                                        name="new_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                        placeholder="Minimal 8 karakter"
                                    >
                                </div>
                                <div class="md:col-span-2">
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input
                                        type="password"
                                        id="new_password_confirmation"
                                        name="new_password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-colors"
                                        placeholder="Ulangi password baru"
                                    >
                                </div>
                            </div>
                        </div> --}}

                        <!-- Signature Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-signature mr-2 text-blue-600"></i>Tanda Tangan Digital
                            </h3>

                            <!-- Current Signature -->
                            @if($user->signature_image_path)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Saat Ini</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                        <img src="{{ Storage::url($user->signature_image_path) }}"
                                             alt="Tanda Tangan"
                                             class="mx-auto max-h-32 max-w-full object-contain">
                                        <p class="mt-2 text-sm text-gray-600">Tanda tangan yang sedang digunakan</p>
                                        <button type="button" onclick="deleteSignature()"
                                                class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Hapus Tanda Tangan
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Saat Ini</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                        <i class="fas fa-signature text-gray-400 text-4xl mb-2"></i>
                                        <p class="text-gray-600">Belum ada tanda tangan</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New Signature -->
                            <div>
                                <label for="signature_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Tanda Tangan Baru
                                </label>
                                <input type="file" id="signature_image" name="signature_image"
                                       accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="mt-2 text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.
                                </p>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end">
                            <button
                                type="submit"
                                class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors font-semibold"
                            >
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl card-shadow p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-info-circle text-3xl text-blue-600 mr-3"></i>
                        <h2 class="text-xl font-bold text-gray-900">Status Akun</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Role -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Role</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ Auth::user()->role == 'super_admin' ? 'bg-purple-100 text-purple-800' :
                                   (Auth::user()->role == 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ Auth::user()->role == 'super_admin' ? 'Super Admin' :
                                   (Auth::user()->role == 'admin' ? 'Admin' : 'Pengguna') }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Status</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ Auth::user()->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ Auth::user()->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>

                        <!-- Approval Status -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Persetujuan</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ Auth::user()->is_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ Auth::user()->is_approved_by_admin ? 'Diterima' : 'Menunggu' }}
                            </span>
                        </div>

                        <!-- Created At -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Bergabung</span>
                            <span class="text-sm text-gray-800">
                                {{ Auth::user()->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <!-- Last Updated -->
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600">Terakhir Diperbarui</span>
                            <span class="text-sm text-gray-800">
                                {{ Auth::user()->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('user.dashboard') }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-home mr-2"></i>Dashboard
                            </a>
                            <!-- <a href="{{ route('logout') }}"
                               class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                               onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('new_password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmation = this.value;

            if (password !== confirmation) {
                this.setCustomValidity('Password tidak sesuai');
            } else {
                this.setCustomValidity('');
            }
        });

        // Show/hide password functionality
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }
    </script>
</body>
</html>
