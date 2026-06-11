<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->enum('kategori', ['Gamepass', 'Voucher', 'Private Server']);
            $table->integer('stok')->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->date('tanggal_masuk')->nullable();
            $table->boolean('aktif')->default(true);
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
