@extends('layouts.app')

@section('title', 'Riwayat Transaksi — RobuxRadit')

@section('content')
<section class="hero">
    <div>
        <span class="hero-tag">Aktivitas Akun • Pembelian</span>
        <h2>Riwayat Transaksi Anda</h2>
        <p>Tinjau seluruh daftar transaksi pembelian item game Anda di Toko RobuxRadit.</p>
    </div>
</section>

<section class="table-section">
    <div class="section-heading">
        <h2>Daftar Transaksi</h2>
        <p>Seluruh riwayat transaksi yang Anda lakukan tercatat di bawah ini.</p>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Transaksi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $transaksi)
                    <tr>
                        <td>#{{ $transaksi->id }}</td>
                        <td><strong>{{ $transaksi->barang->nama }}</strong></td>
                        <td><span class="badge-kategori">{{ $transaksi->barang->kategori }}</span></td>
                        <td>{{ $transaksi->jumlah }} unit</td>
                        <td><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                        <td>{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if ($transaksi->status === 'belum_bayar')
                                <span class="badge" style="font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fee2e2;">Belum Bayar</span>
                            @elseif ($transaksi->status === 'pending')
                                <span class="badge badge-low" style="font-weight:700;">Pending</span>
                            @elseif ($transaksi->status === 'proses')
                                <span class="badge" style="font-weight:700; background:#eff6ff; color:#2563eb; border:1px solid #dbeafe;">Diproses</span>
                            @else
                                <span class="badge badge-safe" style="font-weight:700; background:#ecfdf5; color:#0f766e;">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('customer.transaksi.show', $transaksi) }}" class="action-btn edit" style="text-decoration:none; background:#dbeafe; color:var(--primary); padding:6px 12px; border-radius:8px; font-weight:700;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state" style="text-align:center; padding:30px; color:var(--muted);">
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transaksis->hasPages())
        <div class="pagination-box" style="margin-top:20px;">
            {{ $transaksis->links() }}
        </div>
    @endif
</section>
@endsection
