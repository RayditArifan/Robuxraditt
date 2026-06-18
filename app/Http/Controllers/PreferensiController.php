<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreferensiController extends Controller
{
    /**
     * Menampilkan halaman preferensi sistem (tema, ukuran font, dan informasi kontak toko).
     * Data tema dan font diambil dari session; data kontak dari file JSON di storage.
     */
    public function index(Request $request)
    {
        $username   = auth()->user()->name;
        $tema       = session('tema', 'light');
        $ukuranFont = session('ukuran_font', 'normal');

        // Baca informasi kontak toko dari file JSON (fallback ke nilai default jika belum ada)
        $contactFile = storage_path('app/contact.json');
        $contact = [
            'email'   => 'support@robuxradit.com',
            'phone'   => '+62 825-7511-930',
            'address' => 'Jakarta, Indonesia (Operasional Online)',
            'hours'   => 'Layanan pelanggan kami aktif setiap hari mulai pukul 08:00 hingga 22:00 WIB. Pertanyaan di luar jam kerja tetap akan kami tampung dan kami balas sesegera mungkin pada hari berikutnya.',
        ];

        if (file_exists($contactFile)) {
            $savedContact = json_decode(file_get_contents($contactFile), true);
            if (is_array($savedContact)) {
                $contact = array_merge($contact, $savedContact);
            }
        }

        return view('preferensi.index', compact('username', 'tema', 'ukuranFont', 'contact'));
    }

    /**
     * Menyimpan preferensi tampilan (tema & ukuran font) ke dalam session.
     * Dipanggil via AJAX, mengembalikan respons JSON.
     */
    public function simpan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tema'       => ['required', 'in:light,dark,system'],
            'ukuran_font' => ['required', 'in:kecil,normal,besar'],
        ]);

        // Simpan preferensi ke session agar berlaku selama sesi aktif
        session(['tema' => $validated['tema'], 'ukuran_font' => $validated['ukuran_font']]);

        return response()->json([
            'success'         => true,
            'message'         => 'Preferensi berhasil disimpan.',
            'preferensi_baru' => [
                'tema'       => $validated['tema'],
                'ukuran_font' => $validated['ukuran_font'],
            ],
        ]);
    }

    /**
     * Toggle tema antara light dan dark secara asinkronus.
     * Dipanggil saat user menekan tombol toggle tema di navbar.
     */
    public function toggleTema(Request $request): JsonResponse
    {
        $current  = session('tema', 'light');
        $newTema  = ($current === 'dark') ? 'light' : 'dark';
        session(['tema' => $newTema]);

        return response()->json(['success' => true, 'tema' => $newTema]);
    }

    /**
     * Memperbarui informasi kontak toko yang ditampilkan di halaman publik.
     * Data disimpan ke file JSON di storage/app/contact.json.
     */
    public function simpanKontak(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'hours'   => ['required', 'string', 'max:1000'],
        ]);

        // Tulis ulang file JSON kontak dengan data terbaru
        $contactFile = storage_path('app/contact.json');
        file_put_contents($contactFile, json_encode($validated, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Informasi kontak berhasil diperbarui.',
        ]);
    }
}
