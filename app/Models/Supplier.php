<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
    ];

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_supplier')
                    ->withTimestamps();
    }
}
