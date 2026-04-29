<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Curhat;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\CurhatController;
use App\Http\Controllers\KenanganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController; 
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Hanya bisa diakses sebelum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Authentication
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', function () {
        $credentials = request()->only('email', 'password');
        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['email' => 'Login gagal']);
    })->name('login.process');

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', function () {
        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);
        Auth::login($user);
        return redirect()->route('dashboard');
    })->name('register.process');

    // Google Socialite
    Route::get('/auth/google', function () {
        return Socialite::driver('google')->stateless()->redirect();
    })->name('google.login');

    Route::get('/auth/google/callback', function () {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt('google-login')
            ]
        );
        Auth::login($user);
        return redirect()->route('dashboard');
    })->name('google.callback');

    // Forgot & Reset Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    
    Route::post('/reset-password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Hanya bisa diakses setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard & Analytics
    Route::get('/dashboard', function () {
        $today = Carbon::today();
        $sudahMenulis = Curhat::where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->exists();
        return view('dashboard', compact('sudahMenulis'));
    })->name('dashboard');

    Route::post('/update-time', function (\Illuminate\Http\Request $request) {
        $seconds = (int) $request->input('seconds', 0);
        if ($seconds > 0 && $seconds <= 300) {
            auth()->user()->increment('total_time', $seconds);
        }
        return response()->json(['status' => 'ok']);
    })->name('update.time');

    // Mood Management
    Route::get('/mood', [MoodController::class, 'index'])->name('mood.index');
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');
    Route::post('/get-motivation', [MoodController::class, 'getMotivation'])->name('mood.getMotivation');
    Route::get('/mood-calendar', [MoodController::class, 'calendar'])->name('mood.calendar');
    Route::post('/mood-calendar/store', [MoodController::class, 'storeFromCalendar'])->name('mood.calendar.store');

    // Ruang Curhat (Journaling)
    Route::get('/ruang-curhat', [CurhatController::class, 'index'])->name('curhat.index');
    Route::get('/ruang-curhat/create', [CurhatController::class, 'create'])->name('curhat.create');
    Route::post('/ruang-curhat', [CurhatController::class, 'store'])->name('curhat.store');
    Route::get('/ruang-curhat/{curhat}/edit', [CurhatController::class, 'edit'])->name('curhat.edit');
    Route::put('/ruang-curhat/{curhat}', [CurhatController::class, 'update'])->name('curhat.update');
    Route::delete('/ruang-curhat/{curhat}', [CurhatController::class, 'destroy'])->name('curhat.destroy');

    // Kotak Kenangan
    Route::get('/kotak-kenangan', [KenanganController::class, 'index'])->name('kenangan.index');
    Route::post('/kotak-kenangan/store', [KenanganController::class, 'store'])->name('kenangan.store');
    Route::delete('/kenangan/{kenangan}', [KenanganController::class, 'destroy'])->name('kenangan.destroy');

    // Profile & Settings
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/reset-avatar', [ProfileController::class, 'resetAvatar'])->name('profil.resetAvatar');
    Route::get('/profil/reset-avatar', function () {
        return redirect()->route('profil.edit');
    });

    // Data Management & Danger Zone
    Route::get('/settings/export-pdf', [ProfileController::class, 'exportPdf'])->name('settings.export');
    Route::post('/settings/clear-history', [ProfileController::class, 'clearHistory'])->name('settings.clearHistory');
    Route::delete('/settings/delete-account', [ProfileController::class, 'destroyAccount'])->name('settings.deleteAccount');
    });

Route::middleware(['auth'])->group(function () {
    // Route untuk kirim pesan ke bot
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    
    // Route untuk ambil riwayat chat (biar chat lama tetap muncul)
    Route::get('/chat/history', [ChatController::class, 'getHistory'])->name('chat.history');
});
