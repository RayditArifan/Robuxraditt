@extends('layouts.app')

@section('title', 'Pengelolaan Inventaris - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Pengelolaan - CRUD Barang - Stok</span>
    <h2>Pengelolaan Inventaris</h2>
    <p>Halo <strong>{{ $username }}</strong>, di halaman ini kamu bisa menambah, melihat detail, mengubah, dan menghapus barang.</p>
  </div>
</section>

<section class="dashboard-section">
  <div class="section-heading section-heading-row">
    <div>
      <h2>Ringkasan Barang</h2>
      <p>Total {{ $totalBarang }} jenis barang terdaftar.</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
  </div>

  <div class="card-grid">
    <article class="card stat-card">
      <p class="stat-label">Jenis Barang</p>
      <h3>{{ $totalBarang }}</h3>
      <small>Total jenis</small>
    </article>
    <article class="card stat-card">
      <p class="stat-label">Total Stok</p>
      <h3>{{ $totalStok }}</h3>
      <small>Seluruh unit</small>
    </article>
    <article class="card stat-card">
      <p class="stat-label">Nilai Inventaris</p>
      <h3 class="money-text">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
      <small>Stok x harga</small>
    </article>
    <article class="card stat-card warning-card">
      <p class="stat-label">Stok Menipis</p>
      <h3>{{ $stokMenipis }}</h3>
      <small>Stok &lt; 5</small>
    </article>
  </div>
</section>


<section class="dashboard-section ajax-section">
  <div class="section-heading">
    <h2>Live Search Barang</h2>
  </div>

  <div class="search-panel">
    <input
      type="text"
      id="search-barang"
      class="form-input search-input"
      placeholder="Cari Gamepass, Voucher, Private Server, atau kode barang..."
      autocomplete="off"
    >
    <div id="search-status" class="ajax-status">Data awal akan dimuat otomatis.</div>
  </div>

  <div id="search-loading" class="ajax-loading hidden">
    <span class="mini-spinner"></span>
    <span>Mencari barang...</span>
  </div>

  <div id="search-result" class="ajax-result"></div>
</section>

<section class="dashboard-section ajax-section">
  <div class="section-heading">
    <h2>Tambah Barang Cepat</h2>
  </div>

  <form id="form-barang-ajax" class="ajax-form">
    <div class="form-group">
      <label for="ajax-kode">Kode Barang</label>
      <input id="ajax-kode" type="text" name="kode" placeholder="BRG-NEW" required>
    </div>

    <div class="form-group">
      <label for="ajax-nama">Nama Barang</label>
      <input id="ajax-nama" type="text" name="nama" placeholder="Voucher Robux 500" required>
    </div>

    <div class="form-group">
      <label for="ajax-kategori">Kategori</label>
      <select id="ajax-kategori" name="kategori" required>
        <option value="">Pilih Kategori</option>
        <option value="Gamepass">Gamepass</option>
        <option value="Voucher">Voucher</option>
        <option value="Private Server">Private Server</option>
      </select>
    </div>

    <div class="form-group">
      <label for="ajax-stok">Stok</label>
      <input id="ajax-stok" type="number" name="stok" placeholder="10" min="0" required>
    </div>

    <div class="form-group">
      <label for="ajax-harga">Harga</label>
      <input id="ajax-harga" type="number" name="harga" placeholder="50000" min="1000" required>
    </div>

    <div class="form-group">
      <label for="ajax-tanggal">Tanggal Masuk</label>
      <input id="ajax-tanggal" type="date" name="tanggal_masuk" required>
    </div>

    <button type="submit" class="btn btn-primary ajax-submit-btn">Tambah</button>
  </form>

  <div id="form-ajax-message" class="ajax-message"></div>
</section>

<section class="dashboard-section session-section">
  <div class="section-heading">
    <h2>Aktivitas Kunjungan Inventaris</h2>
  </div>

  <div class="visit-grid">
    <article class="visit-card">
      <span>Total Kunjungan</span>
      <strong id="visit-jumlah">{{ $jumlahKunjungan }}</strong>
      <small>kali membuka halaman pengelolaan</small>
    </article>

    <article class="visit-card">
      <span>Kunjungan Pertama</span>
      <strong id="visit-pertama">{{ $kunjunganPertama }}</strong>
      <small>disimpan di session server</small>
    </article>

    <article class="visit-card">
      <span>Kunjungan Terakhir</span>
      <strong id="visit-terakhir">{{ $kunjunganTerakhir }}</strong>
      <small>diperbarui setiap halaman dibuka</small>
    </article>
  </div>

  <button type="button" id="reset-kunjungan" class="btn btn-danger">Reset Hitungan</button>
  <div id="reset-kunjungan-message" class="ajax-message"></div>
</section>

<section id="inventaris-section" class="table-section">
  <div class="section-heading">
    <h2>Daftar Inventaris Barang</h2>
    <p>Data berikut berasal dari tabel <code>barangs</code> melalui Model Barang.</p>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Kategori</th>
          <th>Stok</th>
          <th>Harga</th>
          <th>Tanggal Masuk</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($barangs as $barang)
          <tr>
            <td>{{ $barangs->firstItem() + $loop->index }}</td>
            <td>{{ $barang->kode }}</td>
            <td>{{ $barang->nama }}</td>
            <td><span class="badge-kategori">{{ $barang->kategori }}</span></td>
            <td>{{ $barang->stok }}</td>
            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
            <td>{{ optional($barang->tanggal_masuk)->translatedFormat('d F Y') ?? '-' }}</td>
            <td>
              @if ($barang->stok < 5)
                <span class="badge badge-low">Menipis</span>
              @else
                <span class="badge badge-safe">Aman</span>
              @endif
            </td>
            <td>
              <div class="action-group">
                <a href="{{ route('barang.show', $barang) }}" class="action-btn detail">Detail</a>
                <a href="{{ route('barang.edit', $barang) }}" class="action-btn edit">Edit</a>
                <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="delete-form" data-name="{{ $barang->nama }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn delete">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="empty-text">Belum ada data barang.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="pagination-wrapper">
    {{ $barangs->links() }}
  </div>
</section>

@endsection

@push('scripts')
<script>
  const CSRF_PENGELOLAAN = document.querySelector('meta[name="csrf-token"]').content;

  document.querySelectorAll('.delete-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      const namaBarang = form.dataset.name || 'barang ini';
      if (!confirm('Yakin ingin menghapus ' + namaBarang + '?')) {
        event.preventDefault();
      }
    });
  });

  const searchInput = document.getElementById('search-barang');
  const searchLoading = document.getElementById('search-loading');
  const searchResult = document.getElementById('search-result');
  const searchStatus = document.getElementById('search-status');
  let searchTimer;

  searchInput?.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const keyword = this.value.trim();
    searchStatus.textContent = 'Menunggu input...';

    searchTimer = setTimeout(function () {
      cariBarang(keyword);
    }, 400);
  });

  async function cariBarang(keyword = '') {
    searchLoading.classList.remove('hidden');
    searchResult.innerHTML = '';

    try {
      const url = `{{ route('barang.searchAjax') }}?q=${encodeURIComponent(keyword)}`;
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const json = await response.json();
      searchStatus.textContent = keyword
        ? `Ditemukan ${json.total} barang untuk keyword "${keyword}"`
        : `Menampilkan ${json.total} barang terbaru`;

      renderBarang(json.data);
    } catch (error) {
      searchResult.innerHTML = `<div class="alert-error">Gagal mencari barang: ${error.message}</div>`;
    } finally {
      searchLoading.classList.add('hidden');
    }
  }

  function escapeHtml(value) {
    return String(value ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function formatTanggal(value) {
    if (!value) return '-';
    return new Date(value).toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
    });
  }

  function renderBarang(barangs) {
    if (!barangs.length) {
      searchResult.innerHTML = '<p class="empty-state">Barang tidak ditemukan.</p>';
      return;
    }

    let html = `
      <div class="table-wrapper ajax-table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Stok</th>
              <th>Harga</th>
              <th>Tanggal Masuk</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
    `;

    barangs.forEach(function (barang) {
      const statusClass = Number(barang.stok) < 5 ? 'badge-low' : 'badge-safe';
      const statusText = Number(barang.stok) < 5 ? 'Menipis' : 'Aman';

      html += `
        <tr>
          <td>${escapeHtml(barang.kode)}</td>
          <td>${escapeHtml(barang.nama)}</td>
          <td><span class="badge-kategori">${escapeHtml(barang.kategori)}</span></td>
          <td>${escapeHtml(barang.stok)}</td>
          <td>Rp ${Number(barang.harga).toLocaleString('id-ID')}</td>
          <td>${formatTanggal(barang.tanggal_masuk)}</td>
          <td><span class="badge ${statusClass}">${statusText}</span></td>
        </tr>
      `;
    });

    html += '</tbody></table></div>';
    searchResult.innerHTML = html;
  }
  const formBarangAjax = document.getElementById('form-barang-ajax');
  const formAjaxMessage = document.getElementById('form-ajax-message');

  formBarangAjax?.addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(formBarangAjax);
    const payload = Object.fromEntries(formData.entries());
    formAjaxMessage.innerHTML = '<div class="ajax-loading">Menyimpan barang...</div>';

    try {
      const response = await fetch('{{ route('barang.storeAjax') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_PENGELOLAAN,
        },
        body: JSON.stringify(payload),
      });

      const json = await response.json();

      if (!response.ok) {
        const pesanValidasi = json.message || 'Data tidak valid.';
        throw new Error(pesanValidasi);
      }

      formAjaxMessage.innerHTML = `<div class="alert-success">${json.message}</div>`;
      formBarangAjax.reset();
      cariBarang('');
    } catch (error) {
      formAjaxMessage.innerHTML = `<div class="alert-error">Gagal menambah barang: ${error.message}</div>`;
    }
  });

  const resetKunjunganBtn = document.getElementById('reset-kunjungan');
  const resetKunjunganMessage = document.getElementById('reset-kunjungan-message');

  resetKunjunganBtn?.addEventListener('click', async function () {
    if (!confirm('Reset hitungan kunjungan inventaris?')) {
      return;
    }

    resetKunjunganMessage.innerHTML = '<div class="ajax-loading">Mereset hitungan...</div>';

    try {
      const response = await fetch('{{ route('barang.resetKunjungan') }}', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_PENGELOLAAN,
        },
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const json = await response.json();
      document.getElementById('visit-jumlah').textContent = '0';
      document.getElementById('visit-pertama').textContent = '-';
      document.getElementById('visit-terakhir').textContent = '-';
      resetKunjunganMessage.innerHTML = `<div class="alert-success">${json.message}</div>`;
    } catch (error) {
      resetKunjunganMessage.innerHTML = `<div class="alert-error">Gagal reset kunjungan: ${error.message}</div>`;
    }
  });

  cariBarang('');
</script>
@endpush
