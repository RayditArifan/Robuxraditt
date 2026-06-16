@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $transaksi->id . ' — RobuxRadit')

@section('content')
<section class="hero">
    <div>
        <span class="hero-tag">Admin • Invoice #{{ $transaksi->id }}</span>
        <h2>Detail Transaksi</h2>
        <p>Tinjau detail pesanan, bukti pembayaran, dan lakukan tindakan persetujuan di bawah ini.</p>
    </div>
</section>

<section class="dashboard-section" style="max-width: 700px; margin: 0 auto 24px;">
    <div class="card detail-product-card" style="padding:28px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid var(--border); padding-bottom:18px; margin-bottom:20px;">
            <div>
                <span class="stat-label" style="display:block; margin-bottom: 4px;">STATUS</span>
                @if ($transaksi->status === 'belum_bayar')
                    <span class="badge" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#fef2f2; color:#dc2626; border:1px solid #fee2e2;">Belum Bayar</span>
                @elseif ($transaksi->status === 'pending')
                    <span class="badge badge-low" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px;">Menunggu Verifikasi Admin</span>
                @elseif ($transaksi->status === 'proses')
                    <span class="badge" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#eff6ff; color:#2563eb; border:1px solid #dbeafe;">Pembayaran Diterima / Diproses</span>
                @else
                    <span class="badge badge-safe" style="font-size:13px; font-weight:700; padding:6px 12px; display:inline-block; border-radius:999px; background:#ecfdf5; color:#0f766e;">Transaksi Selesai</span>
                @endif
            </div>
            <div style="text-align:right;">
                <span class="stat-label" style="display:block;">TANGGAL TRANSAKSI</span>
                <strong>{{ $transaksi->created_at->format('d M Y, H:i') }}</strong>
            </div>
        </div>

        <div style="margin-bottom:24px;">
            <h3 style="color:var(--primary); margin-bottom:12px; font-size:18px;">Informasi Akun & Pembeli</h3>
            <table class="detail-table" style="width:100%;">
                <tr>
                    <th style="width:40%;">Nama Akun Pembeli</th>
                    <td><strong>{{ $transaksi->user->name }}</strong></td>
                </tr>
                <tr>
                    <th>Email Pembeli</th>
                    <td>{{ $transaksi->user->email }}</td>
                </tr>
                <tr>
                    <th>Username Roblox</th>
                    <td><span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:13px; font-weight:700; display:inline-block; border-radius:6px; padding:4px 8px;">{{ $transaksi->username_roblox }}</span></td>
                </tr>
            </table>
        </div>

        <div style="margin-bottom:24px;">
            <h3 style="color:var(--primary); margin-bottom:12px; font-size:18px;">Rincian Item Pembelian</h3>
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
            </table>
        </div>

        <div style="background:#f8fafc; border-radius:12px; padding:18px; border:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <span class="stat-label" style="display:block; font-size:12px; text-transform:uppercase;">Total Harga Pembayaran</span>
                <strong class="money-text" style="font-size:24px; color:var(--primary);">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Display Receipt Preview -->
        <div style="background:#f8fafc; border-radius:12px; padding:18px; border:1px solid var(--border); margin-bottom:24px; text-align:center;">
            <h4 style="color:var(--primary); font-size:15px; margin-bottom:12px; font-weight:700;">📷 Bukti Pembayaran</h4>
            @if ($transaksi->bukti_pembayaran)
                <div style="max-width:300px; margin:0 auto 12px; border-radius:8px; border:1px solid var(--border); overflow:hidden; background:white;">
                    <a href="#" class="preview-trigger" data-src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" data-caption="Bukti Pembayaran Customer" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="width:100%; height:auto; display:block; object-fit:contain; max-height:240px; margin: 0 auto;">
                    </a>
                </div>
            @else
                <p style="color:var(--muted); font-size:13px; margin-bottom:12px;">Bukti pembayaran belum diunggah oleh customer.</p>
            @endif
        </div>

        <!-- Admin Actions Section -->
        <div style="background:#f8fafc; border-radius:12px; padding:20px; border:1.5px solid var(--border); margin-bottom:24px;">
            <h4 style="color:var(--primary); font-size:15px; margin-bottom:12px; font-weight:700;">⚙️ Tindakan Admin</h4>
            
            @if ($transaksi->status === 'pending')
                <div style="display:flex; gap:12px;">
                    <form action="{{ route('admin.transaksi.setujui', $transaksi) }}" method="POST" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:14px; background:#22c55e; color:white; border-radius:8px; font-weight:700; border:none; cursor:pointer;" onclick="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini? Stok barang akan berkurang dan status akan berubah menjadi Diproses.')">
                            Setujui Pembayaran
                        </button>
                    </form>
                    <form action="{{ route('admin.transaksi.tolak', $transaksi) }}" method="POST" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="width:100%; padding:12px; font-size:14px; background:#ef4444; color:white; border-radius:8px; font-weight:700; border:none; cursor:pointer;" onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini? Bukti pembayaran akan dihapus.')">
                            Tolak Transaksi
                        </button>
                    </form>
                </div>
            @elseif ($transaksi->status === 'proses')
                <form action="{{ route('admin.transaksi.selesaikan', $transaksi) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:14px; background:#3b82f6; color:white; border-radius:8px; font-weight:700; border:none; cursor:pointer;" onclick="return confirm('Apakah Anda yakin transaksi ini sudah selesai dikirim (Robux/Gamepass sudah dibeli)?')">
                        Tandai Selesai & Kirim Robux
                    </button>
                </form>
            @else
                <p style="color:var(--muted); font-size:13px; text-align:center; margin:0;">Tidak ada tindakan yang diperlukan untuk status saat ini.</p>
            @endif
        </div>

        <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-top: 10px;">
            <a href="{{ route('admin.transaksi.list') }}" class="quick-card" style="text-decoration:none; padding:10px 18px; border-radius:8px; font-weight:600; background: var(--border); color: var(--text); border: 1px solid var(--border);">
                ← Kembali ke Daftar Transaksi
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
