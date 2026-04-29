<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mood;
use App\Models\Motivation;
use App\Models\Curhat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MoodController extends Controller
{
    // =========================
    // Halaman Mood
    // =========================
    public function index()
    {
        return view('Mood');
    }

    // =========================
    // SIMPAN MOOD (FIXED)
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'mood'  => 'required|string|max:50',
            'emoji' => 'required|string|max:10',
            'note'  => 'nullable|string'
        ]);

        $userId = auth()->id();
        $today = now()->toDateString();

        // 1️⃣ Simpan / update mood harian
        Mood::updateOrCreate(
            [
                'user_id' => $userId,
                'date'    => $today,
            ],
            [
                'mood' => $request->mood
            ]
        );

        // 2️⃣ Simpan ke ruang curhat (history lengkap)
        Curhat::create([
            'user_id' => $userId,
            'mood'    => $request->mood,
            'emoji'   => $request->emoji,
            'content' => $request->note ?? 'Hari ini merasa ' . $request->mood,
        ]);

        return redirect()
            ->route('curhat.index')
            ->with('success', 'Mood berhasil disimpan 💛');
    }

    // =========================
    // SIMPAN DARI KALENDER
    // =========================
    public function storeFromCalendar(Request $request)
    {
        $request->validate([
            'mood'  => 'required|string',
            'emoji' => 'nullable|string'
        ]);

        Mood::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date'    => now()->toDateString(),
            ],
            [
                'mood' => $request->mood
            ]
        );

        return redirect()->route('mood.calendar', [
            'month' => now()->format('Y-m')
        ]);
    }

    // =========================
    // KALENDER
    // =========================
    public function calendar(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $moods = Mood::where('user_id', auth()->id())
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        return view('mood_calendar', [
            'date'  => $date,
            'moods' => $moods
        ]);
    }

    // =========================
    // MOTIVATION
    // =========================
    public function getMotivation(Request $request)
    {
        $request->validate([
            'mood' => 'required|string'
        ]);

        $motivations = Motivation::where('mood', $request->mood)->get();

        if ($motivations->isEmpty()) {
            return response()->json([
                'message' => 'Tetap kuat, kamu pasti bisa melewati hari ini 💛'
            ]);
        }

        return response()->json([
            'message' => $motivations->random()->message
        ]);
    }
}