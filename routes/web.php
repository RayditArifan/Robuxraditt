<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HalController;
use App\Http\Controllers\PreferensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/kontak', 'kontak')->name('kontak');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [HalController::class, 'showProfile'])->name('profile');

    Route::get('/pengelolaan', [BarangController::class, 'index'])->name('pengelolaan');
    Route::get('/pengelolaan/search', [BarangController::class, 'searchAjax'])->name('barang.searchAjax');
    Route::post('/pengelolaan/ajax', [BarangController::class, 'storeAjax'])->name('barang.storeAjax');
    Route::post('/pengelolaan/kunjungan/reset', [BarangController::class, 'resetKunjungan'])->name('barang.resetKunjungan');
    Route::get('/pengelolaan/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/pengelolaan', [BarangController::class, 'store'])->name('barang.store');

    Route::get('/preferensi', [PreferensiController::class, 'index'])->name('preferensi.index');
    Route::post('/preferensi/simpan', [PreferensiController::class, 'simpan'])->name('preferensi.simpan');

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
    });

require __DIR__.'/auth.php';
