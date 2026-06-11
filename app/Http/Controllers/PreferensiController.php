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

        return view('preferensi.index', compact('username', 'tema', 'ukuranFont'));
    }

    public function simpan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tema' => ['required', 'in:light,dark,system'],
            'ukuran_font' => ['required', 'in:kecil,normal,besar'],
        ]);

        $cookieTema = Cookie::make('tema', $validated['tema'], 60 * 24 * 30);
        $cookieFont = Cookie::make('ukuran_font', $validated['ukuran_font'], 60 * 24 * 30);

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
}
