@extends('layouts.app')

@section('title', 'Kontak - RobuxRadit')

@section('content')

@php
  $contactFile = storage_path('app/contact.json');
  $contact = [
      'email' => 'support@robuxradit.com',
      'phone' => '+62 825-7511-930',
      'address' => 'Jakarta, Indonesia (Operasional Online)',
      'hours' => 'Layanan pelanggan kami aktif setiap hari mulai pukul 08:00 hingga 22:00 WIB. Pertanyaan di luar jam kerja tetap akan kami tampung dan kami balas sesegera mungkin pada hari berikutnya.'
  ];
  if (file_exists($contactFile)) {
      $savedContact = json_decode(file_get_contents($contactFile), true);
      if (is_array($savedContact)) {
          $contact = array_merge($contact, $savedContact);
      }
  }

  // Clean phone to generate whatsapp link
  $cleanPhone = preg_replace('/[^0-9]/', '', $contact['phone']);
  if (str_starts_with($cleanPhone, '0')) {
      $cleanPhone = '62' . substr($cleanPhone, 1);
  }
@endphp

<section class="hero">
  <div>
    <span class="hero-tag">Bantuan • Hubungi Kami</span>
    <h2>Hubungi Kami</h2>
    <p>
      Ada pertanyaan mengenai stok, harga, atau transaksi? Layanan pelanggan kami siap membantu Anda kapan saja.
    </p>
  </div>
</section>

<!-- Display View Only (Visible to both Admin and Customer) -->
<section class="dashboard-section">
  <div class="section-heading">
    <h2>Informasi Kontak @if(auth()->check() && auth()->user()->role === 'admin') (Pratinjau) @endif</h2>
    <p>Silakan hubungi kami melalui salah satu kontak resmi di bawah ini untuk respon yang cepat.</p>
  </div>

  <div class="detail-grid">
    <div class="card detail-card">
      <h3>Data Kontak</h3>
      <table class="detail-table">
        <tr>
          <th>Email</th>
          <td id="view-email">{{ $contact['email'] }}</td>
        </tr>
        <tr>
          <th>WhatsApp</th>
          <td>
            <a id="view-whatsapp-link" href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" style="color: #25d366; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
              Chat WhatsApp (<span id="view-phone">{{ $contact['phone'] }}</span>)
            </a>
          </td>
        </tr>
        <tr>
          <th>Telepon</th>
          <td id="view-phone-alt">{{ $contact['phone'] }}</td>
        </tr>
        <tr>
          <th>Alamat</th>
          <td id="view-address">{{ $contact['address'] }}</td>
        </tr>
      </table>
    </div>

    <div class="card detail-card">
      <h3>Jam Layanan</h3>
      <p id="view-hours">
        {{ $contact['hours'] }}
      </p>
    </div>
  </div>
</section>

<!-- Admin Edit Form (Visible only to Admin) -->
@if(auth()->check() && auth()->user()->role === 'admin')
  <section class="dashboard-section preference-section" style="margin-top: 30px;">
    <div class="section-heading">
      <h2>Pengaturan Kontak Toko (Admin)</h2>
      <p>Ubah informasi kontak resmi Toko RobuxRadit yang ditampilkan ke customer.</p>
    </div>

    <form id="form-kontak" class="preference-form">
      <div class="form-group" style="margin-bottom: 15px;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 6px;">Email Support</label>
        <input type="email" id="email" name="email" value="{{ $contact['email'] }}" required style="padding: 10px; border-radius: 8px; border: 1.5px solid var(--border); width: 100%; max-width: 400px; background: var(--bg); color: var(--text);">
      </div>

      <div class="form-group" style="margin-bottom: 15px;">
        <label for="phone" style="display: block; font-weight: 600; margin-bottom: 6px;">No. Telepon / WhatsApp (Contoh: +62 825-7511-930 atau 0825-7511-930)</label>
        <input type="text" id="phone" name="phone" value="{{ $contact['phone'] }}" required style="padding: 10px; border-radius: 8px; border: 1.5px solid var(--border); width: 100%; max-width: 400px; background: var(--bg); color: var(--text);">
      </div>

      <div class="form-group" style="margin-bottom: 15px;">
        <label for="address" style="display: block; font-weight: 600; margin-bottom: 6px;">Alamat Operasional</label>
        <input type="text" id="address" name="address" value="{{ $contact['address'] }}" required style="padding: 10px; border-radius: 8px; border: 1.5px solid var(--border); width: 100%; max-width: 400px; background: var(--bg); color: var(--text);">
      </div>

      <div class="form-group" style="margin-bottom: 15px;">
        <label for="hours" style="display: block; font-weight: 600; margin-bottom: 6px;">Jam Layanan / Deskripsi</label>
        <textarea id="hours" name="hours" required rows="3" style="padding: 10px; border-radius: 8px; border: 1.5px solid var(--border); width: 100%; max-width: 500px; background: var(--bg); color: var(--text); resize: vertical; font-family: inherit;">{{ $contact['hours'] }}</textarea>
      </div>

      <div class="preference-actions" style="margin-top: 20px;">
        <button type="submit" class="btn btn-primary" style="background: #22c55e;">Simpan Kontak</button>
      </div>
    </form>

    <div id="kontak-message" class="ajax-message" style="margin-top: 15px;"></div>
  </section>

  @push('scripts')
  <script>
    const CSRF_PREF = document.querySelector('meta[name="csrf-token"]').content;
    const formKontak = document.getElementById('form-kontak');
    const kontakMessage = document.getElementById('kontak-message');

    formKontak?.addEventListener('submit', async function (event) {
      event.preventDefault();

      const formData = new FormData(formKontak);
      const payload = Object.fromEntries(formData.entries());

      kontakMessage.innerHTML = '<div class="ajax-loading">Menyimpan kontak...</div>';

      try {
        const response = await fetch('{{ route('admin.kontak.update') }}', {
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

        // Live update the view-only preview cards immediately
        document.getElementById('view-email').textContent = payload.email;
        let cleanPhone = payload.phone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
          cleanPhone = '62' + cleanPhone.slice(1);
        }
        document.getElementById('view-whatsapp-link').href = `https://wa.me/${cleanPhone}`;
        document.getElementById('view-phone').textContent = payload.phone;
        document.getElementById('view-phone-alt').textContent = payload.phone;
        document.getElementById('view-address').textContent = payload.address;
        document.getElementById('view-hours').textContent = payload.hours;

        kontakMessage.innerHTML = `<div class="alert-success">${json.message}</div>`;
      } catch (error) {
        kontakMessage.innerHTML = `<div class="alert-error">Gagal menyimpan kontak: ${error.message}</div>`;
      }
    });
  </script>
  @endpush
@endif

@endsection
