<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['nama' => 'RobuxRadit Official', 'email' => 'official@robuxradit.test', 'telepon' => '081234567890', 'alamat' => 'Banyuwangi'],
            ['nama' => 'Digital Item Store', 'email' => 'store@digitalitem.test', 'telepon' => '082233445566', 'alamat' => 'Indonesia'],
        ];

        foreach ($suppliers as $item) {
            Supplier::updateOrCreate(['email' => $item['email']], $item);
        }

        $supplierIds = Supplier::pluck('id')->toArray();

        Barang::all()->each(function ($barang) use ($supplierIds) {
            $barang->suppliers()->syncWithoutDetaching($supplierIds);
        });
    }
}
