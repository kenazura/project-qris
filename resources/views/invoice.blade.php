<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice | #INV-{{ $membership->id ?? '...' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-sm bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in duration-500">
        
        <div class="gradient-bg p-8 text-center text-white">
            <div class="bg-white/10 backdrop-blur-lg rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-white/20 animate-bounce">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-lg font-black uppercase tracking-[0.2em]">Pesanan Diterima</h1>
            <p class="text-slate-400 text-[9px] mt-1 uppercase tracking-[0.2em]">Menunggu verifikasi admin</p>
        </div>

        <div class="p-8">
            <div class="space-y-5">
                <div class="flex justify-between items-center pb-4 border-b border-dashed border-slate-200">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Order ID</p>
                        <p class="font-black text-sm text-slate-900 tracking-tight">#INV-{{ $membership->id ?? '0' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
                        <p class="font-bold text-xs text-slate-700">{{ date('d M Y') }}</p>
                    </div>
                </div>

                @php
                    // 1. Data dari database
                    $m_paket = $membership->package_name ?? 'Umum';
                    $m_metode = strtoupper($membership->payment_method ?? 'DANA');
                    $penerima = "RoseGold"; // Nama penerima tetap

                    // 2. Logika Harga Otomatis
                    $harga = "Hubungi Admin";
                    if (str_contains($m_paket, 'Paket 1')) $harga = "Rp 100.000";
                    elseif (str_contains($m_paket, 'Paket 2')) $harga = "Rp 1.000.000";
                    elseif (str_contains($m_paket, 'Paket 3')) $harga = "Rp 3.500.000";
                @endphp

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase">Username</span>
                        <span class="font-black text-xs text-slate-900 uppercase tracking-wide">{{ $membership->user_telegram ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase">Paket</span>
                        <span class="font-black text-xs text-slate-900 uppercase tracking-wide">{{ $m_paket }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase">Harga</span>
                        <span class="font-black text-xs text-slate-900 uppercase tracking-wide">{{ $harga }}</span>
                    </div>
                    
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Transfer Via {{ $m_metode }} Merchant:</p>
                        <p class="text-[10px] font-bold text-slate-700">Nama Penerima: {{ $penerima }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                @php
                    $teks_pesan = "Halo Admin, saya ingin melakukan konfirmasi pembayaran." . "\n\n" .
                                  "ID Pesanan: #INV-" . ($membership->id ?? '0') . "\n" .
                                  "Username: " . ($membership->user_telegram ?? 'Guest') . "\n" .
                                  "Paket: " . $m_paket . "\n" .
                                  "Harga: " . $harga . "\n" .
                                  "Metode: " . $m_metode . "\n" .
                                  "Penerima: " . $penerima . "\n\n" .
                                  "Mohon segera diproses ya, terima kasih.";
                @endphp
                
                <a href="https://t.me/arabelleorla97?text={{ rawurlencode($teks_pesan) }}" target="_blank" 
                   class="block w-full text-center bg-slate-900 hover:bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all shadow-xl hover:shadow-2xl active:scale-[0.98]">
                    Kirim Bukti Pembayaran
                </a>

                <a href="/" class="block w-full text-center mt-6 text-slate-400 hover:text-slate-600 text-[9px] font-bold uppercase tracking-widest underline transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>