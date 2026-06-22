@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="{ search: '' }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-slate-100 pb-6 gap-4">
        <div>
            <h2 class="text-2xl font-black uppercase tracking-widest text-slate-900">Komisi Admin</h2>
            <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] mt-1 italic">Rincian pendapatan 30% dari member per hari</p>
        </div>
        <div class="text-right bg-slate-900 px-8 py-4 rounded-[2rem] shadow-xl w-full md:w-auto">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Komisi Terkumpul</p>
            <p class="text-xl font-black text-amber-400 mt-1">Rp {{ number_format($totalKomisi ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($groupedMembers as $date => $membersInDate)
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group">
            
            <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-black text-slate-700 uppercase tracking-widest text-[10px]">{{ $date }}</h3>
                <span class="font-black text-green-600 text-[10px] bg-green-50 px-3 py-1 rounded-full border border-green-100">
                    Total komisi: Rp {{ number_format($membersInDate->sum('commission'), 0, ',', '.') }}
                </span>
            </div>

            <div class="overflow-x-auto custom-scroll">
                <table class="w-full text-left text-[11px] whitespace-nowrap">
                    <thead class="uppercase text-slate-400 font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Username</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4 text-green-600">Komisi (30%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($membersInDate as $member)
                        <tr x-show="search == '' || '{{ $member->user_telegram }}'.toLowerCase().includes(search.toLowerCase())" 
                            class="hover:bg-slate-50 transition-all duration-300">
                            <td class="px-8 py-6 font-bold text-slate-900">{{ $member->user_telegram }}</td>
                            <td class="px-6 py-6 text-slate-600 font-medium">{{ $member->package_name }}</td>
                            <td class="px-6 py-6 font-mono text-slate-500">Rp {{ number_format($member->price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-6 font-black text-green-600">Rp {{ number_format($member->commission ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="py-16 text-center font-bold text-slate-400 uppercase tracking-widest">
            Belum ada komisi dari member aktif
        </div>
        @endforelse
    </div>
</div>
@endsection