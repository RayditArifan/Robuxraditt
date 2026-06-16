@extends('layouts.app')

@section('title', 'Dashboard Customer — RobuxRadit')

@section('content')
<style>
    .customer-dashboard-product-image-wrap {
        width: 100%;
        height: 150px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .customer-dashboard-product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }
</style>
<section class="hero">
    <div>
        <span class="hero-tag">Katalog Resmi • Produk Terlengkap</span>
        <h2>Selamat datang, {{ $username }}!</h2>
        <p>
            Temukan berbagai macam produk game dan item game termurah serta tepercaya hanya di Toko RobuxRadit.
        </p>
    </div>
</section>



<section class="quick-action-section">
    <div class="section-heading">
        <h2>Menu Utama</h2>
        <p>Kelola profil Anda atau telusuri langsung katalog produk kami.</p>
    </div>

    <div class="quick-grid">
        <a href="{{ route('customer.katalog') }}" class="quick-card">
            <span class="quick-icon"></span>
            <h3>Lihat Katalog</h3>
            <p>Jelajahi seluruh koleksi produk terbaik kami dengan harga bersaing.</p>
        </a>

        <a href="{{ route('profile') }}" class="quick-card">
            <span class="quick-icon"></span>
            <h3>Profil Saya</h3>
            <p>Kelola informasi akun dan preferensi tampilan Anda.</p>
        </a>
    </div>
</section>

<section class="dashboard-section">
    <div class="section-heading">
        <h2>Produk Terbaru</h2>
        <p>Lihat koleksi terbaru yang baru saja mendarat di toko kami.</p>
    </div>

    <div class="card-grid">
        @forelse ($produkTerbaru as $barang)
            <article class="card stat-card">
                @if ($barang->foto)
                    <div class="customer-dashboard-product-image-wrap">
                        <img src="{{ asset('storage/' . $barang->foto) }}" alt="{{ $barang->nama }}" class="customer-dashboard-product-image">
                    </div>
                @endif

                <p class="stat-label">{{ $barang->kategori }}</p>
                <h3>{{ $barang->nama }}</h3>
                <small>Rp {{ number_format($barang->harga, 0, ',', '.') }} • Stok {{ $barang->stok }}</small>
                <br><br>
                <a href="{{ route('customer.katalog.detail', $barang) }}" class="quick-card" style="display:inline-block; padding:10px 14px;">
                    Lihat Detail
                </a>
            </article>
        @empty
            <article class="card stat-card">
                <p class="stat-label">Belum Ada Produk</p>
                <h3>-</h3>
                <small>Produk aktif belum tersedia.</small>
            </article>
        @endforelse
    </div>
</section>
@endsection
