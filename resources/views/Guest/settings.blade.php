@extends('layouts.layout')

@section('title', 'Pengaturan Akun - Tamu')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Akun</h1>
                        <p class="mt-1 text-sm text-gray-600">Kelola informasi akun dan tanda tangan digital Anda</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-user-tag mr-2"></i>Role: Tamu
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
                        <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil Anda</p>
                    </div>

                    <form id="profileForm" class="p-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Nama Lengkap
                            </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                   value="{{ $user->nama_lengkap }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                        </div>

                        <!-- Instansi -->
                        <div>
                            <label for="instansi" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-2 text-blue-600"></i>Instansi Asal
                            </label>
                            <input type="text" id="instansi" name="instansi"
                                   value="{{ $user->instansi }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                        </div>


                        <!-- NIP -->
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2 text-blue-600"></i>NIP
                            </label>
                            <input type="text" id="nip" name="nip"
                                   value="{{ $user->nip }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Username
                            </label>
                            <input type="text" id="username" name="username"
                                   value="{{ $user->username }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>Alamat Email
                            </label>
                            <input type="email" id="email" name="email"
                                   value="{{ $user->email }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                        </div>

                        {{-- <!-- Password Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>Ubah Password
                            </h3>

                            <!-- Current Password -->
                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Saat Ini
                                </label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-12">
                                    <button type="button" onclick="togglePassword('current_password')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-4">
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" id="new_password" name="new_password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-12">
                                    <button type="button" onclick="togglePassword('new_password')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-12">
                                    <button type="button" onclick="togglePassword('new_password_confirmation')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                    </button>
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
                        <div class="flex justify-end pt-6">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Informasi Akun</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-tag mr-1"></i>Tamu
                                </span>
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>{{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>

                        <!-- Approval Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Persetujuan</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_approved_by_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $user->is_approved_by_admin ? 'Diterima' : 'Menunggu Persetujuan' }}
                                </span>
                            </p>
                        </div>

                        <!-- Join Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1"></i>{{ $user->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <!-- Last Updated -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1"></i>{{ $user->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Aksi Cepat</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('guest.dashboard') }}"
                           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-tachometer-alt mr-2"></i>Kembali ke Dashboard
                        </a>
                        <a href="{{ route('guest.peminjaman-aset') }}"
                           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-building mr-2"></i>Peminjaman Aset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white border-l-4 p-4 shadow-lg rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i id="notification-icon" class="text-lg"></i>
            </div>
            <div class="ml-3">
                <p id="notification-message" class="text-sm font-medium"></p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="hideNotification()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const icon = document.getElementById('notification-icon');
    const messageEl = document.getElementById('notification-message');

    // Set message
    messageEl.textContent = message;

    // Set icon and colors based on type
    if (type === 'success') {
        icon.className = 'fas fa-check-circle text-green-500';
        notification.className = 'fixed top-4 right-4 z-50 bg-white border-l-4 border-green-500 p-4 shadow-lg rounded-lg';
    } else if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle text-red-500';
        notification.className = 'fixed top-4 right-4 z-50 bg-white border-l-4 border-red-500 p-4 shadow-lg rounded-lg';
    } else if (type === 'warning') {
        icon.className = 'fas fa-exclamation-triangle text-yellow-500';
        notification.className = 'fixed top-4 right-4 z-50 bg-white border-l-4 border-yellow-500 p-4 shadow-lg rounded-lg';
    }

    // Show notification
    notification.classList.remove('hidden');

    // Auto hide after 5 seconds
    setTimeout(() => {
        hideNotification();
    }, 5000);
}

// Hide notification
function hideNotification() {
    document.getElementById('notification').classList.add('hidden');
}

// Form submission
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    // Disable submit button
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

    try {
        const response = await fetch('{{ route("guest.update-profile") }}', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        const data = await response.json();

        if (response.ok) {
            showNotification(data.message || 'Profil berhasil diperbarui!', 'success');

            // Reload page after 2 seconds to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            if (data.errors) {
                // Show validation errors
                let errorMessage = 'Terjadi kesalahan validasi:\n';
                for (const field in data.errors) {
                    errorMessage += `• ${data.errors[field][0]}\n`;
                }
                showNotification(errorMessage, 'error');
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat memperbarui profil.', 'error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
    } finally {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

// Delete signature
async function deleteSignature() {
    if (!confirm('Apakah Anda yakin ingin menghapus tanda tangan?')) {
        return;
    }

    try {
        const response = await fetch('{{ route("guest.delete-signature") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });

        const data = await response.json();

        if (response.ok) {
            showNotification(data.message || 'Tanda tangan berhasil dihapus!', 'success');

            // Reload page after 2 seconds to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showNotification(data.message || 'Terjadi kesalahan saat menghapus tanda tangan.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
    }
}

// Show notification from session if exists
@if(session('notification'))
    showNotification('{{ session('notification.message') }}', '{{ session('notification.type') }}');
@endif
</script>
@endsection
