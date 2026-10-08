<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lomba;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth; // Fitur untuk mengecek siapa yang sedang login

class SiswaController extends Controller
{
    // 1. Menampilkan Halaman Dashboard Siswa
    public function index()
    {
        // Ambil semua data lomba untuk ditampilkan di pilihan form daftar
        $lombas = Lomba::all();
        
        // Ambil data pendaftaran, TAPI HANYA milik siswa yang sedang login saat ini!
        $riwayat = Pendaftaran::with('lomba')->where('user_id', Auth::id())->get();
        
        // Kirim datanya ke halaman (view) siswa.dashboard
        return view('siswa.dashboard', compact('lombas', 'riwayat'));
    }

    // 2. Menyimpan Data Pendaftaran Lomba (Create)
    public function daftarLomba(Request $request)
    {
        // Simpan ke tabel pendaftaran
        Pendaftaran::create([
            'user_id' => Auth::id(),        // Otomatis mengambil ID siswa yang sedang login
            'lomba_id' => $request->lomba_id, // Mengambil ID lomba yang dipilih di form
            'status' => 'Dokumen dalam Tinjauan' // Status default!
        ]);
        
        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Berhasil mendaftar lomba!');
    }
}
