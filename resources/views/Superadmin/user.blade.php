@extends('layouts.layout')
@section('title', 'Manajemen User')
@section('Manajemen User', 'active')

@section('content')
    <div class="mt-8 bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Daftar User</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Cari:</label>
                            <input type="text" 
                                   id="searchInput" 
                                   placeholder="Nama, email, atau username..." 
                                   value="{{ request('search') }}"
                                   class="border rounded-lg px-3 py-1 text-sm w-64"
                                   onkeypress="handleSearch(event)">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Tampilkan:</label>
                            <select onchange="changePerPage(this.value)" class="border rounded-lg px-3 py-1 text-sm">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="text-sm text-gray-600">per halaman</span>
                        </div>
                    </div>
                    <a href="{{ route('super_admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah User
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left">Instansi</th>
                        <th class="px-6 py-3 text-left">NIP</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Password</th>
                        <th class="px-6 py-3 text-left">Peran</th>
                        <th class="px-6 py-3 text-left">Permintaan Role</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr class="border-t">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $user->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $user->instansi }}</td>
                            <td class="px-6 py-4">{{ $user->nip }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <input type="password" 
                                           id="password_{{ $user->id }}" 
                                           value="{{ $user->password }}" 
                                           readonly 
                                           class="text-sm bg-gray-100 border-none px-2 py-1 rounded w-32">
                                    <button onclick="togglePassword({{ $user->id }})" 
                                            class="text-blue-600 hover:text-blue-800 text-sm p-1"
                                            title="Tampilkan/Sembunyikan Password">
                                        <i class="fas fa-eye" id="eye_{{ $user->id }}"></i>
                                    </button>
                                    <button onclick="copyPassword({{ $user->id }})" 
                                            class="text-green-600 hover:text-green-800 text-sm p-1"
                                            title="Copy Password">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                @if ($user->role === 'super_admin') bg-purple-100 text-purple-800
                                @elseif ($user->role === 'admin') bg-blue-100 text-blue-800
                                @elseif ($user->role === 'kasubag') bg-yellow-100 text-yellow-800
                                @elseif ($user->role === 'kepala_bpmp') bg-red-100 text-red-800
                                @elseif ($user->role === 'tamu') bg-orange-100 text-orange-800
                                @else bg-green-100 text-green-800 @endif">
                                    @if ($user->role === 'super_admin')
                                        Super Admin
                                    @elseif ($user->role === 'admin')
                                        Admin
                                    @elseif ($user->role === 'kasubag')
                                        Kasubag
                                    @elseif ($user->role === 'kepala_bpmp')
                                        Kepala BPMP
                                    @elseif ($user->role === 'tamu')
                                        Tamu
                                    @else
                                        Pengguna
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                @if ($user->role_request === 'super_admin') bg-purple-100 text-purple-800
                                @elseif ($user->role_request === 'admin') bg-blue-100 text-blue-800
                                @elseif ($user->role_request === 'kasubag') bg-yellow-100 text-yellow-800
                                @elseif ($user->role_request === 'kepala_bpmp') bg-red-100 text-red-800
                                @elseif ($user->role_request === 'tamu') bg-orange-100 text-orange-800
                                @else bg-green-100 text-green-800 @endif">
                                    @if ($user->role_request === 'super_admin')
                                        Super Admin
                                    @elseif ($user->role_request === 'admin')
                                        Admin
                                    @elseif ($user->role_request === 'kasubag')
                                        Kasubag
                                    @elseif ($user->role_request === 'kepala_bpmp')
                                        Kepala BPMP
                                    @elseif ($user->role_request === 'tamu')
                                        Tamu
                                    @else
                                        Pengguna
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-sm
                                @if ($user->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- Tombol untuk Menyetujui Permintaan Admin --}}
                                @if ($user->is_approved_by_admin == 0 && $user->role_request === 'admin')
                                    <button onclick="approveAdmin({{ $user->id }})" class="text-green-600 hover:text-green-800 mr-3">
                                        <i class="fas fa-check-circle"></i> Setujui Admin
                                    </button>
                                @endif

                                {{-- Tombol untuk Menyetujui Permintaan User/Pegawai --}}
                                @if ($user->is_approved_by_admin == 0 && $user->role_request === 'user')
                                    <button onclick="approveUser({{ $user->id }})" class="text-green-600 hover:text-green-800 mr-3">
                                        <i class="fas fa-check-circle"></i> Setujui User
                                    </button>
                                @endif

                                {{-- Tombol Aksi lainnya --}}
                                <button onclick="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="toggleUserStatus({{ $user->id }})" class="text-yellow-600 hover:text-yellow-800 mr-3">
                                    <i class="fas fa-toggle-on"></i>
                                </button>
                                <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan 
                    <span class="font-medium">{{ $users->firstItem() }}</span> 
                    sampai 
                    <span class="font-medium">{{ $users->lastItem() }}</span> 
                    dari 
                    <span class="font-medium">{{ $users->total() }}</span> 
                    hasil
                </div>
                
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            <i class="fas fa-chevron-left mr-1"></i>Sebelumnya
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="flex items-center space-x-1">
                        @php
                            $currentPage = $users->currentPage();
                            $lastPage = $users->lastPage();
                            
                            // Hitung range halaman yang akan ditampilkan (maksimal 5 halaman)
                            $maxPages = 5;
                            $halfMax = floor($maxPages / 2);
                            
                            // Tentukan halaman awal dan akhir
                            if ($lastPage <= $maxPages) {
                                // Jika total halaman <= 5, tampilkan semua
                                $startPage = 1;
                                $endPage = $lastPage;
                            } else {
                                // Jika total halaman > 5, hitung range
                                if ($currentPage <= $halfMax + 1) {
                                    // Jika current page di awal (1-3), tampilkan 1-5
                                    $startPage = 1;
                                    $endPage = $maxPages;
                                } elseif ($currentPage >= $lastPage - $halfMax) {
                                    // Jika current page di akhir, tampilkan 5 halaman terakhir
                                    $startPage = $lastPage - $maxPages + 1;
                                    $endPage = $lastPage;
                                } else {
                                    // Jika current page di tengah, tampilkan 2 halaman sebelum dan sesudah
                                    $startPage = $currentPage - $halfMax;
                                    $endPage = $currentPage + $halfMax;
                                }
                            }
                        @endphp

                        {{-- First page dengan ellipsis --}}
                        @if ($startPage > 1)
                            <a href="{{ $users->url(1) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                1
                            </a>
                            @if ($startPage > 2)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                        @endif

                        {{-- Page range (maksimal 5 halaman) --}}
                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $users->url($page) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last page dengan ellipsis --}}
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-sm text-gray-500">...</span>
                            @endif
                            <a href="{{ $users->url($lastPage) }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                                {{ $lastPage }}
                            </a>
                        @endif
                    </div>

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-sm text-purple-600 bg-white border border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Selanjutnya<i class="fas fa-chevron-right ml-1"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>

    // FUNGSI UNTUK TOGGLE PASSWORD VISIBILITY
    function togglePassword(userId) {
        const passwordInput = document.getElementById(`password_${userId}`);
        const eyeIcon = document.getElementById(`eye_${userId}`);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    // FUNGSI UNTUK COPY PASSWORD
    function copyPassword(userId) {
        const passwordInput = document.getElementById(`password_${userId}`);
        passwordInput.select();
        passwordInput.setSelectionRange(0, 99999); // Untuk mobile
        
        try {
            document.execCommand('copy');
            alert('Password berhasil dicopy ke clipboard!');
        } catch (err) {
            // Fallback untuk browser modern
            navigator.clipboard.writeText(passwordInput.value).then(function() {
                alert('Password berhasil dicopy ke clipboard!');
            }, function(err) {
                alert('Gagal copy password: ' + err);
            });
        }
    }

    // FUNGSI UNTUK MENGUBAH JUMLAH ITEM PER HALAMAN
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset ke halaman 1
        window.location.href = url.toString();
    }

    // FUNGSI UNTUK PENCARIAN
    function handleSearch(event) {
        if (event.key === 'Enter') {
            const searchValue = event.target.value;
            const url = new URL(window.location);
            
            if (searchValue.trim()) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            
            url.searchParams.delete('page'); // Reset ke halaman 1
            window.location.href = url.toString();
        }
    }

    
    // FUNGSI BARU: MENYETUJUI PERMINTAAN ADMIN
    async function approveAdmin(id) {
        if (!confirm('Apakah Anda yakin ingin menyetujui akun admin ini?')) {
            return;
        }

        try {
            const response = await fetch(`/super-admin/super_admin/users/${id}/approve-admin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menyetujui akun admin.');
            }

            alert(result.message);
            location.reload();

        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    // FUNGSI BARU: MENYETUJUI PERMINTAAN USER/PEGAWAI
    async function approveUser(id) {
        if (!confirm('Apakah Anda yakin ingin menyetujui akun user/pegawai ini?')) {
            return;
        }

        try {
            const response = await fetch(`/super-admin/super_admin/users/${id}/approve-user`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menyetujui akun user/pegawai.');
            }

            alert(result.message);
            location.reload();

        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    // FUNGSI UNTUK MENGEDIT, MENGHAPUS, DAN MENGUBAH STATUS (PERLU Diperbarui)
     // Fungsi ini akan membuka modal dengan data user yang sudah ada
    // Fungsi ini akan membuka modal dengan data user yang sudah ada
    async function editUser(id) {
    try {
        const response = await fetch(`/super-admin/super_admin/users/${id}/edit`);
        if (!response.ok) {
            throw new Error('Gagal mengambil data user.');
        }
        const user = await response.json();
        
        // Tampilkan modal edit dengan data user yang sudah diisi
        showModal('Edit User', `
            <form id="editUserForm" onsubmit="updateUser(event, ${id})" class="space-y-4">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="${user.nama_lengkap || ''}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Instansi</label>
                    <input type="text" name="instansi" value="${user.instansi || ''}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">NIP</label>
                    <input type="text" name="nip" value="${user.nip || ''}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" value="${user.email || ''}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Username</label>
                    <input type="text" name="username" value="${user.username || ''}" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select name="role" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="super_admin" ${user.role === 'super_admin' ? 'selected' : ''}>Super Admin</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        <option value="kasubag" ${user.role === 'kasubag' ? 'selected' : ''}>Kasubag</option>
                        <option value="kepala_bpmp" ${user.role === 'kepala_bpmp' ? 'selected' : ''}>Kepala BPMP</option>
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="tamu" ${user.role === 'tamu' ? 'selected' : ''}>Tamu</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Perbarui
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600">
                        Batal
                    </button>
                </div>
            </form>
        `);
    } catch (error) {
        alert('Error: ' + error.message);
    }

    }

    async function updateUser(event, id) {
        event.preventDefault();
        const form = document.getElementById('editUserForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(`/super-admin/super_admin/users/${id}/update`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok) {
                if (result.errors) {
                    // Tampilkan error validasi
                    let errorMessage = 'Validasi gagal:\n';
                    for (const field in result.errors) {
                        errorMessage += `- ${result.errors[field][0]}\n`;
                    }
                    alert(errorMessage);
                } else {
                    alert(result.message || 'Gagal memperbarui user.');
                }
                return;
            }

            alert(result.message);
            closeModal();
            location.reload();
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function toggleUserStatus(id) {
        if (!confirm('Apakah Anda yakin ingin mengubah status user ini?')) {
            return;
        }

        try {
            const response = await fetch(`/super-admin/super_admin/users/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal mengubah status user.');
            }

            const result = await response.json();
            alert(result.message);
            location.reload();
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    async function deleteUser(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus user ini? Aksi ini tidak dapat dibatalkan.')) {
            return;
        }

        try {
            const response = await fetch(`/super-admin/super_admin/users/${id}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal menghapus user.');
            }

            const result = await response.json();
            alert(result.message);
            location.reload();
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
</script>
@endpush
