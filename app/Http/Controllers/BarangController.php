<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    /**
     * Menampilkan halaman pengelolaan toko beserta statistik dan data sesi kunjungan.
     * Hanya admin yang dapat melihat semua barang; pengelola hanya melihat barang miliknya.
     */
    public function index()
    {
        $username = auth()->user()->name;

        // Ambil data kunjungan dari session, lalu perbarui hitungannya
        $jumlahKunjungan = session('inventaris_kunjungan_jumlah', 0) + 1;
        $kunjunganPertama = session('inventaris_kunjungan_pertama');

        if (!$kunjunganPertama) {
            $kunjunganPertama = now()->format('d M Y, H:i:s');
        }

        $kunjunganTerakhir = now()->format('d M Y, H:i:s');

        session([
            'inventaris_kunjungan_jumlah' => $jumlahKunjungan,
            'inventaris_kunjungan_pertama' => $kunjunganPertama,
            'inventaris_kunjungan_terakhir' => $kunjunganTerakhir,
        ]);

        // Admin melihat semua barang, pengelola hanya barang miliknya
        $isAdmin = auth()->user()->role === 'admin';
        $query = $isAdmin ? Barang::query() : Barang::where('user_id', auth()->id());

        // Hitung statistik ringkasan dari query yang sama
        $barangs    = (clone $query)->latest()->paginate(10);
        $totalBarang = (clone $query)->count();
        $totalStok   = (clone $query)->sum('stok');
        $totalNilai  = (clone $query)->selectRaw('COALESCE(SUM(stok * harga), 0) as total')->value('total');
        $stokMenipis = (clone $query)->where('stok', '<', 5)->count();

        return view('pengelolaan', compact(
            'username',
            'barangs',
            'totalBarang',
            'totalStok',
            'totalNilai',
            'stokMenipis',
            'jumlahKunjungan',
            'kunjunganPertama',
            'kunjunganTerakhir'
        ));
    }

    /**
     * Pencarian barang secara asinkronus (AJAX) berdasarkan keyword.
     * Mengembalikan JSON berisi daftar barang yang cocok.
     */
    public function searchAjax(Request $request)
    {
        $keyword = trim((string) $request->get('q', ''));

        $isAdmin = auth()->user()->role === 'admin';
        $barangs = ($isAdmin ? Barang::query() : Barang::where('user_id', auth()->id()))
            ->when($keyword !== '', function ($query) use ($keyword) {
                // Filter berdasarkan kode, nama, atau kategori barang
                $query->where(function ($query) use ($keyword) {
                    $query->where('kode', 'like', "%{$keyword}%")
                        ->orWhere('nama', 'like', "%{$keyword}%")
                        ->orWhere('kategori', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->limit(10)
            ->get(['id', 'kode', 'nama', 'kategori', 'stok', 'harga', 'tanggal_masuk', 'aktif']);

        return response()->json([
            'success' => true,
            'keyword' => $keyword,
            'total'   => $barangs->count(),
            'data'    => $barangs,
        ]);
    }

    /**
     * Menyimpan barang baru melalui AJAX POST tanpa reload halaman.
     * CSRF token wajib disertakan di header X-CSRF-TOKEN.
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'kode'         => ['required', 'max:20', 'unique:barangs,kode'],
            'nama'         => ['required', 'min:3', 'max:100'],
            'kategori'     => ['required', Rule::in(['Gamepass', 'Voucher', 'Private Server'])],
            'stok'         => ['required', 'integer', 'min:0'],
            'harga'        => ['required', 'numeric', 'min:1000'],
            'tanggal_masuk' => ['required', 'date'],
        ]);

        // Kaitkan barang dengan user yang sedang login
        $validated['user_id'] = auth()->id();
        $validated['aktif']   = true;

        $barang = Barang::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan tanpa reload halaman.',
            'data'    => $barang,
        ], 201);
    }

    /**
     * Mereset data sesi kunjungan halaman pengelolaan toko melalui AJAX.
     */
    public function resetKunjungan(Request $request)
    {
        $request->session()->forget([
            'inventaris_kunjungan_jumlah',
            'inventaris_kunjungan_pertama',
            'inventaris_kunjungan_terakhir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hitungan kunjungan inventaris berhasil direset.',
        ]);
    }

    /**
     * Menampilkan formulir tambah barang baru.
     */
    public function create()
    {
        $username = auth()->user()->name;

        return view('barang.create', compact('username'));
    }

    /**
     * Menyimpan barang baru ke database beserta upload foto (opsional).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'         => ['required', 'max:20', 'unique:barangs,kode'],
            'nama'         => ['required', 'min:3', 'max:100'],
            'kategori'     => ['required', Rule::in(['Gamepass', 'Voucher', 'Private Server'])],
            'stok'         => ['required', 'integer', 'min:0'],
            'harga'        => ['required', 'numeric', 'min:1000'],
            'tanggal_masuk' => ['required', 'date'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['aktif']   = true;

        // Simpan foto ke disk public jika ada file yang diupload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('barang', 'public');
        }

        Barang::create($validated);

        return redirect()
            ->route('pengelolaan')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu barang. Hanya pemilik atau admin yang boleh melihat.
     */
    public function show(Barang $barang)
    {
        $this->pastikanPemilik($barang);

        $username = auth()->user()->name;

        return view('barang.show', compact('username', 'barang'));
    }

    /**
     * Menampilkan formulir edit barang. Hanya pemilik atau admin yang boleh mengakses.
     */
    public function edit(Barang $barang)
    {
        $this->pastikanPemilik($barang);

        $username = auth()->user()->name;

        return view('barang.edit', compact('username', 'barang'));
    }

    /**
     * Memperbarui data barang di database, termasuk mengganti foto jika ada upload baru.
     */
    public function update(Request $request, Barang $barang)
    {
        $this->pastikanPemilik($barang);

        $validated = $request->validate([
            'kode'         => ['required', 'max:20', Rule::unique('barangs', 'kode')->ignore($barang->id)],
            'nama'         => ['required', 'min:3', 'max:100'],
            'kategori'     => ['required', Rule::in(['Gamepass', 'Voucher', 'Private Server'])],
            'stok'         => ['required', 'integer', 'min:0'],
            'harga'        => ['required', 'numeric', 'min:1000'],
            'tanggal_masuk' => ['required', 'date'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama sebelum menyimpan foto baru
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }

            $validated['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang->update($validated);

        return redirect()
            ->route('pengelolaan')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang dari database beserta file foto jika ada.
     */
    public function destroy(Barang $barang)
    {
        $this->pastikanPemilik($barang);

        // Hapus file foto dari storage sebelum menghapus record
        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }

        $barang->delete();

        return redirect()
            ->route('pengelolaan')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memastikan bahwa user yang mengakses adalah pemilik barang atau admin.
     * Melempar 403 jika tidak punya hak akses.
     */
    private function pastikanPemilik(Barang $barang): void
    {
        $isAdmin = auth()->user()->role === 'admin';

        if (!$isAdmin && $barang->user_id !== auth()->id()) {
            abort(403, 'Kamu tidak punya akses ke data barang ini.');
        }
    }

    /**
     * Menampilkan daftar semua transaksi untuk halaman admin.
     * Memuat relasi user dan barang untuk efisiensi query (eager loading).
     */
    public function transaksiList()
    {
        $username   = auth()->user()->name;
        $transaksis = Transaksi::with(['user', 'barang'])->latest()->paginate(10);
        return view('admin.transaksi.index', compact('username', 'transaksis'));
    }

    /**
     * Menampilkan detail satu transaksi beserta data user dan barang terkait.
     */
    public function transaksiShow(Transaksi $transaksi)
    {
        $username = auth()->user()->name;
        $transaksi->load(['user', 'barang']);
        return view('admin.transaksi.show', compact('username', 'transaksi'));
    }

    /**
     * Menyetujui transaksi yang masih berstatus 'pending'.
     * Stok barang akan dikurangi sesuai jumlah yang dibeli.
     */
    public function transaksiSetujui(Transaksi $transaksi)
    {
        abort_unless($transaksi->status === 'pending', 400);

        $barang = $transaksi->barang;

        // Cek ketersediaan stok sebelum menyetujui
        if ($barang->stok < $transaksi->jumlah) {
            return redirect()->back()->with('error', 'Gagal menyetujui transaksi. Stok barang tidak mencukupi.');
        }

        // Kurangi stok barang
        $barang->stok -= $transaksi->jumlah;
        $barang->save();

        // Ubah status transaksi ke 'proses'
        $transaksi->status = 'proses';
        $transaksi->save();

        return redirect()->back()->with('success', 'Pembayaran transaksi disetujui. Status berubah menjadi Diproses.');
    }

    /**
     * Menyelesaikan transaksi yang sedang dalam status 'proses'.
     */
    public function transaksiSelesaikan(Transaksi $transaksi)
    {
        abort_unless($transaksi->status === 'proses', 400);

        $transaksi->status = 'selesai';
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi berhasil diselesaikan.');
    }

    /**
     * Menolak transaksi dan mengembalikan status ke 'belum_bayar'.
     * File bukti pembayaran dihapus dari storage.
     */
    public function transaksiTolak(Transaksi $transaksi)
    {
        abort_unless($transaksi->status === 'pending', 400);

        // Hapus bukti pembayaran yang sudah diupload sebelumnya
        if ($transaksi->bukti_pembayaran) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            $transaksi->bukti_pembayaran = null;
        }

        // Kembalikan status ke belum_bayar agar customer bisa upload ulang
        $transaksi->status = 'belum_bayar';
        $transaksi->save();

        return redirect()->back()->with('success', 'Transaksi ditolak dan status dikembalikan ke Menunggu Pembayaran.');
    }
}
