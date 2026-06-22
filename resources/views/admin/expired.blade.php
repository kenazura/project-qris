@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="flex justify-between items-center border-b border-slate-100 pb-6">
        <div>
            <h2 class="text-3xl font-black uppercase text-slate-900">Riwayat Expired</h2>
            <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] mt-1">Daftar member yang sudah habis masa langganan</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left text-[11px]">
            <thead class="bg-slate-50 uppercase text-slate-500 font-black">
                <tr>
                    <th class="px-8 py-6">Username</th>
                    <th class="px-6 py-6">Paket</th>
                    <th class="px-6 py-6 text-center">Status</th>
                    <th class="px-6 py-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($members as $member)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="px-8 py-6 font-bold">{{ $member->user_telegram }}</td>
                    <td class="px-6 py-6 text-slate-600">{{ $member->package_name }}</td>
                    <td class="px-6 py-6 text-center">
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 font-black uppercase text-[9px]">EXPIRED</span>
                    </td>
                    <td class="px-6 py-6 text-center">
                        <form action="/admin/delete/{{ $member->id }}" method="POST" onsubmit="return confirm('Hapus permanen data ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 font-black uppercase hover:underline">Hapus Data</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-16 text-center text-slate-400">Tidak ada riwayat expired.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection