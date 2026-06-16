<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'jumlah',
        'total_harga',
        'username_roblox',
        'status',
        'bukti_pembayaran',
    ];

    /**
     * Get the customer that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product (barang) that is associated with the transaction.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
