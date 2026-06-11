@extends('layouts.login')

@section('title', 'Login — Inventaris RobuxRadit')

@section('content')

<section class="login-wrapper">
  <div class="login-card">

    <div class="login-header">
      <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo" class="login-logo"
           onerror="this.style.display='none'">
      <h2>Inventaris RobuxRadit</h2>
      <p>Masuk untuk mengelola data barang</p>
    </div>

    @if (session('error'))
      <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.process') }}" method="POST" class="login-form" novalidate>
      @csrf

      <div class="form-group">
        <label for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          value="{{ old('username') }}"
          placeholder="Masukkan username"
          autocomplete="username"
          required
        >
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Masukkan password"
          autocomplete="current-password"
          required
        >
      </div>

      <button type="submit" class="btn btn-primary btn-full">Masuk</button>
    </form>

    <div class="login-hint">
      <p>Akun pass biar g lupa: <strong>radit</strong> / <strong>robux123</strong></p>
    </div>

  </div>
</section>

@endsection
