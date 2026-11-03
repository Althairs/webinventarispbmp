<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Menggunakan facade Log

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        try {
            $users = User::all();
            $stats = [
                'totalUsers' => $users->count(),
                'activeUsers' => $users->where('status', 'active')->count(),
                'adminUsers' => $users->where('role', 'admin')->count(),
                'regularUsers' => $users->where('role', 'user')->count(),
            ];
            // Perbaikan: Ganti dengan array dummy karena model Log tidak ada di kode
            $systemLogs = collect([
                (object)['user' => 'Super Admin', 'action' => 'Login', 'timestamp' => now(), 'status' => 'Success'],
                (object)['user' => 'Admin', 'action' => 'Add Item', 'timestamp' => now()->subMinutes(5), 'status' => 'Success'],
                (object)['user' => 'User', 'action' => 'Request Item', 'timestamp' => now()->subMinutes(10), 'status' => 'Success'],
            ]);
            
            $userCounts = [
                'super_admin' => User::where('role', 'super_admin')->count(),
                'admin' => User::where('role', 'admin')->count(),
                'user' => User::where('role', 'user')->count(),
                'tamu' => User::where('role', 'tamu')->count(),
            ];

            return view('Superadmin.index', compact('users', 'stats', 'systemLogs', 'userCounts'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data dashboard.', 'error' => $e->getMessage()], 500);
        }
    }

    public function createUser()
    {
        return view('Superadmin.create-user');
    }

    public function users(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');
            
            $query = User::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }
            
            $users = $query->paginate($perPage)->withQueryString();
            return view('Superadmin.user', compact('users'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data users.', 'error' => $e->getMessage()], 500);
        }
    }

    public function addUser(Request $request) 
    { 
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'nip' => 'required|string|max:255|unique:users,nip',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:super_admin,admin,kasubag,kepala_bpmp,user,tamu',
            ]);

            // Hitung nomor urut berikutnya
            $nextNomorUrut = User::max('nomor_urut') + 1;
            
            User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'instansi' => $request->instansi,
                'nip' => $request->nip,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_approved_by_admin' => true, // Akun yang dibuat oleh super admin langsung Diterima
                'nomor_urut' => $nextNomorUrut,
            ]);

            return redirect()->route('super_admin.users')->with('success', 'User berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())->withInput();
        }
    }

    // Metode untuk menampilkan data user ke form edit
    public function editUserForm(User $user)
    {
        try {
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data user.', 'error' => $e->getMessage()], 500);
        }
    }

    // Metode untuk memperbarui data user
    public function updateUser(Request $request, User $user)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'instansi' => 'required|string|max:255',
                'nip' => 'required|string|max:255|unique:users,nip,' . $user->id,
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|in:super_admin,admin,kasubag,kepala_bpmp,user,tamu',
            ]);

            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'instansi' => $request->instansi,
                'nip' => $request->nip,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'role_request' => $request->role, // Update role_request juga
            ]);

            return response()->json(['message' => 'User berhasil diperbarui.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui user.', 'error' => $e->getMessage()], 500);
        }
    }



    // Metode untuk menghapus user
    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            
            // Atur ulang nomor urut setelah penghapusan
            $this->reorderNomorUrut();
            
            return response()->json(['message' => 'User berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus user.', 'error' => $e->getMessage()], 500);
        }
    }
    
    // Method untuk mengatur ulang nomor urut
    private function reorderNomorUrut()
    {
        $users = User::orderBy('id')->get();
        foreach ($users as $index => $user) {
            $user->update(['nomor_urut' => $index + 1]);
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            return response()->json(['message' => "Status user berhasil diubah menjadi {$newStatus}."], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengubah status.', 'error' => $e->getMessage()], 500);
        }
    }

    public function showMenungguAdminRequests()
    {
        try {
            $MenungguAdmins = User::where('role', 'admin')
                                 ->where('is_approved_by_admin', false)
                                 ->get();

            return view('super_admin.Menunggu_admins', compact('MenungguAdmins'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data Menunggu admin.', 'error' => $e->getMessage()], 500);
        }
    }

    public function approveAdminRequest(User $user)
    {
        try {
            // Pastikan hanya user dengan role 'admin' yang belum Diterima yang bisa diproses
            if ($user->role === 'admin' && !$user->is_approved_by_admin) {
                $user->is_approved_by_admin = true;
                $user->save();
                
                // Kirim notifikasi ke user (opsional)
                // Notification::send($user, new AdminApprovedNotification());

                return back()->with('notification', [
                    'message' => 'Permintaan pengguna ' . $user->nama_lengkap . ' berhasil diterima sebagai Admin.',
                    'type' => 'success',
                ]);
            }

            return back()->with('notification', [
                'message' => 'Permintaan tidak valid.',
                'type' => 'error',
            ]);
        } catch (\Exception $e) {
            return back()->with('notification', [
                'message' => 'Gagal menyetujui permintaan admin.',
                'type' => 'error',
            ]);
        }
    }

    // Method untuk AJAX request approve admin
    public function approveAdmin(User $user)
    {
        try {
            // Pastikan hanya user dengan role_request 'admin' yang belum Diterima yang bisa diproses
            if ($user->role_request === 'admin' && !$user->is_approved_by_admin) {
                $user->update([
                    'role' => 'admin',
                    'is_approved_by_admin' => true
                ]);
                
                return response()->json([
                    'message' => 'Permintaan pengguna ' . $user->nama_lengkap . ' berhasil diterima sebagai Admin.',
                    'success' => true
                ], 200);
            }

            return response()->json([
                'message' => 'Permintaan tidak valid.',
                'success' => false
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyetujui permintaan admin.',
                'success' => false
            ], 500);
        }
    }

    // Method untuk AJAX request approve user/pegawai
    public function approveUser(User $user)
    {
        try {
            // Pastikan hanya user dengan role_request 'user' yang belum Diterima yang bisa diproses
            if ($user->role_request === 'user' && !$user->is_approved_by_admin) {
                $user->update([
                    'role' => 'user',
                    'is_approved_by_admin' => true
                ]);
                
                return response()->json([
                    'message' => 'Permintaan pengguna ' . $user->nama_lengkap . ' berhasil diterima sebagai User/Pegawai.',
                    'success' => true
                ], 200);
            }

            return response()->json([
                'message' => 'Permintaan tidak valid atau sudah diproses.',
                'success' => false
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyetujui permintaan admin.',
                'error' => $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
