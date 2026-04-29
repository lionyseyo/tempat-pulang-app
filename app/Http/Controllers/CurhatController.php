<?php

namespace App\Http\Controllers;

use App\Models\Curhat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CurhatController extends Controller
{
    /**
     * Memastikan hanya pengguna yang login yang bisa mengakses.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman Ruang Curhat.
     */
    public function index()
    {
        $userId = auth()->id();

        // 1. Ambil semua data curhat pengguna (Urutkan dari yang terbaru)
        $curhats = Curhat::where('user_id', $userId)
            ->latest()
            ->get();

        // 2. Logika Insight 7 Hari Terakhir
        $oneWeekAgo = now()->subDays(7);
        $weeklyMoods = Curhat::where('user_id', $userId)
            ->where('created_at', '>=', $oneWeekAgo)
            ->whereNotNull('mood')
            ->pluck('mood');

        $insightText = "Belum ada data minggu ini 🌱";

        if ($weeklyMoods->count() > 0) {
            $moodCounts = $weeklyMoods->countBy();

            // Ambil maksimal 2 mood terbanyak
            $topMoods = $moodCounts->sortDesc()->take(2)->keys();

            $formatted = $topMoods->map(function ($mood) {
                return ucfirst($mood);
            })->implode(' & ');

            $insightText = "Kamu lebih sering merasa {$formatted}";
        }

        // 3. Logika Tips Hari Ini (Ganti-ganti secara acak)
        $tips = [
            "Jangan lupa minum air putih dan ambil napas dalam-dalam ya! 💧",
            "Cobalah untuk memejamkan mata sejenak dan istirahatkan pikiranmu. 🧘‍♂️",
            "Hari yang berat bukan berarti hidup yang berat. Semangat! ✨",
            "Sudahkah kamu berterima kasih pada dirimu sendiri hari ini? 💛",
            "Istirahatlah jika lelah, jangan menyerah. Kamu berharga. 🌷",
            "Mendengarkan lagu favorit bisa membantu memperbaiki mood-mu, lho. 🎵",
            "Tidak apa-apa untuk tidak merasa baik-baik saja hari ini. 🫂"
        ];
        $dailyTip = $tips[array_rand($tips)];

        // =========================================================================
        // PERBAIKAN DI SINI:
        // Gunakan 'ruang_curhat' karena filenya resources/views/ruang_curhat.blade.php
        // Jangan pakai titik (.) jika file tidak di dalam sub-folder.
        // =========================================================================
        return view('ruang_curhat', compact('curhats', 'insightText', 'dailyTip'));
    }

    /**
     * Menampilkan form edit curhat.
     */
    public function edit(Curhat $curhat)
    {
        // Pastikan hanya pemilik yang bisa edit
        if (!$this->isOwner($curhat)) {
            return redirect()->route('curhat.index')
                ->with('error', 'Kamu tidak memiliki akses ke cerita ini.');
        }

        // PERBAIKAN: Gunakan nama file blade edit kamu yang benar (misal: edit_curhat.blade.php)
        return view('edit_curhat', compact('curhat'));
    }

    /**
     * Memperbarui data curhat di database.
     */
    public function update(Request $request, Curhat $curhat)
    {
        if (!$this->isOwner($curhat)) {
            return redirect()->route('curhat.index')
                ->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'content' => 'required|string|min:3'
        ]);

        $curhat->update([
            'content' => $request->content
        ]);

        return redirect()
            ->route('curhat.index')
            ->with('success', 'Cerita berhasil diperbarui 💛');
    }

    /**
     * Menghapus data curhat.
     */
    public function destroy(Curhat $curhat)
    {
        if (!$this->isOwner($curhat)) {
            return redirect()->route('curhat.index')
                ->with('error', 'Akses ditolak.');
        }

        $curhat->delete();

        return redirect()
            ->route('curhat.index')
            ->with('success', 'Cerita berhasil dihapus 🗑');
    }

    /**
     * Helper: Mengecek apakah data curhat milik user yang sedang login.
     */
    private function isOwner(Curhat $curhat)
    {
        return $curhat->user_id && $curhat->user_id == auth()->id();
    }
}