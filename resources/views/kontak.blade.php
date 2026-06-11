@extends('layouts.app')

@section('title', 'Kontak - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Kontak • Halaman Statis</span>
    <h2>Kontak</h2>
    <p>
      Halaman ini berisi kontak dan informasi tentang admint
    </p>
  </div>
</section>

<section class="dashboard-section">
  <div class="section-heading">
    <h2>Informasi Kontak</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Isi bagian ini dengan informasi kontak yang kamu inginkan.</p>
  </div>

  <div class="detail-grid">
    <div class="card detail-card">
      <h3>Data Kontak</h3>
      <table class="detail-table">
        <tr>
          <th>Email</th>
          <td>robuxradit@gmail.com</td>
        </tr>
        <tr>
          <th>Telepon</th>
          <td>08257511930</td>
        </tr>
        <tr>
          <th>Alamat</th>
          <td>Jl.Lorem ipsum dolor sit amet</td>
        </tr>
      </table>
    </div>

    <div class="card detail-card">
      <h3>Catatan</h3>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Bagian ini bisa kamu isi dengan pesan, media sosial, atau informasi tambahan lain.
      </p>
    </div>
  </div>
</section>

@endsection
