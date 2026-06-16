@php
    $sessionTema = session('tema', 'light');
    $sessionFont = session('ukuran_font', 'normal');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $sessionTema === 'dark' ? 'dark' : '' }}" data-font="{{ $sessionFont }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Toko RobuxRadit — Sistem Manajemen Barang">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Toko RobuxRadit')</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @stack('styles')
</head>
<body>

  @include('partials.navbar')

  <main class="page-content">
    @include('partials.flash')
    @yield('content')
  </main>

  @include('partials.footer')

  @stack('scripts')
</body>
</html>
