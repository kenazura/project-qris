<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoseGold | Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 font-sans flex min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white p-8 md:translate-x-0 transition-transform duration-300 shadow-2xl flex flex-col">
        
        <div class="mb-10 text-center">
            <h1 class="text-xl font-black tracking-[0.2em] text-white">ROSE<span class="text-amber-500">GOLD</span></h1>
            <p class="text-[9px] uppercase tracking-widest text-slate-500 mt-1">Control Center</p>
        </div>

        <nav class="space-y-3 flex-grow">
            @foreach([['/admin/dashboard', 'Dashboard'], ['/admin/members', 'Daftar Member'], ['/admin/masa-aktif', 'Masa Aktif'], ['/admin/komisi', 'Komisi Admin']] as $item)
            <a href="{{ $item[0] }}" class="block py-3 px-4 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-slate-800 {{ request()->is(ltrim($item[0], '/')) ? 'bg-amber-600 text-white' : 'text-slate-400' }}">
                {{ $item[1] }}
            </a>
            @endforeach
        </nav>
        <a href="/admin/logout" class="text-red-400 text-[10px] font-bold uppercase hover:text-white mt-auto">Logout</a>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden"></div>

    <main class="flex-1 min-w-0 h-screen flex flex-col transition-all duration-300 md:ml-64">
        
        <header class="md:hidden flex items-center justify-between p-6 bg-white border-b border-slate-100">
            <span class="font-black text-slate-900">ROSEGOLD</span>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-slate-900 text-white rounded-lg text-[9px] font-bold uppercase">Menu</button>
        </header>

        <div class="flex-1 p-4 md:p-12 overflow-y-auto animate-fade-in custom-scroll" x-data="{ search: '' }">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <div class="bg-emerald-600 p-5 rounded-2xl md:rounded-3xl text-white shadow-lg">
                        <p class="text-[8px] font-bold uppercase opacity-80">Pemasukan</p>
                        <h2 class="text-sm md:text-md font-black mt-1 truncate">Rp {{ number_format($stats['uang_in'] ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-blue-600 p-5 rounded-2xl md:rounded-3xl text-white shadow-lg">
                        <p class="text-[8px] font-bold uppercase opacity-80">Total Member</p>
                        <h2 class="text-sm md:text-md font-black mt-1">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div class="bg-white p-5 rounded-2xl md:rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-[8px] font-bold text-slate-400 uppercase">Komisi</p>
                        <h2 class="text-sm md:text-md font-black mt-1 text-amber-600 truncate">Rp {{ number_format($stats['komisi'] ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-white p-5 rounded-2xl md:rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-[8px] font-bold text-slate-400 uppercase">Aktif</p>
                        <h2 class="text-sm md:text-md font-black mt-1 text-slate-900">{{ $stats['active'] ?? 0 }}</h2>
                    </div>
                </div>

                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <input type="text" x-model="search" placeholder="🔍 Cari Data..." 
                           class="w-full p-5 md:p-6 border-b outline-none text-[10px] font-bold uppercase tracking-widest bg-slate-50/50">
                    
                    <div class="overflow-x-auto custom-scroll">
                        <table class="w-full text-left text-[10px] border-collapse min-w-[600px]">
                            <thead class="bg-slate-50 uppercase text-slate-400 font-black">
                                <tr>
                                    <th class="px-6 py-4">User</th> <th class="px-4 py-4">Paket</th>
                                    <th class="px-4 py-4">Metode</th> <th class="px-4 py-4">Harga</th>
                                    <th class="px-4 py-4 text-center">Status</th> <th class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($members ?? [] as $member)
                                <tr x-show="search == '' || '{{ $member->user_telegram }} {{ $member->package_name }}'.toLowerCase().includes(search.toLowerCase())"
                                    class="hover:bg-slate-50 transition-all">
                                    <td class="px-6 py-4 font-bold">{{ $member->user_telegram }}</td>
                                    <td class="px-4 py-4 font-black uppercase text-slate-600">{{ $member->package_name }}</td>
                                    <td class="px-4 py-4 font-bold text-amber-600 uppercase">{{ $member->payment_method ?? '-' }}</td>
                                    <td class="px-4 py-4 font-mono">Rp {{ number_format($member->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-2 py-1 rounded-full text-[8px] font-black uppercase {{ ($member->status ?? '') == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ substr($member->status ?? 'N/A', 0, 3) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                                        @if(($member->status ?? '') == 'pending')
                                            <a href="/admin/approve/{{ $member->id }}" class="text-blue-600 font-black uppercase hover:underline">App</a>
                                        @endif
                                        <form action="/admin/delete/{{ $member->id }}" method="POST" onsubmit="return confirm('Hapus Member?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 font-black uppercase hover:underline">Del</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-10 font-bold text-slate-400">DATA KOSONG</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>