@extends('layouts.app')

@section('title', 'Tambah Barang - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Kelola Stok • Tambah Barang</span>
    <h2>Tambah Barang Baru</h2>
    <p>Isi formulir berikut untuk menambahkan item baru ke dalam katalog toko.</p>
  </div>
</section>

<section class="form-section">
  <div class="section-heading">
    <h2>Form Tambah Barang</h2>
    <p>Semua field wajib diisi supaya data toko tetap lengkap.</p>
  </div>

  <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
    @include('barang._form', ['submitLabel' => 'Simpan Barang'])
  </form>
</section>

@endsection
