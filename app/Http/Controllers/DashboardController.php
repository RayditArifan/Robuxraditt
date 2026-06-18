<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     * Customer diarahkan ke dashboard customer, admin ke dashboard pengelolaan.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Jika customer, arahkan ke dashboard katalog customer
        if ($user->role === 'customer') {
            return app(CustomerController::class)->dashboard();
        }

        if ($user->role !== 'admin') {
            abort(403, 'Halaman dashboard pengelolaan hanya boleh diakses admin.');
        }

        $username = $user->name;

        // Ambil statistik barang aktif menggunakan clone query agar efisien
        $query = Barang::aktif();

        $totalBarang = (clone $query)->count();
        $totalStok   = (clone $query)->sum('stok');
        $totalNilai  = (clone $query)->selectRaw('COALESCE(SUM(stok * harga), 0) as total')->value('total');
        $stokMenipis = (clone $query)->stokMenipis()->count();

        return view('dashboard', compact('username', 'totalBarang', 'totalStok', 'totalNilai', 'stokMenipis'));
    }
}
