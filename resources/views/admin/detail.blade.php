<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Member | {{ $member->user_telegram }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-6 md:p-10 font-sans">

    <div class="max-w-2xl mx-auto space-y-6">
        <a href="/admin/dashboard" class="text-[10px] font-bold text-slate-500 hover:text-black uppercase tracking-widest">&larr; Kembali ke Dashboard</a>

        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div>
                <h1 class="text-xl font-black uppercase tracking-widest">Detail Member</h1>
                <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mt-1">Informasi lengkap ID #INV-{{ $member->id }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Telegram Username</p>
                    <p class="font-bold text-lg">{{ $member->user_telegram }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Status</p>
                    <span class="px-2 py-1 rounded-lg font-black text-[9px] uppercase {{ $member->status == 'active' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $member->status }}
                    </span>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Paket</p>
                    <p class="font-medium text-slate-700">{{ $member->package_name }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Tanggal Expired</p>
                    <p class="font-medium text-slate-700">{{ $member->expired_date ? \Carbon\Carbon::parse($member->expired_date)->format('d M Y, H:i') : '-' }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex gap-3">
                @if($member->status == 'pending')
                    <a href="/admin/approve/{{ $member->id }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase hover:bg-blue-700">Approve Sekarang</a>
                @endif
                <a href="/admin/edit/{{ $member->id }}" class="bg-slate-100 text-slate-700 px-6 py-2 rounded-xl font-bold text-xs uppercase hover:bg-slate-200">Edit Data</a>
            </div>
        </div>
    </div>

</body>
</html>