<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // --- AUTHENTICATION ---
    public function loginForm() { return view('admin.login'); }
    public function doLogin(Request $request) {
        if ($request->password == 'Admin12345') {
            session(['admin_login' => true]);
            return redirect('/admin/dashboard');
        }
        return back()->withErrors(['password' => 'Password salah!']);
    }
    public function logout() {
        session()->forget('admin_login');
        return redirect('/admin/login');
    }

    // --- DASHBOARD UTAMA ---
    public function dashboard()
    {
        // 1. Update Status Expired secara Real-time setiap kali dashboard diakses
        // Menggunakan loop agar pengecekan lebih presisi terhadap expired_date
        $allMembers = Membership::all();
        foreach ($allMembers as $member) {
            if ($member->status === 'active' && $member->expired_date && Carbon::parse($member->expired_date)->isPast()) {
                $member->update(['status' => 'expired']);
            }
        }

        // 2. Ambil data untuk ditampilkan (termasuk status pending)
        $members = Membership::latest()->get(); 
        foreach ($members as $m) { 
            $m->price = $this->hitungHarga($m->package_name); 
        }

        // 3. Statistik (Tetap hitung omzet meskipun member sudah expired)
        $validMembers = $members->whereIn('status', ['active', 'expired']);
        $totalPemasukan = $validMembers->sum('price');
        
        $stats = [
            'total'       => $members->count(),
            'pending'     => $members->where('status', 'pending')->count(),
            'active'      => $members->where('status', 'active')->count(),
            'expired'     => $members->where('status', 'expired')->count(),
            'uang_in'     => $totalPemasukan,
            'komisi'      => $totalPemasukan * 0.3,
        ];

        return view('admin.dashboard', compact('members', 'stats'));
    }

    // --- HALAMAN NAVIGASI ---
    public function members() {
        $members = Membership::latest()->paginate(15);
        foreach ($members as $m) { $m->price = $this->hitungHarga($m->package_name); }
        return view('admin.members', compact('members'));
    }

    public function masaAktif() {
        $members = Membership::where('status', 'active')->orderBy('expired_date', 'asc')->get();
        foreach ($members as $m) {
            $pkg = strtolower($m->package_name);
            if (strpos($pkg, 'lifetime') !== false || strpos($pkg, 'paket 3') !== false) {
                $m->expired_text = 'SELAMANYA';
                $m->sisa_hari = 'LIFETIME';
                $m->is_expired_soon = false;
            } else {
                $m->expired_text = $m->expired_date ? Carbon::parse($m->expired_date)->format('d M Y') : 'N/A';
                $diff = Carbon::now()->diffInDays(Carbon::parse($m->expired_date), false);
                $m->sisa_hari = ($diff < 0) ? 'Expired' : round($diff) . ' Hari Lagi';
                $m->is_expired_soon = ($diff >= 0 && $diff <= 3);
            }
        }
        return view('admin.masa_aktif', compact('members'));
    }

    public function komisi() {
    // Ambil member yang sudah aktif atau expired
    $members = Membership::whereIn('status', ['active', 'expired'])
        ->orderBy('updated_at', 'desc') // Asumsi updated_at adalah tanggal approve
        ->get();

    foreach ($members as $m) { 
        $m->price = $this->hitungHarga($m->package_name); 
        $m->commission = $m->price * 0.3;
    }

    // GROUPING DATA BERDASARKAN TANGGAL
    $groupedMembers = $members->groupBy(function($date) {
        return \Carbon\Carbon::parse($date->updated_at)->format('d M Y');
    });

    $totalKomisi = $members->sum('commission');
    
    return view('admin.komisi', compact('groupedMembers', 'totalKomisi'));
}

    public function expiredMembers() {
        $members = Membership::where('status', 'expired')->latest()->paginate(15);
        foreach ($members as $m) { $m->price = $this->hitungHarga($m->package_name); }
        return view('admin.expired', compact('members'));
    }

    // --- CRUD ACTIONS ---
    public function approve($id)
    {
        $member = Membership::findOrFail($id);
        $pkg = strtolower($member->package_name);
        
        $expiry = (strpos($pkg, 'lifetime') !== false) ? Carbon::now()->addYears(100) : 
                  ((strpos($pkg, 'paket 2') !== false || strpos($pkg, 'paket 3') !== false) ? Carbon::now()->addDays(365) : Carbon::now()->addDays(30));

        $member->update(['status' => 'active', 'expired_date' => $expiry]);
        return redirect()->back()->with('success', 'Member diaktifkan!');
    }

    public function delete($id) {
        Membership::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data dihapus!');
    }

    public function update(Request $request, $id)
    {
        $member = Membership::findOrFail($id);
        $member->update($request->validate([
            'user_telegram' => 'required',
            'package_name'  => 'required',
            'status'        => 'required',
            'expired_date'  => 'nullable|date',
        ]));
        return redirect()->back()->with('success', 'Data diupdate!');
    }

    // --- HELPER ---
    private function hitungHarga($packageName) {
        $name = strtolower(trim($packageName));
        if (strpos($name, '100.000') !== false || strpos($name, 'paket 1') !== false) return 100000;
        if (strpos($name, '1.000.000') !== false || strpos($name, 'paket 2') !== false) return 1000000;
        if (strpos($name, '3.500.000') !== false || strpos($name, 'paket 3') !== false) return 3500000;
        return 0;
    }
}