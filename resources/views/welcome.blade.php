<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket | Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .modal-box { position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 20px; }
        [x-cloak] { display: none !important; }
        .paket-card { transition: all 0.3s ease; }
        .paket-card:hover { transform: scale(1.02); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans p-4 md:p-10 animate-slide-up">

    <div x-data="{ selectedPackage: '', method: '', zoomImg: null }" class="max-w-md mx-auto space-y-8">
        
        @if ($errors->any())
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl text-xs font-bold uppercase tracking-widest text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="text-center space-y-1">
            <h1 class="text-xl font-black uppercase tracking-widest text-slate-900 animate-pulse">PILIH PAKET ANDA</h1>
        </div>

        <div class="space-y-3">
            <template x-for="pkg in [
                {name:'Paket 1', price:'Rp 100.000/bulan'}, 
                {name:'Paket 2', price:'Rp 1.000.000/tahun'}, 
                {name:'Paket 3', price:'Rp 3.500.000/lifetime'}
            ]">
                <div @click="selectedPackage = pkg.name" 
                     :class="selectedPackage === pkg.name ? 'ring-2 ring-black bg-white shadow-lg' : 'bg-white/60'"
                     class="paket-card p-5 rounded-xl border border-black/20 flex justify-between items-center cursor-pointer transition shadow-sm">
                    <div class="flex items-center gap-4">
                        <div :class="selectedPackage === pkg.name ? 'border-black' : 'border-slate-300'"
                             class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all">
                            <div :class="selectedPackage === pkg.name ? 'bg-black' : 'bg-transparent'" class="w-2 h-2 rounded-full"></div>
                        </div>
                        <h3 class="font-bold text-sm" x-text="pkg.name"></h3>
                    </div>
                    <span class="font-bold text-black text-sm" x-text="pkg.price"></span>
                </div>
            </template>
        </div>

        <form action="/membership/store" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="package_name" :value="selectedPackage" required>
            <input type="hidden" name="payment_method" :value="method" required>
            
            <div class="space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500 ml-1">Telegram Username</label>
                <input type="text" name="user_telegram" placeholder="@username" required
                       class="w-full p-4 border border-black/30 rounded-xl text-sm outline-none focus:ring-1 focus:ring-black transition-all hover:border-black">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <template x-for="m in ['DANA', 'GOPAY']">
                    <div @click="method = m" 
                         :class="method === m ? 'border-black bg-slate-100 scale-105' : 'border-black/30'"
                         class="p-4 rounded-xl border cursor-pointer flex justify-center items-center shadow-sm transition-all hover:border-black">
                        <img :src="m === 'DANA' ? 'https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg' : 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg'" class="h-6">
                    </div>
                </template>
            </div>

            <div x-show="method" x-cloak class="text-center p-4 bg-white rounded-xl border border-black/20 animate-in fade-in duration-500" @click="zoomImg = (method === 'DANA' ? '{{ asset('images/qris-dana.jpg') }}' : '{{ asset('images/qris-gopay.jpg') }}')">
                <p class="text-[9px] font-bold text-slate-400 uppercase mb-2 tracking-widest italic">Click to view detail</p>
                <img :src="method === 'DANA' ? '{{ asset('images/qris-dana.jpg') }}' : '{{ asset('images/qris-gopay.jpg') }}'" class="w-24 h-24 mx-auto border border-black/20 p-1 cursor-pointer hover:opacity-80 transition">
            </div>

            <button type="submit" class="w-full bg-black text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-slate-800 transition transform active:scale-95 shadow-xl">
                Konfirmasi Aktivasi
            </button>
        </form>

        <div x-show="zoomImg" x-cloak class="modal-box" @click="zoomImg = null">
            <div class="bg-white p-6 rounded-2xl w-full max-w-sm border-2 border-black transform transition-all duration-300 scale-100" @click.stop>
                <img :src="zoomImg" class="w-full h-auto mb-4 border border-black/20">
                <table class="w-full text-xs border border-black">
                    <tr class="border-b border-black"><th class="p-2 border-r border-black text-left">Paket</th><td class="p-2" x-text="selectedPackage"></td></tr>
                    <tr class="border-b border-black"><th class="p-2 border-r border-black text-left">Metode</th><td class="p-2" x-text="method"></td></tr>
                    <tr><th class="p-2 border-r border-black text-left">Status</th><td class="p-2 font-bold italic">Pending</td></tr>
                </table>
                <button @click="zoomImg = null" class="w-full mt-4 py-2 bg-black text-white text-xs font-bold uppercase hover:bg-slate-800 transition">Tutup</button>
            </div>
        </div>
    </div>
</body>
</html>