# 🎮 RobuxRadit — Sistem Manajemen & Toko Roblox Digital

RobuxRadit adalah aplikasi web dinamis berbasis **Laravel 11** dan **SQLite** yang dirancang sebagai platform e-commerce dan sistem manajemen inventaris (CRUD) untuk barang digital Roblox (seperti Gamepass, Voucher Robux, dan Private Server). 

Proyek ini dibangun untuk memenuhi seluruh kriteria penilaian mata kuliah Pemrograman Berbasis Web (kecuali modul testing).

---

## 🚀 Fitur Utama Aplikasi

### 1. Frontend & UI/UX Responsif (HTML5 & CSS3)
*   Menggunakan elemen **HTML5 semantik** (`<section>`, `<article>`, `<main>`, `<header>`, `<nav>`, dll.) untuk struktur yang bersih dan SEO-friendly.
*   **CSS Kustom Variabel & Flexbox/Grid** untuk tata letak modern dan responsif yang menyesuaikan ukuran layar (Mobile, Tablet, Desktop).
*   **Dua Pilihan Tema** (Light Mode / Dark Mode) & Ukuran Font (Kecil, Normal, Besar) yang disimpan secara dinamis.

### 2. Interaktivitas JavaScript (DOM & Klien Sisi Validasi)
*   **Validasi Formulir Sisi Klien** di form Tambah Barang (dengan visual error, scroll otomatis ke error pertama, dan penanda sukses).
*   **Manipulasi DOM Dinamis** untuk merender tabel inventaris, menyembunyikan/menampilkan password, serta memperbarui kalkulator konversi Robux.
*   **Konfirmasi Interaktif** menggunakan dialog konfirmasi sebelum menghapus data barang.

### 3. Backend PHP & CRUD Database Relasional (Laravel Eloquent)
*   **Autentikasi Pengguna & Role**: Pembagian akses untuk **Admin** (Mengelola Inventaris & Verifikasi Transaksi) dan **Customer** (Melihat Katalog & Membeli/Checkout).
*   **CRUD Lengkap Inventaris Barang**: Create, Read, Update, Delete (dilengkapi fitur **Soft Delete**).
*   **CRUD Transaksi**: Alur pembelian barang oleh customer, upload bukti pembayaran, hingga persetujuan status transaksi oleh Admin.
*   **Unggah Foto Produk**: Penyimpanan file gambar produk menggunakan Laravel Storage.

### 4. Cookies & Session
*   **Session State**: Manajemen autentikasi berbasis session (Laravel Breeze) serta penyimpanan data sementara seperti flash message, session preferensi (tema/font), dan pencatatan sesi kunjungan admin.

### 5. Komunikasi Asinkronus (AJAX & API Integrasi)
*   **Live Search Real-time**: Pencarian produk di halaman pengelolaan admin menggunakan Fetch API (AJAX) dengan fungsi *debounce* untuk efisiensi query database.
*   **Quick Add (Tambah Cepat)**: Penambahan barang baru secara langsung melalui form popup (modal) tanpa reload halaman.
*   **Eksternal API Kurs USD-IDR**: Integrasi secara langsung dengan `frankfurter.dev` untuk mengambil kurs USD ke IDR secara real-time dan menampilkannya di dashboard admin.
*   **Reset Kunjungan via AJAX**: Manajemen data kunjungan admin yang di-reset tanpa memuat ulang seluruh halaman.

---

## 🛠️ Tech Stack & Kebutuhan Sistem

*   **PHP** >= 8.2
*   **Framework**: Laravel 11.x
*   **Database**: SQLite
*   **Frontend**: Vanilla HTML5, Vanilla CSS3 (Kustom), JavaScript ES6+

---

## 📦 Panduan Instalasi & Setup Lokal

1.  **Clone Repositori & Masuk ke Direktori Proyek**
    ```bash
    git clone <repository-url>
    cd Robuxraditt
    ```

2.  **Instal Dependensi PHP (Composer)**
    ```bash
    composer install
    ```

3.  **Instal & Compile Dependensi Node (Vite)**
    ```bash
    npm install
    npm run build
    ```
    *(atau jalankan `npm run dev` untuk mode pengembangan).*

4.  **Konfigurasi Environment File**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Secara default, Laravel 11 menggunakan database SQLite. Pastikan konfigurasi database di file `.env` mengarah ke SQLite:
    ```env
    DB_CONNECTION=sqlite
    # DB_DATABASE akan otomatis mengarah ke database/database.sqlite
    ```

5.  **Jalankan Migrasi Database dan Seeder**
    Jalankan perintah ini untuk membuat tabel-tabel database beserta data awal (seperti admin default dan daftar barang):
    ```bash
    php artisan migrate --seed
    ```

6.  **Membuat Link Symbolic Storage**
    Hubungkan folder storage agar foto produk yang diunggah dapat diakses oleh publik:
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Local Development Server**
    ```bash
    php artisan serve
    ```
    Buka `http://127.0.0.1:8000` di web browser Anda.

---

## 🔑 Akun Demo Pengujian

Untuk mempermudah pengujian fitur-fitur ber-role khusus, Anda dapat menggunakan akun bawaan berikut:

### 1. Akun Admin (Pengelola Inventaris)
*   **Email**: `rayditarifan@gmail.com`
*   **Password**: `Pristine123!`
*   **Fitur Akses**: Dashboard admin dengan kurs USD real-time, menu Pengelolaan (CRUD Barang + Live Search + Quick Add), Verifikasi Transaksi, dan Preferensi Sistem.

### 2. Akun Customer (Pembeli)
*   **Registrasi Mandiri**: Dapat mendaftar langsung melalui menu **Daftar** di pojok kanan atas halaman katalog.
*   **Fitur Akses**: Dashboard customer, melakukan Checkout/Pembelian, mengunggah bukti pembayaran, melihat riwayat transaksi, dan kalkulator Robux.
