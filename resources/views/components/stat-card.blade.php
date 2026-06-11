@props([
  'judul' => 'Judul Statistik',
  'nilai' => '0',
  'ikon' => '📊',
  'warna' => '',
])

<article class="card stat-card {{ $warna }}">
  <div class="stat-card-top">
    <span class="stat-icon">{{ $ikon }}</span>
    <p class="stat-label">{{ $judul }}</p>
  </div>

  <h3>{{ $nilai }}</h3>

  @if (trim($slot) !== '')
    <small>{{ $slot }}</small>
  @endif
</article>
