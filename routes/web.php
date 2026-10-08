<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Untuk mengecek user yang login
use App\Http\Controllers\AdminController; // Memanggil Controller Admin
use App\Http\Controllers\SiswaController; // Memanggil Controller Siswa

// 1. Rute Halaman Depan (Welcome Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. Rute "Penyortir" Pintar (Otomatis arahkan sesuai Role sesaat setelah Login)
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. JALUR KHUSUS ADMIN (Dijaga oleh Middleware Role:admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Halaman Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Proses Simpan Lomba Baru
    Route::post('/admin/lomba', [AdminController::class, 'storeLomba'])->name('admin.lomba.store');
    
    // Proses Update Status Pendaftaran
    Route::post('/admin/pendaftaran/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.status.update');
});

// 4. JALUR KHUSUS SISWA (Dijaga oleh Middleware Role:siswa)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php'; // (Ini baris yang paling bawah

