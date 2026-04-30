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
        $userId = Auth::id() ?? 0;

        try {
            // 2. Simpan pesan User ke Database
            Chat::create([
                'user_id' => $userId,
                'message' => $userMessage,
                'is_bot' => false
            ]);

            // 3. Ambil API Key dari ENV
            $apiKey = env('CLAUDE_API_KEY');
            if (!$apiKey) {
                Log::error('Claude API Key tidak ditemukan di file .env');
                return response()->json(['message' => 'Kunci API belum dipasang.. 😔'], 500);
            }

            // 4. Panggil API Claude
            // Kita pakai model claude-3-haiku karena paling murah dan cepat
            $response = Http::withoutVerifying() // Agar aman di localhost/XAMPP
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01', // Wajib ada untuk Claude
                    'content-type' => 'application/json',
                ])
                ->timeout(30)
                ->post("https://api.anthropic.com/v1/messages", [
                    'model' => 'claude-3-haiku-20240307',
                    'max_tokens' => 1024,
                    'system' => "Nama kamu adalah 'Teman Bicara'. Kamu adalah pendamping kesehatan mental yang sangat empati, lembut, dan menenangkan. Tugasmu mendengarkan curhatan user dan memberikan penguatan. Gunakan Bahasa Indonesia yang sangat hangat dan santai seperti sahabat dekat.",
                    'messages' => [
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                ]);

            // 5. Cek Respon API
            if ($response->successful()) {
                $data = $response->json();
                
                // Claude menyimpan teks balasannya di content[0]['text']
                $botResponse = $data['content'][0]['text'] ?? "Aku di sini mendengarkanmu, tapi aku sedang kesulitan merangkai kata. 🤍";
            } else {
                Log::error('Claude API Error: ' . $response->status() . ' - ' . $response->body());
                $botResponse = "Maaf ya, pikiranku sedang sedikit bising. Bisa kamu ulangi ceritanya? 🕊️";
            }

            // 6. Simpan balasan Bot ke Database
            Chat::create([
                'user_id' => $userId,
                'message' => $botResponse,
                'is_bot' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $botResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Claude Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => "Sepertinya ada gangguan teknis. Aku tetap di sini untukmu."
            ], 500);
        }
    }

    public function getHistory()
    {
        $chats = Chat::where('user_id', Auth::id() ?? 0)
                     ->orderBy('created_at', 'asc')
                     ->get();

        return response()->json($chats);
    }
}