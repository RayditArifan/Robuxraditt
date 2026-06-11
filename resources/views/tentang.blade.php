@extends('layouts.app')

@section('title', 'Tentang - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Tentang • Halaman Statis</span>
    <h2>Tentang Aplikasi</h2>
    <p>
      Halaman ini berisi informasi tentang sistem
    </p>
  </div>
</section>

<section class="dashboard-section">
  <div class="section-heading">
    <h2>Deskripsi Singkat</h2>
    <p>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aplikasi ini digunakan untuk menampilkan informasi umum tentang sistem yang dibuat.
    </p>
  </div>

  <div class="card-grid">
    <article class="card">
      <h3>Tujuan Aplikasi</h3>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Isi bagian ini dengan tujuan aplikasi kamu sendiri.
      </p>
    </article>

    <article class="card">
      <h3>Fitur Utama</h3>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Isi bagian ini dengan fitur utama aplikasi kamu sendiri.
      </p>
    </article>
  </div>
</section>

@endsection
