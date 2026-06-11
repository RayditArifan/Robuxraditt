@extends('layouts.app')

@section('title', 'Preferensi Tampilan - RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">P1 Nomor 3 - Cookies - Dark Mode</span>
    <h2>Preferensi Tampilan RobuxRadit</h2>
    <p>Halo <strong>{{ $username }}</strong>, atur tema dan ukuran font inventaris..</p>
  </div>
</section>

<section class="dashboard-section preference-section">
  <div class="section-heading">
    <h2>Form Preferensi</h2>
    <p>Pilih tema dan ukuran font tampilan.</p>
  </div>

  <form id="form-preferensi" class="preference-form">
    <div class="form-group">
      <label for="tema">Tema</label>
      <select id="tema" name="tema" required>
        <option value="light" @selected($tema === 'light')>Light</option>
        <option value="dark" @selected($tema === 'dark')>Dark</option>
        <option value="system" @selected($tema === 'system')>System</option>
      </select>
    </div>

    <div class="form-group">
      <label for="ukuran_font">Ukuran Font</label>
      <select id="ukuran_font" name="ukuran_font" required>
        <option value="kecil" @selected($ukuranFont === 'kecil')>Kecil</option>
        <option value="normal" @selected($ukuranFont === 'normal')>Normal</option>
        <option value="besar" @selected($ukuranFont === 'besar')>Besar</option>
      </select>
    </div>

    <div class="preference-actions">
      <button type="submit" class="btn btn-primary">Simpan Preferensi</button>
    </div>
  </form>

  <div id="preferensi-message" class="ajax-message"></div>
</section>

@endsection

@push('scripts')
<script>
  const CSRF_PREF = document.querySelector('meta[name="csrf-token"]').content;
  const formPreferensi = document.getElementById('form-preferensi');
  const preferensiMessage = document.getElementById('preferensi-message');

  formPreferensi?.addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(formPreferensi);
    const payload = Object.fromEntries(formData.entries());

    preferensiMessage.innerHTML = '<div class="ajax-loading">Menyimpan preferensi...</div>';

    try {
      const response = await fetch('{{ route('preferensi.simpan') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_PREF,
        },
        body: JSON.stringify(payload),
      });

      const json = await response.json();

      if (!response.ok) {
        throw new Error(json.message || `HTTP ${response.status}`);
      }

      if (payload.tema === 'dark') {
        document.documentElement.classList.add('dark');
      } else if (payload.tema === 'system') {
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', systemDark);
      } else {
        document.documentElement.classList.remove('dark');
      }

      document.documentElement.dataset.font = payload.ukuran_font;

      preferensiMessage.innerHTML = `<div class="alert-success">${json.message}</div>`;
    } catch (error) {
      preferensiMessage.innerHTML = `<div class="alert-error">Gagal menyimpan preferensi: ${error.message}</div>`;
    }
  });
</script>
@endpush
