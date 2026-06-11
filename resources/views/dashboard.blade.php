@extends('layouts.app')

@section('title', 'Dashboard — Inventaris RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Dashboard • Statistik • Ringkasan</span>
    <h2>Selamat datang, {{ auth()->user()->name ?? $username }}! 👋</h2>
    <p>
      Pantau statistik inventaris barang Roblox kamu dalam satu tampilan ringkas.
    </p>
  </div>
</section>

<section id="dashboard" class="dashboard-section">
  <div class="section-heading">
    <h2>Statistik Inventaris</h2>
    <p>Ringkasan data barang saat ini.</p>
  </div>

  <div class="card-grid">

    <article class="card stat-card">
      <p class="stat-label">Total Jenis Barang</p>
      <h3>{{ $totalBarang }}</h3>
      <small>Jumlah jenis barang terdaftar</small>
    </article>

    <article class="card stat-card">
      <p class="stat-label">Total Stok</p>
      <h3>{{ $totalStok }}</h3>
      <small>Total seluruh unit barang</small>
    </article>

    <article class="card stat-card">
      <p class="stat-label">Total Nilai Inventaris</p>
      <h3>Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
      <small>Stok × harga per barang</small>
    </article>

    <article class="card stat-card warning-card">
      <p class="stat-label">Stok Menipis</p>
      <h3>{{ $stokMenipis }}</h3>
      <small>Barang dengan stok &lt; 5</small>
    </article>

    <article class="card stat-card kurs-card">
      <div class="kurs-header">
        <p class="stat-label">Kurs USD-IDR RobuxRadit</p>
        <button type="button" id="refresh-kurs" class="kurs-refresh-btn">Refresh</button>
      </div>

      <div id="kurs-loading" class="kurs-status">
        <span class="kurs-spinner"></span>
        <span>Mengambil kurs terbaru...</span>
      </div>

      <div id="kurs-result" class="kurs-result hidden">
        <h3 id="kurs-value">Rp -</h3>
        <small id="kurs-date">Tanggal kurs: -</small>
      </div>

      <p id="kurs-error" class="kurs-error hidden">
        Gagal mengambil kurs. Gunakan acuan harga manual sementara.
      </p>

      <p class="kurs-note">
        Digunakan sebagai acuan admin saat menentukan harga Robux, Voucher, dan Private Server.
      </p>
    </article>

  </div>
</section>

<div class="stock-note">
  <h2>Keterangan Status Stok</h2>
  <p><strong>Aman</strong> berarti stok masih 5 atau lebih.</p>
  <p><strong>Menipis</strong> berarti stok di bawah 5.</p>
</div>

<section class="quick-action-section">
  <div class="section-heading">
    <h2>Aksi Cepat</h2>
    <p>Navigasi langsung ke halaman yang kamu butuhkan.</p>
  </div>

  <div class="quick-grid">
    <a href="{{ route('pengelolaan') }}" class="quick-card">
      <span class="quick-icon">📦</span>
      <h3>Kelola Inventaris</h3>
      <p>Lihat dan kelola seluruh daftar barang.</p>
    </a>

    <a href="{{ route('profile') }}" class="quick-card">
      <span class="quick-icon">👤</span>
      <h3>Profil Saya</h3>
      <p>Lihat informasi akun kamu.</p>
    </a>
  </div>
</section>

@endsection

@push('scripts')
<script>
  const FRANKFURTER_API = 'https://api.frankfurter.dev/v1/latest?base=USD&symbols=IDR';

  const kursLoading = document.getElementById('kurs-loading');
  const kursResult = document.getElementById('kurs-result');
  const kursError = document.getElementById('kurs-error');
  const kursValue = document.getElementById('kurs-value');
  const kursDate = document.getElementById('kurs-date');
  const refreshKursBtn = document.getElementById('refresh-kurs');

  function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0
    }).format(value);
  }

  function setKursState(state) {
    kursLoading.classList.toggle('hidden', state !== 'loading');
    kursResult.classList.toggle('hidden', state !== 'success');
    kursError.classList.toggle('hidden', state !== 'error');
    refreshKursBtn.disabled = state === 'loading';
    refreshKursBtn.textContent = state === 'loading' ? 'Memuat...' : 'Refresh';
  }

  async function ambilKursRobuxRadit() {
    setKursState('loading');

    try {
      const response = await fetch(FRANKFURTER_API, {
        headers: {
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const data = await response.json();
      const kursIdr = data?.rates?.IDR;

      if (!kursIdr) {
        throw new Error('Data IDR tidak tersedia');
      }

      kursValue.textContent = `${formatRupiah(kursIdr)} / USD`;
      kursDate.textContent = `Tanggal kurs: ${data.date}`;
      setKursState('success');
    } catch (error) {
      console.error('Gagal mengambil kurs USD-IDR:', error);
      setKursState('error');
    }
  }

  refreshKursBtn.addEventListener('click', ambilKursRobuxRadit);
  document.addEventListener('DOMContentLoaded', ambilKursRobuxRadit);
</script>
@endpush
