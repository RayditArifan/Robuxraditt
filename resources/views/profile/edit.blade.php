@extends('layouts.app')

@section('title', 'Profil Saya — Toko RobuxRadit')

@section('content')

<style>
    .profile-page {
        max-width: 680px;
        margin: 0 auto;
    }

    .profile-avatar-lg {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .profile-hero-info h2 {
        margin: 0 0 4px;
        font-size: 1.4rem;
    }

    .profile-hero-info p {
        margin: 0;
        font-size: 0.875rem;
        opacity: 0.7;
    }

    .profile-hero-row {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 32px;
    }

    .profile-section-card {
        background: var(--surface, #fff);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 20px;
    }

    .profile-section-card h3 {
        margin: 0 0 6px;
        font-size: 1.05rem;
    }

    .profile-section-card .section-sub {
        font-size: 0.85rem;
        opacity: 0.6;
        margin: 0 0 22px;
    }

    .profile-form-group {
        margin-bottom: 18px;
    }

    .profile-form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 6px;
        opacity: 0.8;
    }

    .profile-form-group input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 10px;
        font-size: 0.95rem;
        background: var(--bg, #f8fafc);
        color: inherit;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .profile-form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        background: var(--surface, #fff);
    }

    .profile-form-group .field-error {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 4px;
        display: block;
    }

    .profile-btn-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 6px;
    }

    .profile-success-badge {
        font-size: 0.85rem;
        color: #16a34a;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .badge-role {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #dbeafe;
        color: #1d4ed8;
    }

    html.dark .profile-section-card {
        background: #1e293b;
        border-color: #334155;
    }

    html.dark .profile-form-group input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }

    html.dark .profile-form-group input:focus {
        background: #1e293b;
        border-color: #60a5fa;
    }

    html.dark .badge-role {
        background: #1e3a5f;
        color: #93c5fd;
    }
</style>

<section class="hero">
    <div>
        <span class="hero-tag">Akun • Pengaturan Profil</span>
        <h2>Profil Saya</h2>
        <p>Kelola informasi akun dan keamanan password kamu.</p>
    </div>
</section>

<section class="dashboard-section">
    <div class="profile-page">

        {{-- Hero avatar row --}}
        <div class="profile-hero-row">
            <div class="profile-avatar-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="profile-hero-info">
                <h2>{{ auth()->user()->name }}</h2>
                <p>{{ auth()->user()->email }}</p>
                <span class="badge-role">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('status') === 'profile-updated')
            <div class="alert-success" style="margin-bottom:16px; padding:12px 16px; border-radius:10px;">
                ✅ Informasi profil berhasil diperbarui.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert-success" style="margin-bottom:16px; padding:12px 16px; border-radius:10px;">
                ✅ Password berhasil diperbarui.
            </div>
        @endif

        {{-- === FORM UPDATE INFO PROFIL === --}}
        <div class="profile-section-card">
            <h3>Informasi Profil</h3>
            <p class="section-sub">Perbarui nama dan alamat email akun kamu.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="profile-form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}"
                           required autocomplete="name">
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           required autocomplete="email">
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-btn-row">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- === FORM GANTI PASSWORD === --}}
        <div class="profile-section-card">
            <h3>Ganti Password</h3>
            <p class="section-sub">Gunakan password yang kuat dan unik untuk keamanan akun.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="profile-form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password"
                           autocomplete="current-password"
                           placeholder="Masukkan password saat ini">
                    @error('current_password', 'updatePassword')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password"
                           autocomplete="new-password"
                           placeholder="Minimal 8 karakter">
                    @error('password', 'updatePassword')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password"
                           placeholder="Ulangi password baru">
                    @error('password_confirmation', 'updatePassword')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-btn-row">
                    <button type="submit" class="btn btn-primary">Perbarui Password</button>
                </div>
            </form>
        </div>

    </div>
</section>

@endsection
