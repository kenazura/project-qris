<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>RoseGold | Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        
        /* Custom scrollbar yang cantik */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans flex min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white p-8 md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
        
        <div class="mb-10 text-center">
            <h1 class="text-2xl font-black tracking-[0.2em] text-white">ROSE<span class="text-amber-500">GOLD</span></h1>
            <p class="text-[9px] uppercase tracking-widest text-slate-500 mt-1">Admin Control Center</p>
        </div>

        <nav class="space-y-3 flex-grow">
            @foreach([
                ['/admin/dashboard', 'Dashboard'], 
                ['/admin/members', 'Daftar Member'], 
                ['/admin/masa-aktif', 'Masa Aktif'], 
                ['/admin/expired-members', 'Riwayat Expired'], 
                ['/admin/komisi', 'Komisi Admin']
            ] as $item)
            <a href="{{ $item[0] }}" 
               class="block py-3 px-4 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300 
               {{ request()->is(ltrim($item[0], '/')) ? 'bg-amber-600 text-white shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                {{ $item[1] }}
            </a>
            @endforeach
        </nav>

        <a href="/admin/logout" class="text-red-400 text-[10px] font-bold uppercase tracking-widest hover:text-white mt-auto transition-colors">
            Logout
        </a>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <header class="md:hidden flex justify-between items-center p-6 bg-white border-b border-slate-100 shadow-sm z-30">
            <h1 class="font-black text-slate-900 tracking-widest">ROSEGOLD</h1>
            <button @click="sidebarOpen = true" class="p-2 bg-slate-900 text-white rounded-lg text-[9px] font-bold uppercase">Menu</button>
        </header>

        <div class="flex-1 p-6 md:p-12 overflow-y-auto animate-fade-in custom-scroll">
            <div class="max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </div>
    </main>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black/50 z-40 md:hidden">
    </div>
</body>
</html>