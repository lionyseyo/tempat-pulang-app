<?php

namespace App\Http\Controllers;

use App\Models\Kenangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KenanganController extends Controller
{
    /**
     * Konstruktor untuk memastikan hanya user login yang bisa akses.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar kenangan.
     */
    public function index()
    {
        $kenangans = Kenangan::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('kotak_kenangan', compact('kenangans'));
    }

    /**
     * Menyimpan kenangan baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'nullable|string',
            'foto'    => 'nullable|image|max:2048',
            'video'   => 'nullable|mimetypes:video/mp4,video/avi,video/quicktime|max:20000'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('kenangan', 'public');
        }

        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('kenangan', 'public');
        }

        $data['user_id'] = auth()->id();

        Kenangan::create($data);

        return redirect()->back()->with('success', 'Kenangan indah berhasil disimpan! ✨');
    }

    /**
     * Menghapus kenangan beserta file fisiknya.
     * PERBAIKAN: Fungsi sekarang berada di dalam class dan memiliki nama 'destroy'.
     */
    public function destroy(Kenangan $kenangan)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        if ($kenangan->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        // 1. Cek dan hapus file foto jika ada di storage
        if ($kenangan->foto) {
            Storage::disk('public')->delete($kenangan->foto);
        }

        // 2. Cek dan hapus file video jika ada di storage
        if ($kenangan->video) {
            Storage::disk('public')->delete($kenangan->video);
        }

        // 3. Hapus data dari database
        $kenangan->delete();

        return redirect()->back()->with('success', 'Kenangan berhasil dihapus 🗑️');
    }
}