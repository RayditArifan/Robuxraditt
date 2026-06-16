<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_supplier', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->onDelete('cascade');

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['barang_id', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_supplier');
    }
};
