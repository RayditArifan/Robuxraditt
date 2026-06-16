<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreferensiController extends Controller
{
    public function index(Request $request)
    {
        $username = auth()->user()->name;
        $tema = session('tema', 'light');
        $ukuranFont = session('ukuran_font', 'normal');

        $contactFile = storage_path('app/contact.json');
        $contact = [
            'email' => 'support@robuxradit.com',
            'whatsapp' => '628257511930',
            'phone' => '+62 825-7511-930',
            'address' => 'Jakarta, Indonesia (Operasional Online)',
            'hours' => 'Layanan pelanggan kami aktif setiap hari mulai pukul 08:00 hingga 22:00 WIB. Pertanyaan di luar jam kerja tetap akan kami tampung dan kami balas sesegera mungkin pada hari berikutnya.'
        ];
        if (file_exists($contactFile)) {
            $savedContact = json_decode(file_get_contents($contactFile), true);
            if (is_array($savedContact)) {
                $contact = array_merge($contact, $savedContact);
            }
        }

        return view('preferensi.index', compact('username', 'tema', 'ukuranFont', 'contact'));
    }

    public function simpan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tema' => ['required', 'in:light,dark,system'],
            'ukuran_font' => ['required', 'in:kecil,normal,besar'],
        ]);

        session(['tema' => $validated['tema'], 'ukuran_font' => $validated['ukuran_font']]);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi berhasil disimpan.',
            'preferensi_baru' => [
                'tema' => $validated['tema'],
                'ukuran_font' => $validated['ukuran_font'],
            ],
        ]);
    }

    public function toggleTema(Request $request): JsonResponse
    {
        $current = session('tema', 'light');
        $newTema = ($current === 'dark') ? 'light' : 'dark';
        session(['tema' => $newTema]);

        return response()->json(['success' => true, 'tema' => $newTema]);
    }

    public function simpanKontak(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'hours' => ['required', 'string', 'max:1000'],
        ]);

        $contactFile = storage_path('app/contact.json');
        file_put_contents($contactFile, json_encode($validated, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Informasi kontak berhasil diperbarui.',
        ]);
    }
}
