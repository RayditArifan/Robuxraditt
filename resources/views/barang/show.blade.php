@extends('layouts.app')

@section('title', 'Detail Barang - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Detail - Barang</span>
    <h2>Detail Barang</h2>
    <p>Informasi lengkap barang <strong>{{ $barang->nama }}</strong>.</p>
  </div>
</section>

<section class="dashboard-section">
  <div class="card detail-card">
    <h3>{{ $barang->nama }}</h3>

    @if ($barang->foto)
      <div class="barang-photo-wrapper" style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto {{ $barang->nama }}" class="barang-photo" style="max-width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px;">
      </div>
    @else
      <p>Belum ada foto barang.</p>
    @endif

    <table class="detail-table">
      <tr><th>Kode Barang</th><td>{{ $barang->kode }}</td></tr>
      <tr><th>Nama Barang</th><td>{{ $barang->nama }}</td></tr>
      <tr><th>Kategori</th><td>{{ $barang->kategori }}</td></tr>
      <tr><th>Stok</th><td>{{ $barang->stok }}</td></tr>
      <tr><th>Harga</th><td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td></tr>
      <tr><th>Tanggal Masuk</th><td>{{ optional($barang->tanggal_masuk)->translatedFormat('d F Y') ?? '-' }}</td></tr>
      <tr><th>Status Stok</th><td>{{ $barang->stok < 5 ? 'Menipis' : 'Aman' }}</td></tr>
    </table>

    <div class="button-row">
      <a href="{{ route('barang.edit', $barang) }}" class="btn btn-primary">Edit Barang</a>
      <a href="{{ route('pengelolaan') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</section>

@endsection
