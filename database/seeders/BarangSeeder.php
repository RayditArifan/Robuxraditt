<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'BRG-001', 'nama' => 'Gamepass VIP Roblox', 'kategori' => 'Gamepass', 'stok' => 12, 'harga' => 35000, 'tanggal_masuk' => '2026-05-01', 'aktif' => true],
            ['kode' => 'BRG-002', 'nama' => 'Voucher Robux 100', 'kategori' => 'Voucher', 'stok' => 20, 'harga' => 25000, 'tanggal_masuk' => '2026-05-01', 'aktif' => true],
            ['kode' => 'BRG-003', 'nama' => 'Private Server Bulanan', 'kategori' => 'Private Server', 'stok' => 4, 'harga' => 15000, 'tanggal_masuk' => '2026-05-02', 'aktif' => true],
            ['kode' => 'BRG-004', 'nama' => 'Gamepass Premium', 'kategori' => 'Gamepass', 'stok' => 7, 'harga' => 50000, 'tanggal_masuk' => '2026-05-02', 'aktif' => true],
            ['kode' => 'BRG-005', 'nama' => 'Voucher Robux 500', 'kategori' => 'Voucher', 'stok' => 3, 'harga' => 110000, 'tanggal_masuk' => '2026-05-03', 'aktif' => true],
            ['kode' => 'BRG-006', 'nama' => 'Private Server Mingguan', 'kategori' => 'Private Server', 'stok' => 9, 'harga' => 8000, 'tanggal_masuk' => '2026-05-03', 'aktif' => true],
            ['kode' => 'BRG-007', 'nama' => 'Gamepass Starter', 'kategori' => 'Gamepass', 'stok' => 2, 'harga' => 12000, 'tanggal_masuk' => '2026-05-04', 'aktif' => true],
            ['kode' => 'BRG-008', 'nama' => 'Voucher Robux 1000', 'kategori' => 'Voucher', 'stok' => 6, 'harga' => 210000, 'tanggal_masuk' => '2026-05-04', 'aktif' => true],
        ];

        foreach ($data as $item) {
            Barang::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
