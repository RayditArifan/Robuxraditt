<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    /**
     * Menampilkan halaman dashboard customer dengan produk terbaru yang tersedia.
     */
    public function dashboard(): View
    {
        $username = auth()->user()->name;

        // Tampilkan 4 produk aktif terbaru yang masih ada stoknya
        $produkTerbaru = Barang::aktif()
            ->where('stok', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        return view('customer.dashboard', compact(
            'username',
            'produkTerbaru'
        ));
    }

    /**
     * Menampilkan halaman katalog produk yang bisa diakses publik (tanpa login).
     * Produk dikelompokkan berdasarkan kategori dan juga ditampilkan dengan paginasi.
     */
    public function katalog(): View
    {
        // Kelompokkan produk per kategori untuk tampilan tab/filter
        $produkKategori = Barang::aktif()
            ->where('stok', '>', 0)
            ->orderBy('kategori')
            ->latest()
            ->get()
            ->groupBy(function ($barang) {
                return $barang->kategori ?: 'Lainnya';
            });

        // Daftar semua produk dengan paginasi
        $semuaBarangs = Barang::aktif()
            ->where('stok', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.katalog', compact('produkKategori', 'semuaBarangs'));
    }

    /**
     * Menampilkan halaman detail satu produk.
     * Produk yang tidak aktif atau stoknya habis akan mengembalikan 404.
     */
    public function detail(Barang $barang): View
    {
        abort_unless($barang->aktif && $barang->stok > 0, 404);

        return view('customer.detail', compact('barang'));
    }

    /**
     * Membuat transaksi baru (checkout) untuk barang yang dipilih customer.
     * Stok belum dikurangi; pengurangan terjadi saat admin menyetujui.
     */
    public function checkout(Request $request, Barang $barang): RedirectResponse
    {
        $request->validate([
            'jumlah'          => ['required', 'integer', 'min:1'],
            'username_roblox' => ['required', 'string', 'max:255'],
        ]);

        $jumlah         = (int) $request->input('jumlah');
        $usernameRoblox = $request->input('username_roblox');

        // Cek ketersediaan stok sebelum membuat transaksi
        if ($barang->stok < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk jumlah pembelian ini.');
        }

        $transaksi = Transaksi::create([
            'user_id'         => auth()->id(),
            'barang_id'       => $barang->id,
            'jumlah'          => $jumlah,
            'total_harga'     => $jumlah * $barang->harga,
            'username_roblox' => $usernameRoblox,
            'status'          => 'belum_bayar', // Status awal: menunggu pembayaran
        ]);

        return redirect()->route('customer.transaksi.show', $transaksi)
            ->with('success', 'Transaksi berhasil dibuat. Silakan konfirmasi dan masukkan bukti pembayaran.');
    }

    /**
     * Menampilkan detail transaksi milik customer yang sedang login.
     * Customer tidak dapat melihat transaksi milik orang lain (403).
     */
    public function transaksiShow(Transaksi $transaksi): View
    {
        abort_unless($transaksi->user_id === auth()->id(), 403);

        return view('customer.transaksi.show', compact('transaksi'));
    }

    /**
     * Memproses upload bukti pembayaran dari customer.
     * Status transaksi berubah dari 'belum_bayar' menjadi 'pending' (menunggu verifikasi admin).
     */
    public function transaksiProses(Request $request, Transaksi $transaksi): RedirectResponse
    {
        abort_unless($transaksi->user_id === auth()->id(), 403);
        abort_unless($transaksi->status === 'belum_bayar', 400);

        $request->validate([
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Simpan file bukti ke disk public, lalu ubah status ke pending
            $path = $request->file('bukti_pembayaran')->store('transaksi', 'public');
            $transaksi->bukti_pembayaran = $path;
            $transaksi->status           = 'pending';
            $transaksi->save();

            return redirect()->route('customer.transaksi.show', $transaksi)
                ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    /**
     * Menampilkan riwayat transaksi milik customer yang sedang login.
     */
    public function transaksiList(): View
    {
        $transaksis = auth()->user()->transaksis()->with('barang')->latest()->paginate(10);
        return view('customer.transaksi.index', compact('transaksis'));
    }
}
