@extends('layouts.login')

@section('title', 'Login — Toko RobuxRadit')

@section('content')
<div class="login-container">
  <div class="login-card">

    <div class="login-header">
      <div class="login-logo">
        <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo RobuxRadit" onerror="this.parentElement.innerHTML='<span>R</span>'">
      </div>
      <h2>Toko RobuxRadit</h2>
      <p>Masuk untuk mengelola data barang</p>
    </div>

    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form">
      @csrf

      <div class="form-group">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          placeholder="Masukkan email Anda"
          required
          autofocus
        >
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-container">
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password Anda"
            required
          >
          <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <div class="form-options">
        <label class="remember-me">
          <input type="checkbox" name="remember" id="remember_me">
          <span class="checkbox-box"></span>
          Ingat saya
        </label>

        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot-password">Lupa password?</a>
        @endif
      </div>

      <button type="submit" class="btn-submit">Masuk</button>
    </form>

    @if (Route::has('register'))
      <div style="text-align:center; margin-top:16px; font-size:0.9rem; color:#64748b;">
        Belum punya akun?
        <a href="{{ route('register') }}" style="color:#3b82f6; font-weight:600; text-decoration:none;">Daftar sekarang</a>
      </div>
    @endif

    <div class="login-hint">
      Default: <strong>admin@example.com</strong> / <strong>password</strong>
    </div>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
      // SVG paths for open eye and closed eye
      const eyeOpenSvg = `<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/>`;
      const eyeClosedSvg = `<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>`;

      togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'password') {
          eyeIcon.innerHTML = eyeOpenSvg;
          togglePassword.setAttribute('aria-label', 'Tampilkan password');
        } else {
          eyeIcon.innerHTML = eyeClosedSvg;
          togglePassword.setAttribute('aria-label', 'Sembunyikan password');
        }
      });
    }
  });
</script>
@endsection
