@php
    $cookieTema = trim(request()->cookie('tema', 'light'), '"');
    $cookieFont = trim(request()->cookie('ukuran_font', 'normal'), '"');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $cookieTema === 'dark' ? 'dark' : '' }}" data-font="{{ $cookieFont }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Toko RobuxRadit — Sistem Manajemen Barang">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Toko RobuxRadit')</title>
  <script>
    (function () {
      function getCookie(name) {
        const cookies = document.cookie.split(';');

        for (const cookie of cookies) {
          const [key, value] = cookie.trim().split('=');

          if (key === name) {
            return decodeURIComponent(value || '');
          }
        }

        return null;
      }

      let tema = getCookie('tema') || 'light';
      let ukuranFont = getCookie('ukuran_font') || 'normal';

      if (tema.startsWith('"') && tema.endsWith('"')) {
        tema = tema.slice(1, -1);
      }
      if (ukuranFont.startsWith('"') && ukuranFont.endsWith('"')) {
        ukuranFont = ukuranFont.slice(1, -1);
      }

      if (tema === 'dark') {
        document.documentElement.classList.add('dark');
      } else if (tema === 'system') {
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', systemDark);
      }

      document.documentElement.dataset.font = ukuranFont;
    })();
  </script>

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
