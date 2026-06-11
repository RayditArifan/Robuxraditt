@extends('layouts.app')

@section('title', 'Profil — Inventaris RobuxRadit')

@section('content')

<section class="hero">
  <div>
    <span class="hero-tag">Profil • Informasi Akun</span>
    <h2>Profil Pengguna</h2>
    <p>
      Kamu sedang login sebagai <strong>{{ $username }}</strong>.
      Data profil ini dikirim dari controller dan ditampilkan via Blade.
    </p>
  </div>
</section>

<section class="profile-section">
  <div class="profile-card">

    <div class="profile-avatar">
      {{ strtoupper(substr($username, 0, 2)) }}
    </div>

    <div class="profile-info">
      <h2>{{ $profileData['namaLengkap'] }}</h2>
      <p class="profile-role">{{ $profileData['role'] }}</p>
    </div>

  </div>

    <div class="detail-grid">

      <div class="card detail-card">
        <h3>Informasi Akun</h3>
        <table class="detail-table">
          <tr>
            <th>Username</th>
            <td>{{ $profileData['username'] }}</td>
          </tr>
          <tr>
            <th>Nama Lengkap</th>
            <td>{{ $profileData['namaLengkap'] }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $profileData['email'] }}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td>{{ $profileData['role'] }}</td>
          </tr>
          <tr>
            <th>Bergabung</th>
            <td>{{ $profileData['bergabung'] }}</td>
          </tr>
        </table>
      </div>

      <div class="card detail-card">
        <h3>Keahlian & Tanggung Jawab</h3>
        <ul class="skills-list">
          @foreach ($profileData['keahlian'] as $keahlian)
            <li>
              <span class="skill-dot"></span>
              {{ $keahlian }}
            </li>
          @endforeach
        </ul>
      </div>

    </div>

</section>

@endsection
