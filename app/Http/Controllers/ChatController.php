<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        $userMessage = $request->message;

        try {
            // 2. Simpan pesan User ke Database
            Chat::create([
                'user_id' => Auth::id() ?? 0, // fallback jika user belum login
                'message' => $userMessage,
                'is_bot' => false
            ]);

            // 3. Ambil API Key dari ENV
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                Log::error('Gemini API Key tidak ditemukan di file .env');
                return response()->json(['message' => 'Konfigurasi API rusak.. 😔'], 500);
            }

            // 4. Instruksi Karakter AI
            $systemInstruction = "Nama kamu adalah 'Teman Bicara'. Kamu adalah pendamping kesehatan mental yang empati, lembut, dan menenangkan. Tugasmu: Mendengarkan, memberikan validasi perasaan, dan kata-kata penguatan. Gunakan Bahasa Indonesia yang hangat. Jangan terlalu kaku.";

            // 5. Request ke Gemini API
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($apiUrl, [
                'contents' => [[
                    'parts' => [['text' => $systemInstruction . "\n\nUser curhat: " . $userMessage]]
                ]],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            // 6. Cek Respon API
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Gemini API raw response:', $data);

                // Parsing lebih aman
                $botResponse = $data['candidates'][0]['content']['parts'][0]['text']
                    ?? "Aku di sini mendengarkanmu, tapi aku sedang kesulitan merangkai kata. Bisa ulangi? 🤍";
            } else {
                Log::error('Gemini API Error: ' . $response->status() . ' - ' . $response->body());
                $botResponse = "Maaf ya, koneksiku ke pusat sedang terganggu.. 😔";
            }

            // 7. Simpan balasan Bot ke Database
            Chat::create([
                'user_id' => Auth::id() ?? 0,
                'message' => $botResponse,
                'is_bot' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $botResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => "Sepertinya ada gangguan teknis. Aku tetap di sini untukmu."
            ], 500);
        }
    }

    public function getHistory()
    {
        // Ambil riwayat chat user yang sedang login
        $chats = Chat::where('user_id', Auth::id() ?? 0)
                     ->orderBy('created_at', 'asc')
                     ->get();

        return response()->json($chats);
    }
}
