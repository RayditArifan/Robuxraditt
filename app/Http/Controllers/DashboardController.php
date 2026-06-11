<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        $username = session('username', 'radit');

        $totalBarang = Barang::aktif()->count();
        $totalStok = Barang::aktif()->sum('stok');
        $totalNilai = Barang::aktif()->selectRaw('COALESCE(SUM(stok * harga), 0) as total')->value('total');
        $stokMenipis = Barang::aktif()->stokMenipis()->count();

        return view('dashboard', compact('username', 'totalBarang', 'totalStok', 'totalNilai', 'stokMenipis'));
    }
}
