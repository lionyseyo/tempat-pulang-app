<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\Curhat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Pastikan user harus login untuk mengakses semua fungsi ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman settings/edit profil
     * PERBAIKAN: Mengarahkan ke file 'settings.blade.php' 
     */
public function edit()
{
    // Jika file berada di resources/views/settings.blade.php
    return view('settings', [
        'user' => auth()->user()
    ]);
}

    /**
     * Memperbarui data profil & foto
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'tentangku' => 'nullable|string',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->tentangku = $request->tentangku;

        // Logika upload foto profil
        if ($request->hasFile('avatar')) {
            // Hapus foto lama dari storage jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder 'avatars' di public storage
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($user->save()) {
            return back()->with('success', 'Profil berhasil diperbarui! ✨');
        }

        return back()->with('error', 'Gagal memperbarui profil.');
    }

    /**
     * 1. Fungsi Export PDF Kenangan
     */
    public function exportPdf()
    {
        $user = Auth::user();
        
        // Mengambil data curhat user
        $dataCurhat = Curhat::where('user_id', $user->id)->latest()->get();

        // Pastikan kamu punya file resources/views/pdf/kenangan.blade.php
        $pdf = Pdf::loadView('pdf.kenangan', compact('user', 'dataCurhat'));
        
        return $pdf->download('Kenangan-Tempat-Pulang.pdf');
    }

    /**
     * 2. Fungsi Hapus Semua Riwayat (Clear History)
     */
    public function clearHistory()
    {
        $userId = Auth::id();
        
        // Menghapus Mood dan Curhat berdasarkan user_id
        Mood::where('user_id', $userId)->delete();
        Curhat::where('user_id', $userId)->delete();

        return back()->with('success', 'Riwayat (Mood & Curhat) berhasil dibersihkan! 🧹');
    }

    /**
     * 3. Fungsi Hapus Akun Permanen
     */
    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        // Hapus file foto dari storage agar tidak memenuhi server
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Proses Logout
        Auth::logout();
        
        // Hapus User dari Database
        $user->delete(); 

        // Hancurkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Sampai jumpa lagi, akun Anda telah dihapus secara permanen.');
    }
public function resetAvatar()
{
    $user = Auth::user();

    if ($user->avatar) {
        // Hapus file fisik foto dari folder storage
        Storage::disk('public')->delete($user->avatar);
        
        // Set kolom avatar di database jadi null
        $user->update(['avatar' => null]);
        
        return back()->with('success', 'Foto profil berhasil direset! ✨');
    }

    return back()->with('info', 'Foto profil kamu sudah menggunakan default.');
}
}