<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PreferensiController extends Controller
{
    public function index(Request $request)
    {
        $username = auth()->user()->name;
        $tema = $request->cookie('tema', 'light');
        $ukuranFont = $request->cookie('ukuran_font', 'normal');

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

        $cookieTema = Cookie::make('tema', $validated['tema'], 60 * 24 * 30, null, null, null, false);
        $cookieFont = Cookie::make('ukuran_font', $validated['ukuran_font'], 60 * 24 * 30, null, null, null, false);

        return response()->json([
            'success' => true,
            'message' => 'Preferensi berhasil disimpan.',
            'cookie_sebelumnya' => [
                'tema' => $request->cookie('tema', 'belum ada'),
                'ukuran_font' => $request->cookie('ukuran_font', 'belum ada'),
            ],
            'preferensi_baru' => [
                'tema' => $validated['tema'],
                'ukuran_font' => $validated['ukuran_font'],
            ],
        ])->withCookie($cookieTema)
          ->withCookie($cookieFont);
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
