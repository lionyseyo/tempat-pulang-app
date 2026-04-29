<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body style="
    background: url('{{ asset('images/background.png') }}') no-repeat center center;
    background-size: cover;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
">

<div class="login-card">

    <img src="{{ asset('images/bg-login.png') }}" class="top-image">

    <h1>Buat Akun</h1>
    <p class="subtitle">Bergabunglah dan jadi bagian dari kami!</p>

    @if ($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <div class="input-box">
            <span class="icon">👤</span>
            <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="input-box">
            <span class="icon">✉</span>
            <input type="email" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="input-box">
            <span class="icon">🔒</span>
            <input type="password" name="password" placeholder="Buat kata sandi" required>
        </div>

        <div class="input-box">
            <span class="icon">🔒</span>
            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required>
        </div>

        <div class="option-row">
            <label>
                <input type="checkbox" required>
                Saya setuju dengan semua 
                <a href="#" style="color:#ff4f87;">Syarat & Ketentuan</a>
            </label>
        </div>

        <button type="submit" class="btn-gradient">
            Sign up
        </button>

    </form>

    <div class="divider">
        <span>Atau daftar dengan</span>
    </div>

    <div class="social-login">

        <a href="{{ route('google.login') }}" class="social-btn">
            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" class="social-icon">
            Sign up dengan Google
        </a>

    </div>

    <div class="signup">
        Sudah punya akun?
        <a href="{{ route('login') }}">Log in</a>
    </div>

</div>

</body>
</html>