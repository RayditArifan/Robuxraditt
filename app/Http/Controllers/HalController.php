<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HalController extends Controller
{
    private function getUsername(Request $request): ?string
    {
        $username = trim($request->query('username', ''));
        return $username !== '' ? $username : null;
    }

    public function showLogin()
    {
        return view('login');
    }

    public function processLogin(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = trim($request->input('password', ''));

        if ($username === '' || $password === '') {
            return redirect()->route('login')
                ->with('error', 'Username dan password wajib diisi.')
                ->withInput(['username' => $username]);
        }

        $validUsers = [
            'radit' => 'robux123',
            'admin' => 'admin123',
            'user' => 'user123',
        ];

        if (!isset($validUsers[$username]) || $validUsers[$username] !== $password) {
            return redirect()->route('login')
                ->with('error', 'Username atau password salah. Coba: radit / robux123')
                ->withInput(['username' => $username]);
        }

        return redirect()->route('dashboard', ['username' => $username]);
    }

    public function logout()
    {
        return redirect()->route('login')->with('info', 'Kamu berhasil keluar.');
    }

    public function showProfile(Request $request)
    {
        $username = $this->getUsername($request);

        if (!$username) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $profileData = [
            'username' => $username,
            'namaLengkap' => ucfirst($username) . ' (RobuxRadit)',
            'email' => $username . '@robuxradit.id',
            'role' => 'Admin Inventaris',
            'bergabung' => '10 April 2026',
            'keahlian' => ['Manajemen Stok', 'Transaksi Robux', 'Pengelolaan Gamepass'],
        ];

        return view('profile', compact('username', 'profileData'));
    }
}
