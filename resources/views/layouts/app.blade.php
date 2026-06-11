<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Inventaris RobuxRadit — Sistem Manajemen Barang">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Inventaris RobuxRadit')</title>
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

      const tema = getCookie('tema') || 'light';
      const ukuranFont = getCookie('ukuran_font') || 'normal';

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
