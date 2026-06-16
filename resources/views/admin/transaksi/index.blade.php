@extends('layouts.app')

@section('title', 'Transaksi Toko — RobuxRadit')

@section('content')
<section class="hero">
    <div>
        <span class="hero-tag">Manajemen Pesanan</span>
        <h2>Transaksi Toko</h2>
        <p>Halo <strong>{{ $username }}</strong>, di halaman ini kamu bisa meninjau, menyetujui, atau menolak bukti pembayaran transaksi dari customer.</p>
    </div>
</section>

<section class="table-section">
    <div class="section-heading">
        <h2>Semua Transaksi</h2>
        <p>Tinjau dan verifikasi pembayaran dari customer di bawah ini.</p>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Customer</th>
                    <th>Username Roblox</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $transaksi)
                    <tr>
                        <td>#{{ $transaksi->id }}</td>
                        <td>
                            <strong>{{ $transaksi->user->name }}</strong><br>
                            <small style="color:var(--muted)">{{ $transaksi->user->email }}</small>
                        </td>
                        <td>
                            @if($transaksi->username_roblox)
                                <span class="badge" style="background:#e0f2fe; color:#0369a1; font-size:13px; font-weight:700; display:inline-block; border-radius:6px; padding:4px 8px;">
                                    {{ $transaksi->username_roblox }}
                                </span>
                            @else
                                <span style="color:var(--muted); font-size:13px;">-</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $transaksi->barang->nama }}</strong><br>
                            <span class="badge-kategori">{{ $transaksi->barang->kategori }}</span>
                        </td>
                        <td>{{ $transaksi->jumlah }} unit</td>
                        <td><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                        <td>
                            @if ($transaksi->status === 'belum_bayar')
                                <span class="badge" style="font-weight:700; background:#fef2f2; color:#dc2626; border:1px solid #fee2e2;">Belum Bayar</span>
                            @elseif ($transaksi->status === 'pending')
                                <span class="badge badge-low" style="font-weight:700;">Pending</span>
                            @elseif ($transaksi->status === 'proses')
                                <span class="badge" style="font-weight:700; background:#eff6ff; color:#2563eb; border:1px solid #dbeafe;">Pembayaran Diterima</span>
                            @else
                                <span class="badge badge-safe" style="font-weight:700; background:#ecfdf5; color:#0f766e;">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.transaksi.show', $transaksi) }}" class="btn btn-primary" style="padding:6px 12px; font-size:13px; font-weight:700; border-radius:6px; text-decoration:none; color:white; background:var(--secondary); display:inline-block; text-align:center;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state" style="text-align:center; padding:30px; color:var(--muted);">
                            Belum ada transaksi masuk.
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
