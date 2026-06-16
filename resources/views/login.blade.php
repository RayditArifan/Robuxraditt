@extends('layouts.login')

@section('title', 'Login — Toko RobuxRadit')

@section('content')

<div class="login-page">

  {{-- ===== LEFT PANEL (branding) ===== --}}
  <aside class="login-left">
    <div class="login-left-inner">

      <div class="brand-pill">
        <span class="brand-pill-dot"></span>
        Sistem Toko
      </div>

      <div class="login-brand">
        <div class="login-brand-logo">
          <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo RobuxRadit"
               onerror="this.parentElement.innerHTML='<span>R</span>'">
        </div>
        <h1 class="login-brand-name">RobuxRadit</h1>
        <p class="login-brand-tagline">Kelola toko dengan mudah, cepat, dan akurat.</p>
      </div>

      <ul class="login-feature-list">
        <li>
          <span class="feature-icon">📦</span>
          <span>Manajemen stok barang real-time</span>
        </li>
        <li>
          <span class="feature-icon">💱</span>
          <span>Konversi kurs USD–IDR otomatis</span>
        </li>
        <li>
          <span class="feature-icon">📊</span>
          <span>Dashboard analitik &amp; laporan lengkap</span>
        </li>
        <li>
          <span class="feature-icon">🔐</span>
          <span>Data aman &amp; terproteksi</span>
        </li>
      </ul>

      {{-- floating decorative card --}}
      <div class="login-deco-card">
        <div class="deco-dot green"></div>
        <div class="deco-content">
          <span class="deco-label">Total Barang Aktif</span>
          <span class="deco-value">Real-time</span>
        </div>
        <span class="deco-emoji">📈</span>
      </div>

    </div>
  </aside>

  {{-- ===== RIGHT PANEL (form) ===== --}}
  <main class="login-right">
    <div class="login-card">

      <div class="login-card-header">
        <h2>Selamat datang 👋</h2>
        <p>Masuk untuk mengelola data toko</p>
      </div>

      @if (session('error'))
        <div class="login-alert login-alert-error">
          <span>⚠️</span> {{ session('error') }}
        </div>
      @endif

      <form action="{{ route('login.process') }}" method="POST" class="login-form" novalidate>
        @csrf

        <div class="lf-group">
          <label for="username">
            <span class="lf-icon">👤</span>
            Username
          </label>
          <input
            type="text"
            id="username"
            name="username"
            value="{{ old('username') }}"
            placeholder="Masukkan username kamu"
            autocomplete="username"
            required
          >
        </div>

        <div class="lf-group">
          <label for="password">
            <span class="lf-icon">🔑</span>
            Password
          </label>
          <div class="lf-password-wrap">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password kamu"
              autocomplete="current-password"
              required
            >
            <button type="button" class="lf-toggle-pw" onclick="togglePw()" aria-label="Tampilkan password">
              <span id="pw-eye">👁️</span>
            </button>
          </div>
        </div>

        <button type="submit" class="login-btn">
          <span>Masuk</span>
          <span class="login-btn-arrow">→</span>
        </button>

      </form>

      <div class="login-hint">
        <span>💡</span>
        Default: <strong>radit</strong> / <strong>robux123</strong>
      </div>

    </div>
  </main>

</div>

<script>
function togglePw() {
  const input = document.getElementById('password');
  const eye   = document.getElementById('pw-eye');
  if (input.type === 'password') {
    input.type = 'text';
    eye.textContent = '🙈';
  } else {
    input.type = 'password';
    eye.textContent = '👁️';
  }
}
</script>

@endsection
