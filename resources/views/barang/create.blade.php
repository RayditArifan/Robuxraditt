@extends('layouts.app')

@section('title', 'Tambah Barang - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Create - Tambah Barang</span>
    <h2>Tambah Barang Baru</h2>
    <p>Isi form berikut untuk menyimpan barang baru ke database inventaris.</p>
  </div>
</section>

<section class="form-section">
  <div class="section-heading">
    <h2>Form Tambah Barang</h2>
    <p>Semua field wajib diisi supaya data inventaris tetap lengkap.</p>
  </div>

  <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
    @include('barang._form', ['submitLabel' => 'Simpan Barang'])
  </form>
</section>

@endsection
