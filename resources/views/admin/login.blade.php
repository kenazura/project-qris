<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Secure Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-slate-950 flex items-center justify-center h-screen overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[500px] h-[500px] bg-blue-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[500px] h-[500px] bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20"></div>
    </div>

    <form action="/admin/login" method="POST" class="glass relative z-10 p-10 rounded-[2.5rem] border border-white/20 shadow-2xl w-[360px] text-center">
        @csrf
        
        <div class="mb-8">
            <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-black/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-xl font-black uppercase tracking-[0.2em] text-slate-900">Admin Login</h1>
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Authorized personnel only</p>
        </div>

        <input type="password" name="password" placeholder="ENTER ACCESS KEY" 
               class="w-full p-4 mb-6 rounded-2xl bg-white/50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-black outline-none font-bold text-center tracking-widest transition-all">
        
        <button type="submit" class="w-full p-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-black transition-all shadow-xl hover:shadow-2xl">
            Authorize
        </button>

        @if($errors->any())
            <p class="text-[10px] text-red-500 mt-6 font-black uppercase tracking-widest animate-pulse">Access Denied!</p>
        @endif
    </form>

</body>
</html>