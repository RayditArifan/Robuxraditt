@extends('layouts.app')

@section('title', 'Katalog Customer — RobuxRadit')

@section('content')
<style>
    .customer-product-image-wrap {
        width: 100%;
        height: 170px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .customer-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .category-block {
        margin-top: 24px;
        padding: 22px;
        border: 1px solid #dbe6f4;
        border-radius: 20px;
        background: #ffffff;
    }

    .category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .category-header h3 {
        margin: 0;
    }

    .category-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .customer-product-card {
        padding: 18px;
        height: 100%;
    }

    .customer-product-card h3,
    .customer-product-card h4 {
        margin-top: 8px;
        margin-bottom: 8px;
        line-height: 1.15;
    }

    .all-products-section {
        margin-top: 34px;
        padding-top: 26px;
        border-top: 1px solid #e2e8f0;
    }
</style>

<section class="hero">
    <div>
        <span class="hero-tag">Koleksi Item • Katalog Produk</span>
        <h2>Katalog Produk RobuxRadit</h2>
        <p>
            Temukan beragam pilihan gamepass, robux, dan item game Roblox terlaris dengan proses cepat dan harga termurah.
        </p>
    </div>
</section>

<section class="dashboard-section">
    <div class="section-heading">
        <h2>Produk Berdasarkan Kategori</h2>
        <p>Telusuri berbagai kategori item game pilihan untuk memudahkan pencarian Anda.</p>
    </div>

    @forelse ($produkKategori as $kategori => $items)
        <div class="category-block">
            <div class="category-header">
                <div>
                    <p class="stat-label">Kategori</p>
                    <h3>{{ $kategori }}</h3>
                </div>
                <small>{{ $items->count() }} produk tersedia</small>
            </div>

            <div class="category-row">
                @foreach ($items as $barang)
                    <article class="card customer-product-card">
                        @if ($barang->foto)
                            <div class="customer-product-image-wrap">
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="customer-product-image">
                            </div>
                        @endif

                        <p class="stat-label">{{ $barang->kategori }}</p>
                        <h3>{{ $barang->nama }}</h3>
                        <p style="margin-top:12px; font-weight:700;">
                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                        </p>
                        <small>Stok tersedia: {{ $barang->stok }}</small>
                        <br><br>
                        <a href="{{ route('customer.katalog.detail', $barang) }}" class="quick-card" style="display:inline-block; padding:10px 14px;">
                            Lihat Detail
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    @empty
        <article class="card stat-card">
            <p class="stat-label">Kosong</p>
            <h3>Belum ada produk tersedia.</h3>
            <small>Silakan cek kembali nanti.</small>
        </article>
    @endforelse

    <div class="all-products-section">
        <div class="section-heading">
            <h2>Semua Item</h2>
            <p>Jelajahi seluruh daftar koleksi produk terbaik kami.</p>
        </div>

        <div class="card-grid">
            @forelse ($semuaBarangs as $barang)
                <article class="card stat-card customer-product-card">
                    @if ($barang->foto)
                        <div class="customer-product-image-wrap">
                            <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="customer-product-image">
                        </div>
                    @endif

                    <p class="stat-label">{{ $barang->kategori }}</p>
                    <h3>{{ $barang->nama }}</h3>
                    <p style="margin-top:12px; font-weight:700;">
                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    </p>
                    <small>Stok tersedia: {{ $barang->stok }}</small>
                    <br><br>
                    <a href="{{ route('customer.katalog.detail', $barang) }}" class="quick-card" style="display:inline-block; padding:10px 14px;">
                        Lihat Detail
                    </a>
                </article>
            @empty
                <article class="card stat-card">
                    <p class="stat-label">Kosong</p>
                    <h3>Belum ada produk tersedia.</h3>
                    <small>Silakan cek kembali nanti.</small>
                </article>
            @endforelse
        </div>

        <div style="margin-top:24px;">
            {{ $semuaBarangs->links() }}
        </div>
    </div>
</section>
@endsection
