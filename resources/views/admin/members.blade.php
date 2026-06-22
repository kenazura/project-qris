@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="{ search: '' }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-slate-100 pb-6 gap-4">
        <div>
            <h2 class="text-3xl font-black uppercase tracking-widest text-slate-900">Daftar Member</h2>
            <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] mt-1 italic">Database lengkap seluruh member</p>
        </div>
        <div class="px-6 py-2 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
            Total: {{ $members->total() }} Member
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-xl">
        
        <input type="text" x-model="search" placeholder="🔍 Cari Username atau ID..." 
               class="w-full p-8 border-b border-slate-100 focus:ring-0 outline-none text-[11px] font-bold uppercase tracking-widest bg-slate-50/50 focus:bg-white transition-colors">

        <div class="max-h-[500px] overflow-y-auto custom-scroll">
            <table class="w-full text-left text-[11px] whitespace-nowrap">
                <thead class="bg-slate-50 sticky top-0 uppercase text-slate-500 font-black tracking-widest z-10 shadow-sm">
                    <tr>
                        <th class="px-8 py-6">Order ID</th>
                        <th class="px-6 py-6">Username</th>
                        <th class="px-6 py-6">Paket</th>
                        <th class="px-6 py-6">Metode</th>
                        <th class="px-6 py-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($members as $member)
                    <tr x-show="search == '' || '{{ $member->id }} {{ $member->user_telegram }}'.toLowerCase().includes(search.toLowerCase())" 
                        class="row-hover transition-all duration-300">
                        <td class="px-8 py-6 font-mono text-slate-400 italic">#INV-{{ $member->id }}</td>
                        <td class="px-6 py-6 font-bold text-slate-800">{{ $member->user_telegram }}</td>
                        <td class="px-6 py-6 text-slate-600 font-medium">{{ $member->package_name }}</td>
                        <td class="px-6 py-6 font-black text-[10px] text-slate-500 uppercase">{{ $member->payment_method ?? 'N/A' }}</td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 rounded-full font-black text-[9px] uppercase tracking-wider
                                {{ $member->status == 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $member->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $member->status == 'expired' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $member->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center font-bold text-slate-400 uppercase tracking-widest">
                            Tidak ada member tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($members, 'links'))
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection