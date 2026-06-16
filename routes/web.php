<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HalController;
use App\Http\Controllers\PreferensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('customer.katalog');
});

Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');

// Katalog dibuat publik, jadi guest/belum login tetap bisa melihat daftar produk dan detail produk.
Route::prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');
        Route::get('/katalog/{barang}', [CustomerController::class, 'detail'])->name('katalog.detail');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/preferensi/toggle-tema', [PreferensiController::class, 'toggleTema'])->name('preferensi.toggleTema');
});

// Dashboard customer tetap wajib login sebagai customer.
Route::middleware(['auth', 'cek.customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::post('/checkout/{barang}', [CustomerController::class, 'checkout'])->name('checkout');
        Route::get('/transaksi', [CustomerController::class, 'transaksiList'])->name('transaksi.list');
        Route::get('/transaksi/{transaksi}', [CustomerController::class, 'transaksiShow'])->name('transaksi.show');
        Route::post('/transaksi/{transaksi}/proses', [CustomerController::class, 'transaksiProses'])->name('transaksi.proses');
    });

// Pengelolaan disatukan dengan admin. Tidak ada lagi middleware cek.pengelola.
Route::middleware(['auth', 'cek.admin'])->group(function () {
    Route::get('/pengelolaan', [BarangController::class, 'index'])->name('pengelolaan');
    Route::get('/pengelolaan/search', [BarangController::class, 'searchAjax'])->name('barang.searchAjax');
    Route::post('/pengelolaan/ajax', [BarangController::class, 'storeAjax'])->name('barang.storeAjax');
    Route::post('/pengelolaan/kunjungan/reset', [BarangController::class, 'resetKunjungan'])->name('barang.resetKunjungan');
    Route::get('/pengelolaan/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/pengelolaan', [BarangController::class, 'store'])->name('barang.store');

    Route::get('/preferensi', [PreferensiController::class, 'index'])->name('preferensi.index');
    Route::post('/preferensi/simpan', [PreferensiController::class, 'simpan'])->name('preferensi.simpan');
    Route::post('/admin/kontak', [PreferensiController::class, 'simpanKontak'])->name('admin.kontak.update');

    Route::get('/pengelolaan/{barang}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('/pengelolaan/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/pengelolaan/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/pengelolaan/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
});

Route::middleware(['auth', 'cek.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::get('/transaksi', [BarangController::class, 'transaksiList'])->name('transaksi.list');
        Route::get('/transaksi/{transaksi}', [BarangController::class, 'transaksiShow'])->name('transaksi.show');
        Route::post('/transaksi/{transaksi}/setujui', [BarangController::class, 'transaksiSetujui'])->name('transaksi.setujui');
        Route::post('/transaksi/{transaksi}/tolak', [BarangController::class, 'transaksiTolak'])->name('transaksi.tolak');
        Route::post('/transaksi/{transaksi}/selesaikan', [BarangController::class, 'transaksiSelesaikan'])->name('transaksi.selesaikan');
    });

require __DIR__.'/auth.php';
