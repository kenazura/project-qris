<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MembershipController;
use App\Models\Membership;

// --- FRONTEND ---
Route::get('/', function () { return view('welcome'); });
Route::post('/membership/store', [MembershipController::class, 'store']);
Route::get('/invoice/{id}', function ($id) {
    $membership = Membership::findOrFail($id);
    return view('invoice', compact('membership'));
});

// --- AUTHENTICATION ---
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AdminController::class, 'doLogin']);
Route::get('/admin/logout', [AdminController::class, 'logout']);

// --- ADMIN AREA ---
Route::middleware(['admin.protect'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/members', [AdminController::class, 'members']);
    Route::get('/masa-aktif', [AdminController::class, 'masaAktif']);
    Route::get('/komisi', [AdminController::class, 'komisi']);
    Route::get('/expired-members', [AdminController::class, 'expiredMembers']);
    Route::get('/approve/{id}', [AdminController::class, 'approve']);
    Route::get('/edit/{id}', [AdminController::class, 'edit']);
    Route::post('/update/{id}', [AdminController::class, 'update']);
    Route::delete('/delete/{id}', [AdminController::class, 'delete']);
    Route::get('/detail/{id}', [AdminController::class, 'showDetail']);
});