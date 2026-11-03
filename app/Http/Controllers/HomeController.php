<?php

namespace App\Http\Controllers;

use App\Models\Persediaan;
use App\Models\AsetTetap;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan statistik inventaris
     */
    public function index()
    {
        // Ambil data statistik dari database
        $stats = [
            'totalPersediaan' => Persediaan::count(),
            'totalAsetTetap' => AsetTetap::count(),
            'totalItemTerdaftar' => Persediaan::count() + AsetTetap::count(),
        ];

        return view('template-dashbord', compact('stats'));
    }
}
