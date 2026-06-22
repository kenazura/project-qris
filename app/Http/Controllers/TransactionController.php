<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Membership;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan riwayat transaksi untuk admin
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('admin.transactions', compact('transactions'));
    }

    // Fungsi untuk mencatat pembayaran saat user melakukan konfirmasi
    // Biasanya dipanggil oleh TelegramController atau dari dashboard admin
    public function confirmPayment($membershipId)
    {
        $membership = Membership::find($membershipId);
        
        if ($membership) {
            // Catat ke tabel transactions
            Transaction::create([
                'order_id' => 'INV-' . time(),
                'membership_id' => $membership->id,
                'amount' => $this->getPackagePrice($membership->package_name),
                'status' => 'success',
            ]);

            // Update status membership
            $membership->update(['status' => 'active']);
            
            return back()->with('success', 'Transaksi dicatat dan member berhasil diaktifkan!');
        }
        
        return back()->with('error', 'Data member tidak ditemukan.');
    }

    // Helper untuk menentukan harga berdasarkan paket
    private function getPackagePrice($packageName)
    {
        return match ($packageName) {
            'Paket 1' => 100000,
            'Paket 2' => 1000000,
            'Paket 3' => 3500000,
            default => 0,
        };
    }
}