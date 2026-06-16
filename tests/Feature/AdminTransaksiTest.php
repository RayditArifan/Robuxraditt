<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransaksiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin and customer users
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);

        // Create a product
        $this->barang = Barang::create([
            'kode' => 'BRG-001',
            'nama' => '1000 Robux',
            'kategori' => 'Gamepass',
            'stok' => 10,
            'harga' => 150000,
            'tanggal_masuk' => now()->toDateString(),
            'user_id' => $this->admin->id,
            'aktif' => true,
        ]);
    }

    /**
     * Test admin can view transaction list and detail page.
     */
    public function test_admin_can_view_transaction_list_and_detail(): void
    {
        $transaksi = Transaksi::create([
            'user_id' => $this->customer->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 2,
            'total_harga' => 300000,
            'status' => 'pending',
            'username_roblox' => 'RobloxPlayer1',
            'bukti_pembayaran' => 'bukti.png',
        ]);

        // Access transaction list as Admin
        $response = $this->actingAs($this->admin)
            ->get(route('admin.transaksi.list'));

        $response->assertStatus(200);
        $response->assertSee('#' . $transaksi->id);
        $response->assertSee($this->customer->name);
        $response->assertSee('RobloxPlayer1');
        $response->assertSee('Detail');
        // The list page should not contain verification forms/buttons anymore since we simplified it
        $response->assertDontSee('Setujui Pembayaran');

        // Access transaction detail page as Admin
        $responseDetail = $this->actingAs($this->admin)
            ->get(route('admin.transaksi.show', $transaksi));

        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('#' . $transaksi->id);
        $responseDetail->assertSee('Detail Transaksi');
        $responseDetail->assertSee('RobloxPlayer1');
        $responseDetail->assertSee('Setujui Pembayaran');
        $responseDetail->assertSee('Tolak Transaksi');
    }

    /**
     * Test admin can approve a pending transaction.
     */
    public function test_admin_can_approve_transaction(): void
    {
        $transaksi = Transaksi::create([
            'user_id' => $this->customer->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 2,
            'total_harga' => 300000,
            'status' => 'pending',
            'username_roblox' => 'RobloxPlayer1',
            'bukti_pembayaran' => 'bukti.png',
        ]);

        $initialStok = $this->barang->stok;

        // Approve transaction
        $response = $this->actingAs($this->admin)
            ->post(route('admin.transaksi.setujui', $transaksi));

        $response->assertRedirect();
        
        $transaksi->refresh();
        $this->barang->refresh();

        $this->assertEquals('proses', $transaksi->status);
        $this->assertEquals($initialStok - 2, $this->barang->stok);
    }

    /**
     * Test admin can reject a pending transaction.
     */
    public function test_admin_can_reject_transaction(): void
    {
        $transaksi = Transaksi::create([
            'user_id' => $this->customer->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 2,
            'total_harga' => 300000,
            'status' => 'pending',
            'username_roblox' => 'RobloxPlayer1',
            'bukti_pembayaran' => 'bukti.png',
        ]);

        // Reject transaction
        $response = $this->actingAs($this->admin)
            ->post(route('admin.transaksi.tolak', $transaksi));

        $response->assertRedirect();

        $transaksi->refresh();
        $this->assertEquals('belum_bayar', $transaksi->status);
        $this->assertNull($transaksi->bukti_pembayaran);
    }

    /**
     * Test admin can complete a processing transaction.
     */
    public function test_admin_can_complete_transaction(): void
    {
        $transaksi = Transaksi::create([
            'user_id' => $this->customer->id,
            'barang_id' => $this->barang->id,
            'jumlah' => 2,
            'total_harga' => 300000,
            'status' => 'proses',
            'username_roblox' => 'RobloxPlayer1',
            'bukti_pembayaran' => 'bukti.png',
        ]);

        // Complete transaction
        $response = $this->actingAs($this->admin)
            ->post(route('admin.transaksi.selesaikan', $transaksi));

        $response->assertRedirect();

        $transaksi->refresh();
        $this->assertEquals('selesai', $transaksi->status);
    }
}
