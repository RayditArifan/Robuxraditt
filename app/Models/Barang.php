<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'stok',
        'harga',
        'tanggal_masuk',
        'aktif',
        'foto',
    ];

    protected $casts = [
        'stok' => 'integer',
        'harga' => 'decimal:2',
        'tanggal_masuk' => 'date',
        'aktif' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '<', 5);
    }

    public function getNilaiInventarisAttribute()
    {
        return $this->stok * $this->harga;
    }
}
