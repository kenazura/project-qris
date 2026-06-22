<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Member | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-[2rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
        <div class="mb-8 border-b border-slate-100 pb-6">
            <h1 class="text-xl font-black uppercase tracking-widest text-slate-900">Edit Member</h1>
            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] mt-1 font-bold">Ubah data: <span class="text-black">{{ $member->user_telegram }}</span></p>
        </div>

        <form action="/admin/update/{{ $member->id }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-1">Username Telegram</label>
                    <input type="text" name="user_telegram" value="{{ $member->user_telegram }}" 
                           class="w-full p-4 mt-2 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-black outline-none font-bold transition-all">
                </div>

                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-1">Nama Paket</label>
                    <input type="text" name="package_name" value="{{ $member->package_name }}" 
                           class="w-full p-4 mt-2 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-black outline-none font-bold transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-black uppercase text-slate-400 ml-1">Status</label>
                        <select name="status" class="w-full p-4 mt-2 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-black outline-none font-bold cursor-pointer transition-all">
                            <option value="pending" {{ $member->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>AKTIF</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black uppercase text-slate-400 ml-1">Tanggal Expired</label>
                        <input type="date" name="expired_date" value="{{ $member->expired_date ? $member->expired_date->format('Y-m-d') : '' }}" 
                               class="w-full p-4 mt-2 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-black outline-none font-bold transition-all uppercase">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <a href="/admin/dashboard" class="flex-1 p-4 text-center text-[10px] font-black uppercase border border-slate-200 rounded-2xl hover:bg-slate-100 transition-all">Batal</a>
                <button type="submit" class="flex-[2] p-4 bg-slate-900 text-white font-black text-[10px] uppercase rounded-2xl hover:bg-black transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>
</html>