@extends('layouts.login')

@section('title', 'Lupa Password — Toko RobuxRadit')

@section('content')
<div class="login-container">
  <div class="login-card">

    <div class="login-header">
      <div class="login-logo">
        <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo RobuxRadit" onerror="this.parentElement.innerHTML='<span>R</span>'">
      </div>
      <h2>Toko RobuxRadit</h2>
      <p>Masukkan email Anda untuk menerima tautan reset password</p>
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

    <form method="POST" action="{{ route('password.email') }}" class="login-form">
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
          autocomplete="email"
        >
      </div>

      <button type="submit" class="btn-submit">Kirim Tautan Reset Password</button>
    </form>

    <div style="text-align:center; margin-top:16px; font-size:0.9rem; color:#64748b;">
      Ingat password Anda?
      <a href="{{ route('login') }}" style="color:#3b82f6; font-weight:600; text-decoration:none;">Kembali Masuk</a>
    </div>

  </div>
</div>
@endsection
