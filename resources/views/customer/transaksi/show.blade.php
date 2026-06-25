@extends('layouts.app')

@section('title', 'Detail Transaksi — RobuxRadit')

@section('content')
<section class="hero">
    <div>
        <h2>Detail Transaksi</h2>
        <p>Silakan tinjau detail pembelian Anda dan lakukan konfirmasi pembayaran untuk memproses pesanan.</p>
    </div>
</section>

<section class="dashboard-section" style="max-width: 600px; margin: 0 auto 24px;">
    <div class="card detail-product-card" style="padding:28px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid var(--border); padding-bottom:18px; margin-bottom:20px;">
            <div>
                <span class="stat-label" style="display:block; margin-bottom: 4px;">STATUS</span>
                @if ($transaksi->status === 'belum_bayar')
                    <span class="badge" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#fef2f2; color:#dc2626;">Belum Bayar</span>
                @elseif ($transaksi->status === 'pending')
                    <span class="badge badge-low" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px;">Menunggu Verifikasi Admin</span>
                @elseif ($transaksi->status === 'proses')
                    <span class="badge" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#eff6ff; color:#2563eb; border:1px solid #dbeafe;">Pembayaran Diterima / Diproses</span>
                @else
                    <span class="badge badge-safe" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#ecfdf5; color:#0f766e;">Transaksi Selesai</span>
                @endif
            </div>
            <div style="text-align:right;">
                <span class="stat-label" style="display:block;">TANGGAL</span>
                <strong>{{ $transaksi->created_at->format('d M Y, H:i') }}</strong>
            </div>
        </div>

        <div style="margin-bottom:24px;">
            <h3 style="color:var(--primary); margin-bottom:12px; font-size:18px;">Rincian Item</h3>
            <table class="detail-table" style="width:100%;">
                <tr>
                    <th style="width:40%;">Nama Produk</th>
                    <td><strong>{{ $transaksi->barang->nama }}</strong></td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td><span class="badge-kategori">{{ $transaksi->barang->kategori }}</span></td>
                </tr>
                <tr>
                    <th>Harga Satuan</th>
                    <td>Rp {{ number_format($transaksi->barang->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Jumlah Beli</th>
                    <td><strong>{{ $transaksi->jumlah }} unit</strong></td>
                </tr>
                <tr>
                    <th>Username Roblox</th>
                    <td><strong style="color:var(--primary);">{{ $transaksi->username_roblox }}</strong></td>
                </tr>
            </table>
        </div>

        <div style="background:#f8fafc; border-radius:12px; padding:18px; border:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <span class="stat-label" style="display:block; font-size:12px; text-transform:uppercase;">Total Pembayaran</span>
                <strong class="money-text" style="font-size:24px; color:var(--primary);">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
            </div>
        </div>

        @if ($transaksi->status === 'belum_bayar')
            <!-- Payment Instructions -->
            <div style="background:#f8fafc; border-radius:12px; padding:18px; border:1px solid var(--border); margin-bottom:24px;">
                <h4 style="color:var(--primary); font-size:15px; margin-bottom:10px; font-weight:700;">💳 Petunjuk Pembayaran</h4>
                <p style="font-size:14px; margin-bottom:8px; color:var(--text);">Silakan transfer total pembayaran ke rekening berikut:</p>
                @php
                    $contactFile = storage_path('app/contact.json');
                    $gopayNumber = '628257511930';
                    if (file_exists($contactFile)) {
                        $savedContact = json_decode(file_get_contents($contactFile), true);
                        if (!empty($savedContact['phone'])) {
                            $gopayNumber = $savedContact['phone'];
                        } elseif (!empty($savedContact['whatsapp'])) {
                            $gopayNumber = $savedContact['whatsapp'];
                        }
                    }
                @endphp
                <div style="background:white; border-radius:8px; padding:12px; border:1px solid var(--border); font-size:14px; line-height:1.6; color:var(--text);">
                    <strong>Bank Mandiri</strong><br>
                    No. Rekening: <strong>123-456-789-000</strong><br>
                    Atas Nama: <strong>Toko RobuxRadit</strong>
                    <hr style="margin: 8px 0; border: none; border-top: 1px solid var(--border);">
                    <strong>Go-Pay</strong><br>
                    No. HP: <strong>{{ $gopayNumber }}</strong><br>
                    Atas Nama: <strong>Toko RobuxRadit</strong>
                </div>
                <p style="font-size:13px; color:var(--muted); margin-top:8px;">Setelah melakukan transfer, silakan upload bukti transfer di form di bawah ini.</p>
            </div>

            <!-- Upload Receipt Form -->
            <form action="{{ route('customer.transaksi.proses', $transaksi) }}" method="POST" enctype="multipart/form-data" style="margin-bottom:24px; padding:20px; border:1.5px solid var(--border); border-radius:16px; background:#f8fafc;">
                @csrf
                <div class="form-group" style="margin-bottom:16px;">
                    <label for="bukti_pembayaran" style="font-weight:700; font-size:14px; margin-bottom:8px; display:block; color:var(--text);">Upload Bukti Pembayaran</label>
                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/png, image/jpeg, image/jpg" required style="padding:10px; border-radius:8px; border:1.5px solid #cbd5e1; width:100%; background:white;">
                    <span style="font-size:12px; color:var(--muted); margin-top:4px; display:block;">Format: JPG, JPEG, PNG (Maks. 2MB)</span>
                </div>
                <button type="submit" class="btn btn-primary btn-full" style="background:#22c55e; color:white; font-weight:700; border:none; cursor:pointer; padding: 12px;" onclick="return confirm('Apakah Anda yakin ingin mengirim bukti pembayaran ini?')">
                    Upload & Konfirmasi Pembayaran
                </button>
            </form>
        @else
            <!-- Display Receipt Preview -->
            <div style="background:#f8fafc; border-radius:12px; padding:18px; border:1px solid var(--border); margin-bottom:24px; text-align:center;">
                <h4 style="color:var(--primary); font-size:15px; margin-bottom:12px; font-weight:700;">📷 Bukti Pembayaran Anda</h4>
                @if ($transaksi->bukti_pembayaran)
                    <div style="max-width:300px; margin:0 auto 12px; border-radius:8px; border:1px solid var(--border); overflow:hidden; background:white;">
                        <a href="#" class="preview-trigger" data-src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" data-caption="Bukti Pembayaran Anda" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="width:100%; height:auto; display:block; object-fit:contain; max-height:240px; margin: 0 auto;">
                        </a>
                    </div>
                @else
                    <p style="color:var(--muted); font-size:13px; margin-bottom:12px;">Bukti pembayaran tidak tersedia.</p>
                @endif
                
                @if ($transaksi->status === 'pending')
                    <p style="font-size:13px; color:var(--warning); font-weight:600;">Bukti pembayaran telah dikirim. Menunggu verifikasi admin.</p>
                @elseif ($transaksi->status === 'proses')
                    <p style="font-size:13px; color:#2563eb; font-weight:600;">Pembayaran telah diterima! Admin sedang memproses pembelian Robux/Gamepass Anda.</p>
                @else
                    <p style="font-size:13px; color:var(--success); font-weight:600;">Pembayaran telah diverifikasi oleh admin. Transaksi selesai.</p>
                @endif
            </div>
        @endif

        <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-top: 10px;">
            <a href="{{ route('customer.transaksi.list') }}" class="quick-card" style="text-decoration:none; padding:10px 18px; border-radius:8px; font-weight:600; background: var(--border); color: var(--text); border: 1px solid var(--border);">
                ← Riwayat Transaksi
            </a>
        </div>
    </div>
</section>

{{-- Modal HTML --}}
<div id="imageModal" class="image-modal">
    <span class="close-modal" aria-label="Tutup">&times;</span>
    <div class="image-modal-content-wrapper">
        <img class="image-modal-content" id="modalImg" alt="Bukti Transfer">
        <div id="caption"></div>
    </div>
</div>

@push('styles')
<style>
/* Style the Image Modal */
.image-modal {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    background-color: rgba(15, 23, 42, 0.65); /* Slate-900 with opacity */
    backdrop-filter: blur(12px); /* Glassmorphism blur */
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-modal.show {
    display: flex;
    opacity: 1;
}

/* Modal Content (Image) */
.image-modal-content-wrapper {
    position: relative;
    width: 90%;
    max-width: 650px;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    padding: 20px;
    transform: scale(0.95);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.image-modal.show .image-modal-content-wrapper {
    transform: scale(1);
}

/* Dark mode adjustments */
.dark .image-modal-content-wrapper {
    background: rgba(30, 41, 59, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.image-modal-content {
    display: block;
    width: 100%;
    height: auto;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 12px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 100%;
    text-align: center;
    color: #0f172a;
    font-weight: 700;
    padding: 16px 0 0;
    font-size: 16px;
}

.dark #caption {
    color: #f8fafc;
}

/* The Close Button */
.close-modal {
    position: absolute;
    top: 20px;
    right: 20px;
    color: #f1f5f9;
    font-size: 32px;
    font-weight: 300;
    transition: all 0.2s ease;
    cursor: pointer;
    z-index: 10000;
    background: rgba(15, 23, 42, 0.5);
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    line-height: 1;
}

.close-modal:hover {
    color: #ffffff;
    background: rgba(15, 23, 42, 0.8);
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');
    const captionText = document.getElementById('caption');
    const closeModal = document.querySelector('.close-modal');

    document.querySelectorAll('.preview-trigger').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = "flex";
            // Wait for display change to apply transition class
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            modalImg.src = this.getAttribute('data-src');
            captionText.textContent = this.getAttribute('data-caption') || 'Bukti Transfer';
        });
    });

    const hideModal = () => {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = "none";
        }, 300); // match transitions duration
    };

    closeModal.addEventListener('click', hideModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && modal.classList.contains('show')) {
            hideModal();
        }
    });
});
</script>
@endpush
@endsection
