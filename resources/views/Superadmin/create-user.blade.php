@extends('layouts.layout')
@section('title', 'Tambah User Baru')
@section('Manajemen User', 'active')

@section('content')
    <div class="bg-white rounded-lg card-shadow">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Tambah User Baru</h3>
                <a href="{{ route('super_admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('super_admin.users.add') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('nama_lengkap') border-red-500 @enderror" 
                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="instansi" class="block text-sm font-medium mb-2">Instansi <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('instansi') border-red-500 @enderror" 
                               id="instansi" name="instansi" value="{{ old('instansi') }}" required>
                        @error('instansi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nip" class="block text-sm font-medium mb-2">NIP <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('nip') border-red-500 @enderror" 
                               id="nip" name="nip" value="{{ old('nip') }}" required>
                        @error('nip')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Username <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2 @error('username') border-red-500 @enderror" 
                               id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full border rounded-lg px-3 py-2 @error('email') border-red-500 @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" class="w-full border rounded-lg px-3 py-2 @error('password') border-red-500 @enderror" 
                               id="password" name="password" required minlength="8">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="role" class="block text-sm font-medium mb-2">Role <span class="text-red-500">*</span></label>
                        <select class="w-full border rounded-lg px-3 py-2 @error('role') border-red-500 @enderror" 
                                id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasubag" {{ old('role') == 'kasubag' ? 'selected' : '' }}>Kasubag</option>
                            <option value="kepala_bpmp" {{ old('role') == 'kepala_bpmp' ? 'selected' : '' }}>Kepala BPMP</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pegawai</option>
                            <option value="tamu" {{ old('role') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2">Status</label>
                        <select class="w-full border rounded-lg px-3 py-2 @error('status') border-red-500 @enderror" 
                                id="status" name="status">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

