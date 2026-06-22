@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="{ search: '' }">
    <div class="flex justify-between items-end border-b border-slate-100 pb-6">
        <div>
            <h2 class="text-3xl font-black uppercase tracking-widest text-slate-900">Masa Aktif</h2>
            <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] mt-1 italic">Monitoring durasi langganan member</p>
        </div>
        <div class="px-5 py-2 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
            {{ $members->count() }} Member Aktif
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-xl">
        <div class="p-6 border-b border-slate-50">
            <input type="text" x-model="search" placeholder="🔍 Cari Username atau Nama Paket..." 
                   class="w-full bg-slate-50 p-4 rounded-xl outline-none text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-amber-500/20 transition-all">
        </div>

        <div class="overflow-x-auto custom-scroll max-h-[600px]">
            <table class="w-full text-left text-[11px] whitespace-nowrap">
                <thead class="bg-slate-50 uppercase text-slate-500 font-black sticky top-0 shadow-sm">
                    <tr>
                        <th class="px-8 py-6">Username</th>
                        <th class="px-6 py-6">Paket</th>
                        <th class="px-6 py-6">Tanggal Habis</th>
                        <th class="px-6 py-6 text-center">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
    @forelse($members as $member)
    <tr x-show="search == '' || '{{ $member->user_telegram ?? '' }} {{ $member->package_name ?? '' }}'.toLowerCase().includes(search.toLowerCase())"
        class="hover:bg-slate-50 transition-all duration-300">
        <td class="px-8 py-6 font-bold text-slate-900">{{ $member->user_telegram ?? 'N/A' }}</td>
        <td class="px-6 py-6 text-slate-600 font-medium">{{ $member->package_name ?? 'N/A' }}</td>
        
        <td class="px-6 py-6 font-mono text-slate-500 italic">
            {{ $member->expired_text }}
        </td>
        
        <td class="px-6 py-6 text-center">
            <span class="px-4 py-1.5 rounded-lg font-black uppercase text-[9px] 
                {{ ($member->sisa_hari ?? '') == 'LIFETIME' ? 'bg-amber-100 text-amber-700' : 
                   (($member->is_expired_soon ?? false) ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-blue-50 text-blue-600') }}">
                {{ $member->sisa_hari ?? 'N/A' }}
            </span>
        </td>
    </tr>
    @empty
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection