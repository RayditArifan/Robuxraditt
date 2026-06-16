<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function dashboard(): View
    {
        $username = auth()->user()->name;

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

    public function katalog(): View
    {
        $produkKategori = Barang::aktif()
            ->where('stok', '>', 0)
            ->orderBy('kategori')
            ->latest()
            ->get()
            ->groupBy(function ($barang) {
                return $barang->kategori ?: 'Lainnya';
            });

        $semuaBarangs = Barang::aktif()
            ->where('stok', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.katalog', compact('produkKategori', 'semuaBarangs'));
    }

    public function detail(Barang $barang): View
    {
        abort_unless($barang->aktif && $barang->stok > 0, 404);

        return view('customer.detail', compact('barang'));
    }

    /**
     * Create a pending transaction.
     */
    public function checkout(Request $request, Barang $barang): RedirectResponse
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'username_roblox' => ['required', 'string', 'max:255'],
        ]);

        $jumlah = (int) $request->input('jumlah');
        $usernameRoblox = $request->input('username_roblox');

        if ($barang->stok < $jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk jumlah pembelian ini.');
        }

        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'barang_id' => $barang->id,
            'jumlah' => $jumlah,
            'total_harga' => $jumlah * $barang->harga,
            'username_roblox' => $usernameRoblox,
            'status' => 'belum_bayar',
        ]);

        return redirect()->route('customer.transaksi.show', $transaksi)
            ->with('success', 'Transaksi berhasil dibuat. Silakan konfirmasi dan masukkan bukti pembayaran.');
    }

    /**
     * Show a transaction invoice/receipt.
     */
    public function transaksiShow(Transaksi $transaksi): View
    {
        abort_unless($transaksi->user_id === auth()->id(), 403);

        return view('customer.transaksi.show', compact('transaksi'));
    }

    /**
     * Process/complete the transaction.
     */
    public function transaksiProses(Request $request, Transaksi $transaksi): RedirectResponse
    {
        abort_unless($transaksi->user_id === auth()->id(), 403);
        abort_unless($transaksi->status === 'belum_bayar', 400);

        $request->validate([
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('transaksi', 'public');
            $transaksi->bukti_pembayaran = $path;
            $transaksi->status = 'pending';
            $transaksi->save();

            return redirect()->route('customer.transaksi.show', $transaksi)
                ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    /**
     * List customer's transaction history.
     */
    public function transaksiList(): View
    {
        $transaksis = auth()->user()->transaksis()->with('barang')->latest()->paginate(10);
        return view('customer.transaksi.index', compact('transaksis'));
    }
}
