<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $credentials = ['username' => $request->username, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Periksa status persetujuan untuk role 'admin'
            if ($user->role === 'admin' && !$user->is_approved_by_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('notification', [
                    'message' => 'Akun admin Anda belum Diterima. Silakan tunggu konfirmasi dari Super Admin.',
                    'type' => 'error',
                ]);
            }

            if ($user->status === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('notification', [
                    'message' => 'Akun anda telah dinonaktifkan.',
                    'type' => 'error',
                ]);
            }

            if ($user->is_suspend) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('notification', [
                    'message' => 'Akun anda telah disuspend, silahkan hubungi admin.',
                    'type' => 'error',
                ]);
            }

            // Cek status approval untuk user dan admin
            if (($user->role === 'user' || $user->role === 'admin') && !$user->is_approved_by_admin) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('notification', [
                    'message' => 'Akun Anda masih menunggu persetujuan dari Super Admin. Silakan hubungi Super Admin untuk aktivasi akun.',
                    'type' => 'error',
                ]);
            }

            $request->session()->regenerate();
            
            // Normalize role value (trim whitespace)
            $userRole = trim($user->role ?? '');
            
            switch ($userRole) {
                case 'super_admin':
                    return redirect()->route('super_admin.dashboard')->with('notification', [
                        'message' => 'Login berhasil, selamat datang kembali!',
                        'type' => 'success',
                    ]);
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('notification', [
                        'message' => 'Login berhasil, selamat datang kembali!',
                        'type' => 'success',
                    ]);
                case 'kasubag':
                    return redirect()->route('kasubag.dashboard')->with('notification', [
                        'message' => 'Login berhasil, selamat datang kembali!',
                        'type' => 'success',
                    ]);
                 case 'user':
                     return redirect()->route('user.dashboard')->with('notification', [
                         'message' => 'Login berhasil, selamat datang kembali!',
                         'type' => 'success',
                     ]);
                 case 'tamu':
                     return redirect()->route('guest.dashboard')->with('notification', [
                         'message' => 'Login berhasil, selamat datang kembali!',
                         'type' => 'success',
                     ]);
                 case 'kepala_bpmp':
                     return redirect()->route('kepala_bpmp.dashboard')->with('notification', [
                         'message' => 'Login berhasil, selamat datang kembali!',
                         'type' => 'success',
                     ]);
                default:
                    Auth::logout();
                    return redirect('/login')->with('notification', [
                        'message' => 'Peran pengguna tidak valid.',
                        'type' => 'error',
                    ]);
            }
        }
            
        return back()->with('notification', [
            'message' => 'Username atau password salah.',
            'type' => 'error',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('notification', [
            'message' => 'Anda telah berhasil keluar.',
            'type' => 'success',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            // Log request data untuk debugging
            \Log::info('Registration attempt started', [
                'all_data' => $request->all(),
                'method' => $request->method(),
                'url' => $request->url(),
                'headers' => $request->headers->all()
            ]);
            
            // 1. Validasi semua input
            \Log::info('Starting validation');
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'nip' => 'required|string|max:255|unique:users,nip',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'role_request' => 'required|in:user,admin,tamu',
            ]);
            \Log::info('Validation passed');

        // 2. Menentukan status persetujuan berdasarkan role yang diminta
        // Jika pengguna meminta role 'user' atau 'pegawai', is_approved_by_admin false (menunggu persetujuan).
        // Jika pengguna meminta role 'tamu', is_approved_by_admin langsung true.
        // Jika pengguna meminta role 'admin', is_approved_by_admin false (menunggu persetujuan).
        $isApproved = ($request->role_request === 'tamu');
        \Log::info('Approval status determined', ['isApproved' => $isApproved, 'role_request' => $request->role_request]);

        // Hitung nomor urut berikutnya
        $nextNomorUrut = User::max('nomor_urut') + 1;
        \Log::info('Next nomor urut calculated', ['nextNomorUrut' => $nextNomorUrut]);
        
        // 3. Buat pengguna baru dengan logika yang telah disederhanakan
        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'instansi' => $request->instansi,
            'nip' => $request->nip,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role_request, // Gunakan role yang diminta
            'role_request' => $request->role_request, // Simpan role yang diminta
            'status' => 'active',
            'is_suspend' => false,
            'is_approved_by_admin' => $isApproved, // Terapkan status persetujuan
            'nomor_urut' => $nextNomorUrut,
        ];
        
        \Log::info('About to create user', ['userData' => $userData]);
        
        $user = User::create($userData);
        
        \Log::info('User created successfully', ['user_id' => $user->id, 'user_role' => $user->role]);

        // 4. Periksa role yang diminta dan berikan respons yang sesuai
        if ($request->role_request === 'admin') {
            return redirect()->route('login')->with('notification', [
                'message' => 'Permintaan akun admin Anda berhasil diajukan. Silakan tunggu persetujuan dari Super Admin.',
                'type' => 'success',
            ]);
        }

        if ($request->role_request === 'user') {
            return redirect()->route('login')->with('notification', [
                'message' => 'Registrasi berhasil! Akun Anda sedang menunggu persetujuan dari Super Admin. Silakan login setelah akun Anda disetujui.',
                'type' => 'success',
            ]);
        }

        // Jika pendaftaran sebagai 'tamu', lakukan login otomatis
        if ($request->role_request === 'tamu') {
            Auth::login($user);
            return redirect()->route('guest.dashboard')->with('notification', [
                'message' => 'Registrasi berhasil! Selamat datang sebagai Tamu di Sistem Inventaris BPMP Provinsi Gorontalo.',
                'type' => 'success',
            ]);
        }
        } catch (\Illuminate\Validation\ValidationException $e) {
             \Log::error('Registration validation failed', $e->errors());
             return redirect()->back()->withErrors($e->errors())->withInput();
         } catch (\Exception $e) {
             \Log::error('Registration failed', [
                 'message' => $e->getMessage(),
                 'file' => $e->getFile(),
                 'line' => $e->getLine(),
                 'trace' => $e->getTraceAsString()
             ]);
             return redirect()->back()->with('notification', [
                 'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
                 'type' => 'error',
             ])->withInput();
         }
     }
 }
