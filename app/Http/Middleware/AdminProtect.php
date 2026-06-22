<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminProtect
{
    public function handle(Request $request, Closure $next)
    {
        // Jika tidak ada session 'admin_login', tendang ke halaman login
        if (!session()->has('admin_login')) {
            return redirect('/admin/login');
        }
        return $next($request);
    }
}