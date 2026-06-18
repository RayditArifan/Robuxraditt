@extends('layouts.app')

@section('title', 'Tambah Barang - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Kelola Stok • Tambah Barang</span>
    <h2>Tambah Barang Baru</h2>
    <p>Isi formulir berikut untuk menambahkan item baru ke dalam katalog toko.</p>
  </div>
</section>

<section class="form-section">
  <div class="section-heading">
    <h2>Form Tambah Barang</h2>
    <p>Semua field wajib diisi supaya data toko tetap lengkap.</p>
  </div>

  <form id="form-tambah-barang" action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @include('barang._form', ['submitLabel' => 'Simpan Barang'])
  </form>
</section>

@endsection

@push('scripts')
<script>
  /**
   * Validasi formulir tambah barang di sisi klien (JavaScript).
   * Menampilkan pesan error custom dengan highlight merah pada field yang tidak valid
   * sebelum form dikirim ke server.
   */

  const form = document.getElementById('form-tambah-barang');

  // Aturan validasi: { fieldId, label, aturan }
  const aturanValidasi = [
    {
      id: 'kode',
      label: 'Kode Barang',
      cek: (val) => {
        if (!val) return 'Kode barang wajib diisi.';
        if (val.length > 20) return 'Kode barang maksimal 20 karakter.';
        if (!/^[A-Za-z0-9\-]+$/.test(val)) return 'Kode barang hanya boleh huruf, angka, dan tanda hubung (-).';
        return null;
      },
    },
    {
      id: 'nama',
      label: 'Nama Barang',
      cek: (val) => {
        if (!val) return 'Nama barang wajib diisi.';
        if (val.length < 3) return 'Nama barang minimal 3 karakter.';
        if (val.length > 100) return 'Nama barang maksimal 100 karakter.';
        return null;
      },
    },
    {
      id: 'kategori',
      label: 'Kategori',
      cek: (val) => {
        if (!val) return 'Silakan pilih kategori barang.';
        return null;
      },
    },
    {
      id: 'stok',
      label: 'Stok',
      cek: (val) => {
        if (val === '') return 'Stok wajib diisi.';
        if (isNaN(val) || Number(val) < 0) return 'Stok tidak boleh bernilai negatif.';
        if (!Number.isInteger(Number(val))) return 'Stok harus berupa bilangan bulat.';
        return null;
      },
    },
    {
      id: 'harga',
      label: 'Harga',
      cek: (val) => {
        if (val === '') return 'Harga wajib diisi.';
        if (isNaN(val) || Number(val) < 1000) return 'Harga minimal Rp 1.000.';
        return null;
      },
    },
    {
      id: 'tanggal_masuk',
      label: 'Tanggal Masuk',
      cek: (val) => {
        if (!val) return 'Tanggal masuk wajib diisi.';
        return null;
      },
    },
  ];

  /**
   * Tampilkan pesan error di bawah field dan beri highlight merah pada input.
   */
  function tampilkanError(id, pesan) {
    const input = document.getElementById(id);
    if (!input) return;

    // Hapus pesan error lama jika ada
    hapusError(id);

    input.style.borderColor = '#ef4444';
    input.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.2)';

    const msg = document.createElement('small');
    msg.className      = 'error-message js-error';
    msg.style.color    = '#ef4444';
    msg.style.fontSize = '12px';
    msg.style.display  = 'block';
    msg.style.marginTop = '4px';
    msg.textContent    = pesan;

    input.parentElement.appendChild(msg);
  }

  /**
   * Hapus pesan error dan kembalikan style field ke kondisi normal.
   */
  function hapusError(id) {
    const input = document.getElementById(id);
    if (!input) return;

    input.style.borderColor = '';
    input.style.boxShadow   = '';

    const errorLama = input.parentElement.querySelector('.js-error');
    if (errorLama) errorLama.remove();
  }

  // Validasi real-time saat user meninggalkan field (blur)
  aturanValidasi.forEach(function (aturan) {
    const input = document.getElementById(aturan.id);
    if (!input) return;

    input.addEventListener('blur', function () {
      const pesan = aturan.cek(this.value.trim());
      if (pesan) {
        tampilkanError(aturan.id, pesan);
      } else {
        hapusError(aturan.id);
        // Tunjukkan tanda valid (hijau) bila benar
        this.style.borderColor = '#10b981';
        this.style.boxShadow   = '0 0 0 3px rgba(16,185,129,0.15)';
      }
    });

    // Reset style saat user mulai mengetik lagi
    input.addEventListener('input', function () {
      hapusError(aturan.id);
    });
  });

  // Validasi penuh saat form disubmit
  form.addEventListener('submit', function (event) {
    let adaError = false;

    aturanValidasi.forEach(function (aturan) {
      const input = document.getElementById(aturan.id);
      if (!input) return;

      const pesan = aturan.cek(input.value.trim());
      if (pesan) {
        tampilkanError(aturan.id, pesan);
        adaError = true;
      }
    });

    if (adaError) {
      event.preventDefault(); // Cegah submit jika ada error

      // Scroll ke field pertama yang error
      const pertama = form.querySelector('.js-error');
      if (pertama) {
        pertama.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });
</script>
@endpush

