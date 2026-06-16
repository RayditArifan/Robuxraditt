@extends('layouts.app')

@section('title', 'Detail Produk — RobuxRadit')

@section('content')
<style>
    .detail-product-image-wrap {
        width: 100%;
        height: 360px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
    }

    .detail-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .detail-product-card {
        padding: 24px;
    }
</style>

<section class="hero">
    <div>
        <span class="hero-tag">Katalog • Detail Produk</span>
        <h2>{{ $barang->nama }}</h2>
        <p>
            Informasi lengkap mengenai spesifikasi, ketersediaan stok, dan harga produk game pilihan Anda.
        </p>
    </div>
</section>

<section class="dashboard-section">
    <div class="card detail-product-card" style="padding:28px;">
        <div class="detail-main-row" style="display: flex; flex-wrap: wrap; gap: 28px; width: 100%;">
            
            <!-- Left Content: Image and Details -->
            <div class="detail-left-col" style="flex: 1; min-width: 280px;">
                @if ($barang->foto)
                    <div class="detail-product-image-wrap">
                        <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="detail-product-image">
                    </div>
                @endif

                <p class="stat-label">{{ $barang->kategori }}</p>
                <h2 style="margin-bottom:16px;">{{ $barang->nama }}</h2>

                <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:24px;">
                    <p><strong>Harga:</strong> <span style="font-size:18px; font-weight:700; color:var(--primary);">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span></p>
                    <p><strong>Stok Tersedia:</strong> {{ $barang->stok }}</p>
                    <p><strong>Tanggal Masuk:</strong> {{ optional($barang->tanggal_masuk)->format('d M Y') }}</p>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('customer.katalog') }}" class="quick-card" style="display:inline-block; padding:10px 14px; text-decoration:none;">
                        ← Kembali ke Katalog
                    </a>
                    <a href="{{ route('kontak') }}" class="quick-card" style="display:inline-block; padding:10px 14px; text-decoration:none;">
                        📞 Hubungi Penjual
                    </a>
                </div>
            </div>

            <!-- Right Content: Checkout box on the far right -->
            <div class="detail-right-col" style="width: 320px; flex-shrink: 0; align-self: flex-start;">
                @if ($barang->stok > 0)
                    <form action="{{ route('customer.checkout', $barang) }}" method="POST" style="padding:24px; border:1.5px solid var(--border); border-radius:16px; background:#f8fafc; box-shadow: 0 4px 12px rgba(0,0,0,0.02); width:100%;">
                        @csrf
                        <div class="form-group" style="margin-bottom:20px;">
                            <label for="username_roblox" style="font-weight:700; font-size:14px; margin-bottom:8px; display:block; color:var(--text);">Username Roblox</label>
                            <input
                                type="text"
                                id="username_roblox"
                                name="username_roblox"
                                placeholder="Username Roblox Anda"
                                required
                                style="padding:10px 12px; border-radius:8px; border:1.5px solid #cbd5e1; width:100%; font-size:15px; background:white;"
                            >
                        </div>

                        <div class="form-group" style="margin-bottom:20px;">
                            <label for="jumlah" style="font-weight:700; font-size:14px; margin-bottom:8px; display:block; color:var(--text);">Jumlah Pembelian</label>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <input
                                    type="number"
                                    id="jumlah"
                                    name="jumlah"
                                    value="1"
                                    min="1"
                                    max="{{ $barang->stok }}"
                                    required
                                    style="padding:10px 12px; border-radius:8px; border:1.5px solid #cbd5e1; width:80px; font-weight:700; text-align:center; font-size:16px;"
                                >
                                <span style="font-size:13px; color:var(--muted); font-weight:500;">Maksimal {{ $barang->stok }} item</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%; border-radius:10px; padding:14px; font-weight:700; background: #22c55e; color: white; border: none; cursor: pointer; font-size:15px; transition: background 0.2s ease;" onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'">
                            Checkout / Beli Sekarang
                        </button>
                    </form>
                @else
                    <div style="padding:16px; background:#fef2f2; color:var(--danger); border-radius:12px; font-weight:700; text-align:center; border: 1.5px solid #fee2e2;">
                        Stok Sedang Habis
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
